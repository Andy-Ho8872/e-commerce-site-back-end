<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditCardFieldsInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('card_type')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_holder')->nullable();
            $table->string('card_expiration_date')->nullable();
            $table->string('card_CVV')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'card_type',
                'card_number',
                'card_holder',
                'card_expiration_date',
                'card_CVV',
            ]);
        });
    }
}
