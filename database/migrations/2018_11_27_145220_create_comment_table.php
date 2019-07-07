<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->integer('user_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->integer('comment_id')->unsigned();

            $table->foreign('user_id', 'fk_comment_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('article_id', 'fk_comment_article_id')
                ->references('id')
                ->on('article')
                ->onDelete('cascade');
            $table->foreign('comment_id', 'fk_comment_comment_id')
                ->references('id')
                ->on('comment')
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
        Schema::dropIfExists('comment');
    }
}
