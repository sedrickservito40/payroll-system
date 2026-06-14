<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dtr', function (Blueprint $table) {
            $table->decimal('overtime', 8, 2)->nullable()->change();
            $table->string('ot_type')->nullable()->change();
            $table->decimal('night_hours', 8, 2)->nullable()->change();
        });

        // MySQL reorder workaround (raw SQL)
        DB::statement("
            ALTER TABLE dtr 
            MODIFY overtime DECIMAL(8,2) NULL AFTER cutoff,
            MODIFY ot_type VARCHAR(255) NULL AFTER overtime,
            MODIFY night_hours DECIMAL(8,2) NULL AFTER ot_type
        ");
    }

    public function down(): void
    {
        // optional rollback
    }
};