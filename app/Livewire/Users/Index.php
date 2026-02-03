<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public function delete(User $user): void
    {
        $user->delete();
        Flux::toast(
            text: 'User berhasil disimpan',
            heading: 'Berhasil',
            variant: 'success',
        );
        $this->dispatch('user-deleted');


    }

    #[On('user-deleted')]
    #[On('user-created')]
    #[On('user-updated')]
    public function render()
    {
        return view('livewire.users.index', [
            'users' => User::latest()->paginate(10),
        ]);
    }
}
