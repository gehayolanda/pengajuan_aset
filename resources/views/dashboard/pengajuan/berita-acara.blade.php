<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Berita Acara - {{ $pengajuan->nomor_pengajuan }}</title>
<style>
    @page { margin: 15mm 15mm 15mm 15mm; }
    body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; line-height: 1.4; }

    /* ── Kop Surat ── */
    .kop { text-align: center; margin-bottom: 10px; border-bottom: 4px double #000; padding-bottom: 10px; }
    .kop img { height: 70px; margin-bottom: 4px; }
    .kop .instansi { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
    .kop .alamat { font-size: 10pt; margin-top: 2px; }

    /* ── Judul ── */
    .judul { text-align: center; margin: 16px 0 6px; }
    .judul h2 { font-size: 13pt; text-decoration: underline; text-transform: uppercase; }
    .judul .nomor { font-size: 11pt; margin-top: 4px; }

    /* ── Isi ── */
    .isi { margin-top: 12px; text-align: justify; }
    .isi p { margin-bottom: 8px; text-indent: 30px; }
    .isi p.no-indent { text-indent: 0; }

    /* ── Tabel Aset ── */
    table.data {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        margin: 10px 0;
        font-size: 9pt;
    }
    table.data th, table.data td {
        border: 1px solid #000;
        padding: 4px 4px;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
    }
    table.data th { background-color: #d9d9d9; font-weight: bold; }
    table.data td.left { text-align: left; }
    table.data td.right { text-align: right; }

    /* ── Paragraf Setelah Tabel ── */
    .isi .pernyataan { margin-top: 12px; }
    .isi .pernyataan ol { margin-left: 20px; }
    .isi .pernyataan ol li { margin-bottom: 4px; }

    /* ── Tanda Tangan ── */
    .ttd-section { margin-top: 20px; }
    .ttd-table { width: 100%; border: none; table-layout: fixed; }
    .ttd-table td { vertical-align: top; padding: 6px 8px; }
    .ttd-label { font-size: 9pt; text-align: center; font-weight: bold; margin-bottom: 4px; }
    .ttd-qr { text-align: center; margin-bottom: 4px; }
    .ttd-qr img { width: 90px; height: 90px; }
    .ttd-name { text-align: center; font-size: 10pt; font-weight: bold; text-decoration: underline; }
    .ttd-nip { text-align: center; font-size: 8pt; color: #444; }
    .ttd-blank { text-align: center; font-size: 8pt; color: #999; font-style: italic; margin-top: 12px; }

    /* ── Footer ── */
    .footer-note { margin-top: 20px; font-size: 8pt; color: #666; border-top: 1px solid #ccc; padding-top: 4px; }
</style>
</head>
<body>
<div class="page">

    {{-- ═══════════ KOP SURAT ═══════════ --}}
    <div class="kop">
        @if(file_exists(public_path('images/logo-ketapang.png')))
            <img src="{{ public_path('images/logo-ketapang.png') }}" alt="Logo">
        @endif
        <div class="instansi">Dinas Pendidikan</div>
        <div class="instansi" style="font-size:13pt;">Kabupaten Ketapang</div>
        <div class="alamat">Jl. Pahlawan No. 1, Ketapang &mdash; Kalimantan Barat</div>
    </div>

    {{-- ═══════════ JUDUL ═══════════ --}}
    <div class="judul">
        <h2>Berita Acara</h2>
        <div class="nomor">Nomor: {{ $pengajuan->nomor_pengajuan }}</div>
    </div>

    {{-- ═══════════ ISI BERITA ACARA ═══════════ --}}
    <div class="isi">
        <p>
            Pada hari ini, tanggal {{ $pengajuan->created_at->translatedFormat('d') }}
            bulan {{ $pengajuan->created_at->translatedFormat('F') }}
            tahun {{ $pengajuan->created_at->translatedFormat('Y') }},
            telah dilaksanakan pemeriksaan dan pengajuan penghapusan/pemusnahan aset milik
            <strong>{{ $pengajuan->sekolah->nama_sekolah ?? '-' }}</strong>,
            yang terletak di Kecamatan {{ $pengajuan->sekolah->kecamatan->nama_kecamatan ?? '-' }},
            Kabupaten Ketapang.
        </p>

        <p>
            Pengajuan ini diajukan oleh
            <strong>{{ $pengajuan->pengaju->name ?? '-' }}</strong>
            dengan nomor pengajuan <strong>{{ $pengajuan->nomor_pengajuan }}</strong>,
            atas dasar alasan: <em>{{ $pengajuan->alasan_penghapusan }}</em>,
            dengan metode penghapusan:
            <strong>{{ match($pengajuan->metode_penghapusan) {
                'pemusnahan' => 'Pemusnahan',
                'lelang'     => 'Lelang',
                'hibah'      => 'Hibah',
                'tukar_tambah' => 'Tukar Tambah',
                default      => $pengajuan->metode_penghapusan,
            } }}</strong>.
        </p>

        <p class="no-indent">Adapun rincian aset yang diajukan untuk penghapusan/pemusnahan adalah sebagai berikut:</p>

        {{-- ═══════════ TABEL ASET ═══════════ --}}
        <table class="data">
            <thead>
                <tr>
                    <th style="width:5%;">No.</th>
                    <th style="width:22%;">Nama Aset</th>
                    <th style="width:15%;">Kode Aset</th>
                    <th style="width:12%;">Kondisi</th>
                    <th style="width:8%;">Jumlah</th>
                    <th style="width:8%;">Satuan</th>
                    <th style="width:15%;">Harga Perolehan</th>
                    <th style="width:15%;">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($pengajuan->assets ?? [$pengajuan->aset] as $aset)
                    @php $total += $aset->harga_perolehan * $pengajuan->jumlah_diajukan; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="left">{{ $aset->nama_aset }}</td>
                        <td>{{ $aset->kode_aset }}</td>
                        <td>{{ match($aset->kondisi) {
                            'baik'        => 'Baik',
                            'rusak_ringan' => 'Rusak Ringan',
                            'rusak_berat' => 'Rusak Berat',
                            default       => $aset->kondisi,
                        } }}</td>
                        <td>{{ $pengajuan->jumlah_diajukan }}</td>
                        <td>{{ $aset->satuan }}</td>
                        <td class="right">Rp {{ number_format($aset->harga_perolehan * $pengajuan->jumlah_diajukan, 0, ',', '.') }}</td>
                        <td>{{ $aset->tahun_pengadaan }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" style="text-align:right; font-weight:bold;">Total Nilai Perolehan</td>
                    <td class="right" style="font-weight:bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        @if($pengajuan->keterangan)
        <p>
            Keterangan: {{ $pengajuan->keterangan }}
        </p>
        @endif

        <div class="pernyataan">
            <p class="no-indent" style="margin-bottom:8px;">Dengan ini menyatakan bahwa:</p>
            <ol>
                <li>Aset-aset tersebut di atas sudah tidak dapat digunakan lagi / sudah memenuhi syarat untuk dihapuskan sesuai ketentuan yang berlaku.</li>
                <li>Penghapusan/pemusnahan dilakukan sesuai dengan peraturan perundang-undangan yang berlaku.</li>
                <li>Berita acara ini dibuat dengan sebenar-benarnya dan dapat dipertanggungjawabkan secara hukum.</li>
            </ol>
        </div>
    </div>

    {{-- ═══════════ TANDA TANGAN + QR CODE ═══════════ --}}
    <div class="ttd-section">
        <table class="ttd-table">
            <tr>
                <td style="width:50%;"></td>
                {{-- ── Kolom Kanan: Pejabat Pembuat Komitmen (Kepala Dinas) ── --}}
                <td style="width:50%;">
                    <div class="ttd-label">Pejabat Pembuat Komitmen</div>
                    @if($pengajuan->status === 'disetujui')
                        <div class="ttd-qr">{!! $qrKepalaDinas !!}</div>
                        <div class="ttd-name">KEPALA DINAS</div>
                        <div class="ttd-nip">Kabupaten Ketapang</div>
                    @else
                        <div class="ttd-blank">Menunggu persetujuan</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <div class="footer-note">
        Berita Acara ini dibuat secara otomatis oleh Sistem Informasi Pengelolaan dan Pengajuan Penghapusan Aset (SI-PPASET)
        pada {{ now()->format('d/m/Y H:i') }} WIB. Tanda tangan digital diverifikasi melalui QR Code.
    </div>

</div>
</body>
</html>
