<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('permission name');
            $table->string('pid')->nullable($value = true)->default(0)->comment('parent permission id');
            $table->string('path')->nullable($value = true)->default('javascript:;')->comment('current permission uri');
            $table->integer('sort')->nullable($value = true)->default(100000)->comment('sorting rules in nav');
            $table->string('is_show')->nullable($value = true)->default(0)->comment('show in navigation or not');
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
        Schema::dropIfExists('permission');
    }
}
