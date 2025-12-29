<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert sessions table to InnoDB to avoid table-level locking
        DB::statement('ALTER TABLE `sessions` ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to MyISAM if needed (rare). Adjust if your original engine differs.
        DB::statement('ALTER TABLE `sessions` ENGINE = MyISAM');
    }
};
