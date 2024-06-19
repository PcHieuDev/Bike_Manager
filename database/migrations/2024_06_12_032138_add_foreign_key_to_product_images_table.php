<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Tạo tên rõ ràng cho khóa ngoại
            $constraintName = 'product_images_product_id_foreign';

            // Kiểm tra trước khi thêm ràng buộc mới
            $constraints = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_NAME = 'product_images' AND CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = ?", [$constraintName]);

            if (empty($constraints)) {
                $table->foreign('product_id', $constraintName)
                    ->references('id')->on('products')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign('product_images_product_id_foreign');
        });
    }
};
