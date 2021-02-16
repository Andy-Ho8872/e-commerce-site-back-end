<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('product_quantity')->default(1); // 產品訂購數 預設 1 個
            // Foreign Keys
                // 產品 id
            $table->foreignId('product_id')->nullable()->constrained('products')
            ->onUpdate('cascade')->onDelete('cascade');
                // 訂購者 id
            $table->foreignId('user_id')->nullable()->constrained('users') 
            ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
