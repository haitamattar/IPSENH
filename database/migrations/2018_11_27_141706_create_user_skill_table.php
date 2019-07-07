<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skill', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('skill_id')->unsigned();
            $table->timestamps();

            $table->primary(['user_id', 'skill_id'], 'pk_user_skill');

            $table->foreign('user_id', 'fk_skill_user_id')
                 ->references('id')
                 ->on('users')
                 ->onDelete('cascade');

            $table->foreign('skill_id', 'fk_skill_skill_id')
                 ->references('id')
                 ->on('skill')
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
        Schema::dropIfExists('user_skill');
    }
}
