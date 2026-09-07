<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadershipDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leadership_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',30)->nullable();
            $table->decimal('investment',18,6)->default(0);
            $table->decimal('roi',6,3)->default(0);
            $table->smallInteger('isRepeating')->default(0)->comment('0 no 1 yes');
            $table->smallInteger('status')->default(1)->comment('0 inactive 1 active');
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
        Schema::dropIfExists('leadership_details');
    }
}
