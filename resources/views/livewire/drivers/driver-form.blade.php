<form wire:submit="{{ $action }}" class="flex flex-col gap-4">
    <flux:input 
        label="{{ __('Nama') }}" 
        wire:model="form.name"  
        autofocus
        :error="$errors->first('form.name')"
    />

    <flux:input 
        label="{{ __('No. Telepon') }}" 
        wire:model="form.phone_number" 
        type="tel"
        :error="$errors->first('form.phone_number')"
    />

    <flux:textarea 
        label="{{ __('Alamat') }}" 
        wire:model="form.address" 
        rows="3"
        :error="$errors->first('form.address')"
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