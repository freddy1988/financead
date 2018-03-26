<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenancies', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total_rent_amount', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->string('deposit_reference')->nullable();
            $table->string('deposit_holder')->nullable();
            $table->string('deposit_type')->nullable();
            $table->string('deposit_scheme')->nullable();
            $table->string('deposit_status')->nullable();
            $table->string('rent_payment_reference')->nullable();
            $table->string('deposit_payment_reference')->nullable();
            $table->string('holding_deposit_payment_reference')->nullable();
            $table->date('deposit_protected_date')->nullable();
            $table->date('deposit_received_date')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->integer('is_periodic')->nullable();
            $table->date('next_payment_date')->nullable();
            $table->string('rent_term')->nullable();
            $table->string('statement_term')->nullable();
            $table->string('tenancy_term')->nullable();
            $table->integer('term')->nullable();
            $table->integer('rent_frequency')->nullable();
            $table->longText('state')->nullable();
            $table->longText('management_type')->nullable();
            $table->longText('tenants')->nullable();
            $table->longText('term_interval')->nullable();
            $table->longText('rent_frequency_interval')->nullable();
            $table->longText('lead_tenant')->nullable();
            $table->longText('documents')->nullable();
            $table->string('property_full_address')->nullable();
            $table->string('match')->nullable();
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
        Schema::dropIfExists('tenancies');
    }
}

