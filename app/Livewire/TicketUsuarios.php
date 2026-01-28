<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TicketUsuarios extends Component
{
    use WithPagination;
    public $search = '';
    public $page;
    public $open = false;
    public $usuarioSoporte = '';

    public function limpiar_page()
    {
        $this->reset('page');
    }

    //Metodo para abrir modal de Usuarios
    public function asignarSoporte($soporte_id)
    {
        $this->usuarioSoporte = $soporte_id;
        $this->open = true;
    }

    // Método para asignar un colaborador (soporte) a un ticket
    public function asignarColaborador($userId)
    {
        // Verifica que haya un ticket seleccionado
        if (!$this->usuarioSoporte) {
            $this->dispatch('error', message: 'Debe seleccionar un ticket primero.');
            return;
        }

        // Busca el ticket y el usuario
        $ticket = Ticket::find($this->usuarioSoporte);
        $user = User::find($userId);

        // Verifica que ambos existan
        if (!$ticket || !$user) {
            $this->dispatch('error', message: 'Ticket o usuario no encontrado.');
            return;
        }

        // Asigna el soporte al ticket
        $ticket->soporte_asignado = $user->id;
        $ticket->fecha_asignacion = now();
        $ticket->save();

        // Cierra el modal y resetea la variable
        $this->open = false;
        $this->reset('usuarioSoporte');

        // Muestra notificación de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Asignado correctamente',
            'text' => 'El colaborador fue asignado al ticket.',
        ]);
    }

    public function cambiarEstadoTicket($ticketId)
{
    $ticket = Ticket::find($ticketId);
    // Cambiar estado: 1 = Abierto, 2 = Cerrado
    $ticket->tick_estado = $ticket->tick_estado == 1 ? 2 : 1;
    $ticket->save();
    if ($ticket->tick_estado == 2) {
        \App\Models\TicketDetalle::create([
            'tick_id' => $ticket->tick_id,
            'usu_id' => auth()->id(),
            'tickd_descrip' => 'Ticket cerrado.',
            'est' => 1,
        ]);
    }
   
}


    public function getColaboradoresDisponiblesProperty()
    {
        return User::role('admin')->get();
    }

    public function render()
    {

        if (auth()->user()->hasRole('Admin')) {
            $tickets = Ticket::where('tick_titulo', 'LIKE', '%' . $this->search . '%')
                ->latest('tick_id')
                ->paginate(10);
        } else {
            $tickets = Ticket::where('usu_id', auth()->id())
                ->where('tick_titulo', 'LIKE', '%' . $this->search . '%')
                ->latest('tick_id')
                ->paginate(10);
        }


        return view('livewire.ticket-usuarios', compact('tickets'));
    }
}
