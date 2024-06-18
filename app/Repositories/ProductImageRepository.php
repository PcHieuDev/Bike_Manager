<?php
namespace App\Repositories;

use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductImageRepositoryInterface;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    public function find($id)
    {
        return ProductImage::find($id);
    }

    public function delete($id)
    {
       
        $productImage = $this->find($id);
        if ($productImage) {
            $productImage->delete();
            return true;
        }
        return false;
    }

    public function create(array $data)
    {
        return ProductImage::create($data);
    }
      public function getByProductId($productId)
    {
        return ProductImage::where('product_id', $productId)->get();

    }
}