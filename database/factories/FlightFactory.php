<?php

namespace Database\Factories;

use DateInterval;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $flightHours = $this->faker->numberBetween(1, 5);
        $flightTime = new DateInterval('PT'.$flightHours.'H');
        $arrival = $this->faker->dateTime();
        $depart = clone $arrival;
        $depart->sub($flightTime);

        return [
            'flightNumber' => Str::random(3) . $this->faker->unique()->randomNumber(5),
            'arrivalAirport_id' => $this->faker->numberBetween(1,5),
            'arrivalDateTime' => $arrival,
            'departureAirport_id'=>$this->faker->numberBetween(1,5),
            'departureDateTime'=>$depart,
            'status'=>$this->faker->boolean() ? "ontime" : "delayed"
        ];
    }
}
