<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class MeasurementSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->table = 'measurement';
        $this->filename = base_path().'/database/seeds/csvs/measurement.csv';
    }

    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Recommended when importing larger CSVs
        DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
//        DB::table($this->table)->truncate();

        parent::run();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
