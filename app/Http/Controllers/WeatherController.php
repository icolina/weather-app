<?php

namespace App\Http\Controllers;

use App\Services\WeatherDisplayService;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WeatherController extends Controller
{
    /**
     * var WeatherDisplayService $weatherDisplayService
     */
    protected $weatherDisplayService;

    /**
     * var WeatherService $weatherService
     */
    protected $weatherService;

    /**
     * Class Constructor
     *
     * @param WeatherDisplayService $weatherDisplayService
     * @param WeatherService        $weatherService
     */
    public function __construct(WeatherDisplayService $weatherDisplayService, WeatherService $weatherService)
    {
        $this->weatherDisplayService = $weatherDisplayService;
        $this->weatherService        = $weatherService;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) : View
    {
        $query  = $request->input('query') ?? null;
        $reset  = $request->query('reset') ? true : false;

        $result = $this->weatherDisplayService->displayResult($query, $reset);

        return view('weather.index')
                    ->withQuery($query)
                    ->withResult($result);
    }

    /**
     * Store new weather forecast
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) : JsonResponse
    {
        $weather = $this->weatherService->store($request->except('_token'));

        return response()->json($weather);
    }
}
