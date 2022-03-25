<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Flight::factory(15)->create()->each(function ($flight){
                \App\Models\Costumer::factory(20)->make()->each(
                    function ($customer) use($flight){
                        $flight->Pasajeros()->save($customer);
                    });
            });
    }
}
