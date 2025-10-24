<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'patient'])->id,
            'full_name' => $this->faker->name(),
            'date_of_birth' => $this->faker->dateTimeBetween('-80 years', '-18 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'full_address' => $this->faker->address(),
            'contact_number' => $this->faker->phoneNumber(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_number' => $this->faker->phoneNumber(),
            'civil_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'occupation' => $this->faker->jobTitle(),
            'religion' => $this->faker->randomElement(['Catholic', 'Protestant', 'Muslim', 'Buddhist', 'None']),
        ];
    }
}
