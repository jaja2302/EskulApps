<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Eskul;
use App\Helpers\HashHelper;

class Eskuldetail extends Component
{
    public $eskul;
    
    public function mount($hash)
    {
        $id = HashHelper::decrypt($hash);
        $this->eskul = Eskul::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.eskuldetail');
    }
}
