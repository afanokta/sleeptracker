<section class="w-full">
    <x-heading title="{{ __('Admin') }}" subtitle="{{ __('Edit Admin') }}" />
    
    <flux:heading class="sr-only">{{ __('Edit Admin') }}</flux:heading>

    <div class="mt-6">
        @include('livewire.users.user-form', ['action' => 'update'])
    </div>
</section>
