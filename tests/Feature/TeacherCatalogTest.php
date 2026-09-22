<?php

namespace Tests\Feature;

use App\Livewire\Learner\TeacherCatalog;
use App\Models\AvailabilitySlot;
use App\Models\Learner;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeacherCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    private function learner(): User
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole('learner');
        Learner::factory()->self()->create(['user_id' => $user->id]);

        return $user;
    }

    public function test_catalog_page_loads(): void
    {
        $this->actingAs($this->learner())
            ->get('/teachers')
            ->assertOk();
    }

    public function test_catalog_shows_only_approved_teachers(): void
    {
        TeacherProfile::factory()->approved()->create();
        TeacherProfile::factory()->create(['status' => 'pending']);
        TeacherProfile::factory()->create(['status' => 'suspended']);

        Livewire::actingAs($this->learner())
            ->test(TeacherCatalog::class)
            ->assertViewHas('teachers', fn ($teachers) => $teachers->count() === 1);
    }

    public function test_catalog_filters_by_level(): void
    {
        TeacherProfile::factory()->approved()->create(['levels' => ['beginner']]);
        TeacherProfile::factory()->approved()->create(['levels' => ['advanced']]);

        Livewire::actingAs($this->learner())
            ->test(TeacherCatalog::class)
            ->set('filterLevel', 'beginner')
            ->assertViewHas('teachers', fn ($teachers) => $teachers->count() === 1);
    }

    public function test_catalog_filters_by_language(): void
    {
        TeacherProfile::factory()->approved()->create(['languages' => ['kabyle', 'french']]);
        TeacherProfile::factory()->approved()->create(['languages' => ['kabyle', 'arabic']]);

        Livewire::actingAs($this->learner())
            ->test(TeacherCatalog::class)
            ->set('filterLanguage', 'french')
            ->assertViewHas('teachers', fn ($teachers) => $teachers->count() === 1);
    }

    public function test_catalog_shows_available_slot_count(): void
    {
        $profile = TeacherProfile::factory()->approved()->create();
        AvailabilitySlot::factory()->create([
            'teacher_profile_id' => $profile->id,
            'status' => 'available',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHour(),
        ]);

        Livewire::actingAs($this->learner())
            ->test(TeacherCatalog::class)
            ->assertViewHas('teachers', fn ($teachers) => $teachers->first()->available_slots_count === 1);
    }

    public function test_guest_cannot_access_catalog(): void
    {
        $this->get('/teachers')->assertRedirect('/login');
    }
}
