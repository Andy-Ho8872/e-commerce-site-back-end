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
        // 使用者
            $table->foreignId('user_id')->nullable()->constrained('users') 
            ->onUpdate('cascade')->onDelete('cascade');
        // 付款方式
            $table->foreignId('payment_id')->nullable()->constrained('payments') 
            ->onUpdate('cascade')->onDelete('cascade');
        // 貨物狀態 (預設出貨中)
            $table->foreignId('status_id')->nullable()->default(1)->constrained('status') 
            ->onUpdate('cascade')->onDelete('cascade');
        // 運送地址
            $table->string('address');
        // 時間戳記
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
