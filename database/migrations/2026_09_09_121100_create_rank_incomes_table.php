<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userid')->index();
            $table->unsignedBigInteger('rank_id');
            $table->string('rank_name', 20)->comment('V1 to V8');
            $table->decimal('amount', 36, 8)->default(0.00000000)->comment('Amount in native token');
            $table->decimal('remaining', 36, 8)->default(0.00000000);
            $table->decimal('amt_usdt', 16, 2)->default(0.00)->comment('Amount in USDT');
            $table->decimal('remaining_usdt', 16, 2)->default(0.00);
            $table->decimal('power_leg_business', 16, 2)->default(0.00);
            $table->decimal('weaker_leg_business', 16, 2)->default(0.00);
            $table->date('distributed_date')->index();
            $table->tinyInteger('status')->default(0)->comment('0: Credited, 1: Withdrawn, 3: Locked');
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
        Schema::dropIfExists('rank_incomes');
    }
}
