<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role' => 'student',
            'email_verified_at' => now(),
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'username' => 'admin',
                'email' => 'admin@school.com',
                'role' => 'admin',
            ];
        });
    }

    public function counselor()
    {
        return $this->state(function (array $attributes) {
            return [
                'username' => 'konselor1',
                'email' => 'konselor@school.com',
                'role' => 'counselor',
            ];
        });
    }

    public function student()
    {
        return $this->state(function (array $attributes) {
            return [
                'username' => 'siswa1',
                'email' => 'siswa@school.com',
                'role' => 'student',
            ];
        });
    }
}