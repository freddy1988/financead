<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYodleeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yodlee_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('container')->nullable();
            $table->longText('amount')->nullable();
            $table->string('baseType')->nullable();
            $table->string('categoryType')->nullable();
            $table->integer('categoryId')->nullable();
            $table->string('category')->nullable();
            $table->string('categorySource')->nullable();
            $table->string('createdDate')->nullable();
            $table->string('lastUpdated')->nullable();
            $table->longText('description')->nullable();
            $table->string('type')->nullable();
            $table->string('subType')->nullable();
            $table->string('isManual')->nullable();
            $table->date('date')->nullable();
            $table->date('transactionDate')->nullable();
            $table->date('postDate')->nullable();
            $table->string('status')->nullable();
            $table->integer('accountId')->nullable();
            $table->longText('runningBalance')->nullable();
            $table->integer('highLevelCategoryId')->nullable();
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
        Schema::dropIfExists('yodlee_transactions');
    }
}
