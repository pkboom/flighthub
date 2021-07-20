<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained();
            $table->unsignedInteger('number');
            $table->unsignedBigInteger('departure_airport_id');
            $table->timestamp('departure_time');
            $table->unsignedBigInteger('arrival_airport_id');
            $table->timestamp('arrival_time');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('departure_airport_id')->references('id')->on('airports');
            $table->foreign('arrival_airport_id')->references('id')->on('airports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
