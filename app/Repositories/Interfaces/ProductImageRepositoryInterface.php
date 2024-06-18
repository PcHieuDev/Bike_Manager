<?php
namespace App\Repositories\Interfaces;
use App\Models\ProductImage;

interface ProductImageRepositoryInterface
{
    public function find($id);
    public function delete($id);
    public function create(array $data);
    public function getByProductId($productId);

}