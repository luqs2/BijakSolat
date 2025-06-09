<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluation_items', function (Blueprint $table) {
            $table->enum('type', ['RUKUN', 'SUNAT', 'WAJIB'])->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('evaluation_items', function (Blueprint $table) {
            $table->enum('type', ['RUKUN', 'SUNAT', 'WAJIB'])->nullable(false)->change();
        });
    }
};
