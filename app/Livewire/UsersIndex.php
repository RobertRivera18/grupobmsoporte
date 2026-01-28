<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;
    public $search;
    public $page;
    protected $listeners = ['deleteUser'];

    public function limpiar_page()
    {
        $this->reset('page');
    }


    //Funcion para eliminar un usuario

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $this->dispatch('swal:success');
        }
    }

    public function render()
    {
        $users = User::where('name', 'LIKE', '%' . $this->search . '%')
            ->latest('id')
            ->paginate(15);
        return view('livewire.users-index', compact('users'));
    }
}
