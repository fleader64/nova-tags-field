<?php

namespace Spatie\TagsField;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class TagsFieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::mix('nova-tags-field', __DIR__.'/../dist/mix-manifest.json');
            //Nova::style('nova-tags-field', __DIR__.'/../dist/css/field.css');
        });

        $this->app->booted(function () {
            $this->routes();
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware('nova:api')
            ->prefix('nova-vendor/spatie/nova-tags-field')
            ->group(__DIR__.'/../routes/api.php');
    }
}
