<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilDesaController extends Controller
{
    public function index()
    {
        return redirect()->route('pelapor.profile.edit');
    }

    public function update(Request $request)
    {
        return redirect()->route('pelapor.profile.edit');
    }
}
