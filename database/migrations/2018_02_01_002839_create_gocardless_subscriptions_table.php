<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGocardlessSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gocardless_subscriptions', function (Blueprint $table) {
            $table->string('id');
            $table->string('model_name')->nullable();
            $table->integer('amount')->nullable();
            $table->string('created_at_api')->nullable();
            $table->string('currency')->nullable();
            $table->integer('day_of_month')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('interval')->nullable();
            $table->string('interval_unit')->nullable();
            $table->longText('links')->nullable();
            $table->longText('metadata')->nullable();
            $table->string('month')->nullable();
            $table->string('name')->nullable();
            $table->string('payment_reference')->nullable();
            $table->date('start_date')->nullable();
            $table->string('status')->nullable();
            $table->longText('upcoming_payments')->nullable();
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
        Schema::dropIfExists('gocardless_subscriptions');
    }
}
