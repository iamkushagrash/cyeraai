<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dao_details')) {
            Schema::create('dao_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->tinyInteger('dao_type')->comment('1=Platinum, 2=Golden, 3=Diamond')->unique();
                $table->string('dao_name');
                $table->decimal('package_amount', 15, 2);
                $table->integer('max_members');
                $table->integer('rank_required')->comment('3=V3, 4=V4, 5=V5');
                $table->integer('days_limit')->default(60)->comment('60 Days from 5 Nov 2026');
                $table->decimal('pool_percent', 5, 2);
                $table->decimal('capping_multiplier', 5, 2);
                $table->date('start_date')->default('2026-11-05');
                $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('dao_details');
    }
}
