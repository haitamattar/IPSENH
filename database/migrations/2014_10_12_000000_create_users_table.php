<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('surname');
            $table->string('password')->nullable(true);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('country_id')->unsigned()->default(1);
            $table->integer('github_id')->nullable(true)->default(null);
            $table->integer('gitlab_id')->nullable(true)->default(null);
            $table->string('bitbucket_id')->nullable(true)->default(null);
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->boolean('deleted')->default(false);
            $table->softDeletes();
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('users');
    }
}
