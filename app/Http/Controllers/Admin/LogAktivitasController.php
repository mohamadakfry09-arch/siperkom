<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index()
    {
        // Logika untuk menampilkan halaman log aktivitas
        return view('admin.log-aktivitas.index');
    }

    public function export()
    {
        // Logika untuk mengekspor log aktivitas
    }
}