<?php

namespace Tests\Feature;

use App\Models\Rate;
use Carbon\Carbon;
use Tests\TestCase;

class RatesTest extends TestCase
{
    public function test_success(): void
    {
        $rates = Rate::query()->get()->toArray();

        $response = $this->getJson(route('api.rates'));

        $response->assertStatus(200)->assertJson(['rates' => $rates], true);
    }

    public function test_date_success(): void
    {
        $date = Carbon::now()->subDay()->toDateString();
        $rates = Rate::query()->where('date', $date)->get()->toArray();

        $response = $this->getJson(route('api.rates', ['date' => $date]));

        $response->assertStatus(200)->assertJson(['rates' => $rates], true);
    }

    public function test_date_failed(): void
    {
        $date = Carbon::now()->addDay()->toDateString();

        $response = $this->getJson(route('api.rates', ['date' => $date]));

        $response->assertStatus(200)->assertJson(['error' => 'The date field must be a date before tomorrow.']);
    }

    public function test_currency_success(): void
    {
        $currency = 'EUR';
        $rates = Rate::query()->where('char_code', $currency)->get()->toArray();

        $response = $this->getJson(route('api.rates', ['currency' => $currency]));

        $response->assertStatus(200)->assertJson(['rates' => $rates], true);
    }

    public function test_currency_failed(): void
    {
        $currency = 'Invalid Currency';

        $response = $this->getJson(route('api.rates', ['currency' => $currency]));

        $response->assertStatus(200)->assertJson(['error' => 'The currency field must be 3 characters.']);
    }
}
