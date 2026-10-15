<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLuckydrawTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. luckydraw_details: 4 Royal Jackpot Pool configurations
        if (!Schema::hasTable('luckydraw_details')) {
            Schema::create('luckydraw_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->tinyInteger('pool_tier')->unique()->comment('1: $10, 2: $25, 3: $50, 4: $100');
                $table->string('pool_name');
                $table->decimal('ticket_price', 10, 2);
                $table->integer('max_members')->default(500);
                $table->decimal('min_staking_required', 12, 2)->default(100.00);
                $table->text('prizes_json');
                $table->decimal('total_prize_pool', 12, 2);
                $table->integer('current_round')->default(1);
                $table->integer('total_rounds_completed')->default(0);
                $table->tinyInteger('status')->default(1)->comment('1: Active, 0: Inactive');
                $table->timestamps();
            });
        }

        // 2. luckydraw_rounds: Keeps track of each 500-member round/cycle
        if (!Schema::hasTable('luckydraw_rounds')) {
            Schema::create('luckydraw_rounds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->tinyInteger('pool_tier')->index();
                $table->integer('round_number')->default(1);
                $table->integer('max_members')->default(500);
                $table->integer('current_members')->default(0);
                $table->decimal('ticket_price', 10, 2);
                $table->decimal('total_collected', 12, 2)->default(0.00);
                $table->tinyInteger('status')->default(0)->comment('0: In Progress/Collecting, 1: Completed & Drawn');
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('drawn_at')->nullable();
                $table->timestamps();

                $table->index(['pool_tier', 'status']);
            });
        }

        // 3. luckydraw_participants: Individual ticket entries
        if (!Schema::hasTable('luckydraw_participants')) {
            Schema::create('luckydraw_participants', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('round_id')->index();
                $table->tinyInteger('pool_tier')->index();
                $table->unsignedBigInteger('userid')->index();
                $table->string('wallet_address', 64)->nullable()->index();
                $table->integer('ticket_number');
                $table->decimal('amount_paid', 10, 2);
                $table->string('tx_hash', 80)->nullable()->index();
                $table->timestamps();

                $table->unique(['round_id', 'ticket_number']);
            });
        }

        // 4. luckydraw_winners: Top 50 winners for each completed round
        if (!Schema::hasTable('luckydraw_winners')) {
            Schema::create('luckydraw_winners', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('round_id')->index();
                $table->tinyInteger('pool_tier')->index();
                $table->unsignedBigInteger('participant_id')->index();
                $table->unsignedBigInteger('userid')->index();
                $table->string('wallet_address', 64)->nullable();
                $table->integer('ticket_number');
                $table->integer('rank')->comment('1 to 50');
                $table->decimal('prize_amount', 12, 2);
                $table->tinyInteger('credited_to_wallet')->default(1);
                $table->timestamps();

                $table->unique(['round_id', 'rank']);
            });
        }

        // 5. royal_wallet_ledgers: Complete audit trail of Royal Wallet credits & claims
        if (!Schema::hasTable('royal_wallet_ledgers')) {
            Schema::create('royal_wallet_ledgers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('userid')->index();
                $table->string('type', 20)->comment('win: credited from jackpot, claim: withdrawn via Web3 vault');
                $table->decimal('amount', 12, 4);
                $table->decimal('balance_before', 12, 4)->default(0.00);
                $table->decimal('balance_after', 12, 4)->default(0.00);
                $table->unsignedBigInteger('round_id')->nullable()->index();
                $table->string('claim_id', 64)->nullable()->index();
                $table->string('tx_hash', 80)->nullable()->index();
                $table->tinyInteger('status')->default(1)->comment('1: Confirmed/Success, 0: Pending, 2: Failed');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 6. Add royal_wallet column to user_details if not present
        if (Schema::hasTable('user_details') && !Schema::hasColumn('user_details', 'royal_wallet')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->decimal('royal_wallet', 14, 4)->default(0.0000)->after('cai_balance');
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
        Schema::dropIfExists('royal_wallet_ledgers');
        Schema::dropIfExists('luckydraw_winners');
        Schema::dropIfExists('luckydraw_participants');
        Schema::dropIfExists('luckydraw_rounds');
        Schema::dropIfExists('luckydraw_details');

        if (Schema::hasTable('user_details') && Schema::hasColumn('user_details', 'royal_wallet')) {
            Schema::table('user_details', function (Blueprint $table) {
                $table->dropColumn('royal_wallet');
            });
        }
    }
}
