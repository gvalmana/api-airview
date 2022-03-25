<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Airport::factory(5)->create();
    }
}
