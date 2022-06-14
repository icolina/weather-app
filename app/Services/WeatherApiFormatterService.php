<?php

namespace App\Services;

use App\Interfaces\WeatherApiFormatter;
use App\Traits\ResponseApi;

class WeatherApiFormatterService implements WeatherApiFormatter
{
    use ResponseApi;

    public function __construct() {}

    /**
     * @param Array $data
     * @return Array
     */
    public function formatResponse(Array $data): Array
    {
        $temperatures = json_decode($data['result']->body());

        if (isset($temperatures->error)) return $this->error('Place not found!');

        $data = [
            'temperature'   => $temperatures->current->temp_c ? $temperatures->current->temp_c : 0,
            'region'        => $temperatures->location->name,
            'city'          => $temperatures->location->region,
            'country'       => $temperatures->location->country,
            'cloud'         => $temperatures->current->condition->text,
            'img'           => $temperatures->current->condition->icon
        ];

        return $data;
    }
}

?>
