<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['TRAIN', 'BUS'])->default('TRAIN');
            $table->integer('line')->default(0);
            $table->integer('from_place_id')->default(0);
            $table->integer('to_place_id')->default(0);
            $table->time('departure_time')->useCurrent();
            $table->time('arrival_time')->useCurrent();
            $table->integer('distance')->default(0);
            $table->integer('speed')->default(0);
            $table->enum('status', ['AVAILABLE', 'UNAVAILABLE'])->default('AVAILABLE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
