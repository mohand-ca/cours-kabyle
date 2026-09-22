<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_learner_can_login(): void
    {
        $user = User::factory()->create(['email' => 'learner@example.com']);
        $user->assignRole('learner');

        Livewire::test(Login::class)
            ->set('email', 'learner@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_shows_error(): void
    {
        User::factory()->create(['email' => 'learner@example.com']);

        Livewire::test(Login::class)
            ->set('email', 'learner@example.com')
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        Livewire::test(Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
