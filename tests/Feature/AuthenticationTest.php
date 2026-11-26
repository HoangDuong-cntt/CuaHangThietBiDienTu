<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Đăng nhập tài khoản');
    }

    public function test_register_page_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Đăng ký tài khoản');
    }

    public function test_user_can_register_successfully(): void
    {
        $email = 'newuser_'.time().'@smarttech.test';

        $response = $this->post(route('register.post'), [
            'name' => 'Nguyễn Văn Test',
            'email' => $email,
            'phone' => '0901234567',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    public function test_user_cannot_register_without_phone(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Không Có Số Điện Thoại',
            'email' => 'nophone_'.time().'@smarttech.test',
            'phone' => '',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_duplicate_email(): void
    {
        $existingUser = User::factory()->create([
            'email' => 'duplicate@smarttech.test',
        ]);

        $response = $this->post(route('register.post'), [
            'name' => 'Nguyễn Trùng Lặp',
            'email' => 'duplicate@smarttech.test',
            'phone' => '0901234567',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_user_can_authenticate_using_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'login_test@smarttech.test',
            'password' => Hash::make('Secret123!'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'login_test@smarttech.test',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'email' => 'invalid_pass@smarttech.test',
            'password' => Hash::make('CorrectPassword123!'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'invalid_pass@smarttech.test',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'banned@smarttech.test',
            'password' => Hash::make('Secret123!'),
            'is_active' => false,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'banned@smarttech.test',
            'password' => 'Secret123!',
        ]);

        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
