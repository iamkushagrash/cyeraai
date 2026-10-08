<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaoDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dao_distributions')) {
            Schema::create('dao_distributions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->tinyInteger('dao_type')->comment('1=Platinum, 2=Golden, 3=Diamond');
                $table->string('dao_name');
                $table->timestamp('week_start')->nullable();
                $table->timestamp('week_end')->nullable();
                $table->decimal('total_weekly_business', 15, 2)->default(0.00);
                $table->decimal('pool_percent', 5, 2);
                $table->decimal('pool_amount', 15, 2)->default(0.00);
                $table->integer('qualified_members')->default(0);
                $table->decimal('per_member_payout', 15, 2)->default(0.00);
                $table->tinyInteger('status')->default(1)->comment('1=Calculated, 2=Distributed');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dao_distributions');
    }
}
