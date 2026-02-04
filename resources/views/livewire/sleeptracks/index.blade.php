<section class="w-full">
    <x-heading title="{{ __('Sleeptrack') }}" subtitle="{{ __('Daftar Laporan Tidur Supir (AMT)') }}" />

    <flux:heading class="sr-only">{{ __('Sleeptrack List') }}</flux:heading>

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

                <div class="flex flex-wrap items-center gap-2">
                    <flux:button
                        variant="outline"
                        size="sm"
                        tag="a"
                        href="{{ route('sleeptrack.export.pdf', request()->query()) }}"
                    >
                        {{ __('Export PDF') }}
                    </flux:button>
                    <flux:button
                        variant="outline"
                        size="sm"
                        tag="a"
                        href="{{ route('sleeptrack.export.excel', request()->query()) }}"
                    >
                        {{ __('Export Excel') }}
                    </flux:button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">
                        {{ __('Nama AMT') }}
                    </label>
                    <input
                        type="text"
                        wire:model.debounce.500ms="driverName"
                        class="w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="{{ __('Cari nama supir...') }}"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">
                        {{ __('Kategori Tidur') }}
                    </label>
                    <select
                        wire:model="sleepCategory"
                        class="w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">{{ __('Semua') }}</option>
                        <option value="Kurang">{{ __('Kurang') }}</option>
                        <option value="Cukup">{{ __('Cukup') }}</option>
                        <option value="Lebih dari cukup">{{ __('Lebih dari cukup') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">
                        {{ __('Periode Dari') }}
                    </label>
                    <input
                        type="date"
                        wire:model="dateFrom"
                        class="w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <div>
                    <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1">
                        {{ __('Periode Sampai') }}
                    </label>
                    <input
                        type="date"
                        wire:model="dateTo"
                        class="w-full rounded-md border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-950 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

        </div>

        @if ($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('ID') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Nama AMT') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Status') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Tanggal') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Kecukupan Tidur') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Aksi') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            @php
                                $tracks = $report->sleeptracks->sortBy('input_time')->values();
                                $sleepDurationHours = 0;

                                for ($i = 0; $i < $tracks->count(); $i++) {
                                    $track = $tracks[$i];
                                    if ($track->input_type === 'sleep') {
                                        $next = $tracks->get($i + 1);
                                        if ($next && $next->input_type === 'wake') {
                                            $sleepDurationHours += $track->input_time->diffInMinutes($next->input_time) / 60;
                                        }
                                    }
                                }

                                if ($sleepDurationHours < 7) {
                                    $sleepStatus = 'Kurang';
                                    $sleepBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                                } elseif ($sleepDurationHours <= 9) {
                                    $sleepStatus = 'Cukup';
                                    $sleepBadgeClass = 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
                                } else {
                                    $sleepStatus = 'Lebih dari cukup';
                                    $sleepBadgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300';
                                }
                            @endphp
                            <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $report->id }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $report->driver->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ ucfirst($report->status) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $report->date?->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sleepBadgeClass }}">
                                        {{ $sleepStatus }} ({{ number_format($sleepDurationHours, 1) }} jam)
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <flux:button
                                            variant="ghost"
                                            size="sm"
                                            wire:navigate
                                            href="{{ route('sleeptrack.edit', $report) }}"
                                        >
                                            {{ __('Detail') }}
                                        </flux:button>
                                        <flux:button
                                            variant="danger"
                                            size="sm"
                                            wire:click="delete({{ $report->id }})"
                                            wire:confirm="{{ __('Apakah Anda yakin ingin menghapus sleepreport dan seluruh sleeptrack terkait?') }}"
                                        >
                                            {{ __('Hapus') }}
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        @else
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 p-8">
                <div class="text-center">
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Belum ada data sleeptrack.') }}
                    </flux:text>
                </div>
            </div>
        @endif
    </div>
</section>


