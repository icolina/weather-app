<?php

namespace App\Services;

use App\Interfaces\WeatherApi;
use App\Traits\ResponseApi;

class WeatherTemperatureService implements WeatherApi
{
    use ResponseApi;

    /**
     * @var OpenWeatherApiService $openWeatherApi
     */
    protected $openWeatherApi;

    /**
     * @var WeatherApiService $weatherApi
     */
    protected $weatherApi;

    /**
     * @var OpenWeatherApiFormatterService $openWeatherApiFormatter
     */
    protected $openWeatherApiFormatter;

    /**
     * @var WeatherApiFormatterService $weatherApiFormatter
     */
    protected $weatherApiFormatter;

    /**
     * Class constructor
     *
     * @param OpenWeatherApiService $openWeatherApi
     * @param WeatherApiService $weatherApi
     * @param OpenWeatherApiFormatterService $openWeatherApiFormatter
     * @param WeatherApiFormatterService $weatherApiFormatter
     */
    public function __construct(
        OpenWeatherApiService          $openWeatherApi,
        WeatherApiService              $weatherApi,
        OpenWeatherApiFormatterService $openWeatherApiFormatter,
        WeatherApiFormatterService     $weatherApiFormatter,
    ) {
        $this->openWeatherApi          = $openWeatherApi;
        $this->weatherApi              = $weatherApi;
        $this->openWeatherApiFormatter = $openWeatherApiFormatter;
        $this->weatherApiFormatter     = $weatherApiFormatter;
    }


    /**
     * Get Temperature by query
     *
     * @param String $query
     * @return Array $array
     **/
    public function getTemperatureByQuery(string $query): Array
    {
        $openWeatherApiRes = $this->openWeatherApi->getTemperatureByQuery($query);
        $weatherApiRes     = $this->weatherApi->getTemperatureByQuery($query);

        if ($openWeatherApiRes['error'] || $weatherApiRes['error']) {
            return $this->error('Something went wrong with the call!');
        }

        $openWeatherFormattedRes = $this->openWeatherApiFormatter->formatResponse($openWeatherApiRes);
        $weatherFormattedRes     = $this->weatherApiFormatter->formatResponse($weatherApiRes);

        if ($openWeatherFormattedRes['error'] || $weatherFormattedRes['error']) {
            return $this->error($openWeatherFormattedRes['message']);
        }

        return $this->success([
            'open_weather_res' => $openWeatherFormattedRes,
            'weather_res'      => $weatherFormattedRes
        ]);
    }

}

?>
