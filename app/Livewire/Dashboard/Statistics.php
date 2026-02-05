<?php

namespace App\Livewire\Dashboard;

use App\Models\Driver;
use App\Models\SleepReport;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Statistics extends Component
{
    public ?string $quickRange = 'today';

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function mount(): void
    {
        $this->setQuickRange($this->quickRange ?? 'today');
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
        $this->dispatch('charts-updated');
    }

    public function render()
    {
        $reportsQuery = SleepReport::with(['driver', 'sleeptracks']);

        if ($this->dateFrom) {
            $reportsQuery->whereDate('date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $reportsQuery->whereDate('date', '<=', $this->dateTo);
        }

        $reports = $reportsQuery->get();

        $totalDrivers = Driver::count();
        $totalSleepReports = $reports->count();

        $averageSleepDuration = $totalSleepReports > 0
            ? round($reports->avg(fn (SleepReport $report) => $report->sleep_duration_hours), 1)
            : 0.0;

        $fitCount = $reports->filter(function (SleepReport $report) {
            return strtolower((string) $report->status) === 'fit';
        })->count();

        $notFitCount = $totalSleepReports - $fitCount;

        $sleepCategoryCounts = [
            'Kurang' => $reports->filter(fn (SleepReport $report) => $report->sleep_category === 'Kurang')->count(),
            'Cukup' => $reports->filter(fn (SleepReport $report) => $report->sleep_category === 'Cukup')->count(),
            'Lebih dari cukup' => $reports->filter(fn (SleepReport $report) => $report->sleep_category === 'Lebih dari cukup')->count(),
        ];

        return view('livewire.dashboard.statistics', [
            'totalDrivers' => $totalDrivers,
            'totalSleepReports' => $totalSleepReports,
            'averageSleepDuration' => $averageSleepDuration,
            'fitCount' => $fitCount,
            'notFitCount' => $notFitCount,
            'sleepCategoryCounts' => $sleepCategoryCounts,
        ]);
    }
}


