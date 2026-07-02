<x-layouts.app>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Daftar Aset</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Aset</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                @hasanyrole('admin|operator_sekolah')
                    <a href="{{ route('aset.trash') }}" class="btn btn-outline-danger btn-sm">
                        <i class="ti ti-trash me-1"></i> Tempat Sampah
                    </a>
                    <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Tambah Aset
                    </a>
                @endhasanyrole
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tabel Aset --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px">#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Tipe</th>
                                <th>Sekolah</th>
                                <th>Jumlah</th>
                                <th>Harga Perolehan</th>
                                <th>Lokasi</th>
                                <th class="text-center pe-4" style="width: 130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aset as $item)
                                <tr>
                                    <td class="ps-4 text-muted">
                                        {{ $loop->iteration + ($aset->currentPage() - 1) * $aset->perPage() }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border">
                                            {{ $item->kode_aset ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="fw-medium">{{ $item->nama_aset }}</td>
                                    <td>{{ $item->kategoriLabel() }}</td>
                                    <td class="text-muted">{{ $item->tipe_barang ?? '-' }}</td>
                                    <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                    <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                                    <td class="text-nowrap">{{ $item->hargaFormat() }}</td>
                                    <td class="text-muted">{{ $item->lokasi ?? '-' }}</td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex gap-1 justify-content-center">
                                            @hasanyrole('admin|operator_sekolah')
                                                <a href="{{ route('aset.edit', $item) }}"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('aset.destroy', $item) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Hapus aset ini ke tempat sampah?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            @endhasanyrole
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="ti ti-inbox fs-2 d-block mb-2"></i>
                                        Belum ada data aset.
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
                        dari {{ $aset->total() }} aset
                    </small>
                    {{ $aset->links() }}
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>
