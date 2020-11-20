<?php

namespace Database\Seeders;

use http\Client\Request;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for($i=6; $i <= 24; $i++){
            DB::table('users')->insert([
                'userID' => $i,
                'firstName' => $faker->firstName,
                'lastName' => $faker->lastName,
                'address' => $faker->address,
                'email' => $faker->email,
                'phoneNumber' => $faker->phoneNumber,
                'password' => Hash::make('password'),
                'regionID' => '2',
                'aconto' => '350'
            ]);
        }
    }
}
