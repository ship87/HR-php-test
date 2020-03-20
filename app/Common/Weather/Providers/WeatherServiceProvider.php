<?php

namespace App\Common\Weather\Providers;

use App\Services\Weather\Adapters\WeatherAdapterYandex;
use Illuminate\Support\ServiceProvider;

/**
 * Class WeatherServiceProvider - данные для соединения с api погоды
 * @package App\Common\Weather\Providers
 */
class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WeatherAdapterYandex::class, function ($app) {
            return new WeatherAdapterYandex(
                $app['config']['app.yandex_weather_api.test_mode'],
                $app['config']['app.yandex_weather_api.key'],
                $app['config']['app.yandex_weather_api.url'],
                $app['config']['app.yandex_weather_api.url_test']
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
