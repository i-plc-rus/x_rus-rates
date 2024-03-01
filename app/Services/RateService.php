<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Repositories\RateRepository;

/**
 * Class RateService
 * @package App\Services
 */
class RateService
{
    /**
     * @var RateRepository
     */
    protected $repository;

    /**
     * RateService constructor.
     * @param RateRepository $repository
     */
    public function __construct(RateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $url
     * @return array|bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url)
    {
        $client = new Client();
        $provider_type = 'application/xml';

        $param_data = [
            'headers' => [
                'Accept' => $provider_type,
            ]
        ];
        try {
            $response = $client->get($url, $param_data);
            if ($response->getStatusCode() === 200) {
                $response = $response->getBody()->getContents();
                $res = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
                $array = json_decode(json_encode((array)$res), TRUE);

                $collection = $this->parse($array);
                return $collection;
            } else {
                Log::error("invalid response");
                return false;
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * Parse XML data to array and save to DB
     * @param $xmlData
     * @return array
     */
    public function parse($xmlData)
    {
        $array = [];
        if (!empty($xmlData)) {
            $date = date("Y-m-d", strtotime($xmlData['@attributes']['Date']));

            if (!empty($xmlData['Valute'])) {
                foreach ($xmlData['Valute'] as $key => $value) {
                    $item = [
                        'uuid' => $value['@attributes']['ID'],
                        'num_code' => $value['NumCode'],
                        'char_code' => $value['CharCode'],
                        'nominal' => $value['Nominal'],
                        'name' => $value['Name'],
                        'value' => str_replace(',', '.', $value['Value']),
                        'unit_rate' => str_replace(',', '.', $value['VunitRate']),
                        'date' => $date,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    $exists = $this->repository->exists($item);
                    if (!$exists) {
                        $array[] = $item;
                    }
                }
                $this->repository->saveMultiple($array);
                Log::info('Data inserted to DB :' . json_encode($array));
            }
        }

        return $array;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function fetch($startDate , $endDate)
    {
        return $this->repository->get($startDate , $endDate);
    }
}
