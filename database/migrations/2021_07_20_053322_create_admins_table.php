<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->integer('telephone')->length(10)->unsigned();
            $table->string('credit');
            $table->string('share_percentage')->comment = 'เปอร์เซนหุ้น';//เปอร์เซนหุ้น
            $table->integer('position')->nullable()->comment = 'ตำแหน่งงาน 0 = เจ้าของ, 1 = เอเยน';
            $table->integer('admin_id')->nullable()->comment = 'ถ้าเป็นเอเจน มาจาก เจ้าของคนไหน';
            $table->rememberToken();
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
        Schema::dropIfExists('admins');
    }
}
