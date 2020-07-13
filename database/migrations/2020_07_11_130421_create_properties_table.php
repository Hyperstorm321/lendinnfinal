<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id('property_id');
            $table->bigInteger('landlord_id')->unsigned();
            $table->bigInteger('property_type_id')->unsigned();
            $table->string('name');
            $table->double('price_peso', 8, 2);
            $table->double('land_size', 8, 2)->default(0);
            $table->double('living_size', 8, 2)->default(0);
            $table->longText('features')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('is_pet_allowed')->default(false);
            $table->longText('pet_allowed_comment')->nullable();
            $table->integer('no_of_bedrooms')->default(0);
            $table->integer('no_of_bathrooms')->default(0);
            $table->boolean('is_salable')->default(1);
            $table->string('main_photo_src', 255)->nullable();

            $table->string('detailed_address')->nullable();
            $table->bigInteger('region_id')->unsigned()->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('barangay_id')->unsigned()->nullable();
            $table->bigInteger('postal_code_id')->unsigned()->nullable();

            $table->foreign('property_type_id')
                  ->references('property_type_id')->on('property_types');

            $table->foreign('landlord_id')
                  ->references('landlord_id')->on('landlords');

            $table->foreign('region_id')
                  ->references('region_id')->on('regions');
            
            $table->foreign('province_id')
                  ->references('province_id')->on('provinces');
            
            $table->foreign('city_id')
                  ->references('city_id')->on('cities');
            
            $table->foreign('barangay_id')
                  ->references('barangay_id')->on('barangays');
            
            $table->foreign('postal_code_id')
                  ->references('postal_code_id')->on('postal_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
