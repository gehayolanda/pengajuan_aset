<x-layouts.app>
<div class="container-fluid">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-0 fw-semibold">Pengajuan Penghapusan Aset</h4>
      <small class="text-muted">Daftar seluruh pengajuan penghapusan / pemusnahan aset</small>
    </div>
    @role('operator_sekolah')
      <a href="{{ route('pengajuan-penghapusan-asset.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Buat Pengajuan
      </a>
    @endrole
  </div>

  {{-- Alert --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="ti ti-circle-check me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Filter --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <form method="GET" action="{{ route('pengajuan-penghapusan-asset.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
          <input type="text" name="search" class="form-control form-control-sm"
                 placeholder="Cari nomor / aset / alasan…" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select form-select-sm">
            <option value="">-- Semua Status --</option>
            <option value="menunggu"  {{ request('status') === 'menunggu'  ? 'selected' : '' }}>Menunggu</option>
            <option value="diproses"  {{ request('status') === 'diproses'  ? 'selected' : '' }}>Diproses</option>
            <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="ditolak"   {{ request('status') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
          </select>
        </div>
        @unless(Auth::user()->hasRole('operator_sekolah'))
        <div class="col-md-3">
          <select name="sekolah_id" class="form-select form-select-sm">
            <option value="">-- Semua Sekolah --</option>
            @foreach($sekolahs as $s)
              <option value="{{ $s->id }}" {{ request('sekolah_id') == $s->id ? 'selected' : '' }}>
                {{ $s->nama_sekolah }}
              </option>
            @endforeach
          </select>
        </div>
        @endunless
        <div class="col-auto">
          <button type="submit" class="btn btn-sm btn-secondary">
            <i class="ti ti-filter me-1"></i> Filter
          </button>
          <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
            <i class="ti ti-x"></i> Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- Table --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-3">#</th>
              <th>No. Pengajuan</th>
              <th>Aset</th>
              <th>Sekolah</th>
              <th>Metode</th>
              <th>Jumlah</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th class="text-center pe-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pengajuans as $item)
            <tr>
              <td class="ps-3 text-muted">{{ $loop->iteration + ($pengajuans->currentPage() - 1) * $pengajuans->perPage() }}</td>
              <td><code>{{ $item->nomor_pengajuan }}</code></td>
              <td>
                <div>{{ $item->aset->nama_aset ?? '-' }}</div>
                <small class="text-muted">{{ $item->aset->kode_aset ?? '' }}</small>
              </td>
              <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
              <td>{{ $item->metode_label }}</td>
              <td>{{ $item->jumlah_diajukan }} {{ $item->aset->satuan ?? '' }}</td>
              <td>{!! $item->status_label !!}</td>
              <td><small>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</small></td>
              <td class="text-center pe-3">
                <div class="d-flex gap-1 justify-content-center">
                  {{-- Detail --}}
                  <a href="{{ route('pengajuan-penghapusan-asset.show', $item) }}"
                     class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="ti ti-eye"></i>
                  </a>

                  {{-- Edit & Hapus: hanya operator pemilik, status masih menunggu --}}
                  @if($item->status === 'menunggu' && $item->diajukan_oleh === Auth::id())
                  <a href="{{ route('pengajuan-penghapusan-asset.edit', $item) }}"
                     class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                  <form action="{{ route('pengajuan-penghapusan-asset.destroy', $item) }}"
                        method="POST" onsubmit="return confirm('Hapus pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                  @endif

                  {{-- Admin: tombol Proses (hanya status menunggu) --}}
                  @role('admin')
                  @if($item->status === 'menunggu')
                  <button type="button" class="btn btn-sm btn-outline-info" title="Proses"
                          data-bs-toggle="modal" data-bs-target="#modalProses{{ $item->id }}">
                    <i class="ti ti-settings"></i>
                  </button>
                  @endif
                  @endrole

                  {{-- Kadis: tombol Setujui/Tolak (hanya status diproses) --}}
                  @role('kepala_dinas')
                  @if($item->status === 'diproses')
                  <button type="button" class="btn btn-sm btn-outline-success" title="Setujui / Tolak"
                          data-bs-toggle="modal" data-bs-target="#modalValidasi{{ $item->id }}">
                    <i class="ti ti-check"></i>
                  </button>
                  @endif
                  @endrole
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-muted">
                <i class="ti ti-inbox fs-3 d-block mb-1"></i>
                Belum ada pengajuan penghapusan aset.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if($pengajuans->hasPages())
    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end">
      {{ $pengajuans->links() }}
    </div>
    @endif
  </div>

</div>

{{-- Modal Proses (Admin: menunggu → diproses) --}}
@role('admin')
@foreach($pengajuans as $item)
@if($item->status === 'menunggu')
<div class="modal fade" id="modalProses{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pengajuan-penghapusan-asset.validasi', $item) }}" method="POST">
        @csrf @method('PATCH')
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti ti-settings me-1 text-info"></i> Proses Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">
            <code>{{ $item->nomor_pengajuan }}</code> — {{ $item->aset->nama_aset ?? '-' }}
          </p>
          <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
            <i class="ti ti-info-circle"></i>
            <span>Pengajuan akan diteruskan ke <strong>Kepala Dinas</strong> untuk disetujui atau ditolak.</span>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan (opsional)</label>
            <textarea name="catatan_validasi" class="form-control" rows="3"
                      placeholder="Catatan untuk Kepala Dinas…"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info text-white">
            <i class="ti ti-send me-1"></i> Teruskan ke Kadis
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach
@endrole

{{-- Modal Validasi (Kepala Dinas: diproses → disetujui/ditolak) --}}
@role('kepala_dinas')
@foreach($pengajuans as $item)
@if($item->status === 'diproses')
<div class="modal fade" id="modalValidasi{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pengajuan-penghapusan-asset.validasi', $item) }}" method="POST">
        @csrf @method('PATCH')
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti ti-clipboard-check me-1 text-success"></i> Keputusan Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">
            <code>{{ $item->nomor_pengajuan }}</code> — {{ $item->aset->nama_aset ?? '-' }}
          </p>
          <div class="mb-3">
            <label class="form-label fw-semibold">Keputusan <span class="text-danger">*</span></label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" value="disetujui" id="setuju{{ $item->id }}" required>
                <label class="form-check-label text-success fw-medium" for="setuju{{ $item->id }}">Disetujui</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="status" value="ditolak" id="tolak{{ $item->id }}">
                <label class="form-check-label text-danger fw-medium" for="tolak{{ $item->id }}">Ditolak</label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan</label>
            <textarea name="catatan_validasi" class="form-control" rows="3" placeholder="Opsional…"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach
@endrole

</x-layouts.app>
