@php
    $statusText = [
        'menunggu'  => 'Menunggu',
        'diproses'  => 'Diproses',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
    ];
    $metodeText = [
        'pemusnahan'   => 'Pemusnahan',
        'lelang'       => 'Lelang',
        'hibah'        => 'Hibah',
        'tukar_tambah' => 'Tukar Tambah',
    ];
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { border-collapse: collapse; }
        th, td { border: 0.5pt solid #000; padding: 4px 6px; font-size: 11px; vertical-align: top; }
        th { background-color: #7f2600; color: #ffffff; text-align: center; }
        .title { font-size: 15px; font-weight: bold; }
        .subtitle { font-size: 12px; }
        .no-border { border: none; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="no-border title" colspan="12">LAPORAN PENGAJUAN PENGHAPUSAN ASET</td>
        </tr>
        <tr>
            <td class="no-border subtitle" colspan="12">{{ $label }}</td>
        </tr>
        <tr>
            <td class="no-border subtitle" colspan="12">Dicetak: {{ now()->format('d/m/Y H:i') }} &mdash; Total: {{ $pengajuans->count() }} pengajuan</td>
        </tr>
        <tr><td class="no-border" colspan="12">&nbsp;</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pengajuan</th>
                <th>Tanggal Pengajuan</th>
                <th>Aset</th>
                <th>Kode Aset</th>
                <th>Sekolah</th>
                <th>Metode</th>
                <th>Jumlah</th>
                <th>Alasan Penghapusan</th>
                <th>Diajukan Oleh</th>
                <th>Status</th>
                <th>Tanggal Validasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->nomor_pengajuan }}</td>
                <td>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->aset->nama_aset ?? '-' }}</td>
                <td>{{ $item->aset->kode_aset ?? '-' }}</td>
                <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                <td>{{ $metodeText[$item->metode_penghapusan] ?? '-' }}</td>
                <td class="text-center">{{ $item->jumlah_diajukan }} {{ $item->aset->satuan ?? '' }}</td>
                <td>{{ $item->alasan_penghapusan }}</td>
                <td>{{ $item->pengaju->name ?? '-' }}</td>
                <td>{{ $statusText[$item->status] ?? $item->status }}</td>
                <td>{{ $item->tanggal_validasi?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="12">Tidak ada data pengajuan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
