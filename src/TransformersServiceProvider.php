<?php

namespace Themsaid\Transformers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;

class TransformersServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/modelTransformers.php' => config_path('modelTransformers.php'),
        ]);
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/modelTransformers.php', 'modelTransformers'
        );
    }
}