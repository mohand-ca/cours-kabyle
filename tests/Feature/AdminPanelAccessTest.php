<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_learner_cannot_access_admin_panel(): void
    {
        $learner = User::factory()->create(['email_verified_at' => now()]);
        $learner->assignRole('learner');

        $this->actingAs($learner)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_teacher_cannot_access_admin_panel(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_unverified_admin_cannot_access_panel(): void
    {
        $admin = User::factory()->unverified()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    }
}
