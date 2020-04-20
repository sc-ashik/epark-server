<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parking_id');
            $table->foreign('parking_id')->references('id')->on('parkings');
            $table->timestamp("locked_at");
            $table->timestamp("unlock_requested_at")->nullable();
            $table->unsignedBigInteger('unlock_requested_by');
            $table->foreign("unlock_requested_by")->references('id')->on('users');
            $table->string('categories_applied');
            $table->double('fee');
            $table->timestamp("unlocked_at")->nullable();
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')->references('transaction_id')->on('payments');
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
        Schema::dropIfExists('completed_transactions');
    }
}
