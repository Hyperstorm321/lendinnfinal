<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvincesAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces_audit', function (Blueprint $table) {
            $table->id('provinces_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('province_id');
            $table->string('province');

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
        Schema::dropIfExists('provinces_audit');
    }
}
