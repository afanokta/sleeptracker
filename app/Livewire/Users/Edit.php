<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public UserForm $form;

    public function mount(User $user): void
    {
        $user->password = '';
        $this->user = $user;
        $this->form = new UserForm($this, $user);
        $this->form->setUser($user);
    }

    public function update(): void
    {
        $this->form->update($this->user);
        $this->redirect(route('user.index'), navigate: true);
        $this->dispatch('user-updated');
    }

    public function render()
    {
        return view('livewire.users.edit');
    }
}
