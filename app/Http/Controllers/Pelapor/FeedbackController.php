<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create(mixed $laporan_id)
    {
        return redirect()->route('pelapor.riwayat', ['open_id' => $laporan_id])
            ->with('info', 'Terima kasih atas masukan Anda.');
    }

    public function store(Request $request)
    {
        return redirect()->route('pelapor.riwayat')
            ->with('success', 'Umpan balik berhasil dikirim.');
    }
}
