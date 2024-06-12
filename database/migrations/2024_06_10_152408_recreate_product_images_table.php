<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          // Xóa bảng product_images nếu tồn tại
          Schema::dropIfExists('product_images');
        
          // Tạo lại bảng product_images
          Schema::create('product_images', function (Blueprint $table) {
              $table->id();
              $table->string('image_path');
              $table->unsignedBigInteger('product_id');
              $table->integer('image_position');  // Đặt cột image_position sau product_id
              $table->timestamps();
              $table->foreign('product_id')->references('id')->on('products');
            });

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('product_images');

        //
    }
};
