<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPayTypeToYodleeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yodlee_transactions', function (Blueprint $table) {
            $table->string("pay_type")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yodlee_transactions', function (Blueprint $table) {
            $table->dropColumn("pay_type");
        });
    }
}
