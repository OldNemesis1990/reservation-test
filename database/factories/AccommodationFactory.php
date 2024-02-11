<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Accommodation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Accommodation>
 */
class AccommodationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Accommodation::class;

    public function definition()
    {
        $accommodationType = $this->faker->randomElement(['Guest Room', 'Cabin', 'Luxury Suite']);
        $descriptionPrefix = $accommodationType == 'Luxury Suite' ? 'Experience luxury like never before. ' : '';

        return [
            'title' => $accommodationType . ' ' . $this->faker->unique()->word,
            'description' => $descriptionPrefix . $this->faker->paragraph,
            'price_per_person' => $this->faker->randomFloat(2, 1000, 5000),
            'user_id' => 1,
        ];
    }
}
