<?php

namespace App\Livewire\Sleeptracks;

use App\Models\SleepReport;
use Livewire\Component;

class Edit extends Component
{
    public SleepReport $sleepReport;

    public function mount(SleepReport $sleepReport): void
    {
        $this->sleepReport = $sleepReport->load(['driver', 'sleeptracks' => function ($query) {
            $query->orderBy('input_time');
        }]);
    }

    public function render()
    {
        return view('livewire.sleeptracks.edit');
    }
}


