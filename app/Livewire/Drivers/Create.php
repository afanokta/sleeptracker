<?php

namespace App\Livewire\Drivers;

use App\Livewire\Forms\DriverForm;
use Livewire\Component;
use Flux\Flux;

class Create extends Component
{
    public DriverForm $form;

    public function mount(): void
    {
        $this->form = new DriverForm($this, null);
    }

    public function save(): void
    {
        $this->form->store();

        $this->dispatch('driver-created');

        $this->redirect(route('driver.index'), navigate: true);

    }

    public function render()
    {
        return view('livewire.drivers.create');
    }
}
