<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankColumnsToUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'rank_level')) {
                $table->tinyInteger('rank_level')->default(0)->comment('0: None, 1: V1, 2: V2 ... 8: V8')->after('userstate');
            }
            if (!Schema::hasColumn('user_details', 'rank_name')) {
                $table->string('rank_name', 20)->default('None')->comment('V1 to V8 or None')->after('rank_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (Schema::hasColumn('user_details', 'rank_level')) {
                $table->dropColumn('rank_level');
            }
            if (Schema::hasColumn('user_details', 'rank_name')) {
                $table->dropColumn('rank_name');
            }
        });
    }
}
