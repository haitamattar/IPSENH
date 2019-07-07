<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('image_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->text('description');


            $table->foreign('image_id', 'fk_project_image_image_id')
                ->references('id')
                ->on('file')
                ->onDelete('cascade');
            $table->foreign('project_id', 'fk_project_image_project_id')
                ->references('id')
                ->on('project')
                ->onDelete('cascade');

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
        Schema::dropIfExists('project_image');
    }
}
