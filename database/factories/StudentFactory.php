<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' =>$this->faker->firstName,
            'last_name' =>$this->faker->lastName,
            'birthdate' =>$this->faker->date,
            'gender' => $this->randomElement(UserRoleEnum::getValues()),
            'role' => $faker->randomElement(UserRoleEnum::getValues()),
        ];
    }
}
