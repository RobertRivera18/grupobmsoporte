<?php

namespace App\Livewire;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketsUsers extends Component
{
    public $chartData = [];

    public function mount()
    {
        $user = Auth::user();

        $abiertos = $user->tickets()->where('tick_estado', 1)->count();
        $cerrados = $user->tickets()->where('tick_estado', 2)->count();

        $this->chartData = [
            'labels' => ['Abiertos', 'Cerrados'],
            'data' => [$abiertos, $cerrados],
        ];
    }

   

    public function render()
    {
        return view('livewire.tickets-users');
    }
}
