<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_successfully(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@azhalal.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Overview');
        $response->assertSee('Total Revenue');
    }

    public function test_admin_can_create_new_product(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $category = Category::first();

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Wagyu Halal Ribeye',
            'category_id' => $category->id,
            'price' => 34.99,
            'sku' => 'BEEF-WAGYU-1',
            'stock' => 10,
            'badge' => 'EXCLUSIVE',
            'desc' => 'Finest A5 wagyu beef halal certified.',
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Wagyu Halal Ribeye',
            'sku' => 'BEEF-WAGYU-1',
        ]);
    }

    public function test_admin_can_create_product_with_image_upload(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $category = Category::first();
        $file = UploadedFile::fake()->image('test_product.jpg', 600, 400);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Uploaded Organic Goat',
            'category_id' => $category->id,
            'price' => 22.50,
            'sku' => 'GOAT-UPLOAD-1',
            'stock' => 5,
            'image_file' => $file,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $product = Product::where('sku', 'GOAT-UPLOAD-1')->first();
        $this->assertNotNull($product);
        $this->assertStringContainsString('uploads/products/product_', $product->getRawOriginal('img'));

        // Clean up test upload file if written
        if (file_exists(public_path($product->getRawOriginal('img')))) {
            unlink(public_path($product->getRawOriginal('img')));
        }
    }

    public function test_admin_can_update_product_with_image_upload(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $product = Product::first();
        $file = UploadedFile::fake()->image('updated_product.jpg', 600, 400);

        $response = $this->actingAs($admin)->put("/admin/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'category_id' => $product->category_id,
            'price' => 29.99,
            'sku' => $product->sku,
            'stock' => 20,
            'image_file' => $file,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');
        $product->refresh();
        $this->assertEquals('Updated Product Name', $product->name);
        $this->assertStringContainsString('uploads/products/product_', $product->getRawOriginal('img'));

        if (file_exists(public_path($product->getRawOriginal('img')))) {
            unlink(public_path($product->getRawOriginal('img')));
        }
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $order = Order::first();

        $response = $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", [
            'status' => 'completed',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_customer_can_place_order_via_checkout(): void
    {
        $product = Product::first();

        $response = $this->postJson('/checkout', [
            'customer_name' => 'Zubair Malik',
            'customer_email' => 'zubair@example.com',
            'customer_phone' => '919-555-4422',
            'delivery_address' => '200 High House Rd',
            'city' => 'Cary',
            'postal_code' => '27519',
            'delivery_type' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'notes' => 'Please ring the doorbell.',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => 2,
                    'img' => $product->img,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'order_number', 'redirect']);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Zubair Malik',
            'customer_email' => 'zubair@example.com',
            'delivery_type' => 'delivery',
        ]);
    }
}
