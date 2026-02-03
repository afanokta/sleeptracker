<?php

namespace App\Livewire\Drivers;

use App\Livewire\Forms\DriverForm;
use App\Models\Driver;
use Livewire\Component;

class Edit extends Component
{
    public Driver $driver;

    public DriverForm $form;

    public function mount(Driver $driver): void
    {
        $this->driver = $driver;
        $this->form = new DriverForm($this, $driver);
        $this->form->setDriver($driver);
    }

    public function update(): void
    {
        $this->form->update($this->driver);
        $this->redirect(route('driver.index'), navigate: true);
        $this->dispatch('driver-updated');
    }

    public function render()
    {
        return view('livewire.drivers.edit');
    }
}
