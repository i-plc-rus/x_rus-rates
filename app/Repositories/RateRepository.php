<?php

namespace App\Repositories;

use App\Models\Rates;

/**
 * Class RateRepository
 * @package App\Repositories
 */
class RateRepository
{
    /**
     * @var Rates
     */
    private $model;

    /**
     * RateRepository constructor.
     * @param Rates $model
     */
    public function __construct(Rates $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function saveMultiple($data)
    {
        $save = $this->model::insert($data);
        return $save;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function exists($item)
    {
        $exists = $this->model::where('uuid', $item['uuid'])->where('date', $item['date'])->exists();
        return $exists;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function get($startDate , $endDate)
    {
        return $this->model::where('date', '>=', $startDate)->where('date', '<=', $endDate)->get();
    }
}
