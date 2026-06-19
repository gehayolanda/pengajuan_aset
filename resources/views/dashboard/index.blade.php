<x-layouts.app>
    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="fw-semibold mb-1">Dashboard</h4>
            <p class="text-muted mb-0">
                Selamat datang, <strong>{{ Auth::user()->name }}</strong>.
                @role('admin') Anda masuk sebagai <span class="badge bg-primary">Admin</span> @endrole
                @role('operator_sekolah') Anda masuk sebagai <span class="badge bg-success">Operator Sekolah</span> @endrole
                @role('kepala_dinas') Anda masuk sebagai <span class="badge bg-warning text-dark">Kepala Dinas</span> @endrole
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3">
        @role('admin')
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-primary me-3">
                            <i class="ti ti-school text-primary fs-6"></i>
                        </span>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Sekolah</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalSekolah) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endrole

        @hasanyrole('admin|operator_sekolah')
        <div class="col-sm-12 col-xl-3">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-success me-3">
                            <i class="ti ti-package text-success fs-6"></i>
                        </span>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Aset Aktif</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalAset) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endhasanyrole

        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-warning me-3">
                            <i class="ti ti-file-description text-warning fs-6"></i>
                        </span>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Total Pengajuan</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalPengajuan) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @hasanyrole('admin|operator_sekolah')
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <span class="rounded p-2 d-flex align-items-center justify-content-center bg-light-danger me-3">
                            <i class="ti ti-trash text-danger fs-6"></i>
                        </span>
                        <div>
                            <p class="fs-3 mb-1 text-muted">Aset Dihapus</p>
                            <h5 class="fw-semibold mb-0">{{ number_format($totalAsetHapus) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endhasanyrole
    </div>

    {{-- Status Pengajuan + Kondisi Aset --}}
    <div class="row mt-4 g-3">
        {{-- Status Pengajuan --}}
        <div class="col-lg-5">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">
                        <i class="ti ti-report-analytics me-1 text-primary"></i> Status Pengajuan
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-warning">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark"><i class="ti ti-clock"></i></span>
                                <span class="fw-medium">Menunggu</span>
                            </div>
                            <span class="fw-bold fs-5">{{ $pengajuanMenunggu }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-success">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success"><i class="ti ti-check"></i></span>
                                <span class="fw-medium">Disetujui</span>
                            </div>
                            <span class="fw-bold fs-5">{{ $pengajuanDisetujui }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light-danger">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger"><i class="ti ti-x"></i></span>
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

        {{-- Pengajuan Menunggu Validasi --}}
        <div class="col-lg-7">
            <div class="card shadow-none border h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title fw-semibold mb-0">
                            <i class="ti ti-clock me-1 text-warning"></i> Menunggu Validasi
                        </h5>
                        <a href="{{ route('pengajuan-penghapusan-asset.index') }}?status=menunggu"
                           class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                    </div>
                    @if($pengajuanMenungguList->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Aset</th>
                                    <th>Sekolah</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanMenungguList as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('pengajuan-penghapusan-asset.show', $item) }}"
                                           class="text-decoration-none">
                                            <code>{{ $item->nomor_pengajuan }}</code>
                                        </a>
                                    </td>
                                    <td>{{ $item->aset->nama_aset ?? '-' }}</td>
                                    <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                    <td><small class="text-muted">{{ $item->created_at?->format('d M Y') ?? '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="ti ti-circle-check fs-3 d-block mb-1 text-success"></i>
                        Tidak ada pengajuan yang menunggu validasi.
                    </div>
                    @endif
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
                        <h5 class="card-title fw-semibold mb-0">
                            <i class="ti ti-clock-hour-3 me-1 text-muted"></i> Pengajuan Terbaru
                        </h5>
                        <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
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
                                        <td>{{ $item->created_at?->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="ti ti-inbox fs-3 d-block mb-1"></i>
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
</x-layouts.app>
