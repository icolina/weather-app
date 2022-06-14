<?php

namespace App\Services;

use App\Interfaces\WeatherApi;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenWeatherApiService implements WeatherApi
{
    use ResponseApi;

    const TEMPERATURE_UNIT = 'metric';

    public function __construct() {}

    /**
     * Get Temperature by query
     *
     * @param String $query
     * @return Array $array
     **/
    public function getTemperatureByQuery(String $query) : Array
    {
        try {
            $data = Http::get('https://api.openweathermap.org/data/2.5/find', [
                'appid'     => env('OPEN_WEATHER_API_KEY'),
                'q'         => $query,
                'units'     => self::TEMPERATURE_UNIT
            ]);

            return $this->success($data);

        } catch(\Exception $e) {
            $errMsg = $e->getMessage();
            Log::error($errMsg);

            return $this->error($errMsg);
        }
    }
}
