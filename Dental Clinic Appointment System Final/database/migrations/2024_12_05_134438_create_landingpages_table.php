<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingpagesTable extends Migration
{
    public function up()
    {
        Schema::create('landingpages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->date('date');
            $table->time('time');
            $table->timestamps(); // automatically includes created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('landingpages');
    }
}
