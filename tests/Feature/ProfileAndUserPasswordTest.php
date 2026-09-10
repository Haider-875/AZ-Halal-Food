<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileAndUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/admin/profile');
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee($admin->email);
        $response->assertSee('Profile Information');
        $response->assertSee('Change Password');
    }

    public function test_user_can_update_own_profile_details(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->patch('/admin/profile', [
            'name' => 'Tariq Aziz Updated',
            'email' => 'admin@azhalal.com',
            'phone' => '919-555-8899',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertEquals('Tariq Aziz Updated', $admin->name);
        $this->assertEquals('919-555-8899', $admin->phone);
    }

    public function test_user_cannot_update_profile_with_another_users_email(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $otherUser = User::factory()->create([
            'email' => 'staff1@azhalal.com',
            'role' => 'staff',
        ]);

        $response = $this->actingAs($admin)->patch('/admin/profile', [
            'name' => 'Admin User',
            'email' => 'staff1@azhalal.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_change_own_password_with_correct_current_password(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => 'NewSecurePassword!2026',
            'password_confirmation' => 'NewSecurePassword!2026',
        ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword!2026', $admin->password));
    }

    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'wrong_password',
            'password' => 'NewSecurePassword!2026',
            'password_confirmation' => 'NewSecurePassword!2026',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_user_cannot_change_password_with_mismatched_confirmation(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();

        $response = $this->actingAs($admin)->put('/admin/profile/password', [
            'current_password' => 'password123',
            'password' => 'NewSecurePassword!2026',
            'password_confirmation' => 'DifferentPassword!999',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_admin_can_view_user_edit_and_profile_page(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $targetUser = User::factory()->create([
            'name' => 'Hamza Staff',
            'email' => 'hamza@azhalal.com',
            'role' => 'staff',
        ]);

        $response = $this->actingAs($admin)->get("/admin/users/{$targetUser->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Hamza Staff');
        $response->assertSee('hamza@azhalal.com');
        $response->assertSee("Reset User's Password", false);
    }

    public function test_admin_can_update_user_information(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $targetUser = User::factory()->create([
            'name' => 'Sara Staff',
            'email' => 'sara@azhalal.com',
            'role' => 'staff',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => 'Sara Manager',
            'email' => 'sara_updated@azhalal.com',
            'role' => 'manager',
            'phone' => '919-555-1234',
            'is_active' => 1,
        ]);

        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertEquals('Sara Manager', $targetUser->name);
        $this->assertEquals('sara_updated@azhalal.com', $targetUser->email);
        $this->assertEquals('manager', $targetUser->role);
        $this->assertEquals('919-555-1234', $targetUser->phone);
    }

    public function test_admin_can_reset_another_users_password(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        $targetUser = User::factory()->create([
            'email' => 'reset_target@azhalal.com',
            'password' => 'InitialPassword!1',
            'role' => 'staff',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}/password", [
            'password' => 'AdminGrantedNewPass!456',
            'password_confirmation' => 'AdminGrantedNewPass!456',
        ]);

        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('AdminGrantedNewPass!456', $targetUser->password));
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $staffUser = User::factory()->create([
            'role' => 'staff',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staffUser)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_filter_and_search_users(): void
    {
        $admin = User::where('email', 'admin@azhalal.com')->first();
        User::factory()->create([
            'name' => 'SpecificSearchQueryName',
            'email' => 'unique_filter_test@azhalal.com',
            'role' => 'manager',
        ]);

        $response = $this->actingAs($admin)->get('/admin/users?search=SpecificSearchQueryName');
        $response->assertStatus(200);
        $response->assertSee('SpecificSearchQueryName');

        $roleResponse = $this->actingAs($admin)->get('/admin/users?role=manager');
        $roleResponse->assertStatus(200);
        $roleResponse->assertSee('unique_filter_test@azhalal.com');
    }
}
