<?php

namespace Tests\Feature;

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_forgot_password_page_loads(): void
    {
        $this->get('/forgot-password')->assertOk();
    }

    public function test_reset_link_is_sent_for_existing_email(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->call('sendLink')
            ->assertSet('sent', true);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_no_error_shown_for_unknown_email(): void
    {
        Notification::fake();

        Livewire::test(ForgotPassword::class)
            ->set('email', 'nobody@example.com')
            ->call('sendLink')
            ->assertSet('sent', true)
            ->assertHasNoErrors();

        Notification::assertNothingSent();
    }

    public function test_forgot_password_requires_valid_email(): void
    {
        Livewire::test(ForgotPassword::class)
            ->set('email', 'not-an-email')
            ->call('sendLink')
            ->assertHasErrors(['email']);
    }

    public function test_reset_password_page_loads_with_token(): void
    {
        $this->get('/reset-password/some-token')->assertOk();
    }

    public function test_password_is_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Livewire::test(ForgotPassword::class)
            ->set('email', $user->email)
            ->call('sendLink');

        $token = '';
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        Livewire::test(ResetPassword::class, ['token' => $token])
            ->set('email', $user->email)
            ->set('password', 'new-password-123')
            ->set('password_confirmation', 'new-password-123')
            ->call('resetPassword')
            ->assertRedirect(route('login'));
    }

    public function test_reset_fails_with_invalid_token(): void
    {
        $user = User::factory()->create();

        Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', $user->email)
            ->set('password', 'new-password-123')
            ->set('password_confirmation', 'new-password-123')
            ->call('resetPassword')
            ->assertHasErrors(['email']);
    }

    public function test_reset_requires_matching_passwords(): void
    {
        Livewire::test(ResetPassword::class, ['token' => 'some-token'])
            ->set('email', 'user@example.com')
            ->set('password', 'new-password-123')
            ->set('password_confirmation', 'different-password')
            ->call('resetPassword')
            ->assertHasErrors(['password']);
    }
}
