<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CostumerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstname'=>$this->faker->firstName(),
            'lastname'=>$this->faker->lastName(),
            'passport'=>$this->faker->unique()->creditCardNumber(),
        ];
    }
}
