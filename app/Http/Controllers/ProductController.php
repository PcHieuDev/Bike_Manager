<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Traits\HttpResponseCode;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductDeletionException;
use App\Exceptions\ErrorFindingProductException;
use App\Exceptions\ErrorUpdatingProductException;
use App\Exceptions\ErrorSavingProductException;
use Illuminate\Http\Response;
use App\Constants\Constants;
use App\Helpers\ArrayHelper;
use lang\en\Message;
use App\Http\Requests\SearchProductRequest;
use App\Repositories\Interfaces\ProductImageRepositoryInterface;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    use HttpResponseCode;

    protected $productRepository;
    protected $productImageRepository;


    public function __construct(ProductRepositoryInterface $productRepository, ProductImageRepositoryInterface $productImageRepository)
    {
        $this->productRepository = $productRepository;
        $this->productImageRepository = $productImageRepository;

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

    public function getData(Request $request)
    {
        $inputs = $request->all();
        $page = ArrayHelper::getValue($inputs, 'page', Constants::DEFAULT_PAGE_NUMBER);
        $keyword = ArrayHelper::getValue($inputs, 'keyword', '');
        $brandId = ArrayHelper::getValue($inputs, 'brandId', '');
        $productId = ArrayHelper::getValue($inputs, 'productId', '');
        $categoryId = ArrayHelper::getValue($inputs, 'categoryId', '');
        $size = ArrayHelper::getValue($inputs, 'size', Constants::DEFAULT_PAGE_SIZE);
        $data = $this->productRepository->paginate($page, $size, $keyword, $brandId, $categoryId, $productId);

        // Sử dụng biến trung gian để lưu trữ kết quả của hàm count
        $count = $this->productRepository->count($keyword, $brandId, $categoryId, $productId);
        return response()->json([
            'contents' => $data,
            'count' => $count,
            'status' => $this->ok()
        ]);
    }

    public function search(SearchProductRequest $request)
    {
        $query = $request->input('query');
        $categoryName = $request->input('categoryName');
        $brandName = $request->input('brandName');
        $productId = $request->input('productId');
        $products = $this->productRepository->search($query, $categoryName, $brandName, $productId);
        return response()->json($products);
    }

    public function createProduct(ProductRequest $request)
    {
        try {
            $data = $request->all();
            $image = $data['image'];
            if ($image) {
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imageName);
                $data['image'] = '/storage/images/' . $imageName;
            } else {
                $data['image'] = '';
            }
            $product = $this->productRepository->create($data); // Lưu kết quả vào biến $product
            $data['id'] = $product->id; // Lấy id của sản phẩm mới được tạo

            return response()->json([
                'message' => __('messages.product_saved'),
                'status' => $this->ok(),
                'product' => $data
            ]);
        } catch (\Exception $e) {
            throw new ErrorSavingProductException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getByBrand($brandId)
    {

        $products = $this->productRepository->getByBrand($brandId);
        return response()->json([
            'products' => $products,
            'message' => __('messages.success.product_found'),
            'status' => $this->ok()

        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Tìm sản phẩm theo ID
            $product = $this->productRepository->find($id);
            // Nếu không tìm thấy sản phẩm, ném ra ngoại lệ
            if (!$product) {
                throw new ProductNotFoundException();
            }

            // Xử lý hình ảnh nếu có
            if ($request->hasFile('image')) {
                // Lấy file ảnh từ request
                $image = $request->file('image');

                // Tạo tên file mới
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Di chuyển file vào thư mục lưu trữ
                $image->move(public_path('storage/images'), $imageName);

                // Xóa hình ảnh cũ nếu có
                if ($product->image) {
                    $oldImagePath = public_path($product->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Cập nhật đường dẫn hình ảnh mới
                $imagePath = '/storage/images/' . $imageName;
            } else {
                // Giữ nguyên đường dẫn hình ảnh cũ nếu không có hình ảnh mới
                $imagePath = $product->image;
            }

            // Cập nhật thông tin sản phẩm
            $this->productRepository->update($id, [
                'name' => $request->input('name'),
                'note' => $request->input('note'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'brand_id' => $request->input('brand_id'),
                'image' => $imagePath
            ]);

            // Xử lý các ảnh chi tiết nếu có
            if ($request->hasFile('image_detail')) {
                // Xóa tất cả ảnh chi tiết cũ
                $oldImages = $this->productImageRepository->getByProductId($id);
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path($oldImage->image_path);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    $oldImage->delete();
                }

                // Thêm ảnh chi tiết mới
                foreach ($request->file('image_detail') as $index => $image) {
                    $imageProductDetailName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('storage/images'), $imageProductDetailName);
                    $imageProductDetailPath = '/storage/images/' . $imageProductDetailName;
                    $this->productImageRepository->create([
                        'product_id' => $id,
                        'image_path' => $imageProductDetailPath,
                        'image_position' => $index, // Thêm vị trí của ảnh = vị trí trong mảng ảnh

                    ]);
                }
            }
            DB::commit();
            // Trả về response thành công cùng với sản phẩm
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Xử lý ngoại lệ
            throw new ErrorUpdatingProductException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function show($id)
    {
        try {
            // Sử dụng eager loading để tải thông tin brand id cùng sản phẩm
            $product = $this->productRepository->find($id)->load('brand');
            // Truy cập brand_id một cách trực tiếp
            $brandId = $product->brand_id;
            $products = $this->productRepository->getByBrand($brandId);
            $imageDetails = $this->productImageRepository->getByProductId($id);
            return response()->json([
                'product' => $product,
                'status' => $product ? $this->ok() : $this->notFound(),
                'products' => $products,
                'imageDetails' => $imageDetails
            ]);
        } catch (\Exception $e) {
            throw new ErrorFindingProductException($e->getMessage(), $e->getCode(), $e);
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
