<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WeatherDisplayService
{
    /**
     * @var WeatherTemperatureService weatherTemperature
     */
    protected $weatherTemperature;

    /**
     * @param WeatherTemperatureService $weatherTemperature
     */
    public function __construct(WeatherTemperatureService $weatherTemperature)
    {
        $this->weatherTemperature = $weatherTemperature;
    }

    /**
     * Get Temperature by query
     *
     * @param String $query
     * @param Bool $reset
     *
     * @return Array $array
     **/
    public function displayResult(String $query = null, Bool $reset = false) : Array
    {
        $cacheKey   = env('CACHED_KEY');
        $cachedData = Cache::get($cacheKey);

        if ($reset) {
            Cache::forget($cacheKey);
            return [];
        }

        if (!$query && !$cachedData) {
            return [];
        }

        if (!$query && $cachedData) {
            return $cachedData;
        }

        if ($query) {
            $apiRes = $this->weatherTemperature->getTemperatureByQuery($query);

            if ($apiRes['error']) return $apiRes;

            $averageTemperature = $this->getAverageOfTemperatures($apiRes['result']);
            $result             = [
                'message'       => $apiRes['message'],
                'error'         => (bool) $apiRes['error'],
                'temperature_c' => $averageTemperature . "°C"
            ];
            $finalRes           = array_merge($result, $apiRes['result']['weather_res']);

            return Cache::remember($cacheKey, 300, function () use ($finalRes) {
                return $finalRes;
            });
        }
    }

    /**
	 * Get average of temperatures
	 *
	 * @param Array $result
     *
	 * @return Float
	 **/
	private function getAverageOfTemperatures(Array $result) : Float
	{
        $temperatures  = array_column(array_values($result), 'temperature');
		$size          = count($temperatures);
		$tempTotal     = $size > 0 ? array_sum($temperatures) : 0;

		return ($tempTotal !== 0 && $size !== 0)
                    ? round(($tempTotal / $size), 2)
                    : 0;
	}
}

?>
