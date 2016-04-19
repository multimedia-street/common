<?php

namespace Mmstreet\Common;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Other Providers included to this package.
     *
     * @var array
     */
    protected $otherProviders = [
        \Intervention\Image\ImageServiceProvider::class,
        \Orangehill\Iseed\IseedServiceProvider::class,
        \Clockwork\Support\Laravel\ClockworkServiceProvider::class,
        \Barryvdh\Cors\ServiceProvider::class,
        \Maatwebsite\Excel\ExcelServiceProvider::class,
        \Barryvdh\DomPDF\ServiceProvider::class,
    ];

    /**
     * Other Middleware included to this package.
     *
     * @var array
     */
    protected $otherMiddlewares = [
        \Clockwork\Support\Laravel\ClockworkMiddleware::class,
        \Barryvdh\Cors\HandleCors::class,
    ];


    /**
     * @var boolean
     */
    protected $defer = false;

    /**
     * Register any package services and middlewares.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->otherProviders as $key => $value) {
            $this->app->register($value);
        }
    }

    public function boot()
    {
        $this->addMiddlewares($this->app->make('Illuminate\Contracts\Http\Kernel'));
    }

    protected function addMiddlewares(Kernel $kernel)
    {
        foreach ($this->otherMiddlewares as $key => $value) {
            $kernel->pushMiddleware($value);
        }
    }
}
