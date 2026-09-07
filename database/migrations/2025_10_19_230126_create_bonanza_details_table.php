<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonanzaDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonanza_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userid');
            $table->bigInteger('sponsorid');
            $table->decimal('total_investment',36,8)->default(0);
            $table->decimal('current_investment',36,8)->default(0);
            $table->decimal('total_level_investment',36,8)->default(0);
            $table->decimal('current_level_investment',36,8)->default(0);
            $table->decimal('total_self_investment',36,8)->default(0);
            $table->decimal('current_self_investment',36,8)->default(0);
            $table->decimal('total_direct_investment',36,8)->default(0);
            $table->decimal('current_direct_investment',36,8)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonanza_details');
    }
}
