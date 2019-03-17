<?php

namespace Alvinhu\ChildSelect;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('child-select', __DIR__.'/../dist/js/field.js');
            Nova::style('child-select', __DIR__.'/../dist/css/field.css');
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/child-select')
            ->namespace('Alvinhu\ChildSelect\Http\Controllers')
            ->group(__DIR__.'/../routes/api.php');
    }

    public function register()
    {
        //
    }
}
