<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rank_name', 20)->comment('V1 to V8');
            $table->tinyInteger('rank_level')->comment('1 to 8');
            $table->decimal('power_leg', 16, 2)->default(0.00)->comment('Required Power Leg Business in USDT');
            $table->decimal('weaker_leg', 16, 2)->default(0.00)->comment('Required Weaker Leg Business in USDT');
            $table->decimal('weekly_reward', 16, 2)->default(0.00)->comment('Weekly Payout in USDT');
            $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
            $table->timestamps();
        });

        // Seed initial V1 to V8 ranks
        DB::table('rank_details')->insert([
            ['id' => 1, 'rank_name' => 'V1', 'rank_level' => 1, 'power_leg' => 500.00, 'weaker_leg' => 500.00, 'weekly_reward' => 5.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'rank_name' => 'V2', 'rank_level' => 2, 'power_leg' => 1000.00, 'weaker_leg' => 1000.00, 'weekly_reward' => 10.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'rank_name' => 'V3', 'rank_level' => 3, 'power_leg' => 5000.00, 'weaker_leg' => 5000.00, 'weekly_reward' => 25.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'rank_name' => 'V4', 'rank_level' => 4, 'power_leg' => 10000.00, 'weaker_leg' => 10000.00, 'weekly_reward' => 50.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'rank_name' => 'V5', 'rank_level' => 5, 'power_leg' => 25000.00, 'weaker_leg' => 25000.00, 'weekly_reward' => 100.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'rank_name' => 'V6', 'rank_level' => 6, 'power_leg' => 50000.00, 'weaker_leg' => 50000.00, 'weekly_reward' => 250.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'rank_name' => 'V7', 'rank_level' => 7, 'power_leg' => 100000.00, 'weaker_leg' => 100000.00, 'weekly_reward' => 500.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'rank_name' => 'V8', 'rank_level' => 8, 'power_leg' => 500000.00, 'weaker_leg' => 500000.00, 'weekly_reward' => 2000.00, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_details');
    }
}
