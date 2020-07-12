<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyOwnedAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_owned_audit', function (Blueprint $table) {
            $table->id('property_owned_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('property_owned_id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('property_id')->unsigned();

            $table->integer('quantity')->default(0);
            $table->dateTime('date_acquired');

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
        Schema::dropIfExists('property_owned_audit');
    }
}
