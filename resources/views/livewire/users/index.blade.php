<section class="w-full">
    <x-heading title="{{ __('Admin') }}" subtitle="{{ __('Daftar Admin') }}" />
    
    <flux:heading class="sr-only">{{ __('Admin List') }}</flux:heading>

    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <flux:button variant="primary" wire:navigate href="{{ route('user.new') }}">
                {{ __('Tambah Admin') }}
            </flux:button>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('ID') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Nama') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Email') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Dibuat') }}
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ __('Aksi') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $user->id }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <flux:button 
                                            variant="ghost" 
                                            size="sm"
                                            wire:navigate 
                                            href="{{ route('user.edit', $user) }}"
                                        >
                                            {{ __('Edit') }}
                                        </flux:button>
                                        <flux:button 
                                            variant="danger" 
                                            size="sm"
                                            wire:click="delete({{ $user->id }})"
                                            wire:confirm="{{ __('Apakah Anda yakin ingin menghapus user ini?') }}"
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
                {{ $users->links() }}
            </div>
        @else
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 p-8">
                <div class="text-center">
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Belum ada user.') }}
                    </flux:text>
                </div>
            </div>
        @endif
    </div>
</section>
