<?php

namespace Tests\Feature;

use App\Livewire\Learner\Dashboard;
use App\Livewire\Learner\ManageLearners;
use App\Models\Learner;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LearnerDashboardTest extends TestCase
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

    public function test_dashboard_page_loads(): void
    {
        $this->actingAs($this->learner())
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_teacher_cannot_access_learner_dashboard(): void
    {
        $teacher = User::factory()->create(['email_verified_at' => now()]);
        $teacher->assignRole('teacher');

        $this->actingAs($teacher)
            ->get('/dashboard')
            ->assertForbidden();
    }

    public function test_dashboard_shows_learner_count(): void
    {
        $user = $this->learner();
        Learner::factory()->child()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSee('2');
    }

    public function test_manage_learners_page_loads(): void
    {
        $this->actingAs($this->learner())
            ->get('/learners')
            ->assertOk();
    }

    public function test_learner_can_add_a_child_profile(): void
    {
        $user = $this->learner();

        Livewire::actingAs($user)
            ->test(ManageLearners::class)
            ->call('startAdd')
            ->set('form.firstName', 'Amine')
            ->set('form.lastName', 'Boukhelifa')
            ->set('form.relationship', 'child')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('learners', [
            'user_id' => $user->id,
            'first_name' => 'Amine',
            'last_name' => 'Boukhelifa',
            'relationship' => 'child',
        ]);
    }

    public function test_learner_can_edit_a_profile(): void
    {
        $user = $this->learner();
        $child = Learner::factory()->child()->create([
            'user_id' => $user->id,
            'first_name' => 'Amine',
            'last_name' => 'Old',
        ]);

        Livewire::actingAs($user)
            ->test(ManageLearners::class)
            ->call('startEdit', $child->id)
            ->set('form.firstName', 'Amine')
            ->set('form.lastName', 'Boukhelifa')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Boukhelifa', $child->fresh()->last_name);
    }

    public function test_learner_can_delete_a_child_profile(): void
    {
        $user = $this->learner();
        $child = Learner::factory()->child()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(ManageLearners::class)
            ->call('delete', $child->id);

        $this->assertDatabaseMissing('learners', ['id' => $child->id]);
    }

    public function test_self_learner_cannot_be_deleted(): void
    {
        $user = $this->learner();
        $self = $user->learners()->where('relationship', 'self')->first();

        Livewire::actingAs($user)
            ->test(ManageLearners::class)
            ->call('delete', $self->id);

        $this->assertDatabaseHas('learners', ['id' => $self->id]);
    }

    public function test_add_form_requires_first_and_last_name(): void
    {
        Livewire::actingAs($this->learner())
            ->test(ManageLearners::class)
            ->call('startAdd')
            ->call('save')
            ->assertHasErrors(['form.firstName', 'form.lastName']);
    }

    public function test_learner_cannot_access_another_users_learner(): void
    {
        $user = $this->learner();
        $other = $this->learner();
        $otherChild = Learner::factory()->child()->create(['user_id' => $other->id]);

        Livewire::actingAs($user)
            ->test(ManageLearners::class)
            ->call('delete', $otherChild->id);

        $this->assertDatabaseHas('learners', ['id' => $otherChild->id]);
    }
}
