<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_cate', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pid')->nullable($value = true)->default(0);
            $table->string('name');
            $table->string('position')->nullable($value = true)->default('left');
            $table->integer('sort')->nullable($value = true)->default(100000);
            $table->integer('create_user_id');
            $table->integer('update_user_id');
            $table->tinyInteger('status')->nullable($value = true)->default(1);
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
        Schema::dropIfExists('article_cate');
    }
}
