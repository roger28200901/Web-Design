<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_id')->unsigned()->default(0);
            $table->string('image_id', 11)->default('');
            $table->string('filename', 255)->default('');
            $table->string('title', 255)->default('');
            $table->text('description')->nullable();
            $table->integer('width')->unsigned()->default(0);
            $table->integer('height')->unsigned()->default(0);
            $table->integer('size')->unsigned()->default(0);
            $table->integer('views')->unsigned()->default(0);
            $table->string('link', 255)->default('');
            $table->timestamps();
            $table->string('delete_token', 16)->default('');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
