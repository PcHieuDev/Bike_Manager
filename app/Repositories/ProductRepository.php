<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function applyKeywordFilters($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('brand', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });

                if (is_numeric($keyword)) {
                    $q->orWhere('id', $keyword);
                }
            });
        }
    }

    public function all($keyword = '')
    {
        $query = Product::with('brand', 'category');
        $this->applyKeywordFilters($query, $keyword);
        return $query->get();
    }

    public function find($id)
    {
        return Product::with('brand', 'category')->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return true;
        }
        return false;
    }

    public function getBybrand($brandId)
    {
        return Product::with('brand')
            ->where('brand_id', $brandId)
            ->get();
    }

    public function paginate($page, $size, $keyword, $brandId, $categoryId, $productId)
    {
        $offset = ($page - 1) * $size;
        $query = Product::with('brand', 'category');

        $this->applyKeywordFilters($query, $keyword);

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($productId) {
            $query->where('id', $productId);
        }

        return $query->orderBy('id', 'DESC')
            ->skip($offset)
            ->take($size)
            ->get();
    }

    public function search($query)
    {
        $products = Product::query();
        $this->applyKeywordFilters($products, $query);
        return $products->paginate(12);
    }

    public function count($keyword = null, $brandId, $categoryId, $productId)
    {
        $query = Product::with('brand', 'category');

        $this->applyKeywordFilters($query, $keyword);

        if ($brandId != '') {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId != '') {
            $query->where('category_id', $categoryId);
        }

        if ($productId != '') {
            $query->where('id', $productId);
        }

        return $query->count();
    }

}
