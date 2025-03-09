<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;
use App\Models\EskulEvent;
use App\Models\EskulMember;
use App\Models\Achievement as EskulAchievement;
use App\Models\User;

class GuestController extends Controller
{
    //

    public function index()
    {
        $eskuls = $this->getEskul();
        $stats = $this->getEskulStats();
        // dd($eskuls);
        return view('components.Login.index', compact('eskuls', 'stats'));
    }



    private function getEskul()
    {

        return Eskul::all();
    }

    private function getEskulStats(){

        $kegiatan = EskulEvent::count();
        $siswaAktif = EskulMember::where('is_active', true)->count();
        $mentorAhli = User::whereHas('roles', function($q){
            $q->where('name', 'pelatih');
        })->count();
        $penghargaan = EskulAchievement::count();

        return [
            'kegiatan' => $kegiatan,
            'siswaAktif' => $siswaAktif,
            'mentorAhli' => $mentorAhli,
            'penghargaan' => $penghargaan
        ];
    }
}
