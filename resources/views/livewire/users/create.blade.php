<section class="w-full">
    <x-heading title="{{ __('Admin') }}" subtitle="{{ __('Tambah Admin Baru') }}" />
    
    <flux:heading class="sr-only">{{ __('Buat Admin') }}</flux:heading>

    <div class="mt-6">
        @include('livewire.users.user-form', ['action' => 'save'])
    </div>
</section>
