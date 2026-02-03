<section class="w-full">
    <x-heading title="{{ __('Driver') }}" subtitle="{{ __('Tambah Driver Baru') }}" />
    
    <flux:heading class="sr-only">{{ __('Create Driver') }}</flux:heading>

    <div class="mt-6">
        @include('livewire.drivers.driver-form', ['action' => 'save'])
    </div>
</section>
