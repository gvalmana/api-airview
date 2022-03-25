<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AirportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'iataCode'=>Str::random(3),
            'city'=>$this->faker->city,
            'state'=>$this->faker->stateAbbr
        ];
    }
}
