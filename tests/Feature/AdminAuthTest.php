<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    /** @test */
    public function admin_login_screen_can_be_rendered()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('লগইন');
    }

    /** @test */
    public function admins_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $user->assignRole('admin');

        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    /** @test */
    public function admins_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $user->assignRole('admin');

        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function admins_can_logout()
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $user->assignRole('admin');

        $response = $this->actingAs($user, 'web')->post(route('admin.logout'));

        $this->app['auth']->forgetGuards();

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
    }

    /** @test */
    public function guests_are_redirected_to_admin_login_from_admin_dashboard()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }
}
