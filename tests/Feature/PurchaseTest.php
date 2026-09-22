<?php

namespace Tests\Feature;

use App\Listeners\StripeEventListener;
use App\Livewire\Learner\Dashboard;
use App\Livewire\Learner\PackageCatalog;
use App\Livewire\Learner\TeacherCatalog;
use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\LessonSession;
use App\Models\Purchase;
use App\Models\SessionPackage;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Events\WebhookReceived;
use Livewire\Livewire;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    private function learnerUser(): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('learner');
        Learner::factory()->self()->create(['user_id' => $user->id]);

        return $user;
    }

    private function approvedTeacherWithSlot(): array
    {
        $profile = TeacherProfile::factory()->approved()->create();
        $slot = AvailabilitySlot::factory()->create(['teacher_profile_id' => $profile->id]);

        return [$profile, $slot];
    }

    public function test_package_catalog_page_loads(): void
    {
        $user = $this->learnerUser();
        SessionPackage::factory()->create(['sessions_count' => 5, 'name' => 'Pack 5 séances']);

        Livewire::actingAs($user)
            ->test(PackageCatalog::class)
            ->assertOk()
            ->assertSee('Pack 5 séances');
    }

    public function test_package_catalog_shows_sessions_remaining(): void
    {
        $user = $this->learnerUser();
        Purchase::factory()->create([
            'user_id' => $user->id,
            'sessions_remaining' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(PackageCatalog::class)
            ->assertViewHas('sessionsRemaining', 3);
    }

    public function test_webhook_creates_purchase_on_checkout_completed(): void
    {
        $user = $this->learnerUser();
        $package = SessionPackage::factory()->create(['sessions_count' => 5]);

        $listener = new StripeEventListener;
        $listener->handle(new WebhookReceived([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_abc123',
                    'payment_status' => 'paid',
                    'metadata' => [
                        'user_id' => $user->id,
                        'package_id' => $package->id,
                    ],
                ],
            ],
        ]));

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'stripe_session_id' => 'cs_test_abc123',
            'sessions_total' => 5,
            'sessions_remaining' => 5,
            'status' => 'completed',
        ]);
    }

    public function test_webhook_is_idempotent(): void
    {
        $user = $this->learnerUser();
        $package = SessionPackage::factory()->create(['sessions_count' => 5]);

        $payload = new WebhookReceived([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_abc123',
                    'payment_status' => 'paid',
                    'metadata' => ['user_id' => $user->id, 'package_id' => $package->id],
                ],
            ],
        ]);

        $listener = new StripeEventListener;
        $listener->handle($payload);
        $listener->handle($payload);

        $this->assertDatabaseCount('purchases', 1);
    }

    public function test_webhook_ignores_unpaid_sessions(): void
    {
        $user = $this->learnerUser();
        $package = SessionPackage::factory()->create();

        $listener = new StripeEventListener;
        $listener->handle(new WebhookReceived([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_xyz',
                    'payment_status' => 'unpaid',
                    'metadata' => ['user_id' => $user->id, 'package_id' => $package->id],
                ],
            ],
        ]));

        $this->assertDatabaseCount('purchases', 0);
    }

    public function test_booking_requires_a_purchase_with_sessions(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book')
            ->assertHasErrors(['selectedLearnerId']);

        $this->assertDatabaseMissing('lesson_sessions', ['availability_slot_id' => $slot->id]);
    }

    public function test_booking_with_purchase_decrements_sessions_remaining(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'sessions_remaining' => 3,
        ]);

        Livewire::actingAs($user)
            ->test(TeacherCatalog::class)
            ->call('toggleSlots', $profile->id)
            ->call('selectSlot', $slot->id)
            ->set('selectedLearnerId', $learner->id)
            ->call('book')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id, 'sessions_remaining' => 2]);
        $this->assertDatabaseHas('lesson_sessions', [
            'purchase_id' => $purchase->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_cancelling_session_recredits_purchase(): void
    {
        $user = $this->learnerUser();
        $learner = $user->learners()->first();
        [$profile, $slot] = $this->approvedTeacherWithSlot();

        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'sessions_remaining' => 2,
        ]);

        $slot->update(['status' => 'booked']);
        $session = LessonSession::factory()->create([
            'availability_slot_id' => $slot->id,
            'learner_id' => $learner->id,
            'teacher_profile_id' => $profile->id,
            'purchase_id' => $purchase->id,
            'status' => 'confirmed',
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('confirmCancel', $session->id)
            ->call('cancelSession');

        $this->assertDatabaseHas('purchases', ['id' => $purchase->id, 'sessions_remaining' => 3]);
    }

    public function test_dashboard_shows_sessions_remaining(): void
    {
        $user = $this->learnerUser();
        Purchase::factory()->create(['user_id' => $user->id, 'sessions_remaining' => 7]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertViewHas('sessionsRemaining', 7);
    }
}
