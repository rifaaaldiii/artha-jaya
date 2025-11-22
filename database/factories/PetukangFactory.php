<?php

namespace Database\Factories;

use App\Models\petukang;
use App\Models\team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\petukang>
 */
class PetukangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = petukang::class;

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
            'team_id' => team::factory(),
        ];
    }
}

