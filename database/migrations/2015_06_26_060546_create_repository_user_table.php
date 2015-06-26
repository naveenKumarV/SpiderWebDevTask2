<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepositoryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repository_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('search_id')->unsigned()->index()->nullable(false);
            $table->integer('repo_id')->unsigned()->index()->nullable(false);
            $table->foreign('search_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('repo_id')->references('id')->on('repositories')->onDelete('cascade');
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
        Schema::drop('repository_user');
    }
}
