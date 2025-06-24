<?php

namespace App\Livewire\EksulApps;

use Livewire\Component;
use App\Models\EskulSchedule;

class ScheduleEskul extends Component
{
    public $schedules;

    public function mount()
    {
        $query = EskulSchedule::where('is_active', true)->with('eskul');
        
        if(auth()->user()->hasRole('admin')) {
            $schedules = $query->get();
        } elseif(auth()->user()->hasRole('siswa')) {
            $schedules = $query->where('eskul_id', auth()->user()->eskul_id)->get();
        } else {
            $schedules = $query->where('pelatih_id', auth()->user()->id)->get();
        }
        
        // Kelompokkan jadwal berdasarkan nama eskul dengan pendekatan yang lebih aman
        $grouped = collect();
        foreach ($schedules as $schedule) {
            $eskulName = $schedule->eskul->name ?? 'Tidak Tersedia';
            if (!$grouped->has($eskulName)) {
                $grouped[$eskulName] = collect();
            }
            $grouped[$eskulName]->push($schedule);
        }
        
        $this->schedules = $grouped;
    }


    public function render()
    {
        return view('livewire.eksul-apps.schedule-eskul');
    }
}
