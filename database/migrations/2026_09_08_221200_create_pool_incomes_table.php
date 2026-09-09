<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('distribution_id')->nullable();
            $table->unsignedBigInteger('userid');
            $table->unsignedBigInteger('pool_id')->nullable();
            $table->tinyInteger('pool_type')->comment('1: Daily, 2: Weekly, 3: Monthly');
            $table->decimal('amount', 24, 8)->default(0.00000000)->comment('CAI Token equivalent');
            $table->decimal('remaining', 24, 8)->default(0.00000000);
            $table->decimal('amt_usdt', 20, 2)->default(0.00)->comment('USDT USD amount');
            $table->decimal('remaining_usdt', 20, 2)->default(0.00);
            $table->tinyInteger('status')->default(0)->comment('0: Active/Credit');
            $table->timestamps();

            $table->index('userid');
            $table->index('pool_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pool_incomes');
    }
}
