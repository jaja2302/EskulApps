<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\StudentMotivationReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PimpinanDashboard extends Component
{
    public $totalReports = 0;
    public $pendingReports = 0;
    public $studentsNeedMotivation = 0;
    public $recentReports;

    public function mount()
    {
        $this->loadStatistics();
        $this->loadRecentReports();
    }

    public function loadStatistics()
    {
        // Total laporan motivasi
        $this->totalReports = StudentMotivationReport::count();
        
        // Laporan yang menunggu review
        $this->pendingReports = StudentMotivationReport::where('status', 'pending')->count();
        
        // Siswa yang perlu motivasi (cluster 2)
        $this->studentsNeedMotivation = StudentMotivationReport::where('cluster', 2)->count();
    }

    public function loadRecentReports()
    {
        $this->recentReports = StudentMotivationReport::with(['student', 'eskul', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function refreshReports()
    {
        $this->loadStatistics();
        $this->loadRecentReports();
        session()->flash('message', 'Data berhasil diperbarui.');
    }

    public function viewMotivationReports()
    {
        // Redirect ke halaman laporan motivasi
        return redirect()->route('motivation.reports');
    }

    public function render()
    {
        return view('livewire.dashboard.pimpinan-dashboard');
    }
}
