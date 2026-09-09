<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoolDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pool_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pool_name');
            $table->tinyInteger('pool_type')->comment('1: Daily, 2: Weekly, 3: Monthly');
            $table->decimal('pool_percent', 5, 2)->default(0.00);
            $table->decimal('min_self_investment', 16, 2)->default(100.00);
            $table->integer('min_directs')->default(0);
            $table->decimal('min_direct_amount', 16, 2)->default(100.00);
            $table->decimal('power_leg_amount', 16, 2)->default(0.00);
            $table->decimal('weaker_leg_amount', 16, 2)->default(0.00);
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
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
        Schema::dropIfExists('pool_details');
    }
}
