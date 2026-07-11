<x-layouts.app>
    <div class="container-fluid">

        {{-- Card 1: Header --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                {{-- Title --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 56px; height: 56px;">
                        <i class="ti ti-box fs-3"></i>
                    </div>

                    <div>
                        <h4 class="mb-1 fw-bold">Daftar Aset</h4>
                        <p class="text-muted mb-0">
                            Kelola data aset dan sarana prasarana sekolah.
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('aset.trash') }}" class="btn btn-outline-danger px-4">
                        <i class="ti ti-trash me-1"></i> Tempat Sampah
                    </a>
                    <a href="{{ route('aset.create') }}" class="btn btn-primary px-4">
                        <i class="ti ti-plus me-1"></i> Tambah Aset
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 2: Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('aset.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari nama atau kode barang..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search"></i>
                                Cari
                            </button>
                        </div>

                        <div class="col-md-auto">
                            <a href="{{ route('aset.index') }}" class="btn btn-light border">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card 3: Tabel Aset --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th width="50">#</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th>Sekolah</th>
                                <th>Jumlah</th>
                                <th>Harga Perolehan</th>
                                <th>Gambar</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aset as $item)
                                <tr>
                                    <td class="text-center text-muted">
                                        {{ $loop->iteration + ($aset->currentPage() - 1) * $aset->perPage() }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $item->kode_aset ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-center fw-semibold text-dark">
                                            {{ $item->nama_aset }}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->kategoriLabel() }}</td>
                                    <td class="text-center text-muted">{{ $item->lokasi ?? '-' }}</td>
                                    <td class="text-center">{{ $item->sekolah->nama_sekolah ?? '-' }}</td>
                                    <td class="text-center">{{ $item->jumlah }} {{ $item->satuan }}</td>
                                    <td class="text-center text-nowrap">{{ $item->hargaFormat() }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            @hasanyrole('admin|operator_sekolah')
                                                @if($item->foto_bukti)
                                                    <a href="{{ url('public/' . $item->foto_bukti) }}" class="btn btn-primary btn-sm">
                                                        <i class="ti ti-file"></i> Foto Barang
                                                    </a>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            @endhasanyrole
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @hasanyrole('admin|operator_sekolah')
                                                <a href="{{ route('aset.edit', $item) }}"
                                                   class="btn btn-warning btn-sm"
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
                                                            class="btn btn-danger btn-sm"
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
                                    <td colspan="10" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <i class="ti ti-inbox fs-1 mb-2"></i>
                                            <h6 class="fw-semibold mb-1">Belum ada data aset</h6>
                                            <span class="small">Silakan tambahkan data aset terlebih dahulu.</span>
                                        </div>
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
                        {{ $aset->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
