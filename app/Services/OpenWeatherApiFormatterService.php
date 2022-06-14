<?php

namespace App\Services;

use App\Interfaces\WeatherApiFormatter;
use App\Traits\ResponseApi;

class OpenWeatherApiFormatterService implements WeatherApiFormatter
{
    use ResponseApi;

    public function __construct() {}

    /**
     * @param Array $data
     * @return Array
     */
    public function formatResponse(Array $data): Array
    {
        $tempTotal    = 0;
        $temperatures = json_decode($data['result']->body());

        if ((isset($temperatures->cod) && $temperatures->cod == 400) || $temperatures->count == 0) {
            return $this->error('Place not found!');
        }

        for ($i = 0; $i < $temperatures->count; $i++) {
            $tempTotal += $temperatures->list[$i]->main->temp;
        }

        $totalTemp   = $tempTotal / $temperatures->count;
        $temperature = round($totalTemp, 2);

        return ['temperature' => $temperature];
    }
}
