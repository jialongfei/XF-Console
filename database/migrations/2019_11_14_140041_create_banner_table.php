<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('position');
            $table->string('img_path')->nullable($value = true)->default('javascript:;');
            $table->string('link')->nullable($value = true)->default('/banner/default.jpg');
            $table->string('sort')->nullable($value = true)->default(100000);
            $table->integer('create_user_id');
            $table->integer('update_user_id');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('banner');
    }
}
