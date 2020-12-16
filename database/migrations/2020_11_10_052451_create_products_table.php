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
            $table->id(); // 產品 id
            $table->string('title'); // 產品名稱
            $table->text('description'); // 產品資訊
            $table->decimal('unit_price'); // 單價
            $table->text('imgUrl'); // 產品圖片網址
            $table->integer('stock_quantity'); // 存貨數量
            $table->boolean('available')->default(true); // 只支援 0 和 1
            $table->timestamps();

            
            // foreign ley
            // $table->foreignId('tag_id')->constrained('tags'); // 產品標籤

            // $table->unsignedBigInteger('tag_id')->nullable();
            // $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
