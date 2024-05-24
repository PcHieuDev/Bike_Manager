<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all($keyword = '');

    public function paginate($page, $size, $keyword);

    public function search($query);

    public function delete($id);

    public function create(array $data);

    public function find($id);

    public function count($keyword = '');

    public function update($id, array $productData);

    public function getByBrand($brandId);   
    
    public function getByCategory($categoryId);

}
