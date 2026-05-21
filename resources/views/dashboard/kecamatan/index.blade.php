<x-layouts.app>
    <div class="container-fluid">

        {{-- Header Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                {{-- Title Section --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 56px; height: 56px;">
                        <i class="ti ti-map-pin fs-3"></i>
                    </div>

                    <div>
                        <h4 class="mb-1 fw-bold">Data Kecamatan</h4>
                        <p class="text-muted mb-0">
                            Kelola data kecamatan beserta kabupaten terkait.
                        </p>
                    </div>
                </div>

                {{-- Button --}}
                <a href="{{ route('kecamatan.create') }}" class="btn btn-primary px-4">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Kecamatan
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th width="70">No</th>
                                <th>Nama Kecamatan</th>
                                <th>Nama Kabupaten</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kecamatan as $k)
                                <tr>
                                    <td class="text-center fw-semibold">
                                        {{ $kecamatan->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <div class="text-center fw-semibold text-dark">
                                            {{ $k->nama_kecamatan }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span class="text-center badge bg-light text-dark border px-3 py-2">
                                            {{ $k->kabupaten->nama_kabupaten }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- Delete --}}
                                            <form action="{{ route('kecamatan.destroy', $k->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <i class="ti ti-database-off fs-1 mb-2"></i>
                                            <span>Data kecamatan tidak ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Pagination --}}
            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $kecamatan->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $kecamatan->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $kecamatan->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $kecamatan->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
