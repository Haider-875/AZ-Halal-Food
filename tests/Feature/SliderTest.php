<?php

namespace Tests\Feature;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SliderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_displays_active_sliders(): void
    {
        Slider::query()->delete();

        Slider::create([
            'title' => 'Special Royal Cut Halal Steak',
            'subtitle' => 'Exclusive Weekend Special',
            'badge' => 'LIMITED OFFER',
            'description' => 'Aged to perfection and hand-cut by master butchers.',
            'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1800&q=90',
            'button_text' => 'Order Special Cut',
            'button_link' => '/products',
            'display_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Special Royal Cut Halal Steak');
        $response->assertSee('Exclusive Weekend Special');
        $response->assertSee('LIMITED OFFER');
        $response->assertSee('Order Special Cut');
    }

    public function test_admin_can_view_sliders_index(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->get('/admin/sliders');

        $response->assertStatus(200);
        $response->assertSee('Frontend Hero Slider Management');
    }

    public function test_admin_can_create_slider_with_file_upload(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $file = UploadedFile::fake()->image('banner_hero.jpg', 1920, 1080);

        $response = $this->actingAs($admin)->post('/admin/sliders', [
            'title' => 'Fresh Catch From The Coast',
            'subtitle' => 'Authentic Hilsa & Katla',
            'badge' => 'FRESH SEAFOOD',
            'description' => 'Delivered chilled and fresh directly to Cary, NC.',
            'image_file' => $file,
            'button_text' => 'Browse Fish',
            'button_link' => '/catalog',
            'display_order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/sliders');
        $this->assertDatabaseHas('sliders', [
            'title' => 'Fresh Catch From The Coast',
            'badge' => 'FRESH SEAFOOD',
        ]);

        $slider = Slider::where('title', 'Fresh Catch From The Coast')->first();
        $this->assertNotNull($slider);
        $this->assertStringStartsWith('uploads/sliders/', $slider->image);

        // Clean up created fake file
        if (File::exists(public_path($slider->image))) {
            File::delete(public_path($slider->image));
        }
    }

    public function test_admin_can_create_slider_with_url(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->post('/admin/sliders', [
            'title' => 'Pure Pakistani Mango Imports',
            'subtitle' => 'Direct from Multan Orchards',
            'image_url' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=1800&q=85',
            'button_text' => 'Order Mango Box',
            'button_link' => '/products',
            'display_order' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/sliders');
        $this->assertDatabaseHas('sliders', [
            'title' => 'Pure Pakistani Mango Imports',
            'image' => 'https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=1800&q=85',
        ]);
    }

    public function test_admin_slider_creation_requires_image_file_or_url(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->from('/admin/sliders/create')->post('/admin/sliders', [
            'title' => 'Slide without image',
            'display_order' => 0,
        ]);

        $response->assertRedirect('/admin/sliders/create');
        $response->assertSessionHasErrors(['image_file']);
    }

    public function test_admin_can_update_slider(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $slider = Slider::first() ?? Slider::create([
            'title' => 'Original Banner',
            'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1800&q=90',
            'display_order' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put("/admin/sliders/{$slider->id}", [
            'title' => 'Updated Banner Headline',
            'subtitle' => 'Updated Subtitle',
            'badge' => 'NEW BADGE',
            'display_order' => 5,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/sliders');
        $this->assertDatabaseHas('sliders', [
            'id' => $slider->id,
            'title' => 'Updated Banner Headline',
            'badge' => 'NEW BADGE',
            'display_order' => 5,
        ]);
    }

    public function test_admin_can_delete_slider(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $slider = Slider::first() ?? Slider::create([
            'title' => 'Banner To Delete',
            'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1800&q=90',
            'display_order' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/sliders/{$slider->id}");

        $response->assertRedirect('/admin/sliders');
        $this->assertDatabaseMissing('sliders', [
            'id' => $slider->id,
        ]);
    }
}
