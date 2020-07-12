<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangaysAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangays_audit', function (Blueprint $table) {
            $table->id('barangays_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('barangay_id');
            $table->string('barangay', 10);

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
        Schema::dropIfExists('barangays_audit');
    }
}
