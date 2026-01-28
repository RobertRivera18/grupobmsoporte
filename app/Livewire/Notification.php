<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notification extends Component
{

    public $count = 3;

    public function getNotificationsProperty()
    {
        return auth()->user()->notifications->take($this->count);
    }


    public function incrementCount()
    {
        $this->count += 3;
    }

    
    public function resetNotification()
    {
        auth()->user()->notification = 0;
        auth()->user()->save();
    }

    
    public function readNotification($id)
    {
        auth()->user()->notifications()->find($id)->markAsRead();
    }
    public function render()
    {

        return view('livewire.notification');
    }
}
