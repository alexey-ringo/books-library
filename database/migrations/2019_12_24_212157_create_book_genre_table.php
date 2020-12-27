<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_genre', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('genre_id')->unsigned()->default(1);
            $table->foreign('genre_id')->references('id')->on('genres');

            $table->bigInteger('book_id')->unsigned()->default(1);
            $table->foreign('book_id')->references('id')->on('books');
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
        Schema::table('book_genre', function (Blueprint $table) {
            $table->dropForeign('book_genre_genre_id_foreign');
            $table->dropForeign('book_genre_book_id_foreign');
        });
        Schema::dropIfExists('book_genre');
    }
}
