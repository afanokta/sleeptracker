<?php

namespace App\Livewire\SleepMonitoring;

use App\Models\Driver;
use App\Models\SleepReport;
use App\Models\Sleeptrack;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    use WithFileUploads;

    public $driverSearch = '';
    public $showForm = false;
    public $drivers = [];

    #[Validate('required|exists:drivers,id')]
    public $selectedDriverId = '';

    #[Validate('required|date')]
    public $date = '';

    #[Validate('required')]
    public $time = '';

    #[Validate('required|in:Rumah,SPBU,Lainnya')]
    public $location = '';

    public $customLocation = '';

    #[Validate('nullable|image|max:5120')]
    public $photo;

    public $latitude = null;
    public $longitude = null;

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $this->time = now()->format('H:i');
        $this->loadDrivers();
    }

    public function loadDrivers(): void
    {
        $this->drivers = Driver::orderBy('name')->get();
    }

    public function selectDriver($driverId): void
    {
        $this->selectedDriverId = $driverId;
        $this->showForm = true;
        $this->driverSearch = '';
    }

    public function setLocation($latitude, $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function submit(): void
    {
        $rules = [
            'selectedDriverId' => 'required|exists:drivers,id',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|in:Rumah,SPBU,Lainnya',
            'photo' => 'nullable|image|max:5120',
        ];

        if ($this->location === 'Lainnya') {
            $rules['customLocation'] = 'required|string|max:255';
        }

        $this->validate($rules);

        // Get or create sleep report for the driver and date
        $sleepReport = SleepReport::firstOrCreate(
            [
                'driver_id' => $this->selectedDriverId,
                'date' => $this->date,
            ],
            [
                'status' => 'pending',
                'completed' => false,
            ]
        );

        // Determine input type (sleep or wake)
        $existingTracks = $sleepReport->sleeptracks()->count();
        $inputType = $existingTracks === 0 ? 'sleep' : 'wake';

        // Handle photo upload
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('sleeptrack-photos', 'public');
        }

        // Create sleeptrack
        $locationValue = $this->location === 'Lainnya' ? $this->customLocation : $this->location;
        $inputDateTime = $this->date . ' ' . $this->time;

        Sleeptrack::create([
            'sleep_report_id' => $sleepReport->id,
            'input_type' => $inputType,
            'input_time' => $inputDateTime,
            'location' => $locationValue,
            'long' => $this->longitude,
            'lat' => $this->latitude,
            'photo' => $photoPath,
        ]);

        // Update sleep report status
        if ($inputType === 'wake') {
            $sleepReport->update([
                'status' => 'completed',
                'completed' => true,
            ]);
        }

        Flux::toast(
            text: 'Data monitoring tidur berhasil disimpan',
            heading: 'Berhasil',
            variant: 'success',
        );

        // Reset form
        $this->reset(['selectedDriverId', 'time', 'location', 'customLocation', 'photo', 'latitude', 'longitude', 'showForm', 'driverSearch']);
        $this->date = now()->format('Y-m-d');
        $this->time = now()->format('H:i');
    }

    #[Computed]
    public function filteredDrivers()
    {
        if (empty($this->driverSearch)) {
            return $this->drivers;
        }
        
        return $this->drivers->filter(function ($driver) {
            return stripos($driver->name, $this->driverSearch) !== false;
        });
    }

    #[Computed]
    public function selectedDriver()
    {
        if (empty($this->selectedDriverId)) {
            return null;
        }
        
        return $this->drivers->firstWhere('id', $this->selectedDriverId);
    }

    public function render()
    {
        return view('livewire.sleep-monitoring.form', [
            'filteredDrivers' => $this->filteredDrivers,
        ])->layout('layouts.public');
    }
}
