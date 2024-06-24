<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Tạo một Personal Access Client
        $clientRepository = new ClientRepository();
        $client = $clientRepository->createPersonalAccessClient(
            null, 'Test Personal Access Client', 'http://localhost'
        );

        $client->update(['personal_access_client' => true]);    // Đánh dấu client này là personal access client                                                                    
    }

    public function test_create_product_successfully()
    {
        // Tạo một user mẫu
        $user = User::factory()->create(); 

        // Lấy token của user
        Passport::actingAs($user); // Đăng nhập bằng user vừa tạo để nhận token trả về từ Passport

        $productData = [
            "name" => "Test Product",
            "price" => 100245,
            "brand_id" => 1,
            "category_id" => 1,
            "note" => "Test note",
            "image" => null
        ];

        // Sử dụng token này để gửi yêu cầu POST đến endpoint tạo sản phẩm
        $response = $this->withHeaders([ // Thêm header Authorization chứa token vào request
            'Authorization' => 'Bearer ' . $user->createToken('TestToken')->accessToken,
        ])->postJson('/api/v1/saveProduct', $productData);

        $response->assertStatus(200) // Kiểm tra mã trạng thái trả về       
            ->assertJson(['message' => 'messages.product_saved']); // Kiểm tra dữ liệu trả về
    }
}