<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Traits\HttpResponseCode;
use Illuminate\Support\Facades\Storage;
use App\Helpers\MessageHelper;


class ProductController extends Controller
{
    use HttpResponseCode;

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
                'message' => product_found(),

                'code' => $this->ok()

            ]
        );
    }
    public function getData(Request $request)
    {
        $page = $request->input('page', 1);
        $keyword = $request->input('keyword', '');
        $size = $request->input('size', 12);

        $data = $this->productRepository->paginate($page, $size, $keyword);
        return response()->json([
            'contents' => $data,
            'count' => $keyword != '' ? $this->productRepository->count($keyword) : $this->productRepository->count(),
            'code' => $this->ok()
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

            $image = $request->file('image');

            if ($image) {
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imageName);
                $imagePath = '/storage/images/' . $imageName;
            } else {
                $imagePath = '';
            }

            $this->productRepository->create([
                'name' => $request->input('name'),
                'note' => $request->input('note'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'brand_id' => $request->input('brand_id'),
                'image' => $imagePath
            ]);
            return response()->json([
                'message' => product_saved(),
                'code' => $this->ok()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => error_deleting_product(),
                'code' => $this->internalServerError()
            ]);
        }
    }


    public function updateProduct(ProductRequest $request, $productId)
    {
        
        try {
            $product = $this->productRepository->find($productId);

            if (!$product) {
                return response()->json([
                    'message' => product_not_found(),
                    'code' => $this->notFound()
                ]);
            }

            $image = $request->file('image');

            if ($image) {
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imageName);
                $imagePath = '/storage/images/' . $imageName;
                // Xóa ảnh cũ trước khi lưu ảnh mới
                if ($product->image) {
                    Storage::delete('public/images/' . basename($product->image));
                }
                $product->image = $imagePath;
            }

            $product->name = $request->input('name');
            $product->note = $request->input('note');
            $product->price = $request->input('price');
            $product->category_id = $request->input('category_id');
            $product->brand_id = $request->input('brand_id');

            $this->productRepository->create($product);

            return response()->json([
                'message' => 'Product updated',
                'code' => $this->ok()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating product',
                'code' => $this->internalServerError()
            ]);
        }
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->updateProduct($request, $id);
    }




    public function show($id)
    {
        try {
            $product = $this->productRepository->find($id);
            if ($product) {
                return response()->json([
                    'product' => $product,
                    'message' => product_found(),
                    'code' => $this->ok()
                ]);
            } else {
                return response()->json([
                    'message' => product_not_found(),
                    'code' => $this->notFound()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => error_finding_product(),
                'code' => $this->internalServerError()
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->productRepository->find($id);
            if ($product) {
                $product->delete();
                return response()->json([
                    'message' => product_deleted(),
                    'code' => $this->ok()
                ]);
            } else {
                return response()->json([
                    'message' => product_not_found(),
                    'code' => $this->notFound()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => error_deleting_product(),
                'code' => $this->internalServerError()
            ]);
        }
    }
}
