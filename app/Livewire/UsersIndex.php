<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'deleteUser',
    ];

    /**
     * Reiniciar la paginación cuando cambia la búsqueda.
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Eliminar usuario.
     */
    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();

            $this->dispatch('swal:success');
        }
    }

    /**
     * Cambiar estado del usuario.
     */
    public function toggleEstado($userId)
    {
        $user = User::findOrFail($userId);

        // Cambiar 1 -> 0 / 0 -> 1
        $user->estado = $user->estado == 1 ? 0 : 1;

        $user->save();

        $this->dispatch('swal:toast', [
            'icon' => 'success',
            'title' => $user->estado == 1
                ? 'Usuario activado correctamente'
                : 'Usuario desactivado correctamente',
        ]);
    }

    public function render()
    {
        $users = User::query()
            ->where('name', 'LIKE', '%' . $this->search . '%')
            ->latest('id')
            ->paginate(15);

        return view('livewire.users-index', compact('users'));
    }
}