<x-layouts.app>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Tempat Sampah</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                        <li class="breadcrumb-item active">Tempat Sampah</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('aset.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
                @if ($aset->isNotEmpty())
                    <form action="{{ route('aset.emptyTrash') }}" method="POST"
                          onsubmit="return confirm('Hapus semua aset secara permanen? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="ti ti-trash-x me-1"></i> Kosongkan Semua
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Info banner --}}
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="ti ti-info-circle fs-5"></i>
            <span>Data di bawah ini sudah dihapus namun masih tersimpan di database.
                  Pulihkan untuk mengaktifkan kembali, atau hapus permanen jika tidak diperlukan.</span>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px">#</th>
                                <th>Nama Aset</th>
                                <th>Kode Aset</th>
                                <th>Sekolah</th>
                                <th>Kondisi</th>
                                <th>Dihapus Pada</th>
                                <th class="text-center pe-4" style="width: 160px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aset as $item)
                                <tr class="table-danger bg-opacity-25">
                                    <td class="ps-4 text-muted">
                                        {{ $loop->iteration + ($aset->currentPage() - 1) * $aset->perPage() }}
                                    </td>
                                    <td class="fw-medium text-decoration-line-through text-muted">
                                        {{ $item->nama_aset }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $item->kode_aset ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                    <td>
                                        @if ($item->kondisi === 'baik')
                                            <span class="badge bg-success-subtle text-success">Baik</span>
                                        @elseif ($item->kondisi === 'rusak_ringan')
                                            <span class="badge bg-warning-subtle text-warning">Rusak Ringan</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Rusak Berat</span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 12px">
                                        <i class="ti ti-clock me-1"></i>
                                        {{ $item->deleted_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-1 justify-content-center">

                                            {{-- Pulihkan --}}
                                            <form action="{{ route('aset.restore', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Pulihkan">
                                                    <i class="ti ti-restore me-1"></i> Pulihkan
                                                </button>
                                            </form>

                                            {{-- Hapus Permanen --}}
                                            <form action="{{ route('aset.forceDelete', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus permanen aset ini? Data tidak bisa dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger"
                                                        title="Hapus Permanen">
                                                    <i class="ti ti-trash-x"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="ti ti-circle-check fs-2 d-block mb-2 text-success"></i>
                                        Tempat sampah kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($aset->hasPages())
                <div class="card-footer d-flex align-items-center justify-content-between py-3">
                    <small class="text-muted">
                        Menampilkan {{ $aset->firstItem() }}–{{ $aset->lastItem() }}
                        dari {{ $aset->total() }} aset terhapus
                    </small>
                    {{ $aset->links() }}
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>
