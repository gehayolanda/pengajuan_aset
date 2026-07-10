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
                    <a href="{{ route('aset.trash') }}" class="btn btn-outline-danger btn-sm">
                        <i class="ti ti-trash me-1"></i> Tempat Sampah
                    </a>
                    <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
                        <i class="ti ti-plus me-1"></i> Tambah Aset
                    </a>
            </div>
        </div>

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
                                <th>Lokasi</th>
                                <th>Sekolah</th>
                                <th>Jumlah</th>
                                <th>Harga Perolehan</th>
                                <th>Gambar</th>
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
                                    <td>{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                    <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                                    <td class="text-nowrap">{{ $item->hargaFormat() }}</td>
                                    <td class="text-muted">{{ $item->lokasi ?? '-' }}</td>
                                    <td class="text-muted">
                                        <div class="d-flex justify-content-center">
                                            @hasanyrole('admin|operator_sekolah')
                                                <a href="{{ url('public/' . $item->foto_bukti) }}" class="btn btn-primary btn-sm">
                                                    <i class="ti ti-file fs-3"></i> Foto Barang
                                                </a>
                                            @endhasanyrole
                                        </div>
                                    </td>
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
                                                      class="js-confirm-delete"
                                                      data-confirm-title="Hapus aset?"
                                                      data-confirm-text="Aset akan dipindahkan ke tempat sampah."
                                                      data-confirm-button="Ya, hapus">
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
            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $aset->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $aset->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $aset->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $aset->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
