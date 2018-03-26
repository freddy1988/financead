<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGocardlessPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gocardless_payments', function (Blueprint $table) {
            $table->string('id');
            $table->string('model_name')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('amount_refunded')->nullable();
            $table->date('charge_date')->nullable();
            $table->string('created_at_api')->nullable();
            $table->string('currency')->nullable();
            $table->string('description')->nullable();
            $table->longText('links')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('gocardless_payments');
    }
}
