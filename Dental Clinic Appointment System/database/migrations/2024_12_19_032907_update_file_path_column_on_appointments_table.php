<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('file_path', 300)->nullable(false)->change(); // Ensure NOT NULL
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('file_path', 300)->nullable()->change();
        });
    }
};
