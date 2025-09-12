<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\StudentMotivationReport;

class ReportDetailModal extends Component
{
    public $selectedReport = null;

    protected $listeners = ['openReportDetail' => 'openModal'];

    public function openModal($reportId)
    {
        $this->selectedReport = StudentMotivationReport::with([
            'student', 
            'eskul', 
            'createdBy'
        ])->find($reportId);
        
        if ($this->selectedReport) {
            // Dispatch Filament modal open event
            $this->dispatch('open-modal', id: 'report-detail-modal');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.report-detail-modal');
    }
}