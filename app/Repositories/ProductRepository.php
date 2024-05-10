<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all($keyword = '')
    {
        if ($keyword != '') {
            return Product::where('name', 'like', "%$keyword%")->get();
        } else {   
            return Product::all();
        }
    }

    public function paginate($page, $size, $keyword)
    {
        $offset = ($page - 1) * $size;
        if ($keyword != '') {
            return Product::where('name', 'like', "%$keyword%")->orderBy('id', 'DESC')->skip($offset)->take($size)->get();
        } else {

            return Product::orderBy('id', 'DESC')->skip($offset)->take($size)->get();
        }
    }

    public function search($query)
    {
        return Product::where('name', 'like', "%$query%")->get();
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
        return Product::find($id);
    }

    // public function find($id)
    // {
    //     return Product::find($id);
    // }
}