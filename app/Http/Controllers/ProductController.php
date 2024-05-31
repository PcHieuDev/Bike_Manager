<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Traits\HttpResponseCode;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductDeletionException;
use Illuminate\Http\Response;
use App\Constants\Constants;
use App\Helpers\ArrayHelper;
use lang\en\Message;
use App\Models\ProductImage;


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
                'message' => __('messages.success.product_found'),
                'status' => $this->ok()

            ]
        );
    }
    public function getByBrand($brandId)
    {
        $products = $this->productRepository->getByBrand($brandId);
        return response()->json($products, 200);
    }

    public function getByCategory($categoryId)
    {
        $products = $this->productRepository->getByCategory($categoryId);
        return response()->json($products, 200);
    }

    public function getData(Request $request)
    {
        $inputs = $request->all();
        $page = ArrayHelper::getValue($inputs, 'page', Constants::DEFAULT_PAGE_NUMBER);
        $keyword = ArrayHelper::getValue($inputs, 'keyword', '');
        $size = ArrayHelper::getValue($inputs, 'size', Constants::DEFAULT_PAGE_SIZE);
        $data = $this->productRepository->paginate($page, $size, $keyword);
        // Sử dụng biến trung gian để lưu trữ kết quả của hàm count
        $count = $this->productRepository->count($keyword);
        return response()->json([
            'contents' => $data,
            'count' => $count,
            'status' => $this->ok()
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryName = $request->input('categoryName');
        $brandName = $request->input('brandName');
        $products = $this->productRepository->search($query, $categoryName, $brandName);
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
                'message' => __('messages.product_saved'),
                'status' => $this->ok()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.error_saving_product'),
                'status' => $this->internalServerError()
            ]);
        }
    }

    public function updateProduct(ProductRequest $request, $id)
    {
        try {
            // Tìm sản phẩm theo ID
            $product = $this->productRepository->find($id);
            if (!$product) {
                return response()->json([
                    'message' => __('messages.product_not_found'),
                    'status' => $this->notFound()
                ]);
            }
            // Xử lý hình ảnh nếu có
            $image = $request->file('image');
            if ($image) {
                // Xóa hình ảnh cũ nếu có
                if ($product->image) {
                    $oldImagePath = public_path($product->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                // Lưu hình ảnh mới
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imageName);
                $imagePath = '/storage/images/' . $imageName;
            } else {
                $imagePath = $product->image; // Giữ nguyên hình ảnh cũ nếu không có hình ảnh mới
            }
            // Cập nhật sản phẩm
            $this->productRepository->update($id, [
                'name' => $request->input('name'),
                'note' => $request->input('note'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'brand_id' => $request->input('brand_id'),
                'image' => $imagePath
            ]);
            return response()->json([
                'message' => __('messages.product_updated'),
                'status' => $this->ok()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.error_updating_product'),
                'status' => $this->internalServerError()
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
                    'message' => __('messages.product_found'),
                    'status' => $this->ok()
                ]);
            } else {
                return response()->json([
                    'message' => __('messages.product_not_found'),
                    'status' => $this->notFound()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => __('messages.error_finding_product'),
                'status' => $this->internalServerError()
            ]);
        }
    }

    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new ProductNotFoundException();
        }
        try {
            $product->delete();
            return response()->json([
                'message' => __('messages.product_deleted'),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new ProductDeletionException($e->getMessage(), $e->getCode(), $e);
        }
    }


}
