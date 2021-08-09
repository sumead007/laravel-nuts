<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigTurnOnTurnOffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_turn_on_turn_offs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('status')->comment = '0 = เปิดให้เล่น 1 = ปิดให้เล่น';
            $table->timestamps();
        });

        // Insert some data
        DB::table('config_turn_on_turn_offs')->insert(
            array(
                'name' => 'turn_on_turn_off',
                'status' => 0
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_turn_on_turn_offs');
    }
}
