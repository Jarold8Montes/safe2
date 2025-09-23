<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tracto; // Import the Tracto model

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tracto>
 */
class TractoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tracto::class;

    protected $connection = 'mongodb';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_tracto' => 'TR-' . $this->faker->unique()->randomNumber(5),
            'placas' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'marca' => $this->faker->randomElement(['Volvo', 'Freightliner', 'Kenworth', 'Peterbilt', 'International']),
            'modelo' => $this->faker->word(),
            'activo' => $this->faker->boolean(),
        ];
    }
}