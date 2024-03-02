<?php

namespace App\Services;

use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Mtownsend\XmlToArray\XmlToArray;

class RatesService
{
    private array $ratesArray = [];

    private array $currentRate = [];

    public function getRates(array $settings = []): Collection
    {
        Validator::make($settings, [
            'date' => 'date|before:tomorrow|date_format:Y-m-d',
            'currency' => 'string|size:3',
        ])->validate();

        $this->storeRates();

        $ratesQuery = Rate::query();

        if (isset($settings['date'])) {
            $ratesQuery->where('date', $settings['date']);
        }
        if (isset($settings['currency'])) {
            $ratesQuery->where('char_code', $settings['currency']);
        }

        return $ratesQuery->get();
    }

    public function storeRates(): void
    {
        $this->callRates();

        if ($this->dateDoesntExist()) {
            $this->createRates();
        }
    }

    protected function callRates(): void
    {
        try {
            Log::info('Fetching rates');
            $response = Http::get('https://www.cbr.ru/scripts/XML_daily.asp');
            $this->ratesArray = XmlToArray::convert($response->body());
            Log::info('Fetching rates success');
        } catch (\Exception $exception) {
            Log::error('Fetching rates failure', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    protected function createRates():void
    {
        DB::beginTransaction();

        Log::info('Storing rates');
        try {
            foreach ($this->ratesArray['Valute'] as $item) {
                $this->currentRate = $item;
                $this->createRate();
            }
            DB::commit();
            Log::info('Storing rates success');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Storing rates failure', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    protected function createRate(): void
    {
        Rate::query()->create([
            'date' => $this->getCurrentDate(),
            'name' => $this->currentRate['Name'],
            'nominal' => $this->currentRate['Nominal'],
            'num_code' => $this->currentRate['NumCode'],
            'char_code' => $this->currentRate['CharCode'],
            'external_id' => $this->currentRate['@attributes']['ID'],
            'value' => $this->getCurrentRateValue($this->currentRate['Value']),
            'v_unit_rate' => $this->getCurrentRateValue($this->currentRate['VunitRate']),
        ]);
    }

    protected function getCurrentRateValue($value): string
    {
        return str_replace(',', '.', $value);
    }

    protected function getCurrentDate(): string
    {
        return Carbon::parse($this->ratesArray['@attributes']['Date'])->toDateString();
    }

    protected function dateDoesntExist(): bool
    {
        return Rate::query()->where('date', $this->getCurrentDate())->doesntExist();
    }
}
