<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyOwnedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_owned', function (Blueprint $table) {
            $table->id('property_owned_id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('property_id')->unsigned();

            $table->integer('quantity')->default(0);
            $table->dateTime('date_acquired');

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users');

            $table->foreign('property_id')
                  ->references('property_id')->on('properties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_owned');
    }
}
