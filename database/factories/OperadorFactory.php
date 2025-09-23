<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Operador; // Import the Operador model

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operador>
 */
class OperadorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Operador::class;

    protected $connection = 'mongodb';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_operador' => 'OP-' . $this->faker->unique()->randomNumber(5),
            'nombre' => $this->faker->name(),
            'foto_url' => $this->faker->imageUrl(640, 480, 'people', true),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-50 years', '-18 years'), // Generate a date of birth
            'telefono' => $this->faker->phoneNumber(),
            'factores_riesgo' => $this->faker->randomElements([
                'Hipertensión arterial',
                'Niveles elevados de colesterol o triglicéridos',
                'Obesidad o sobrepeso',
                'Diabetes o prediabetes',
                'Trastornos hormonales',
                'Sistema inmunológico debilitado',
                'ninguno'
            ], $this->faker->numberBetween(1, 3)),
            'activo' => $this->faker->boolean(),
        ];
    }
}