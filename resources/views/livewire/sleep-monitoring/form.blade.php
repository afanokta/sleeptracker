<div class="w-full max-w-md mx-auto p-4 sm:p-6">
    @if (Route::has('login'))
        <flux:button variant="primary" wire:navigate href="{{ route('login') }}" class="w-full mb-4" data-test="confirm-password-button">
            {{ __('Login Admin') }}
        </flux:button>
    @else
        <flux:button variant="primary" wire:navigate href="{{ route('dashboard') }}" class="w-full mb-4" data-test="confirm-password-button">
            {{ __('Dashboard') }}
        </flux:button>
    @endif
    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg p-6 space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('Monitoring Tidur Supir (AMT)') }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Form Input Monitoring Tidur') }}
            </p>
        </div>

        @if (!$showForm)
            <div class="space-y-4">
                <flux:input
                    label="{{ __('Pilih Nama Supir') }}"
                    wire:model.live="driverSearch"
                    placeholder="{{ __('Cari nama supir...') }}"
                    autofocus
                />

                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @forelse ($filteredDrivers as $driver)
                        <button
                            type="button"
                            wire:click="selectDriver({{ $driver->id }})"
                            class="w-full text-left px-4 py-2 rounded-md border border-gray-300 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors"
                        >
                            <div class="font-medium text-gray-900 dark:text-white">{{ $driver->name }}</div>
                        </button>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                            {{ __('Tidak ada supir ditemukan') }}
                        </p>
                    @endforelse
                </div>
            </div>
        @else
            <form wire:submit="submit" class="space-y-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Supir yang dipilih:') }}</p>
                    @if ($this->selectedDriver)
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $this->selectedDriver->name ?? '' }}</p>
                    @endif
                </div>

                @if($this->formType == 'sleep')
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ __('Form Tidur') }}  
                        </p>
                    </div>
                @else
                    <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-md p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">
                            {{ __('Form Bangun') }}  
                        </p>
                    </div>
                @endif

                <div>
                    <flux:input
                        label="{{ __('Tanggal') }}"
                        type="date"
                        wire:model="date"
                        :error="$errors->first('date')"
                    />
                </div>

                <div>
                    <flux:input
                        label="{{ __('Waktu') }}"
                        type="text"
                        id="waktu"
                        wire:model="time"
                        x-init="flatpickr($el, { 
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: 'H:i',   // format yang disimpan
                            time_24hr: true,     // INI KUNCINYA
                            allowInput: true,})"
                        :error="$errors->first('time')"
                    />
                </div>

                <div>
                    <flux:select
                        label="{{ __('Lokasi') }}"
                        wire:model.live="location"
                        :error="$errors->first('location')"
                    >
                        <option value="">{{ __('Pilih Lokasi') }}</option>
                        <option value="Rumah">{{ __('Rumah') }}</option>
                        <option value="SPBU">{{ __('SPBU') }}</option>
                        <option value="Lainnya">{{ __('Lainnya') }}</option>
                    </flux:select>
                </div>

                @if ($location === 'Lainnya')
                    <div>
                        <flux:input
                            label="{{ __('Lokasi Lainnya') }}"
                            wire:model="customLocation"
                            placeholder="{{ __('Masukkan lokasi') }}"
                            :error="$errors->first('customLocation')"
                        />
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Foto Bukti') }}
                    </label>
                    <input
                        type="file"
                        wire:model="photo"
                        accept="image/*"
                        capture="environment"
                        class="block w-full text-sm text-gray-500 dark:text-gray-400
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0
                               file:text-sm file:font-semibold
                               file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100
                               dark:file:bg-blue-900 dark:file:text-blue-300
                               dark:hover:file:bg-blue-800"
                    />
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @if ($photo)
                        <div class="mt-2">
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="max-w-full h-auto rounded-md">
                        </div>
                    @endif
                </div>

                <div>
                    <button
                        type="button"
                        wire:click="$dispatch('get-location')"
                        onclick="getCurrentLocation()"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        {{ __('Ambil Lokasi GPS') }}
                    </button>
                    @if ($latitude && $longitude)
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            {{ __('Lokasi:') }} {{ number_format($latitude, 6) }}, {{ number_format($longitude, 6) }}
                        </p>
                    @endif
                </div>

                <div class="flex gap-3 pt-4">
                    <flux:button
                        type="button"
                        variant="ghost"
                        wire:click="$set('showForm', false)"
                        class="flex-1"
                    >
                        {{ __('Kembali') }}
                    </flux:button>
                    <flux:button
                        type="submit"
                        variant="primary"
                        class="flex-1"
                    >
                        {{ __('Simpan') }}
                    </flux:button>
                </div>
            </form>
        @endif
    </div>
    @if($showForm)
    <div class="mt-4 bg-white dark:bg-zinc-900 rounded-lg shadow-lg p-6 space-y-6">
        <div class="text-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('Riwayat 7 Hari Terakhir') }}
            </h2>
        </div>
        <div class="space-y-2 max-h-60 overflow-y-auto">
            @forelse ($this->historyReport as $hr)
                <div class="w-full text-left px-4 py-2 rounded-md border border-gray-300 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <p class="text-sm flex gap-2 items-center text-gray-600 dark:text-gray-400">
                        <flux:icon.calendar class="flex-inline size-4 w-auto"/> {{ $hr->date->translatedFormat('d, F Y'); }}
                    </p>
                    <span class="flex gap-2 text-sm items-center text-gray-600 dark:text-gray-400">
                        <flux:icon.map-pin class="flex-inline size-4 w-auto"/> {{ $hr->sleeptracks[0]->location; }}
                    </span>
                    <p class="text-sm flex gap-2 items-center text-gray-600 dark:text-gray-400">
                        <flux:icon.clock class="flex-inline size-4 w-auto"/>
                        {{ $hr->sleeptracks[0]->input_time->format('H:i'); }}
                        -
                        {{ isset($hr->sleeptracks[1]) ? $hr->sleeptracks[1]->input_time->format('H:i') : ''; }}
                    </p>
                    @php
                        $sleepDurationHours = 0;
                        if(isset($hr->sleeptracks[1])) {
                            $sleepDurationHours += $hr->sleeptracks[0]->input_time->diffInMinutes($hr->sleeptracks[1]->input_time) / 60;    
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
                        } else {
                            $sleepStatus = 'Belum tuntas';
                            $sleepBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-yellow-300';
                        }
                    @endphp
                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold {{ $sleepBadgeClass }}">
                        {{ $sleepStatus }} ({{ number_format($sleepDurationHours, 1) }} jam)
                    </span>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                    {{ __('Tidak ada data yang ditampilkan') }}
                </p>
            @endforelse
        </div>
    </div>
    @endif
</div>

<script>
    console.log('test')
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    @this.setLocation(position.coords.latitude, position.coords.longitude);
                },
                function(error) {
                    console.error('Error getting location:', error);
                    alert('Gagal mendapatkan lokasi. Pastikan izin lokasi telah diberikan.');
                }
            );
        } else {
            alert('Browser tidak mendukung geolocation.');
        }
    }

    document.addEventListener('livewire:init', () => {
        initFlatpickr();
        Livewire.on('get-location', () => {
            getCurrentLocation();
            initFlatpickr();
        });
    });

    function initFlatpickr() {
        // console.log('initflatpickr')
        // flatpickr("#waktu", {
        //     enableTime: true,
        //     noCalendar: true,
        //     dateFormat: 'H:i',   // format yang disimpan
        //     time_24hr: true,     // INI KUNCINYA
        //     allowInput: true,
        // });
    }

</script>
