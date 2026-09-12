<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaiMiningLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cai_mining_ledgers')) {
            Schema::create('cai_mining_ledgers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('userid')->index();
                $table->string('type', 20)->index(); // 'mine' or 'sell'
                $table->decimal('usdt_amount', 18, 4)->default(0); // USDT converted or received
                $table->decimal('cai_amount', 18, 6)->default(0);  // CAI tokens mined or sold
                $table->decimal('cai_price', 18, 6)->default(0);   // Live DEX price at execution
                $table->decimal('capping_before', 18, 4)->default(0);
                $table->decimal('capping_deducted', 18, 4)->default(0);
                $table->decimal('capping_after', 18, 4)->default(0);
                $table->string('tx_hash', 100)->nullable();        // On-chain BSC transaction hash
                $table->tinyInteger('status')->default(1);          // 1: completed, 0: pending
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('user_details') && !Schema::hasColumn('user_details', 'cai_balance')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->decimal('cai_balance', 18, 6)->default(0)->after('wallet_amount');
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
        Schema::dropIfExists('cai_mining_ledgers');
    }
}
