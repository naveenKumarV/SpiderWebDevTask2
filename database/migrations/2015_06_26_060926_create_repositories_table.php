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
            $table->text('languages')->nullable();
            $table->text('commit_dates')->nullable();
            $table->integer('forks_count')->nullable();
            $table->integer('watchers_count')->nullable();
            $table->integer('subscribers_count')->nullable();
            $table->integer('github_user_id')->nullable(false)->unsigned();
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
        Schema::drop('repositories');
    }
}
