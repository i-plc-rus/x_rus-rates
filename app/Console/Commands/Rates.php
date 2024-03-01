<?php

namespace App\Console\Commands;

use App\Services\RateService;
use DateTime;
use Illuminate\Console\Command;

/**
 * Class Rates
 * @package App\Console\Commands
 */
class Rates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:get {date?}';
    /**
     * @var
     */
    protected $service;


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Rates from CBR';

    /**
     * Rates constructor.
     * @param RateService $rateService
     */
    public function __construct(RateService $rateService)
    {
        parent::__construct();
        $this->service = $rateService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get date format from config
        $format = config('rate.date_format');
        // set date by default
        $date = date($format);
        // check if exist argument date
        if($this->argument('date')){
            $date = $this->argument('date');
            // validate is argument value valid date
            $d = DateTime::createFromFormat($format, $date);

            if(!($d && $d->format($format) === $date)){
                // set date today if argument is not valid
                $date = date($format);
            }
            // end validate
        }

        $url = env('CBR_ENDPOINT') ."?date_req=".$date;
        // get date from service
        $data = $this->service->get($url);
        return $data;
    }
}
