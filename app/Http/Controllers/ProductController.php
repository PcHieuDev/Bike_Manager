<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Traits\HttpResponseCode;
use Illuminate\Support\Facades\Storage;
use App\Helpers\MessageHelper;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductDeletionException;
use Illuminate\Http\Response;
use App\Constants\Constants;
use App\Helpers\ArrayHelper;
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
                'message' => product_found(),

                'status' => $this->ok()

            ]
        );
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
                'message' => error_saving_product(),
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
                'message' => product_updated(),
                'code' => $this->ok()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => error_updating_product(),
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
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        try {
            $product->delete();
            return response()->json([
                'message' => product_deleted(),
                'code' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new ProductDeletionException($e->getMessage(), $e->getCode(), $e);
        }
    }



    // public function delete($id)
    // {
    //     try {
    //         $product = $this->productRepository->find($id);
    //         if ($product) {
    //             $product->delete();
    //             return response()->json([
    //                 'message' => product_deleted(),
    //                 'code' => $this->ok()
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'message' => product_not_found(),
    //                 'code' => $this->notFound()
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => error_deleting_product(),
    //             'code' => $this->internalServerError()
    //         ]);
    //     }
    // }
}
