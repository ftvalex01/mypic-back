<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create2faTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('2fa_tokens', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');
            $table->bigInteger('user_id')->unsigned(); // Cambia 'integer' por 'bigInteger'
            $table->string('token');
            $table->dateTime('expires_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('2fa_tokens');
    }
}
