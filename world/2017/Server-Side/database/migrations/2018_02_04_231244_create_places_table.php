<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('x');
            $table->integer('y');
            $table->string('image_path', 50);
            $table->text('description');
        });
        DB::statement('ALTER TABLE `places` ADD `longitude` FLOAT NOT NULL AFTER `name`, ADD `latitude` FLOAT NOT NULL AFTER `name`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
