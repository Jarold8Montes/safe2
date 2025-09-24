<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('mongodb')->table('operadores', function (Blueprint $collection) {
            $collection->index('id_operador');
            $collection->index(['nombre' => 'text']);
        });

        Schema::connection('mongodb')->table('viajes', function (Blueprint $collection) {
            $collection->index('fecha');
            $collection->index('operador_id');
            $collection->index('tracto_id');
            $collection->index('id_viaje');
        });

        Schema::connection('mongodb')->table('dictamenes', function (Blueprint $collection) {
            $collection->index(['operador_id' => 1, 'fecha' => -1]);
            $collection->index(['viaje_id' => 1, 'fecha' => -1]);
            $collection->index('apto');
        });

        Schema::connection('mongodb')->table('alertas', function (Blueprint $collection) {
            $collection->index(['leida' => 1, 'fecha' => -1]);
            $collection->index('operador_id');
        });

        Schema::connection('mongodb')->table('usuarios', function (Blueprint $collection) {
            $collection->unique('email');
        });
    }

    public function down(): void {}
};