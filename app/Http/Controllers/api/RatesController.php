<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\RateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RatesController extends Controller
{
    /**
     * @var RateService
     */
    private $service;

    /**
     * RatesController constructor.
     * @param RateService $service
     */
    public function __construct(RateService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-d'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        if (strtotime($endDate) < strtotime($startDate)) {
            Log::error('Invalid date range');
            return response()->json(['error' => 'invalid date range']);
        } else {
            $data = $this->handleByAttempts($startDate, $endDate,3);
            Log::info('Data fetch by params:' . json_encode($request->all()));
            Log::info('Data:' . json_encode($data));
            return response()->json($data);
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $attempt
     * @return mixed
     * @throws \Exception
     */
    public function handleByAttempts($startDate, $endDate , $attempt)
    {
        try{
            $data = $this->service->fetch($startDate, $endDate);
            return $data;
        }catch (\Exception $exception){
            if($attempt >= 0){
                sleep(3);
                Log::error('Failed to fetch: trying next attempt:' . $attempt);
                $this->handleByAttempts($startDate, $endDate , --$attempt);
            }else{
                Log::error('No attempt available');
                throw new \Exception('No attempt available');
            }
        }
    }
}
