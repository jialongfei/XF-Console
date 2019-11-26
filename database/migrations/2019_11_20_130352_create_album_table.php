<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable($value = true);
            $table->string('cate')->nullable($value = true);
            $table->string('litpic')->nullable($value = true);
            $table->text('description')->nullable($value = true);
            $table->integer('click')->nullable($value = true)->default(0);
            $table->integer('sort')->nullable($value = true)->default(100000);
            $table->tinyInteger('status')->nullable($value = true)->default(1);
            $table->integer('create_user_id');
            $table->integer('update_user_id');
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
        Schema::dropIfExists('album');
    }
}
