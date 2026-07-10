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
            <option value="diajukan"  {{ request('status') === 'diajukan'  ? 'selected' : '' }}>Diajukan</option>
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

  {{-- Export Excel (Admin & Kepala Dinas) --}}
  @hasanyrole('admin|kepala_dinas')
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="d-flex align-items-center mb-2">
        <i class="ti ti-file-spreadsheet me-2 text-success"></i>
        <strong>Export Excel</strong>
        <small class="text-muted ms-2">Unduh data pengajuan berdasarkan periode</small>
      </div>
      <form method="GET" action="{{ route('pengajuan-penghapusan-asset.export') }}"
            class="row g-2 align-items-end" id="formExport">

        {{-- Ikut membawa filter status & sekolah yang sedang aktif --}}
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="sekolah_id" value="{{ request('sekolah_id') }}">

        <div class="col-md-3">
          <label class="form-label small mb-1">Periode</label>
          <select name="mode" id="exportMode" class="form-select form-select-sm">
            <option value="bulan">Per Bulan</option>
            <option value="minggu">Per Minggu</option>
            <option value="tahun">Per Tahun</option>
            <option value="rentang">Rentang Tanggal</option>
          </select>
        </div>

        {{-- Per Bulan --}}
        <div class="col-md-3 export-field" data-mode="bulan">
          <label class="form-label small mb-1">Bulan</label>
          <input type="month" name="bulan" class="form-control form-control-sm"
                 value="{{ now()->format('Y-m') }}">
        </div>

        {{-- Per Minggu --}}
        <div class="col-md-3 export-field d-none" data-mode="minggu">
          <label class="form-label small mb-1">Minggu</label>
          <input type="week" name="minggu" class="form-control form-control-sm"
                 value="{{ now()->format('o-\WW') }}">
        </div>

        {{-- Per Tahun --}}
        <div class="col-md-3 export-field d-none" data-mode="tahun">
          <label class="form-label small mb-1">Tahun</label>
          <select name="tahun" class="form-select form-select-sm">
            @for($y = now()->year; $y >= now()->year - 10; $y--)
              <option value="{{ $y }}">{{ $y }}</option>
            @endfor
          </select>
        </div>

        {{-- Rentang Tanggal (antar bulan / antar tahun) --}}
        <div class="col-md-3 export-field d-none" data-mode="rentang">
          <label class="form-label small mb-1">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control form-control-sm">
        </div>
        <div class="col-md-3 export-field d-none" data-mode="rentang">
          <label class="form-label small mb-1">Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="form-control form-control-sm">
        </div>

        <div class="col-auto">
          <button type="submit" class="btn btn-sm btn-success">
            <i class="ti ti-download me-1"></i> Export Excel
          </button>
        </div>
      </form>
    </div>
  </div>
  @endhasanyrole

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
              {{-- PERBAIKAN 4: Penomoran pagination yang lebih aman dan rapi --}}
              <td class="ps-3 text-muted">{{ $pengajuans->firstItem() + $loop->index }}</td>
              <td><code>{{ $item->nomor_pengajuan }}</code></td>
              <td>
                {{-- PERBAIKAN 3: Nullsafe operator (?->) untuk mencegah error relasi kosong --}}
                <div>{{ $item->aset?->nama_aset ?? '-' }}</div>
                <small class="text-muted">{{ $item->aset?->kode_aset ?? '' }}</small>
              </td>
              <td>{{ $item->sekolah?->nama_sekolah ?? '-' }}</td>
              <td>{{ $item->metode_label }}</td>
              <td>{{ $item->jumlah_diajukan }} {{ $item->aset?->satuan ?? '' }}</td>
              <td>{!! $item->status_label !!}</td>
              <td><small>{{ $item->created_at?->format('d/m/Y') ?? '-' }}</small></td>
              <td class="text-center pe-3">
                <div class="d-flex gap-1 justify-content-center">
                  {{-- Detail --}}
                  <a href="{{ route('pengajuan-penghapusan-asset.show', $item) }}"
                     class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="ti ti-eye"></i>
                  </a>

                  {{-- Edit & Hapus: hanya operator pemilik, status masih diajukan --}}
                  @if($item->status === 'diajukan' && $item->diajukan_oleh === Auth::id())
                  <a href="{{ route('pengajuan-penghapusan-asset.edit', $item) }}"
                     class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                  <form action="{{ route('pengajuan-penghapusan-asset.destroy', $item) }}"
                        method="POST" class="js-confirm-delete"
                        data-confirm-title="Hapus pengajuan?"
                        data-confirm-text="Data yang dihapus tidak dapat dikembalikan.">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                  @endif

                  {{-- Admin: tombol Proses & Setujui/Tolak (Kepala Dinas hanya melihat) --}}
                  @role('admin')
                  @if($item->status === 'diajukan')
                  <button type="button" class="btn btn-sm btn-outline-info" title="Proses"
                          data-bs-toggle="modal" data-bs-target="#modalProses{{ $item->id }}">
                    <i class="ti ti-settings"></i>
                  </button>
                  @elseif ($item->status === 'diproses')
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

    <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $pengajuans->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $pengajuans->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $pengajuans->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $pengajuans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
  </div>

</div>

{{-- Modal Proses (Admin: diajukan → diproses) --}}
@role('admin')
@foreach($pengajuans as $item)
@if($item->status === 'diajukan')
<div class="modal fade" id="modalProses{{ $item->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pengajuan-penghapusan-asset.validasi', $item) }}" method="POST">
        @csrf @method('PATCH')
        {{-- PERBAIKAN 2: Menambahkan input hidden status --}}
        <input type="hidden" name="status" value="diproses">
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti ti-settings me-1 text-info"></i> Proses Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">
            <code>{{ $item->nomor_pengajuan }}</code> — {{ $item->aset?->nama_aset ?? '-' }}
          </p>
          <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
            <i class="ti ti-info-circle"></i>
            <span>Pengajuan akan berstatus <strong>Diproses</strong>. Anda dapat langsung menyetujui atau menolaknya setelah ini.</span>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan (opsional)</label>
            <textarea name="catatan_validasi" class="form-control" rows="3"
                      placeholder="Catatan…"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-info text-white">
            <i class="ti ti-settings me-1"></i> Proses Pengajuan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach
@endrole

{{-- Modal Validasi (Admin: diproses → disetujui/ditolak) --}}
@role('admin')
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
            <code>{{ $item->nomor_pengajuan }}</code> — {{ $item->aset?->nama_aset ?? '-' }}
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

@push('scripts')
<script>
  (function () {
    const modeSelect = document.getElementById('exportMode');
    if (!modeSelect) return;

    const fields = document.querySelectorAll('.export-field');

    function toggleFields() {
      const mode = modeSelect.value;
      fields.forEach(function (el) {
        const active = el.dataset.mode === mode;
        el.classList.toggle('d-none', !active);
        // Nonaktifkan input yang tersembunyi agar tidak ikut terkirim
        el.querySelectorAll('input, select').forEach(function (input) {
          input.disabled = !active;
        });
      });
    }

    modeSelect.addEventListener('change', toggleFields);
    toggleFields();
  })();
</script>
@endpush

</x-layouts.app>
