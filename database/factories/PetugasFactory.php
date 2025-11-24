<?php

namespace Database\Factories;

use App\Models\petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\petugas>
 */
class PetugasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = petugas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'status' => fake()->randomElement(['ready', 'busy']),
            'kontak' => fake()->phoneNumber(),
        ];
    }
}

