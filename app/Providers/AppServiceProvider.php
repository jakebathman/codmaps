<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::macro('livewire_current', function () {
            if (request()->route()->named('livewire.update')) {
                $previousUrl = url()->previous();
                $previousRoute = app('router')->getRoutes()->match(request()->create($previousUrl));
                return $previousRoute->getName();
            }

            return request()->route()->getName();
        });
    }
}
