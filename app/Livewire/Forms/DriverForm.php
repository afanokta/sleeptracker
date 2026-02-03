<?php

namespace App\Livewire\Forms;

use App\Models\Driver;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Flux\Flux;


class DriverForm extends Form
{
    #[Validate]
    public string $name = '';

    #[Validate]
    public string $address = '';

    #[Validate]
    public string $phone_number = '';

    /**
     * Get the validation rules for the form.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Nama harus diisi',
            'name.max:255' => 'Nama tidak boleh lebih dari 255 karakter',
            'address.required' => 'Alamat harus diisi',
            'address.max:255' => 'Alamat tidak boleh lebih dari 255 karakter',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'phone_number.max:20' => 'Nomor telepon tidak boleh lebih dari 20 karakter',
        ];
    }

    public function setDriver(Driver $driver): void
    {
        $this->name = $driver->name;
        $this->address = $driver->address;
        $this->phone_number = $driver->phone_number;
    }

    public function store(): void
    {
        $this->validate();

        Driver::create([
            'name' => $this->name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
        ]);
        
        Flux::toast(
            text: 'Driver berhasil disimpan',
            heading: 'Berhasil',
            variant: 'success',
        );
    }

    public function update(Driver $driver): void
    {
        $this->validate();

        $driver->update([
            'name' => $this->name,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
        ]);

        Flux::toast(
            text: 'Driver berhasil diubah',
            heading: 'Berhasil',
            variant: 'success',
        );
    }
}
