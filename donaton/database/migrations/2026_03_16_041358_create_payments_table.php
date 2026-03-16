<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donation_id')->constrained('donations')->cascadeOnDelete();

            $table->string('provider', 30); // mercadopago | paypal
            $table->string('provider_ref', 120)->nullable(); // id/preference/order id
            $table->string('status', 20)->default('created');
            // created | pending | approved | rejected | cancelled | error

            $table->json('payload')->nullable(); // respuesta cruda (sin secretos)
            $table->timestamps();

            $table->index(['provider', 'status']);
            $table->unique(['provider', 'provider_ref']); // evita duplicados cuando ya hay ref
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};