<form wire:submit="{{ $action }}" class="flex flex-col gap-4">
    <flux:input 
        label="{{ __('Nama') }}" 
        wire:model="form.name"  
        autofocus
        :error="$errors->first('form.name')"
    />

    <flux:input 
        label="{{ __('Email') }}" 
        wire:model="form.email" 
        type="email"
        :error="$errors->first('form.phone_number')"
    />

    <flux:input 
        label="{{ __('Password') }}" 
        wire:model="form.password" 
        type="password"
        :error="$errors->first('form.phone_number')"
    />

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">
            {{ __('Simpan') }}
        </flux:button>

        <flux:button 
            type="button" 
            variant="ghost" 
            wire:navigate 
            href="{{ route('driver.index') }}"
        >
            {{ __('Batal') }}
        </flux:button>
    </div>
</form>