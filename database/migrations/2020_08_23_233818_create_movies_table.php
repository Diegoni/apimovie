<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('movie_id');
            $table->float('popularity');
            $table->integer('vote_count');
            $table->boolean('video');
            $table->string('poster_path');
            $table->integer('id');
            $table->boolean('adult');
            $table->string('backdrop_path');
            $table->string('original_language');
            $table->string('original_title');
            $table->string('title');
            $table->float('vote_average');
            $table->text('overview');
            $table->text('status');
            $table->date('release_date');
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
        Schema::dropIfExists('movies');
    }
}
