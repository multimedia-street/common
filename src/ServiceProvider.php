<?php

namespace Mmstreet;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Other Providers included to this package.
     *
     * @var array
     */
    protected $otherProviders = [
        Intervention\Image\ImageServiceProvider::class,
        Orangehill\Iseed\IseedServiceProvider::class,
        Clockwork\Support\Laravel\ClockworkServiceProvider::class,
        Barryvdh\Cors\ServiceProvider::class,
    ];

    /**
     * @var boolean
     */
    protected $defer = false;

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->otherProviders as $key => $value) {
            $this->app->register($value);
        }
    }
}
