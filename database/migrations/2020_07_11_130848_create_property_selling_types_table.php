<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertySellingTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_selling_types', function (Blueprint $table) {
            $table->id('property_selling_type_id');
            $table->bigInteger('property_id')->unsigned();
            $table->bigInteger('selling_type_id')->unsigned();
            $table->integer('max_quantity')->default(0);
            $table->integer('available_quantity')->default(0);

            $table->foreign('property_id')
                  ->references('property_id')->on('properties')
                  ->onDelete('cascade');

            $table->foreign('selling_type_id')
                  ->references('selling_type_id')->on('selling_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_selling_types');
    }
}
