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
    public ?string $driverName = null;

    public function delete(Driver $driver): void
    {
        $driver->delete();
        Flux::toast(
            text: 'Driver berhasil dihapus',
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
        $driverName = trim($this->driverName ?? '');
        $drivers = Driver::when($driverName !== '', function($query) use ($driverName) {
            return $query->where('name', 'ilike', '%'.trim($driverName).'%');
        })->orderBy('name', 'asc');
        // logger()->info('sql', [$drivers->toSql()]);
        return view('livewire.drivers.index', [
            'drivers' => $drivers->paginate(10),
        ]);
    }
}
