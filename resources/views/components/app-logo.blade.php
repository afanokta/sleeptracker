@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand class="!text-white [&_*]:text-white" name="Monitoring AMT" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-16 items-center justify-center rounded-md h-full">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
