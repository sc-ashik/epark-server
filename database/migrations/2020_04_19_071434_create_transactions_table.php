<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parking_id')->unique();
            $table->foreign('parking_id')->references('id')->on('parkings');
            $table->timestamp("locked_at");
            $table->timestamp("unlock_requested_at")->nullable();
            $table->unsignedBigInteger('unlock_requested_by')->nullable();
            $table->foreign("unlock_requested_by")->references('id')->on('users');
            $table->string('categories_applied')->nullable();
            $table->double('fee')->nullable();
            $table->timestamp("unlocked_at")->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
