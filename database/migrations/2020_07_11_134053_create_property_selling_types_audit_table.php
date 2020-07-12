<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertySellingTypesAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_selling_types_audit', function (Blueprint $table) {
            $table->id('property_selling_types_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('property_selling_type_id');
            $table->bigInteger('property_id')->unsigned();
            $table->bigInteger('selling_type_id')->unsigned();
            $table->integer('max_quantity')->default(0);
            $table->integer('available_quantity')->default(0);

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
        Schema::dropIfExists('property_selling_types_audit');
    }
}
