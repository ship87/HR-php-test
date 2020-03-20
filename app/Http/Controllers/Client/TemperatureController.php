<?php

namespace App\Http\Controllers\Client;

use App\Common\Weather\Services\Weather as WeatherService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class TemperatureController
 * @package App\Http\Controllers\Client
 */
class TemperatureController extends BaseController
{
    /**
     * Страница отображения температуры
     *
     * @param WeatherService $weatherService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(WeatherService $weatherService)
    {
        $temperature = null;
        try {

            $weatherService->setAdapter(WeatherService::ADAPTER_YANDEX);
            $temperature = $weatherService->getCurrentTemperature(WeatherService::CITY_BRYANSK);

        } catch (Exception $e) {
            Log::info($e->getMessage(), ['code' => $e->getCode()]);
        }

        return view('client.temperature.index', [
                'title' => 'Температура в Брянске',
                'temperature' => $temperature
            ]
        );
    }
}
