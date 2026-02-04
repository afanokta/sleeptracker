<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;


class UserForm extends Form
{
    #[Validate]
    public string $name = '';

    #[Validate]
    public string $email = '';

    #[Validate]
    public string $password = '';

        /**
     * Get the validation rules for the form.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages() {
        return [
            'name.required' => 'Nama harus diisi',
            'name.max:255' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.max:255' => 'Email tidak boleh lebih dari 255 karakter'
        ];
    }

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
