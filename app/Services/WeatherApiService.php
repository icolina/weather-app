<?php

namespace App\Services;

use App\Interfaces\WeatherApi;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherApiService implements WeatherApi
{
    use ResponseApi;

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
            $data = Http::get('https://api.weatherapi.com/v1/current.json', [
                'key'   => env('WEATHER_API_KEY'),
                'q'     => $query,
                'aqi'   => 'no'
            ]);

            return $this->success($data);

        } catch(\Exception $e) {
            $errMsg = $e->getMessage();
            Log::error($errMsg);

            return $this->error($errMsg);
        }
    }
}

?>
