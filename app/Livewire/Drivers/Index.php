<?php

namespace App\Livewire\Drivers;

use App\Models\Driver;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public function delete(Driver $driver): void
    {
        $driver->delete();
        Flux::toast(
            text: 'Driver berhasil disimpan',
            heading: 'Berhasil',
            variant: 'success',
        );
        $this->dispatch('driver-deleted');


    }

    #[On('driver-deleted')]
    #[On('driver-created')]
    #[On('driver-updated')]
    public function render()
    {
        return view('livewire.drivers.index', [
            'drivers' => Driver::latest()->paginate(10),
        ]);
    }
}
