<?php

namespace App\Services;

use App\Models\Weather;
use App\Traits\ResponseApi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WeatherService
{
    use ResponseApi;

    /**
     * @var Weather $model
     */
    protected $model;

    /**
     * Class constructor
     *
     * @param Weather $model
     */
    public function __construct(Weather $model)
    {
        $this->model = $model;
    }

    /**
     * Store new weather forecast
     *
     * @param Array $data
     * @return Array
     */
    public function store(Array $data) : Array
    {
        $response = [];

        DB::beginTransaction();

        try {
            $data['is_cache'] = true;
            $data['error']    = false;
            $data['message']  = null;
            $this->model->create($data);

            // Caching
            $cacheKey = env('CACHED_KEY');
            Cache::forget($cacheKey);
            Cache::remember($cacheKey, 300, function() use ($data) {
                return $data;
            });

            DB::commit();

            $response = $this->success("Successfully saved.");
        } catch(\Exception $e) {
            DB::rollBack();

            $errMsg = $e->getMessage();
            Log::error($errMsg);
            $response = $this->error($errMsg);
        }

        return $response;
    }
}

?>
