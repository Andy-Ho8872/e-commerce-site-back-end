<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // 產品名稱
            $table->text('description'); // 產品資訊
            $table->decimal('unit_price'); // 單價
            $table->text('imgUrl'); // 產品圖片網址
            $table->integer('stock_quantity')->default(100); // 存貨數量
            $table->boolean('available')->default(true); // 只支援 0 和 1
            $table->decimal('discount_rate')->default(1); // 產品折價  初始為 1.00(原價)
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
        Schema::dropIfExists('products');
        
    }
}
