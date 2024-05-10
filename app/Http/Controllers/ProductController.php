<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProducts()
    {

        $products = $this->productRepository->all();
        return response()->json(
            [
                'products' => $products,
                'message' => 'Products',
                'code' => '200'

            ]);

    }
    public function getData(Request $request)
    {
        if ($request->has('page')) {
            $page = $request->input('page');
        } else {
            $page = 1;
        }
        if($request->has('keyword')){
            $keyword = $request->input('keyword');
        } else {
            $keyword = '';
        }

        if ($request->has('size')) {
            $size = $request->input('size');
        } else {
            $size = 12;
        }

        $data = $this->productRepository->paginate($page, $size, $keyword);
        return response()->json([
            'contents' => $data,
            'count' => $keyword != '' ? count($this->productRepository->all($keyword)) : count($this->productRepository->all()),
            'code' => '200'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = $this->productRepository->search($query);

        return response()->json($products);
    }

    public function saveProduct(ProductRequest $request)
    {

        try {
            Product::create([
                'name' => $request->input('name'),
                'note' => $request->input('note'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'brand_id' => $request->input('brand_id'),
                'image' => $request->input('image')
            ]);
            return response()->json([
                'message' => 'Product saved',
                'code' => '200'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving product',
                'code' => '500'
            ]);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);

        // Render the view and pass the product data to it
    }


}
