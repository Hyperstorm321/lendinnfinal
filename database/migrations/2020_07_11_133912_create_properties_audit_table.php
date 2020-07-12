<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_audit', function (Blueprint $table) {
            $table->id('properties_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('property_id');
            $table->bigInteger('landlord_id')->unsigned();
            $table->bigInteger('property_type_id')->unsigned();
            $table->string('name');
            $table->double('price_peso', 8, 2);
            $table->double('land_size', 8, 2)->default(0);
            $table->double('living_size', 8, 2)->default(0);
            $table->longText('features')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('is_pet_allowed')->default(false);
            $table->longText('pet_allowed_comment');
            $table->integer('no_of_bedrooms')->default(0);
            $table->integer('no_of_bathrooms')->default(0);

            $table->string('detailed_address')->nullable();
            $table->bigInteger('region_id')->unsigned();
            $table->bigInteger('province_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('barangay_id')->unsigned();
            $table->bigInteger('postal_code_id')->unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties_audit');
    }
}
