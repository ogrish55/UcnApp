<?php

namespace App\Providers;

use App\Http\Database\GetDataDB;
use App\Http\Database\iDatabase;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(iDatabase::class, GetDataDB::class);
    }
}
