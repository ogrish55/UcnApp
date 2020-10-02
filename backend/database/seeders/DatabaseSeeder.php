<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(HouseholdSeeder::class);
        $this->call(DeviceSeeder::class);
        $this->call(MeasurementSeeder::class);
        $this->call(UserSeeder::class);
    }
}
