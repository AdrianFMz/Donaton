<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cause_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('amount_mxn'); // pesos enteros por ahora
            $table->string('message', 300)->nullable();

            $table->string('status', 20)->default('pending'); 
            // pending | paid | failed | cancelled

            $table->timestamps();

            $table->index(['user_id', 'cause_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};