<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\eskul;
use App\Models\Achievement;
use App\Models\EskulEventParticipant;
use App\Models\Announcements;
use App\Models\EskulSchedule;
use Spatie\Permission\Models\Role;

class Dashboard extends Component
{

    public $userCount;
    public $eskulCount;
    public $achievementCount;
    public $eventParticipantCount;
    public $pelatihCount;
    public $announcements;
    public $upcomingSchedules;
    
    public function mount()
    {
        $this->generateData();
     
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }
        
    private function generateData(){
        // Data yang selalu tersedia untuk semua role
        $this->userCount = User::count();
        $this->pelatihCount = Role::where('name', 'Pelatih')->first()->users()->count();
        
        // Pengumuman yang sama untuk semua role
        $this->announcements = Announcements::with('eskul', 'createdBy')
            ->where('expire_at', '>=', now())
            ->latest('publish_at')
            ->take(5)
            ->get();
        
        // Logika khusus berdasarkan role
        if(auth()->user()->hasRole('admin')){
            // Admin melihat semua data
            $this->eskulCount = Eskul::count();
            $this->achievementCount = Achievement::count();
            $this->eventParticipantCount = EskulEventParticipant::count();
            
            // Admin melihat semua jadwal
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('pelatih')){
            $userId = auth()->user()->id;
            
            // Pelatih hanya melihat data yang terkait dengannya
            $this->eskulCount = Eskul::where('pelatih_id', $userId)->count();
            
            // Changed to use eskul relationship to find achievements
            $this->achievementCount = Achievement::whereHas('eskul', function($query) use ($userId) {
                $query->where('pelatih_id', $userId);
            })->count();
            
            $this->eventParticipantCount = EskulEventParticipant::whereHas('event.eskul', function($query) use ($userId) {
                $query->where('pelatih_id', $userId);
            })->count();
            
            // Pelatih hanya melihat jadwal eskul yang diasuhnya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul', function($query) use ($userId) {
                    $query->where('pelatih_id', $userId);
                })
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('pembina')){
            $userId = auth()->user()->id;
            
            // Pembina hanya melihat data yang terkait dengannya
            $this->eskulCount = Eskul::where('pembina_id', $userId)->count();
            
            // Changed to use eskul relationship to find achievements
            $this->achievementCount = Achievement::whereHas('eskul', function($query) use ($userId) {
                $query->where('pembina_id', $userId);
            })->count();
            
            // Changed to use event.eskul relationship
            $this->eventParticipantCount = EskulEventParticipant::whereHas('event.eskul', function($query) use ($userId) {
                $query->where('pembina_id', $userId);
            })->count();
            
            // Pembina hanya melihat jadwal eskul yang dibinanya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul', function($query) use ($userId) {
                    $query->where('pembina_id', $userId);
                })
                ->take(5)
                ->get();
        }
        elseif(auth()->user()->hasRole('siswa')){
            // Siswa melihat data umum
            $this->eskulCount = Eskul::count();
            $this->achievementCount = Achievement::count();
            $this->eventParticipantCount = EskulEventParticipant::count();
            
            $userId = auth()->user()->id;
            
            // Siswa hanya melihat jadwal eskul yang diikutinya
            $this->upcomingSchedules = EskulSchedule::with('eskul')
                ->where('is_active', true)
                ->whereHas('eskul.participants', function($query) use ($userId) {
                    $query->where('student_id', $userId);
                })
                ->take(5)
                ->get();
        }
    }

}
