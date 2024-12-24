<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class Dashboard extends Component
{

    public $userCount;
    public function mount()
    {
        $this->userCount = User::count();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
