<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumToProjectDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_description', function (Blueprint $table) {
            $table->enum('type', ['text', 'image_left', 'image_right'])
                ->default('text')
                ->after('order');

            $table->integer('image_id')
                ->unsigned()
                ->nullable()
                ->default(null)
                ->after('description');

            $table->foreign('image_id', 'fk_project_description_image_id')
                ->references('id')
                ->on('file');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_description', function (Blueprint $table) {
            $table->dropForeign('fk_project_description_image_id');

            $table->dropColumn('type');
            $table->dropColumn('image_id');
        });
    }
}
