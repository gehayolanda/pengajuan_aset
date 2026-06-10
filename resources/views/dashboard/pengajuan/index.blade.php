<x-layouts.app>
<div class="container-fluid">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-0 fw-semibold">Pengajuan Penghapusan Aset</h4>
      <small class="text-muted">Daftar seluruh pengajuan penghapusan / pemusnahan aset</small>
    </div>
    @can('manage pengajuan')
      <a href="{{ route('pengajuan-penghapusan-asset.create') }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i> Buat Pengajuan
      </a>
    @endcan
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
              <td><small>{{ $item->created_at->format('d/m/Y') }}</small></td>
              <td class="text-center pe-3">
                <div class="d-flex gap-1 justify-content-center">
                  {{-- Detail --}}
                  <a href="{{ route('pengajuan-penghapusan-asset.show', $item) }}"
                     class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="ti ti-eye"></i>
                  </a>

                  {{-- Edit (hanya jika masih menunggu & miliknya) --}}
                  @if($item->status === 'menunggu' && (Auth::user()->hasRole('admin') || $item->diajukan_oleh === Auth::id()))
                  <a href="{{ route('pengajuan-penghapusan-asset.edit', $item) }}"
                     class="btn btn-sm btn-outline-warning" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                  @endif

                  {{-- Hapus (hanya jika masih menunggu & miliknya) --}}
                  @if($item->status === 'menunggu' && (Auth::user()->hasRole('admin') || $item->diajukan_oleh === Auth::id()))
                  <form action="{{ route('pengajuan-penghapusan-asset.destroy', $item) }}"
                        method="POST" onsubmit="return confirm('Hapus pengajuan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                  @endif
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
</x-layouts.app>
