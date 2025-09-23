<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $manager = \Illuminate\Support\Facades\DB::connection('mongodb')->getMongoClient()->getManager();
        $databaseName = \Illuminate\Support\Facades\DB::connection('mongodb')->getDatabaseName();

        // Operadores
        $manager->executeCommand($databaseName, new \MongoDB\Driver\Command([
            'createIndexes' => 'operadores',
            'indexes' => [
                ['key' => ['id_operador' => 1], 'name' => 'id_operador_1'],
                ['key' => ['nombre' => 'text'], 'name' => 'nombre_text'],
            ],
        ]));

        // Viajes
        $manager->executeCommand($databaseName, new \MongoDB\Driver\Command([
            'createIndexes' => 'viajes',
            'indexes' => [
                ['key' => ['fecha' => -1], 'name' => 'fecha_-1'],
                ['key' => ['operador_id' => 1], 'name' => 'operador_id_1'],
                ['key' => ['tracto_id' => 1], 'name' => 'tracto_id_1'],
                ['key' => ['id_viaje' => 1], 'name' => 'id_viaje_1'],
            ],
        ]));

        // Dictamenes
        $manager->executeCommand($databaseName, new \MongoDB\Driver\Command([
            'createIndexes' => 'dictamenes',
            'indexes' => [
                ['key' => ['operador_id' => 1, 'fecha' => -1], 'name' => 'operador_id_1_fecha_-1'],
                ['key' => ['viaje_id' => 1, 'fecha' => -1], 'name' => 'viaje_id_1_fecha_-1'],
                ['key' => ['apto' => 1], 'name' => 'apto_1'],
            ],
        ]));

        // Alertas
        $manager->executeCommand($databaseName, new \MongoDB\Driver\Command([
            'createIndexes' => 'alertas',
            'indexes' => [
                ['key' => ['leida' => 1, 'fecha' => -1], 'name' => 'leida_1_fecha_-1'],
                ['key' => ['operador_id' => 1], 'name' => 'operador_id_1'],
            ],
        ]));

        // Usuarios
        // Drop the 'usuarios' collection if it exists to ensure a clean slate for unique index
        try {
            $manager->executeCommand($databaseName, new \MongoDB\Driver\Command(['drop' => 'usuarios']));
        } catch (\MongoDB\Driver\Exception\CommandException $e) {
            // Collection might not exist, ignore error
        }
        $manager->executeCommand($databaseName, new \MongoDB\Driver\Command([
            'createIndexes' => 'usuarios',
            'indexes' => [
                ['key' => ['email' => 1], 'name' => 'email_1', 'unique' => true],
            ],
        ]));
    }

    public function down(): void {}
};