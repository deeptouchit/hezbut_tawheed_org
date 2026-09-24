<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the admin role
        Role::create(['name' => 'admin', 'guard_name' => 'web']);

        // Create an active admin user
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);
        $this->adminUser->assignRole('admin');
    }

    /** @test */
    public function admin_can_view_profile_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee($this->adminUser->name);
        $response->assertSee($this->adminUser->email);
    }

    /** @test */
    public function admin_can_view_profile_edit_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/profile/edit');

        $response->assertStatus(200);
        $response->assertSee($this->adminUser->name);
    }

    /** @test */
    public function admin_can_update_profile_information()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/profile/update', [
                'name'    => 'Updated Admin Name',
                'email'   => 'updated_admin@example.com',
                'phone'   => '01711223344',
                'address' => 'Dhaka, Bangladesh',
            ]);

        $response->assertRedirect('/admin/profile');
        $response->assertSessionHasNoErrors();

        $this->adminUser->refresh();
        $this->assertEquals('Updated Admin Name', $this->adminUser->name);
        $this->assertEquals('updated_admin@example.com', $this->adminUser->email);
        $this->assertEquals('01711223344', $this->adminUser->phone);
        $this->assertEquals('Dhaka, Bangladesh', $this->adminUser->address);
    }

    /** @test */
    public function profile_update_validation_fails_with_invalid_email()
    {
        $response = $this->actingAs($this->adminUser)
            ->from('/admin/profile/edit')
            ->post('/admin/profile/update', [
                'name'  => 'Updated Admin Name',
                'email' => 'invalid-email-format',
            ]);

        $response->assertRedirect('/admin/profile/edit');
        $response->assertSessionHasErrors('email');
    }
}
