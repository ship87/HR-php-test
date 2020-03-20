<?php

namespace App\Common\Weather\Services;

use App\Common\Weather\Exceptions\ErrorGetWeather;
use App\Services\Weather\Adapters\WeatherAdapter;
use App\Services\Weather\Adapters\WeatherAdapterYandex;
use App;
use Illuminate\Support\Facades\Cache;

/**
 * Class Weather
 * @package App\Common\Weather\Services
 */
class Weather
{
    /**
     * Город Брянск для получения погоды
     *
     * @var int
     */
    CONST CITY_BRYANSK = 1;

    /**
     * Адаптер Яндекс погоды
     *
     * @var int
     */
    CONST ADAPTER_YANDEX = 1;

    /**
     * Количество минут для кэширования температуры
     *
     * @var int
     */
    CONST CACHE_TIME_TEMPERATURE = 10;

    /**
     * Доступные адаптеры
     *
     * @var array
     */
    protected $avaliableAdapters = [
        self::ADAPTER_YANDEX => WeatherAdapterYandex::class
    ];

    /**
     * Адаптер
     *
     * @var WeatherAdapter
     */
    protected $adapter;

    /**
     * Установить адаптер
     *
     * @param int $adapter
     * @throws ErrorGetWeather
     */
    public function setAdapter(int $adapter)
    {

        if (!$this->isExistAdapter($adapter)) {
            throw new ErrorGetWeather('Ошибка выбора адаптера для погоды');
        }
        $this->adapter = App::make($this->avaliableAdapters[$adapter]);
    }

    /**
     * Получить текущую температуру
     *
     * @param $city
     * @return int|null
     * @throws \App\Common\Weather\Exceptions\ErrorGetWeather
     */
    public function getCurrentTemperature(int $city)
    {

        $this->adapter->setCity($city);

        $result = Cache::remember("weather for city " . $city, self::CACHE_TIME_TEMPERATURE, function () {
            return $this->adapter->getCurrentTemperature();
        });

        return $result;
    }

    /**
     * Адаптер погоды существует
     *
     * @param int $adapter
     * @return bool
     */
    protected function isExistAdapter(int $adapter)
    {
        return in_array($adapter, array_keys($this->avaliableAdapters));
    }
}