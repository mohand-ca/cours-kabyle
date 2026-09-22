<?php

namespace Tests\Feature;

use App\Livewire\Auth\Register;
use App\Models\Learner;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_registration_creates_user_with_learner_role(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(Register::class)
            ->set('form.name', 'Samir Test')
            ->set('form.firstName', 'Samir')
            ->set('form.lastName', 'Test')
            ->set('form.email', 'samir@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register');

        $user = User::where('email', 'samir@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('learner', $user->role);
        $this->assertTrue($user->hasRole('learner'));
    }

    public function test_registration_creates_self_learner_profile(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(Register::class)
            ->set('form.name', 'Amina Test')
            ->set('form.firstName', 'Amina')
            ->set('form.lastName', 'Test')
            ->set('form.email', 'amina@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register');

        $user = User::where('email', 'amina@example.com')->first();
        $learner = Learner::where('user_id', $user->id)->first();

        $this->assertNotNull($learner);
        $this->assertEquals('Amina', $learner->first_name);
        $this->assertEquals('Test', $learner->last_name);
        $this->assertEquals('self', $learner->relationship);
    }

    public function test_registration_sends_verification_email(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(Register::class)
            ->set('form.name', 'Test User')
            ->set('form.firstName', 'Test')
            ->set('form.lastName', 'User')
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register');

        Event::assertDispatched(Registered::class);
    }

    public function test_registration_requires_all_fields(): void
    {
        Livewire::test(Register::class)
            ->call('register')
            ->assertHasErrors([
                'form.name',
                'form.firstName',
                'form.lastName',
                'form.email',
                'form.password',
            ]);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::test(Register::class)
            ->set('form.name', 'Someone')
            ->set('form.firstName', 'Someone')
            ->set('form.lastName', 'Else')
            ->set('form.email', 'taken@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['form.email']);
    }

    public function test_registration_rejects_mismatched_passwords(): void
    {
        Livewire::test(Register::class)
            ->set('form.name', 'Test')
            ->set('form.firstName', 'Test')
            ->set('form.lastName', 'User')
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'different456')
            ->call('register')
            ->assertHasErrors(['form.password']);
    }
}
