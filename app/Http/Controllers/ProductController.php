<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Http\Traits\HttpResponseCode;
use App\Exceptions\SomethingHasErrorException;
use Illuminate\Http\Response;
use App\Constants\Constants;
use lang\en\Message;
use App\Http\Requests\SearchProductRequest;
use App\Repositories\Interfaces\ProductImageRepositoryInterface;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

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
        // Sử dụng only để lấy chỉ các trường dữ liệu cần thiết
        $validatedData = $request->only('page', 'keyword', 'brandId', 'productId', 'categoryId', 'size');

        // Sử dụng các giá trị mặc định nếu không tồn tại
        $page = $validatedData['page'] ?? Constants::DEFAULT_PAGE_NUMBER;
        $size = $validatedData['size'] ?? Constants::DEFAULT_PAGE_SIZE;
        $keyword = $validatedData['keyword'] ?? '';
        $brandId = $validatedData['brandId'] ?? '';
        $productId = $validatedData['productId'] ?? '';
        $categoryId = $validatedData['categoryId'] ?? '';

        // Gọi phương thức paginate từ repository
        $data = $this->productRepository->paginate($page, $size, $keyword, $brandId, $categoryId, $productId);

        // Sử dụng phương thức count từ repository để lấy số lượng kết quả tìm kiếm tổng thể
        $count = $this->productRepository->count($keyword, $brandId, $categoryId, $productId);

        // Trả về response JSON với các dữ liệu đã lấy được
        return response()->json([
            'contents' => $data,
            'count' => $count,
            'status' => $this->ok()
        ]);
    }

    public function search(SearchProductRequest $request)
    {
        $query = $request->input('query');
        $products = $this->productRepository->search($query);
        return response()->json($products);
    }

    public function createProduct(ProductRequest $request)
    {
        try {
            $data = $request->all();
            $image = $data['image'];
            if ($image) {
                $imageName = time() . '.' . $image->getClientOriginalExtension(); // Tạo tên mới cho ảnh
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
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
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
            // Tìm sản phẩm dựa trên ID và kiểm tra nếu không tồn tại thì báo lỗi
            $product = $this->findProduct($id);
            // Xử lý và lưu hình ảnh sản phẩm, trả về đường dẫn của hình ảnh
            $imagePath = $this->handleProductImage($request, $product);
            // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
            $this->updateProductInfo($request, $id, $imagePath);
            // Xử lý và lưu các hình ảnh chi tiết của sản phẩm
            $this->handleProductDetailImages($request, $id);
            DB::commit();
            // Trả về phản hồi thành công cùng với thông tin sản phẩm
            return response()->json(['success' => true, 'product' => $product]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    // Tìm kiếm sản phẩm dựa trên ID và kiểm tra sự tồn tại của sản phẩm
    private function findProduct($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new SomethingHasErrorException();
        }
        return $product;
    }

    // Xử lý việc tải lên và cập nhật hình ảnh sản phẩm
    private function handleProductImage($request, $product)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            // Xóa hình ảnh cũ nếu có
            $this->deleteOldImage($product->image);
            return '/storage/images/' . $imageName;
        }
        return $product->image;
    }

    // Xóa hình ảnh cũ khỏi hệ thống file
    private function deleteOldImage($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    private function updateProductInfo($request, $id, $imagePath)
    {
        $this->productRepository->update($id, [
            'name' => $request->input('name'),
            'note' => $request->input('note'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'brand_id' => $request->input('brand_id'),
            'image' => $imagePath
        ]);
        //  dd(is_null($request->input('note')));


  }

    // Xử lý việc tải lên và cập nhật các hình ảnh chi tiết của sản phẩm
    private function handleProductDetailImages($request, $id)
    {
        if ($request->hasFile('image_detail')) {
            // Xóa các hình ảnh chi tiết cũ của sản phẩm
            $oldImages = $this->productImageRepository->getByProductId($id); // Lấy tất cả ảnh chi tiết của sản phẩm
            foreach ($oldImages as $oldImage) { // Duyệt qua từng ảnh chi tiết
                $this->deleteOldImage($oldImage->image_path); // Xóa ảnh chi tiết cũ
                $oldImage->delete();
            }
            // Tải lên và lưu các hình ảnh chi tiết mới
            foreach ($request->file('image_detail') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/images'), $imageName); // Lưu ảnh vào thư mục
                $this->productImageRepository->create([
                    'product_id' => $id,
                    'image_path' => '/storage/images/' . $imageName, // Thêm đường dẫn ảnh
                    'image_position' => $index, // Thêm vị trí của ảnh = vị trí trong mảng ảnh
                ]);
            }
        }
    }


    public function show($id)
    {
        try {
            //  tải thông tin brand id cùng sản phẩm
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
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete($id)
    {
        try {
            // Sử dụng phương thức delete() từ ProductReposi
            $deleted = $this->productRepository->delete($id);

            // Kiểm tra xem sản phẩm có được xóa thành công
            if (!$deleted) {
                throw new SomethingHasErrorException();
            }

            return response()->json([
                'message' => __('messages.product_deleted'),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new SomethingHasErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
