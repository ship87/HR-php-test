<?php

namespace App\Services\Weather\Adapters;

use App\Common\Weather\Exceptions\ErrorGetWeather;
use App\Common\Weather\Services\Weather;

/**
 * Class WeatherAdapterYandex - адаптер для получения погоды по api Яндекса
 * @package App\Services\Weather\Adapters
 */
class WeatherAdapterYandex extends WeatherAdapter
{
    /**
     * Координаты городов
     *
     * @var array
     */
    CONST CITIES_COORDINATES = [
        Weather::CITY_BRYANSK => [
            'lat' => '53.270955',
            'lon' => '34.360938'
        ]
    ];

    /**
     * Параметры для запроса к API
     *
     * @var array
     */
    protected $paramsApi;

    /**
     * Тестовый режим работы API
     *
     * @var bool
     */
    protected $testMode;

    /**
     * Ключ авторизации API
     *
     * @var string
     */
    protected $key;

    /**
     * Url запроса к API
     *
     * @var string
     */
    protected $url;

    /**
     * Url тестового запроса к API
     *
     * @var string
     */
    protected $urlTest;

    /**
     * WeatherAdapterYandex constructor.
     * @param $testMode
     * @param $key
     * @param $url
     * @param $urlTest
     */
    public function __construct($testMode, $key, $url, $urlTest)
    {
        $this->testMode = $testMode;
        $this->key = $key;
        $this->url = $url;
        $this->urlTest = $urlTest;
    }

    /**
     * @inheritdoc
     */
    public function getCurrentTemperature()
    {
        $data = $this->getData();
        return isset($data['fact']['temp']) ? $data['fact']['temp'] : null;
    }

    /**
     * @inheritdoc
     */
    public function setCity(int $city)
    {
        if (empty(self::CITIES_COORDINATES[$city])) {
            throw new ErrorGetWeather('Город не найден');
        }

        $this->paramsApi = self::CITIES_COORDINATES[$city];
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        $endpoint = $this->testMode ? $this->urlTest : $this->url;

        $header = [
            'X-Yandex-API-Key: ' . $this->key,
        ];

        $content = $this->getRequestData($endpoint, $this->paramsApi, $header);

        if (is_string($content)) {
            $result = json_decode($content, true);
            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new ErrorGetWeather('Ошибка при декодировании результата запроса к api');
            }
        } else {
            throw new ErrorGetWeather('Ошибка при запросе к api');
        }

        return $result;
    }
}