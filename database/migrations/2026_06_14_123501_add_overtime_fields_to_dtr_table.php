<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dtr', function (Blueprint $table) {
            $table->decimal('overtime', 8, 2)->nullable();
            $table->string('ot_type')->nullable();
            $table->decimal('night_hours', 8, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('dtr', function (Blueprint $table) {
            $table->dropColumn(['overtime', 'ot_type', 'night_hours']);
        });
    }
};
