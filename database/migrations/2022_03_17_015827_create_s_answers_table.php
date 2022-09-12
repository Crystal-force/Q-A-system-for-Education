<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id');
            $table->integer('q_id');
            $table->unsignedTinyInteger('a_no');
            $table->boolean('status');
            $table->unsignedTinyInteger('score');
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
        Schema::dropIfExists('s_answers');
    }
}
