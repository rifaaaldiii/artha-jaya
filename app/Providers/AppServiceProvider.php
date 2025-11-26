<?php

namespace App\Providers;

use App\Models\Jasa;
use App\Models\Produksi;
use App\Models\Team;
use App\Observers\JasaObserver;
use App\Observers\ProduksiObserver;
use App\Observers\TeamObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Jasa::observe(JasaObserver::class);
        Produksi::observe(ProduksiObserver::class);
        Team::observe(TeamObserver::class);
    }
}