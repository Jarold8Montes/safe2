<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Operador;
use App\Models\Tracto;
use App\Models\Viaje;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 20 Operador records manually
        $operadores = [];
        for ($i = 0; $i < 20; $i++) {
            $operador = new Operador([
                'id_operador' => 'OP-' . $faker->unique()->randomNumber(5),
                'nombre' => $faker->name(),
                'foto_url' => $faker->imageUrl(640, 480, 'people', true),
                'fecha_nacimiento' => $faker->dateTimeBetween('-50 years', '-18 years'), // Use fecha_nacimiento
                'telefono' => $faker->phoneNumber(),
                'factores_riesgo' => $faker->randomElements([
                    'Hipertensión arterial',
                    'Niveles elevados de colesterol o triglicéridos',
                    'Obesidad o sobrepeso',
                    'Diabetes o prediabetes',
                    'Trastornos hormonales',
                    'Sistema inmunológico debilitado',
                    'ninguno'
                ], $faker->numberBetween(1, 3)), // Use updated factores_riesgo
                'activo' => $faker->boolean(),
            ]);
            $operador->save();
            $operadores[] = $operador;
        }

        // Create 20 Tracto records manually
        $tractos = [];
        for ($i = 0; $i < 20; $i++) {
            $tracto = new Tracto([
                'id_tracto' => 'TR-' . $faker->unique()->randomNumber(5),
                'placas' => $faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
                'marca' => $faker->randomElement(['Volvo', 'Freightliner', 'Kenworth', 'Peterbilt', 'International']),
                'modelo' => $faker->word(),
                'activo' => $faker->boolean(),
            ]);
            $tracto->setConnection('mongodb'); // Explicitly set connection
            $tracto->save();
            $tractos[] = $tracto;
        }

        // Create 20 Viaje records manually
        for ($i = 0; $i < 20; $i++) {
            $viaje = new Viaje([
                'id_viaje' => 'VJ-' . $faker->unique()->randomNumber(5),
                'origen' => $faker->city(),
                'destino' => $faker->city(),
                'fecha' => $faker->dateTimeBetween('-1 year', 'now'),
                'operador_id' => $faker->randomElement($operadores)->_id,
                'tracto_id' => $faker->randomElement($tractos)->_id,
                'estado' => $faker->randomElement(['pendiente', 'en_curso', 'completado', 'cancelado']),
            ]);
            $viaje->setConnection('mongodb'); // Explicitly set connection
            $viaje->save();
        }

        // Create a supervisor user
        $user = new User([
            'id_supervisor' => 'SUP-001',
            'nombre' => 'Supervisor Test',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'), // Hash the password
            'rol' => 'supervisor',
            'activo' => true,
        ]);
        $user->setConnection('mongodb'); // Explicitly set connection
        $user->save();
    }
}