<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use Livewire\Component;
use Flux\Flux;

class Create extends Component
{
    public UserForm $form;

    public function mount(): void
    {
        $this->form = new UserForm($this, null);
    }

    public function save(): void
    {
        $this->form->store();

        $this->dispatch('user-created');

        $this->redirect(route('user.index'), navigate: true);

    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
