<?php

namespace App\Interfaces;

interface WeatherApi
{
    /**
     * Get api response from query
     */
    public function getTemperatureByQuery(String $query) : Array;
}
