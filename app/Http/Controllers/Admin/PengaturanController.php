<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function update(Request $request)
    {
        return redirect()->route('admin.dashboard')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
