<?php

namespace Database\Seeders;

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;


class DeviceSeeder extends CsvSeeder
{

    public function __construct()
    {
        $this->table = 'device';
        $this->filename = base_path().'/database/seeds/csvs/device.csv';
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
