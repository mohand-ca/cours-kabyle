<?php

namespace Tests\Feature;

use App\Livewire\Auth\TeacherRegister;
use App\Livewire\Teacher\ProfileSetup;
use App\Models\TeacherProfile;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class TeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(RoleSeeder::class);
    }

    public function test_teacher_registration_creates_user_with_teacher_role(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(TeacherRegister::class)
            ->set('form.name', 'Yidir Ait')
            ->set('form.email', 'yidir@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.timezone', 'Africa/Algiers')
            ->call('register');

        $user = User::where('email', 'yidir@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('teacher', $user->role);
        $this->assertTrue($user->hasRole('teacher'));
    }

    public function test_teacher_registration_creates_blank_teacher_profile(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(TeacherRegister::class)
            ->set('form.name', 'Taziri Ould')
            ->set('form.email', 'taziri@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.timezone', 'Africa/Algiers')
            ->call('register');

        $user = User::where('email', 'taziri@example.com')->first();

        $this->assertNotNull($user->teacherProfile);
        $this->assertEquals('pending', $user->teacherProfile->status);
        $this->assertNull($user->teacherProfile->submitted_at);
    }

    public function test_teacher_registration_saves_timezone(): void
    {
        Event::fake([Registered::class]);

        Livewire::test(TeacherRegister::class)
            ->set('form.name', 'Amazigh Idir')
            ->set('form.email', 'amazigh@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.timezone', 'Europe/Paris')
            ->call('register');

        $this->assertDatabaseHas('users', [
            'email' => 'amazigh@example.com',
            'timezone' => 'Europe/Paris',
        ]);
    }

    public function test_teacher_registration_requires_valid_timezone(): void
    {
        Livewire::test(TeacherRegister::class)
            ->set('form.name', 'Test User')
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.timezone', 'Invalid/Timezone')
            ->call('register')
            ->assertHasErrors(['form.timezone']);
    }

    public function test_teacher_cannot_access_learner_dashboard(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->create(['user_id' => $teacher->id]);

        $this->actingAs($teacher)
            ->get('/dashboard')
            ->assertForbidden();
    }

    public function test_learner_cannot_access_teacher_routes(): void
    {
        $learner = User::factory()->create(['email_verified_at' => now()]);
        $learner->assignRole('learner');

        $this->actingAs($learner)
            ->get('/teacher/profile')
            ->assertForbidden();
    }

    public function test_teacher_can_save_profile(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        TeacherProfile::factory()->create(['user_id' => $teacher->id]);

        Livewire::actingAs($teacher)
            ->test(ProfileSetup::class)
            ->set('bio', str_repeat('Ma présentation complète. ', 5))
            ->set('levels', ['beginner', 'intermediate'])
            ->set('languages', ['kabyle', 'french'])
            ->set('meetLink', 'https://meet.google.com/abc-defg-hij')
            ->call('save');

        $profile = $teacher->fresh()->teacherProfile;

        $this->assertEquals(['beginner', 'intermediate'], $profile->levels);
        $this->assertEquals(['kabyle', 'french'], $profile->languages);
        $this->assertEquals('https://meet.google.com/abc-defg-hij', $profile->meet_link);
    }

    public function test_teacher_can_submit_completed_profile_for_validation(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');
        $profile = TeacherProfile::factory()->create([
            'user_id' => $teacher->id,
            'bio' => str_repeat('Bio complète. ', 5),
            'levels' => ['beginner'],
            'languages' => ['kabyle'],
            'meet_link' => 'https://meet.google.com/abc-defg-hij',
        ]);

        Livewire::actingAs($teacher)
            ->test(ProfileSetup::class)
            ->set('bio', str_repeat('Bio complète. ', 5))
            ->set('levels', ['beginner'])
            ->set('languages', ['kabyle'])
            ->set('meetLink', 'https://meet.google.com/abc-defg-hij')
            ->call('submit');

        $profile->refresh();

        $this->assertNotNull($profile->submitted_at);
        $this->assertEquals('pending', $profile->status);
    }

    public function test_admin_can_approve_teacher(): void
    {
        $profile = TeacherProfile::factory()->submitted()->create();

        $this->assertFalse($profile->isApproved());

        $profile->approve();

        $this->assertTrue($profile->fresh()->isApproved());
    }

    public function test_admin_can_suspend_teacher(): void
    {
        $profile = TeacherProfile::factory()->approved()->create();

        $profile->suspend();

        $this->assertTrue($profile->fresh()->isSuspended());
    }
}
