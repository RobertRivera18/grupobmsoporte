<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class AdminTicketsChart extends Component
{

    public $chartData;

    public function mount()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $admins = User::role('Admin')->withCount('ticketsAsignados')->get();

        $this->chartData = [
            'labels' => $admins->pluck('name')->toArray(),
            'data' => $admins->pluck('tickets_asignados_count')->toArray(),
        ];
    }
    public function render()
    {
        return view('livewire.admin-tickets-chart');
    }
}
