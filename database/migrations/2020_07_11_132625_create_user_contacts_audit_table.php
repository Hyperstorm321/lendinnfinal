<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserContactsAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contacts_audit', function (Blueprint $table) {
            $table->id('user_contacts_audit_id');
            $table->string('modified_by', 128);
            $table->dateTime('modified_date');
            $table->char('operation', 1);

            $table->bigInteger('user_contact_id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('contact_no', 20);
            
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
        Schema::dropIfExists('user_contacts_audit');
    }
}
