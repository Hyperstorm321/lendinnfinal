<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'user_id');
            $table->dropColumn('name');
            
            $table->string('username', 20)->nullable();
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('extension_name', 10)->nullable();
            $table->string('detailed_address')->nullable();
            $table->bigInteger('region_id')->unsigned()->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('barangay_id')->unsigned()->nullable();
            $table->bigInteger('postal_code_id')->unsigned()->nullable();
            $table->string('photo_src')->nullable();
            $table->boolean('is_landlord')->default(false);
            
            $table->foreign('region_id')
                  ->references('region_id')->on('regions');

            $table->foreign('province_id')
                  ->references('province_id')->on('provinces');

            $table->foreign('city_id')
                  ->references('city_id')->on('cities');

            $table->foreign('barangay_id')
                  ->references('barangay_id')->on('barangays');

            $table->foreign('postal_code_id')
                  ->references('postal_code_id')->on('postal_codes');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
