<?php

namespace App\Interfaces;

interface WeatherApiFormatter
{
    public function formatResponse(Array $data) : Array;
}
