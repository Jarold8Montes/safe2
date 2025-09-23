<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Viaje; // Import the Viaje model

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Viaje>
 */
class ViajeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Viaje::class;

    protected $connection = 'mongodb';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_viaje' => 'VJ-' . $this->faker->unique()->randomNumber(5),
            'origen' => $this->faker->city(),
            'destino' => $this->faker->city(),
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'operador_id' => null, // Will be set in the seeder
            'tracto_id' => null,   // Will be set in the seeder
            'estado' => $this->faker->randomElement(['pendiente', 'en_curso', 'completado', 'cancelado']),
        ];
    }
}