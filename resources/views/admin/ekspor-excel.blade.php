@php
    $bulanFilter = $bulan ?? date('m');
    $tahunFilter = $tahun ?? date('Y');
@endphp
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; }
        .title { font-size: 13pt; font-weight: bold; text-align: center; color: #072544; background-color: #FBF6EA; height: 35px; vertical-align: middle; border: .5pt solid #000000; }
        .header { background-color: #0B3D6E; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; height: 25px; border: .5pt solid #072544; }
        
        /* Warna satu baris penuh hanya sampai kolom J */
        .row-selesai td { background-color: #D1E7DD; color: #0F5132; }
        .row-ditolak td { background-color: #F8D7DA; color: #842029; }
        .row-menunggu td { background-color: #CFF4FC; color: #055160; }
        .row-perbaikan td { background-color: #FFF3CD; color: #664D03; }

        .cell { vertical-align: middle; text-align: left; border: .5pt solid #D1D5DB; padding: 5px; }
        .cell-center { vertical-align: middle; text-align: center; border: .5pt solid #D1D5DB; padding: 5px; }
        .table-footer { background-color: #0B3D6E; height: 4px; }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="10" class="title">REKAPITULASI LAPORAN KERUSAKAN PERANGKAT - SIPERKOM CIREBON (Periode: {{ $bulanFilter }}-{{ $tahunFilter }})</th>
            </tr>
            <tr>
                <th class="header" style="width: 45px;">No</th>
                <th class="header" style="width: 140px;">ID Laporan</th>
                <th class="header" style="width: 130px;">Tanggal Masuk</th>
                <th class="header" style="width: 130px;">Tanggal Keluar</th>
                <th class="header" style="width: 120px;">Nama Pelapor</th>
                <th class="header" style="width: 120px;">Alamat</th>
                <th class="header" style="width: 140px;">Perangkat</th>
                <th class="header" style="width: 160px;">Kendala</th>
                <th class="header" style="width: 90px;">Status</th>
                <th class="header" style="width: 160px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $item)
                @php
                    $statusText = trim($item->status ?? $item->Status ?? 'Dalam Perbaikan');
                    $rowClass = 'row-perbaikan';
                    
                    if(strtolower($statusText) == 'selesai') {
                        $rowClass = 'row-selesai';
                    } elseif(strtolower($statusText) == 'ditolak') {
                        $rowClass = 'row-ditolak';
                    } elseif(strtolower($statusText) == 'menunggu part') {
                        $rowClass = 'row-menunggu';
                    }

                    $createdDate = $item->created_at ? \Carbon\Carbon::parse($item->created_at) : now();
                    $yearMonth = $createdDate->format('Y-m');
                    $idNum = (int)$item->id;
                    $paddedId = $idNum < 10 ? '00' . $idNum : ($idNum < 100 ? '0' . $idNum : (string)$idNum);
                    $idLaporan = $item->kode_laporan ?? ('#CRB-' . $yearMonth . '-' . $paddedId);
                    $keteranganText = $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? '-';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="cell-center">{{ $index + 1 }}</td>
                    <td class="cell-center">{{ $idLaporan }}</td>
                    <td class="cell-center">{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') : '-' }}</td>
                    <td class="cell-center">{{ $item->updated_at && in_array($statusText, ['Selesai', 'Ditolak']) ? \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d H:i') : '-' }}</td>
                    <td class="cell">{{ optional($item->user)->name ?? $item->nama_pelapor ?? 'User' }}</td>
                    <td class="cell">{{ (!empty($item->alamat) && $item->alamat !== '-') ? $item->alamat : (optional($item->user)->alamat && optional($item->user)->alamat !== '-' ? optional($item->user)->alamat : ((!empty($item->kecamatan) && $item->kecamatan !== '-') ? $item->kecamatan : 'cirebon')) }}</td>
                    <td class="cell">{{ ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-') }}</td>
                    <td class="cell">{{ $item->kendala ?? '-' }}</td>
                    <td class="cell-center"><b>{{ $statusText }}</b></td>
                    <td class="cell">{{ $keteranganText }}</td>
                </tr>
            @endforeach
            
            <tr>
                <td colspan="10" class="table-footer"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>