<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('service');
            $table->string('subservice');
            $table->string('amount');
            $table->date('date');
            $table->string('time');
            $table->string('file')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
