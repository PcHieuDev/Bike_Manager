<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    public function all($keyword = '')
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

    public function getByBrand($brandId)
    {
        return Product::with('brand', 'category')
            ->where('brand_id', $brandId)
            ->get();
    }
    public function getByCategory($categoryId)
    {
        return Product::with('brand', 'category')
            ->where('category_id', $categoryId)
            ->get();
    }

    public function paginate($page, $size, $keyword)
    {
        $offset = ($page - 1) * $size;
        $query = Product::with('brand', 'category');
    
        if ($keyword != '') {
            $query->where('name', 'like', "%$keyword%")
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                })
                ->orWhereHas('brand', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
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
            $products->where('name', 'like', "%$query%")
                ->whereHas('category', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                })
                ->whereHas('brand', function ($q) use ($query) {
                    $q->where('name', 'like', "%$query%");
                });
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

    public function count($keyword = null)
    {

        if ($keyword != '') {
            return Product::where('name', 'like', "%$keyword%")->count();
        }
        return Product::count();
    }


    public function saveProduct($request)
    {
        $product = new Product();

        if (isset($request->name)) {
            $product->name = $request->name;
        }

        if (isset($request->price)) {
            $product->price = $request->price;
        }

        if (isset($request->description)) {
            $product->description = $request->description;
        }

        if (isset($request->image)) {
            $product->image = $request->image;
        }

        $product->save();

        return $product;
    }

    

   

    


}
