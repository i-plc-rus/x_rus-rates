<?php

namespace Database\Seeders;

use App\Services\RatesService;
use Illuminate\Database\Seeder;

class RatesSeeder extends Seeder
{
    protected RatesService $ratesService;

    public function __construct(RatesService $ratesService)
    {
        $this->ratesService = $ratesService;
    }

    public function run(): void
    {
        $this->ratesService->storeRates();
    }
}
