<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Director>
 */
class DirectorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'biography' => fake()->sentence(6),
            'image' => 'https://randomuser.me/api/portraits/men/15.jpg',
            'birth_date' => fake()->date('d-m-Y'),
            'nationality' => fake()->country(),
        ];
    }
}
