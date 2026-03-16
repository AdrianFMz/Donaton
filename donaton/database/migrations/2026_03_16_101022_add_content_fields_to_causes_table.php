<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->text('problem')->nullable()->after('short_description');
            $table->string('since', 80)->nullable()->after('problem');
            $table->text('funds_usage')->nullable()->after('since');
            $table->text('impact')->nullable()->after('funds_usage');
        });
    }

    public function down(): void
    {
        Schema::table('causes', function (Blueprint $table) {
            $table->dropColumn(['problem', 'since', 'funds_usage', 'impact']);
        });
    }
};