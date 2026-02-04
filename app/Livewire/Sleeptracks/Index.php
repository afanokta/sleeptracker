<?php

namespace App\Livewire\Sleeptracks;

use App\Models\SleepReport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public ?string $driverName = null;

    public ?string $sleepCategory = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public ?string $quickRange = null;

    protected $queryString = [
        'driverName' => ['except' => ''],
        'sleepCategory' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'quickRange' => ['except' => ''],
    ];

    public function updatingDriverName(): void
    {
        $this->resetPage();
    }

    public function updatingSleepCategory(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatingQuickRange(): void
    {
        $this->resetPage();
    }

    public function setQuickRange(string $range): void
    {
        $this->quickRange = $range;

        $now = Carbon::now();

        match ($range) {
            'today' => [
                $this->dateFrom = $now->toDateString(),
                $this->dateTo = $now->toDateString(),
            ],
            'this_week' => [
                $this->dateFrom = $now->copy()->startOfWeek()->toDateString(),
                $this->dateTo = $now->copy()->endOfWeek()->toDateString(),
            ],
            'this_month' => [
                $this->dateFrom = $now->copy()->startOfMonth()->toDateString(),
                $this->dateTo = $now->copy()->endOfMonth()->toDateString(),
            ],
            'this_year' => [
                $this->dateFrom = $now->copy()->startOfYear()->toDateString(),
                $this->dateTo = $now->copy()->endOfYear()->toDateString(),
            ],
            default => [
                $this->dateFrom = null,
                $this->dateTo = null,
            ],
        };

        $this->resetPage();
    }

    public function delete(SleepReport $sleepReport): void
    {
        $sleepReport->sleeptracks()->delete();
        $sleepReport->delete();

        Flux::toast(
            text: 'Data sleepreport & sleeptrack berhasil dihapus',
            heading: 'Berhasil',
            variant: 'success',
        );

        $this->dispatch('sleepreport-deleted');
    }

    #[On('sleepreport-deleted')]
    public function render()
    {
        $query = SleepReport::with(['driver', 'sleeptracks']);
        if(!isset($this->dateFrom)) {
            $this->dateFrom = Carbon::today()->toDateString();
        }
        if(!isset($this->dateTo)) {
            $this->dateTo = Carbon::today()->toDateString();
        }

        if ($this->driverName) {
            $query->whereHas('driver', function ($q) {
                $q->where('name', 'ilike', '%'.trim($this->driverName).'%');
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('date', '<=', $this->dateTo);
        }

        $reports = $query
            ->latest('date')
            ->get();

        if ($this->sleepCategory) {
            $reports = $reports->filter(function (SleepReport $report) {
                return $report->sleep_category === $this->sleepCategory;
            });
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $paginated = new LengthAwarePaginator(
            items: $reports->forPage($currentPage, $perPage)->values(),
            total: $reports->count(),
            perPage: $perPage,
            currentPage: $currentPage,
            options: [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );

        return view('livewire.sleeptracks.index', [
            'reports' => $paginated,
        ]);
    }
}


