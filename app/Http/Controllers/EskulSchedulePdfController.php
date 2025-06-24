<?php

namespace App\Http\Controllers;

use App\Models\EskulSchedule;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EskulSchedulePdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        // Ambil data jadwal sesuai role pengguna saat ini, mirip dengan di Livewire component
        if(auth()->user()->hasRole('admin')){
            $query = EskulSchedule::where('is_active', true)->with('eskul');
        } elseif(auth()->user()->hasRole('siswa')){
            $query = EskulSchedule::where('is_active', true)
                ->where('eskul_id', auth()->user()->eskul_id)
                ->with('eskul');
        } else{
            $query = EskulSchedule::where('is_active', true)
                ->where('pelatih_id', auth()->user()->id)
                ->with('eskul');
        }
        
        $schedules = $query->get();
        
        // Kelompokkan jadwal berdasarkan nama eskul
        $groupedSchedules = collect();
        foreach ($schedules as $schedule) {
            $eskulName = $schedule->eskul->name ?? 'Tidak Tersedia';
            if (!$groupedSchedules->has($eskulName)) {
                $groupedSchedules[$eskulName] = collect();
            }
            $groupedSchedules[$eskulName]->push($schedule);
        }

        // Buat PDF dengan view yang dioptimalkan untuk cetak
        $pdf = PDF::loadView('pdf.schedule', [
            'schedules' => $groupedSchedules
        ]);
        
        // Set beberapa opsi PDF
        $pdf->setPaper('a4', 'landscape');
        
        // Download dengan nama file yang dinamis
        return $pdf->download('jadwal-kegiatan-' . now()->format('Y-m-d') . '.pdf');
    }
} 