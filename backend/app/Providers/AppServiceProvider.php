<?php

namespace App\Providers;

use App\Http\Controllers\DataController;
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
//        $this->app->bind(iDatabase::class, GetDataDB::class);  -- If a class needs iDatabase, give GetDataDB.
        $this->app->when(DataController::class)->needs(iDatabase::class)->give(GetDataDB::class); // When DataController needs iDatabase, give GetDataDB.
        // Above is usefull if you hvae multiple classes using the same interface, but needs a different implementaion. Example could be one class using mongoDB implementaion, and other class
        // Using mySql implementation.
    }
}
