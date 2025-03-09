<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eskul;

class GuestDetailEskul extends Controller
{
    public function show($id)
    {
        $eskul = Eskul::findOrFail($id);
        return view('components.Login.detail-eskul', compact('eskul'));
    }
}
