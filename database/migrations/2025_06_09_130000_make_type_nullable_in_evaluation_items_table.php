<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // For PostgreSQL, we need to drop and recreate the column
        if (DB::getDriverName() === 'pgsql') {
            // First, drop any constraints that might reference this column
            DB::statement('ALTER TABLE evaluation_items DROP CONSTRAINT IF EXISTS evaluation_items_type_check');
            
            // Then alter the column to be nullable
            DB::statement('ALTER TABLE evaluation_items ALTER COLUMN type DROP NOT NULL');
        } else {
            // For other databases, use the standard Laravel method
            Schema::table('evaluation_items', function (Blueprint $table) {
                $table->enum('type', ['RUKUN', 'SUNAT', 'WAJIB'])->nullable()->change();
            });
        }
    }

    public function down()
    {
        if (DB::getDriverName() === 'pgsql') {
            // For PostgreSQL, we need to ensure the column is not null
            DB::statement('UPDATE evaluation_items SET type = \'RUKUN\' WHERE type IS NULL');
            DB::statement('ALTER TABLE evaluation_items ALTER COLUMN type SET NOT NULL');
        } else {
            // For other databases, use the standard Laravel method
            Schema::table('evaluation_items', function (Blueprint $table) {
                $table->enum('type', ['RUKUN', 'SUNAT', 'WAJIB'])->nullable(false)->change();
            });
        }
    }
};
