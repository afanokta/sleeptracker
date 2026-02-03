<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;


class UserForm extends Form
{
    #[Validate('required|string|max:255', message: 'Nama harus diisi')]
    public string $name = '';

    #[Validate('required|string|max:255', message: 'Email harus diisi')]
    public string $email = '';

    #[Validate('string|max:255', message: 'Password harus diisi')]
    public string $password = '';

    public function setUser(User $user): void
    {
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = $user->password;
    }

    public function store(): void
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);


        
        Flux::toast(
            text: 'User berhasil disimpan',
            heading: 'Berhasil',
            variant: 'success',
        );
    }

    public function update(User $user): void
    {
        $this->validate();

        if ($this->password == ''){   
            $user->update([
                'name' => $this->name,
                'email' => $this->email
            ]);
        } else {
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        Flux::toast(
            text: 'User berhasil diubah',
            heading: 'Berhasil',
            variant: 'success',
        );
    }
}
