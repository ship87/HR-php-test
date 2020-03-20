<?php

namespace App\Services\Weather\Adapters;

use App\Common\Weather\Exceptions\ErrorGetWeather;

/**
 * Class WeatherAdapter - базовый адаптер для получения погоды
 * @package App\Services\Weather\Adapters
 */
abstract class WeatherAdapter
{
    /**
     * Получить погоду
     *
     * @throws ErrorGetWeather
     * @return null|int
     */
    abstract public function getCurrentTemperature();

    /**
     * Получить данные о погоде
     *
     * @throws ErrorGetWeather
     * @return array
     */
    abstract protected function getData();

    /**
     * Установить город
     *
     * @param int $city
     * @throws ErrorGetWeather
     */
    abstract public function setCity(int $city);

    /**
     * Выполнить запрос для получения данных
     *
     * @param string $urlApi
     * @param array $params
     * @param array $header
     * @return mixed|null
     */
    protected function getRequestData(string $urlApi, array $params, array $header)
    {
        $requestParams = http_build_query($params);
        $urlApi = $urlApi . '?' . $requestParams;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urlApi);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($curl);

        $info = curl_getinfo($curl);

        $r = null;
        if ($info['http_code'] == 200) {
            $r = $result;
        }

        return $r;
    }
}