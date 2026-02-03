<section class="w-full">
    <x-heading title="{{ __('Driver') }}" subtitle="{{ __('Edit Driver') }}" />
    
    <flux:heading class="sr-only">{{ __('Edit Driver') }}</flux:heading>

    <div class="mt-6">
        @include('livewire.drivers.driver-form', ['action' => 'update'])
    </div>
</section>
