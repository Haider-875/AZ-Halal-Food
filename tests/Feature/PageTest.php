<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AZ Halal');
        $response->assertSee('The Art');
        $response->assertSee('Cary, North Carolina');
    }

    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('A Legacy of');
        $response->assertSee('Halal Integrity');
        $response->assertSee('716 Slash Pine Drive');
    }

    public function test_products_page_returns_successful_response(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Our');
        $response->assertSee('Products');
        $response->assertSee('Pricing');
        $response->assertSee('T-Bone Steak');
        $response->assertSee('Rohu Fish');
    }

    public function test_catalog_page_returns_successful_response(): void
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
        $response->assertSee('Product');
        $response->assertSee('Catalog');
    }

    public function test_gallery_page_returns_successful_response(): void
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);
        $response->assertSee('Visual Archive');
        $response->assertSee('Prime Rib-Eye');
    }

    public function test_contact_page_returns_successful_response(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Get In Touch');
        $response->assertSee('716 Slash Pine Dr');
        $response->assertSee('919-244-8634');
    }

    public function test_wholesale_inquiry_submission(): void
    {
        $response = $this->post('/contact/inquiry', [
            'name' => 'Tariq Ahmad',
            'email' => 'tariq@example.com',
            'company' => 'Triangle Biryani Co',
            'phone' => '919-555-0199',
            'inquiry_type' => 'Wholesale Meat',
            'message' => 'We need 50 lbs of fresh goat and beef weekly.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('inquiry_received', true);
    }

    public function test_checkout_page_renders_successfully(): void
    {
        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Complete');
        $response->assertSee('Checkout');
        $response->assertSee('Cash on Delivery');
        $response->assertSee('Online Payment');
    }

    public function test_order_can_be_placed_with_cash_on_delivery(): void
    {
        $response = $this->post('/checkout', [
            'customer_name' => 'Farhan Qureshi',
            'customer_email' => 'farhan@example.com',
            'customer_phone' => '919-555-8821',
            'delivery_address' => '104 Stonegate Way',
            'city' => 'Cary',
            'postal_code' => '27519',
            'delivery_type' => 'delivery',
            'payment_method' => 'cash_on_delivery',
            'notes' => 'Leave near front door',
            'items' => [
                [
                    'id' => 1,
                    'name' => 'T-Bone Steak',
                    'price' => 18.99,
                    'quantity' => 2,
                    'img' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=600&q=80',
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Farhan Qureshi',
            'payment_method' => 'cash_on_delivery',
            'delivery_type' => 'delivery',
        ]);
    }

    public function test_order_can_be_placed_with_online_payment(): void
    {
        $response = $this->post('/checkout', [
            'customer_name' => 'Aisha Siddiqui',
            'customer_email' => 'aisha@example.com',
            'customer_phone' => '919-555-3344',
            'delivery_type' => 'pickup',
            'payment_method' => 'card',
            'items' => [
                [
                    'id' => 2,
                    'name' => 'Rib-Eye Steak',
                    'price' => 22.99,
                    'quantity' => 1,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Aisha Siddiqui',
            'payment_method' => 'card',
            'delivery_type' => 'pickup',
        ]);
    }

    public function test_checkout_success_page_returns_successful_response(): void
    {
        $response = $this->get('/checkout-success?order=AZ-ORD-1001');
        $response->assertStatus(200);
        $response->assertSee('Order Confirmed');
        $response->assertSee('AZ-ORD-1001');
    }
}
