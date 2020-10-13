<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('users')->insert([
            'userID' => 1,
            'firstName' => 'Jonas',
            'lastName' => 'Haxholm',
            'address' => $faker->address,
            'email' => 'JonasMail@gmail.com',
            'phoneNumber' => $faker->phoneNumber,
        ]);

        DB::table('users')->insert([
            'userID' => 2,
            'firstName' => 'Dan',
            'lastName' => 'Lund Pedersen',
            'address' => $faker->address,
            'email' => 'DanLund@gmail.com',
            'phoneNumber' => $faker->phoneNumber,
        ]);

        DB::table('users')->insert([
            'userID' => 3,
            'firstName' => 'Edin',
            'lastName' => 'Med Sandwich',
            'address' => $faker->address,
            'email' => 'Idin@gmail.com',
            'phoneNumber' => $faker->phoneNumber,
        ]);

        DB::table('users')->insert([
            'userID' => 4,
            'firstName' => 'Kristoffer',
            'lastName' => 'Kristensen',
            'address' => $faker->address,
            'email' => 'Krisser@gmail.com',
            'phoneNumber' => $faker->phoneNumber,
        ]);

        DB::table('users')->insert([
            'userID' => 5,
            'firstName' => 'Aksel',
            'lastName' => 'Søborg',
            'address' => $faker->address,
            'email' => 'Asøborg@gmail.com',
            'phoneNumber' => $faker->phoneNumber,
        ]);
    }
}
