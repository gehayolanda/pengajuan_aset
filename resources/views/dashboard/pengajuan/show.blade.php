<x-layouts.app>
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Detail Pengajuan</h4>
            <small class="text-muted">{{ $pengajuan->nomor_pengajuan }}</small>
        </div>
        <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="ti ti-circle-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- Info Pengajuan --}}
        <div class="col-lg-8">
            <div class="card shadow-none border">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-4">Informasi Pengajuan</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">No. Pengajuan</dt>
                        <dd class="col-sm-8"><code>{{ $pengajuan->nomor_pengajuan }}</code></dd>

                        <dt class="col-sm-4 text-muted">Status</dt>
                        <dd class="col-sm-8">{!! $pengajuan->status_label !!}</dd>

                        <dt class="col-sm-4 text-muted">Nama Aset</dt>
                        <dd class="col-sm-8">{{ $pengajuan->aset->nama_aset ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted">Kode Aset</dt>
                        <dd class="col-sm-8">{{ $pengajuan->aset->kode_aset ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted">Sekolah</dt>
                        <dd class="col-sm-8">{{ $pengajuan->sekolah->nama_sekolah ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted">Metode</dt>
                        <dd class="col-sm-8">{{ $pengajuan->metode_label }}</dd>

                        <dt class="col-sm-4 text-muted">Jumlah Diajukan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->jumlah_diajukan }} {{ $pengajuan->aset->satuan ?? '' }}</dd>

                        <dt class="col-sm-4 text-muted">Alasan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->alasan_penghapusan }}</dd>

                        <dt class="col-sm-4 text-muted">Keterangan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->keterangan ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted">Diajukan Oleh</dt>
                        <dd class="col-sm-8">{{ $pengajuan->pengaju->name ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted">Tanggal Pengajuan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->created_at?->format('d M Y H:i') ?? '-' }}</dd>

                        @if($pengajuan->dokumen_pendukung)
                        <dt class="col-sm-4 text-muted">Dokumen Pendukung</dt>
                        <dd class="col-sm-8">
                            <a href="{{ asset('storage/' . $pengajuan->dokumen_pendukung) }}"
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-file me-1"></i> Lihat Dokumen
                            </a>
                        </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- Validasi & Info Validator --}}
        <div class="col-lg-4">
            {{-- Form Validasi (khusus admin, Kepala Dinas hanya melihat) --}}
            {{-- Admin: proses pengajuan (diajukan → diproses) --}}
            @role('admin')
            @if($pengajuan->status === 'diajukan')
            <div class="card shadow-none border border-info mb-3">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3 text-info">
                        <i class="ti ti-settings me-1"></i> Proses Pengajuan
                    </h5>
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                        <i class="ti ti-info-circle"></i>
                        <span>Pengajuan akan berstatus <strong>Diproses</strong>. Anda dapat langsung menyetujui atau menolaknya setelah ini.</span>
                    </div>
                    <form action="{{ route('pengajuan-penghapusan-asset.validasi', $pengajuan) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="diproses">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan (opsional)</label>
                            <textarea name="catatan_validasi" class="form-control" rows="3"
                                      placeholder="Catatan…"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info text-white w-100">
                            <i class="ti ti-settings me-1"></i> Proses Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endrole

            {{-- Admin: setujui atau tolak (diproses → disetujui/ditolak; Kepala Dinas hanya melihat) --}}
            @role('admin')
            @if($pengajuan->status === 'diproses')
            <div class="card shadow-none border border-warning mb-3">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3 text-warning">
                        <i class="ti ti-clipboard-check me-1"></i> Keputusan Pengajuan
                    </h5>
                    <form action="{{ route('pengajuan-penghapusan-asset.validasi', $pengajuan) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Keputusan <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status"
                                           value="disetujui" id="setuju" required>
                                    <label class="form-check-label text-success fw-medium" for="setuju">Disetujui</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status"
                                           value="ditolak" id="tolak">
                                    <label class="form-check-label text-danger fw-medium" for="tolak">Ditolak</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="catatan_validasi" class="form-control" rows="3"
                                      placeholder="Opsional…"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-check me-1"></i> Simpan Keputusan
                        </button>
                    </form>
                </div>
            </div>
            @endif
            @endrole

            {{-- Info Validator (jika sudah divalidasi) --}}
            @if($pengajuan->status !== 'diajukan')
            <div class="card shadow-none border {{ $pengajuan->status === 'disetujui' ? 'border-success' : 'border-danger' }}">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3 {{ $pengajuan->status === 'disetujui' ? 'text-success' : 'text-danger' }}">
                        <i class="ti ti-{{ $pengajuan->status === 'disetujui' ? 'circle-check' : 'circle-x' }} me-1"></i>
                        Hasil Validasi
                    </h5>
                    <dl class="row mb-0">
                        <dt class="col-12 text-muted mb-1">Divalidasi Oleh</dt>
                        <dd class="col-12">{{ $pengajuan->validator->name ?? '-' }}</dd>

                        <dt class="col-12 text-muted mb-1">Tanggal Validasi</dt>
                        <dd class="col-12">{{ $pengajuan->tanggal_validasi?->format('d M Y H:i') ?? '-' }}</dd>

                        @if($pengajuan->catatan_validasi)
                        <dt class="col-12 text-muted mb-1">Catatan</dt>
                        <dd class="col-12">{{ $pengajuan->catatan_validasi }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
            @endif

            {{-- Aksi edit/hapus untuk operator pemilik (status masih diajukan) --}}
            @if($pengajuan->status === 'diajukan' && $pengajuan->diajukan_oleh === Auth::id())
            <div class="card shadow-none border mt-3">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">Aksi</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pengajuan-penghapusan-asset.edit', $pengajuan) }}"
                           class="btn btn-warning btn-sm flex-fill">
                            <i class="ti ti-edit me-1"></i> Edit
                        </a>
                        <form action="{{ route('pengajuan-penghapusan-asset.destroy', $pengajuan) }}"
                              method="POST" onsubmit="return confirm('Hapus pengajuan ini?')" class="flex-fill">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="ti ti-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
</x-layouts.app>
