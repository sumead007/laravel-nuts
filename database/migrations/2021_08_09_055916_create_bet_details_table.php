<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_details', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->comment = '0=เพิ่้งแทงเข้ามา, 1=ถูก, 2=ไม่ถูก, 3=ยกเลิก';
            $table->integer('user_id');
            $table->integer('number');
            $table->double('money');
            $table->timestamps();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->integer('bet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bet_details');
    }
}
