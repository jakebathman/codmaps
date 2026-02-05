<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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

        View::composer('components.nav', function ($view) {
            $navItems = collect(Route::getRoutes())
                ->filter(fn ($route) => isset($route->defaults['nav']))
                ->map(fn ($route) => [
                    'name' => $route->getName(),
                    'label' => $route->defaults['nav'],
                    'url' => route($route->getName()),
                    'current' => request()->routeIs($route->getName()),
                ])
                ->values();

            $view->with('navItems', $navItems);
        });
    }
}
