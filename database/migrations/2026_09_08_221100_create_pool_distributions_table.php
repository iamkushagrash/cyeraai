<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_distributions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pool_id');
            $table->tinyInteger('pool_type')->comment('1: Daily, 2: Weekly, 3: Monthly');
            $table->dateTime('period_start');
            $table->dateTime('period_end');
            $table->decimal('total_turnover', 20, 2)->default(0.00);
            $table->decimal('pool_percent', 5, 2)->default(0.00);
            $table->decimal('total_pool_amount', 20, 2)->default(0.00);
            $table->integer('eligible_users_count')->default(0);
            $table->decimal('per_user_share', 20, 2)->default(0.00);
            $table->date('distributed_date');
            $table->tinyInteger('status')->default(1)->comment('1: Completed, 0: Failed/Zero');
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
        Schema::dropIfExists('pool_distributions');
    }
}
