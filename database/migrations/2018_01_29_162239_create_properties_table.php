<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('full_address')->nullable();
            $table->string('address_lines')->nullable();
            $table->string('building_name')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();
            $table->string('post_code')->nullable();
            $table->string('county')->nullable();
            $table->string('country_code')->nullable();
            $table->date('available_from')->nullable();
            $table->string('viewing_arrangement_information')->nullable();
            $table->longText('state')->nullable();
            $table->longText('type')->nullable();
            $table->longText('viewing_via')->nullable();
            $table->longText('landlord')->nullable();
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
        Schema::dropIfExists('properties');
    }
}
