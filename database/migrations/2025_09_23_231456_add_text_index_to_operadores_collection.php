<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint as MongoBlueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mongodb')->table('operadores', function (MongoBlueprint $collection) {
            try {
                $collection->dropIndex('nombre_text');
            } catch (\Exception $e) {
                // Ignore if index does not exist
            }
            $collection->index(['nombre' => 'text'], 'operadores_nombre_text_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->table('operadores', function (MongoBlueprint $collection) {
            $collection->dropIndex('operadores_nombre_text_index');
        });
    }
};