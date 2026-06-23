<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard', absolute: false));
    }

    public function test_staff_can_authenticate_using_the_staff_login_screen(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $response = $this->post('/staff/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('staff.portal', absolute: false));
    }

    public function test_member_accounts_cannot_authenticate_using_the_staff_login_screen(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->from('/staff/login')->post('/staff/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/staff/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_staff_accounts_cannot_authenticate_using_the_member_login_screen(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_member_accounts_cannot_access_the_staff_portal(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($user)->get('/management/portal');

        $this->assertGuest();
        $response->assertRedirect('/staff/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_staff_accounts_cannot_access_the_member_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
        ]);

        $response = $this->actingAs($user)->get('/membership/dashboard');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
