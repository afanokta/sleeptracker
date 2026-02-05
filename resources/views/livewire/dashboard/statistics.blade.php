<section class="w-full">
    <x-heading
        title="{{ __('Dashboard Statistik Tidur') }}"
        subtitle="{{ __('Ringkasan data laporan tidur berdasarkan periode') }}"
    />

    <div class="flex flex-col gap-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    <flux:button
                        size="xs"
                        variant="{{ $quickRange === 'today' ? 'primary' : 'outline' }}"
                        wire:click="setQuickRange('today')"
                    >
                        {{ __('Hari ini') }}
                    </flux:button>
                    <flux:button
                        size="xs"
                        variant="{{ $quickRange === 'this_week' ? 'primary' : 'outline' }}"
                        wire:click="setQuickRange('this_week')"
                    >
                        {{ __('Minggu ini') }}
                    </flux:button>
                    <flux:button
                        size="xs"
                        variant="{{ $quickRange === 'this_month' ? 'primary' : 'outline' }}"
                        wire:click="setQuickRange('this_month')"
                    >
                        {{ __('Bulan ini') }}
                    </flux:button>
                    <flux:button
                        size="xs"
                        variant="{{ $quickRange === 'this_year' ? 'primary' : 'outline' }}"
                        wire:click="setQuickRange('this_year')"
                    >
                        {{ __('Tahun ini') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    {{ __('Total Driver') }}
                </p>
                <p class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ number_format($totalDrivers) }}
                </p>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    {{ __('Total Sleepreport') }}
                </p>
                <p class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ number_format($totalSleepReports) }}
                </p>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <p class="text-xs font-medium text-zinc-500 dark:text-zinc-400">
                    {{ __('Rata-rata Waktu Tidur') }}
                </p>
                <p class="mt-2 text-3xl font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ number_format($averageSleepDuration, 1) }} <span class="text-base font-normal text-zinc-500 dark:text-zinc-400">jam</span>
                </p>
            </div>
        </div>

        @php
            $fitSeries = [max($fitCount, 0), max($notFitCount, 0)];
            $sleepSeries = [
                max($sleepCategoryCounts['Kurang'] ?? 0, 0),
                max($sleepCategoryCounts['Cukup'] ?? 0, 0),
                max($sleepCategoryCounts['Lebih dari cukup'] ?? 0, 0),
            ];
        @endphp

        <div class="grid gap-4 md:grid-cols-2">
            @if (false)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    {{ __('Persentase Fit Kerja') }}
                </p>

                <div class="h-64">
                    <canvas
                        id="fitWorkChart"
                        data-series='@json($fitSeries)'
                    ></canvas>
                </div>
            </div>
            @endif

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <p class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                    {{ __('Statistik Kategori Kualitas Tidur') }}
                </p>

                <div class="h-64">
                    <canvas
                        id="sleepCategoryChart"
                        data-series='@json($sleepSeries)'
                    ></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    window.initSleepDashboardCharts = function () {
        if (typeof Chart === 'undefined') {
            return;
        }

        const fitCanvas = document.getElementById('fitWorkChart');
        const sleepCanvas = document.getElementById('sleepCategoryChart');

        if (!fitCanvas || !sleepCanvas) {
            return;
        }

        const fitData = JSON.parse(fitCanvas.dataset.series || '[0,0]');
        const sleepData = JSON.parse(sleepCanvas.dataset.series || '[0,0,0]');

        if (window.fitWorkChartInstance) {
            window.fitWorkChartInstance.destroy();
        }

        if (window.sleepCategoryChartInstance) {
            window.sleepCategoryChartInstance.destroy();
        }

        const baseOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            family: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
                        },
                    },
                },
            },
        };

        window.fitWorkChartInstance = new Chart(fitCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Fit', 'Tidak Fit'],
                datasets: [{
                    data: fitData,
                    backgroundColor: ['#22c55e', '#ef4444'],
                }],
            },
            options: baseOptions,
        });

        window.sleepCategoryChartInstance = new Chart(sleepCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Kurang', 'Cukup', 'Lebih dari cukup'],
                datasets: [{
                    data: sleepData,
                    backgroundColor: ['#eab308', '#3b82f6', '#a855f7'],
                }],
            },
            options: baseOptions,
        });
    };

    document.addEventListener('DOMContentLoaded', window.initSleepDashboardCharts);
    document.addEventListener('livewire:navigated', window.initSleepDashboardCharts);
</script>

