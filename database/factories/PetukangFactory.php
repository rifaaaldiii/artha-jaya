<?php

namespace Database\Factories;

use App\Models\petukang;
use App\Models\team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

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
        $faker = FakerFactory::create();
        
        return [
            'nama' => $faker->name(),
            'status' => $faker->randomElement(['ready', 'busy']),
            'kontak' => $faker->phoneNumber(),
            'team_id' => team::factory(),
        ];
    }
}

