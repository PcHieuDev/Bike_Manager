<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    public function all($keyword = '')
    {
        $query = Product::with('brand', 'category');
    
        if ($keyword != '') {
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%")
                    ->orWhereHas('brand', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('category', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%$keyword%");
                    });
    
                // Kiểm tra xem keyword có phải là số (id) không
                if (is_numeric($keyword)) {
                    $query->orWhere('id', $keyword);
                }
            });
        }
    
        return $query->get();
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

        if ($keyword) {
            $query->where(function ($sql) use ($keyword) {
                $sql->where('name', 'like', "%$keyword%")
                    ->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    })
                    ->orWhereHas('brand', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });

                if (is_numeric($keyword)) {
                    $sql->orWhere('id', $keyword);
                }

            });
        }

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
    
        if (!empty($query)) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhereHas('category', function ($q) use ($query) {
                      $q->where('name', 'like', "%$query%");
                  })
                  ->orWhereHas('brand', function ($q) use ($query) {
                      $q->where('name', 'like', "%$query%");
                  });
            });
    
            if (is_numeric($query)) {
                $products->orWhere('id', $query);
            }
        }
    
        return $products->paginate(12);
    }

    public function create(array $data)
    {
        return Product::create($data);
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

    public function find($id)
    {
        return Product::with('brand', 'category')->find($id);
    }

    public function count($keyword = null, $brandId, $categoryId, $productId)
    {
        $query = Product::with('brand', 'category');

        if ($keyword != '') {
            $query->where('name', 'like', "%$keyword%")
                ->orWhereHas('brand', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%$keyword%");
                })
                ->orWhereHas('category', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%$keyword%");
                });

        }

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


    public function saveProduct($request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'image' => $request->image,
            'note' => $request->note,
            
        ]);
        $id = $product->id;
        echo $id;

        return $product;
    }


}
