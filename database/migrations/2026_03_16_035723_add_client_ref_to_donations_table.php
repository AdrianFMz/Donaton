<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        // 1) Agregar columna como NULLABLE para no romper registros existentes
        if (!Schema::hasColumn('donations', 'client_ref')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->uuid('client_ref')->nullable()->after('id');
            });
        }

        // 2) Rellenar UUIDs para registros que ya existen (NULL o '')
        $ids = DB::table('donations')
            ->whereNull('client_ref')
            ->orWhere('client_ref', '')
            ->pluck('id');

        foreach ($ids as $id) {
            DB::table('donations')
                ->where('id', $id)
                ->update(['client_ref' => (string) Str::uuid()]);
        }

        // 3) Crear UNIQUE index (solo si no existe)
        $indexExists = DB::select("SHOW INDEX FROM donations WHERE Key_name = 'donations_client_ref_unique'");
        if (count($indexExists) === 0) {
            Schema::table('donations', function (Blueprint $table) {
                $table->unique('client_ref');
            });
        }
    }

    public function down(): void
    {
        // Quitar índice si existe
        $indexExists = DB::select("SHOW INDEX FROM donations WHERE Key_name = 'donations_client_ref_unique'");
        if (count($indexExists) > 0) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropUnique(['client_ref']);
            });
        }

        // Quitar columna si existe
        if (Schema::hasColumn('donations', 'client_ref')) {
            Schema::table('donations', function (Blueprint $table) {
                $table->dropColumn('client_ref');
            });
        }
    }
};