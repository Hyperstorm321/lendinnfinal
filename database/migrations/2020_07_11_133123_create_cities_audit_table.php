<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_audit', function (Blueprint $table) {
            $table->id('cities_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('city_id');
            $table->string('city');

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
        Schema::dropIfExists('cities_audit');
    }
}
