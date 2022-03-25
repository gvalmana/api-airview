<?php

namespace Database\Seeders;

use Database\Factories\AirportFactory;
use Faker\Factory;
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
        // \App\Models\User::factory(10)->create();
        \App\Models\Airport::factory(3)->create();
        \App\Models\Flight::factory(10)->create()->each(function ($flight){
        \App\Models\Costumer::factory(20)->make()->each(
            function ($customer) use($flight){
                $flight->Pasajeros()->save($customer);
            });
        });
    }
}
