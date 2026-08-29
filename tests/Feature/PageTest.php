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
    }

    public function test_checkout_success_page_returns_successful_response(): void
    {
        $response = $this->get('/checkout-success?order=AZ-ORD-1001');
        $response->assertStatus(200);
        $response->assertSee('Order Confirmed');
        $response->assertSee('AZ-ORD-1001');
    }
}
