<section class="w-full">
    <x-heading 
        title="{{ __('Monitoring Jam Tidur AMT') }}" 
        subtitle="{{ __('Detail Riwayat Tidur AMT') }}" 
    />

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                    {{ __('Informasi Supir') }}
                </h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Nama AMT') }}</dt>
                        <dd class="text-zinc-900 dark:text-zinc-100">
                            {{ $sleepReport->driver->name ?? '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Tanggal') }}</dt>
                        <dd class="text-zinc-900 dark:text-zinc-100">
                            {{ $sleepReport->date?->format('d/m/Y') }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-zinc-500 dark:text-zinc-400">{{ __('Status') }}</dt>
                        <dd class="text-zinc-900 dark:text-zinc-100">
                            {{ ucfirst($sleepReport->status) }}
                        </dd>
                    </div>
                </dl>
            </div>

            @php
                $tracks = $sleepReport->sleeptracks->sortBy('input_time')->values();
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

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                    {{ __('Kecukupan Tidur') }}
                </h2>
                <p class="text-sm mb-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sleepBadgeClass }}">
                        {{ $sleepStatus }} ({{ number_format($sleepDurationHours, 1) }} jam)
                    </span>
                </p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Kurang: < 7 jam, Cukup: 7 - 9 jam, Lebih dari cukup: > 9 jam') }}
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">
                    {{ __('Riwayat Tidur & Bangun') }}
                </h2>

                @if ($sleepReport->sleeptracks->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                        {{ __('#') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                        {{ __('Tipe') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                        {{ __('Waktu') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                        {{ __('Lokasi') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400">
                                        {{ __('Foto') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tracks as $index => $track)
                                    @php
                                        $durationText = '-';
                                        if ($track->input_type === 'sleep') {
                                            $next = $tracks->get($index + 1);
                                            if ($next && $next->input_type === 'wake') {
                                                $minutes = $next->input_time->diffInMinutes($track->input_time);
                                                $hours = floor($minutes / 60);
                                                $mins = $minutes % 60;
                                                $durationText = sprintf('%02d j %02d m', $hours, $mins);
                                            }
                                        }
                                    @endphp
                                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-4 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-2 text-xs font-medium">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold
                                                {{ $track->input_type === 'sleep' 
                                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' 
                                                    : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' }}">
                                                {{ strtoupper($track->input_type == 'wake' ? 'Bangun' : 'Tidur') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            {{ $track->input_time?->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            {{ $track->location }}
                                        </td>
                                        <td class="px-4 py-2 text-xs text-zinc-600 dark:text-zinc-400">
                                            <flux:avatar href="{{ Storage::url($track->photo) }}" src="{{ Storage::url($track->photo) }}" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('Belum ada data sleeptrack untuk laporan ini.') }}
                    </p>
                @endif
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 space-y-4">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    {{ __('Maps Lokasi Tidur / Bangun') }}
                </h2>

                @if ($sleepReport->sleeptracks->whereNotNull('lat')->whereNotNull('long')->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($sleepReport->sleeptracks as $track)
                            @if ($track->lat && $track->long)
                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                                    <div class="px-3 py-2 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-semibold text-zinc-900 dark:text-zinc-100">
                                                {{ strtoupper($track->input_type == 'wake' ? 'Bangun' : 'Tidur') }} - {{ $sleepReport->driver->name ?? '' }}
                                            </p>
                                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                                {{ $track->input_time?->format('d/m/Y H:i') }} • {{ $track->location }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="w-full h-48">
                                        <iframe
                                            width="100%"
                                            height="100%"
                                            frameborder="0"
                                            scrolling="no"
                                            marginheight="0"
                                            marginwidth="0"
                                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $track->long - 0.01 }},{{ $track->lat - 0.01 }},{{ $track->long + 0.01 }},{{ $track->lat + 0.01 }}&layer=mapnik&marker={{ $track->lat }},{{ $track->long }}"
                                        ></iframe>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        {{ __('Belum ada data koordinat GPS untuk ditampilkan di peta.') }}
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <flux:button
                    type="button"
                    variant="ghost"
                    wire:navigate
                    href="{{ route('sleeptrack.index') }}"
                >
                    {{ __('Kembali ke daftar sleeptrack') }}
                </flux:button>
            </div>
        </div>
    </div>
</section>


