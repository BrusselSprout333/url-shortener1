<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id()->startingValue(
                996_953_000
            ); // It can be smaller but the task requires 6 symbols (and it's the first number for 6 symbols base62)
            $table->char('identifier', 6)->default('');
            $table->string('url', 500);
            $table->char('hash', 32)->unique();
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
        Schema::dropIfExists('links');
    }
};
