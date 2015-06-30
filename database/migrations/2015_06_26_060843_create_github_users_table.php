<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGithubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('github_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->index()->unique()->nullable(false);
            $table->timestamp('account_created_at')->nullable(false);
            $table->integer('repos')->nullable()->unsigned();
            $table->integer('followers')->nullable();
            $table->integer('following')->nullable();
            $table->integer('starred_repos')->nullable()->unsigned();
            $table->integer('watch_repos')->nullable()->unsigned();
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
        Schema::drop('github_users');
    }
}
