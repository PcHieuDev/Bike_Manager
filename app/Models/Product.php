<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'note',
        'image',
        'brand_id',
        'category_id'
    ];

    protected static function boot()
    {
        parent::boot();
    
        // Đăng ký sự kiện deleting để xóa các bản ghi liên quan
        static::deleting(function ($product) {
            // Xóa tất cả các hình ảnh liên quan
            $product->images()->delete();
            // Thêm bất kỳ hành động xóa liên quan nào khác ở đây
        });
    }


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
 
}
