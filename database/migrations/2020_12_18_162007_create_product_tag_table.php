<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 第三方表格 (儲存產品與標籤的關聯)
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('tag_id')->nullable();
            // $table->primary(['product_id', 'tag_id']);
            // $table->timestamp('created_at')->useCurrent();
            // $table->timestamp('updated_at')->useCurrentOnUpdate();
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
        Schema::dropIfExists('product_tag');
    }
}
