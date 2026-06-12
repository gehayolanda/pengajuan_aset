<x-layouts.app>
    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-semibold mb-1">Dashboard</h4>
            <p class="text-muted mb-0">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan data sistem.</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden shadow-none border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-primary">
                                <i class="ti ti-school text-primary fs-6"></i>
                            </span>
                        </div>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Sekolah</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalSekolah) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden shadow-none border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-success">
                                <i class="ti ti-package text-success fs-6"></i>
                            </span>
                        </div>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Aset Aktif</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalAset) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden shadow-none border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-warning">
                                <i class="ti ti-file-description text-warning fs-6"></i>
                            </span>
                        </div>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Pengajuan</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalPengajuan) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card overflow-hidden shadow-none border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-danger">
                                <i class="ti ti-trash text-danger fs-6"></i>
                            </span>
                        </div>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Aset Dihapus</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalAsetHapus) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Pengajuan + Chart --}}
    {{-- <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-none border">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Grafik Pengajuan Pemusnahan (12 Bulan Terakhir)</h5>
                    <div id="chartPengajuan"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-4">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Status Pengajuan</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-warning">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark fs-2">
                                    <i class="ti ti-clock"></i>
                                </span>
                                <span class="fw-medium">Menunggu</span>
                            </div>
                            <span class="fw-bold fs-5">{{ $pengajuanMenunggu }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-success">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success fs-2">
                                    <i class="ti ti-check"></i>
                                </span>
                                <span class="fw-medium">Disetujui</span>
                            </div>
                            <span class="fw-bold fs-5">{{ $pengajuanDisetujui }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-danger">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger fs-2">
                                    <i class="ti ti-x"></i>
                                </span>
                                <span class="fw-medium">Ditolak</span>
                            </div>
                            <span class="fw-bold fs-5">{{ $pengajuanDitolak }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Keseluruhan</span>
                        <strong>{{ $totalPengajuan }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-none border">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title fw-semibold mb-0">Pengajuan Terbaru</h5>
                        <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-sm btn-light-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Nama Aset</th>
                                    <th>Sekolah</th>
                                    <th>Diajukan Oleh</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuanTerbaru as $item)
                                    <tr>
                                        <td><code>{{ $item->nomor_pengajuan }}</code></td>
                                        <td>{{ $item->aset->nama_aset ?? '-' }}</td>
                                        <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                        <td>{{ $item->pengaju->name ?? '-' }}</td>
                                        <td>{!! $item->status_label !!}</td>
                                        <td>{{ $item->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada pengajuan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const chartData = @json($chartData);
        const labels    = chartData.map(d => d.bulan);

        const options = {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [
                { name: 'Menunggu',  data: chartData.map(d => d.menunggu) },
                { name: 'Disetujui', data: chartData.map(d => d.disetujui) },
                { name: 'Ditolak',   data: chartData.map(d => d.ditolak) },
            ],
            xaxis: { categories: labels },
            colors: ['#FFAB00', '#13DEB9', '#FA896B'],
            plotOptions: { bar: { borderRadius: 4, columnWidth: '50%' } },
            dataLabels: { enabled: false },
            legend: { position: 'top' },
            grid: { borderColor: '#e9ecef' },
        };

        new ApexCharts(document.querySelector('#chartPengajuan'), options).render();
    </script>
    @endpush
</x-layouts.app>
