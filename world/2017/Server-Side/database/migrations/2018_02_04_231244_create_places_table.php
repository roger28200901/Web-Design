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
            $table->string('code', 3)->default('');
            $table->string('name', 100)->default('');
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->string('image_path', 50)->default('');
            $table->text('description')->nullable();
        });
        DB::statement('ALTER TABLE `places` ADD `longitude` FLOAT NOT NULL DEFAULT 0 AFTER `name`, ADD `latitude` FLOAT NOT NULL DEFAULT 0 AFTER `name`');
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
