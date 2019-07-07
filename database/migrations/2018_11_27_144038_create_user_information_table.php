<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->text('bio');
            $table->integer('profile_picture_id')->unsigned();
            $table->integer('github_id');
            $table->boolean('search_job')->default(true);
            $table->timestamps();

            $table->primary('user_id', 'pk_user_information');
            $table->foreign('user_id', 'fk_user_information_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->foreign('profile_picture_id', 'fk_user_information_picture_id')
                  ->references('id')
                  ->on('file')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_information');
    }
}
