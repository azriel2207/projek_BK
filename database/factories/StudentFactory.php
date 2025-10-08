<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'student']),
            'nama_lengkap' => $this->faker->name(),
            'nis' => $this->faker->unique()->numerify('########'),
            'tgl_lahir' => $this->faker->dateTimeBetween('-18 years', '-15 years')->format('Y-m-d'),
            'alamat' => $this->faker->address(),
            'no_hp' => $this->faker->phoneNumber(),
            'kelas' => $this->faker->randomElement(['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1', 'XII IPA 2']),
        ];
    }
}