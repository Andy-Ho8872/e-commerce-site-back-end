<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            // 訂單 ID
            $table->foreignId('order_id')->nullable()->constrained('orders')
            ->onUpdate('cascade')->onDelete('cascade');
            // 商品 ID
            $table->foreignId('product_id')->nullable()->constrained('products')
            ->onUpdate('cascade')->onDelete('cascade');
            // 產品訂購數 預設 1 個
            $table->integer('product_quantity')->default(1); 


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
        Schema::dropIfExists('order_products');
    }
}
