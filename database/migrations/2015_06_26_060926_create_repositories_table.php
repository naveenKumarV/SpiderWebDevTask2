<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->unique()->nullable(false);
            $table->timestamp('repo_created_at')->nullable(false);
            $table->text('description')->nullable();
            $table->string('url')->nullable(false);
            $table->string('languages')->nullable();
            $table->integer('subscribers_count')->nullable();
            $table->integer('forks_count')->nullable();
            $table->integer('watchers_count')->nullable();
            $table->text('collaborators')->nullable();
            $table->text('contributors')->nullable();
            $table->integer('user_id')->nullable(false)->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('github_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repositories');
    }
}
