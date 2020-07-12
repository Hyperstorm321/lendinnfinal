<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('property_id')->unsigned();
            $table->bigInteger('transaction_type_id')->unsigned();
            $table->longText('message')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users');

            $table->foreign('property_id')
                  ->references('property_id')->on('properties');

            $table->foreign('transaction_type_id')
                  ->references('transaction_type_id')->on('transaction_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
