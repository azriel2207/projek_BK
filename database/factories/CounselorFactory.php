<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CounselorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'counselor']),
            'nama_lengkap' => $this->faker->name(),
            'nip' => $this->faker->unique()->numerify('##################'),
            'no_hp' => $this->faker->phoneNumber(),
        ];
    }
}