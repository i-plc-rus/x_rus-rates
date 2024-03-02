<?php

namespace App\Http\Controllers;

use App\Services\RatesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RatesController extends Controller
{
    protected RatesService $ratesService;

    public function __construct(RatesService $ratesService)
    {
        $this->ratesService = $ratesService;
    }

    public function get(Request $request): JsonResponse
    {
        try {
            $rates = $this->ratesService->getRates($request->all());
            return response()->json(compact('rates'));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
