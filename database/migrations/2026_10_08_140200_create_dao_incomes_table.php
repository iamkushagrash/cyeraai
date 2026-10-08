<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaoIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dao_incomes')) {
            Schema::create('dao_incomes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('userid');
                $table->tinyInteger('dao_type')->comment('1=Platinum, 2=Golden, 3=Diamond');
                $table->string('dao_name')->nullable();
                $table->unsignedBigInteger('distribution_id')->nullable();
                $table->decimal('amount', 24, 8)->default(0.00000000);
                $table->decimal('remaining', 24, 8)->default(0.00000000);
                $table->decimal('amt_usdt', 20, 2)->default(0.00);
                $table->decimal('remaining_usdt', 20, 2)->default(0.00);
                $table->tinyInteger('status')->default(0)->comment('0: Active/Credit');
                $table->timestamps();

                $table->index(['userid', 'dao_type']);
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
        Schema::dropIfExists('dao_incomes');
    }
}
