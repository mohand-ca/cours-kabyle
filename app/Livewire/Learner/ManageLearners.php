<?php

namespace App\Livewire\Learner;

use App\Livewire\Forms\LearnerForm;
use App\Models\Learner;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ManageLearners extends Component
{
    public LearnerForm $form;

    public bool $showForm = false;

    public ?int $editingId = null;

    public function startAdd(): void
    {
        $this->resetForm();
        $this->form->relationship = 'child';
        $this->showForm = true;
    }

    public function startEdit(int $learnerId): void
    {
        $learner = $this->authorizedLearner($learnerId);

        $this->editingId = $learner->id;
        $this->form->populate($learner);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->form->validate();

        $user = Auth::user();

        if ($this->editingId) {
            $learner = $this->authorizedLearner($this->editingId);
            $learner->update([
                'first_name' => $this->form->firstName,
                'last_name' => $this->form->lastName,
                'relationship' => $this->form->relationship,
                'date_of_birth' => $this->form->dateOfBirth ?: null,
                'notification_email' => $this->form->notificationEmail ?: null,
            ]);
        } else {
            $user->learners()->create([
                'first_name' => $this->form->firstName,
                'last_name' => $this->form->lastName,
                'relationship' => $this->form->relationship,
                'date_of_birth' => $this->form->dateOfBirth ?: null,
                'notification_email' => $this->form->notificationEmail ?: null,
                'points' => 0,
            ]);
        }

        session()->flash('success', __('learner.learners.saved'));
        $this->resetForm();
    }

    public function delete(int $learnerId): void
    {
        $learner = $this->authorizedLearner($learnerId);

        if ($learner->relationship === 'self') {
            session()->flash('error', __('learner.learners.cannot_delete_self'));

            return;
        }

        $learner->delete();
        session()->flash('success', __('learner.learners.deleted'));
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->form->reset();
        $this->editingId = null;
        $this->showForm = false;
    }

    private function authorizedLearner(int $id): Learner
    {
        return Auth::user()->learners()->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.learner.manage-learners', [
            'learners' => Auth::user()->learners()->orderBy('relationship')->get(),
        ])->layout('components.layouts.app');
    }
}
