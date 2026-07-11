<x-layouts.app>
    <div class="container-fluid">

        {{-- Card 1: Header --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                {{-- Title --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 56px; height: 56px;">
                        <i class="ti ti-school fs-3"></i>
                    </div>

                    <div>
                        <h4 class="mb-1 fw-bold">Data Sekolah</h4>
                        <p class="text-muted mb-0">
                            Kelola data sekolah dan operator sekolah.
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 flex-wrap">

                    {{-- Import Excel: tombol memicu file dialog tersembunyi --}}
                    <form action="{{ route('sekolah.import') }}" method="POST" enctype="multipart/form-data"
                        id="form-import" class="m-0">
                        @csrf
                        <input type="file" name="file" id="input-import" class="d-none" accept=".xlsx,.xls,.csv">
                        <button type="button" class="btn btn-success px-4"
                            onclick="document.getElementById('input-import').click()">
                            <i class="ti ti-upload me-1"></i> Import Excel
                        </button>
                    </form>

                    {{-- Tambah Sekolah --}}
                    <a href="{{ route('sekolah.create') }}" class="btn btn-primary px-4">
                        <i class="ti ti-plus me-1"></i>
                        Tambah Sekolah
                    </a>
                </div>
            </div>
        </div>

        {{-- Card 2: Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('sekolah') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari nama atau npsn..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search"></i>
                                Cari
                            </button>
                        </div>

                        <div class="col-md-auto">
                            <a href="{{ route('sekolah') }}"
                                class="btn btn-light border">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Card 3: Table --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        {{-- Table Head --}}
                        <thead class="table-white">
                            <tr class="text-center">
                                <th width="70">No</th>
                                <th>Nama Sekolah</th>
                                <th>NPSN Sekolah</th>
                                <th>Operator</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>

                        {{-- Table Body --}}
                        <tbody>
                            @forelse ($sekolah as $s)
                                <tr>

                                    {{-- No --}}
                                    <td class="text-center fw-semibold">
                                        {{ $sekolah->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Nama Sekolah --}}
                                    <td>
                                        <div class="text-center fw-semibold text-dark">
                                            {{ $s->nama_sekolah }}
                                        </div>
                                    </td>

                                    {{-- NPSN --}}
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $s->npsn_sekolah }}
                                        </span>
                                    </td>

                                    {{-- Operator --}}
                                    <td class="text-center">
                                        @if ($s->operator)
                                            <div class="d-flex align-items-center justify-content-center gap-2">

                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 38px; height: 38px;">
                                                    <i class="ti ti-user"></i>
                                                </div>

                                                <div class="text-start">
                                                    <div class="fw-semibold">
                                                        {{ $s->operator->name }}
                                                    </div>

                                                    <small class="text-muted">
                                                        Operator Sekolah
                                                    </small>
                                                </div>

                                            </div>
                                        @else
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                Belum Ada Operator
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            <a href="{{ route('sekolah.show', $s->id) }}"
                                                class="btn btn-info btn-sm"
                                                title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            <a href="{{ route('sekolah.edit', $s->id) }}"
                                                class="btn btn-warning btn-sm"
                                                title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <form action="{{ route('sekolah.destroy', $s->id) }}"
                                                method="POST"
                                                class="form-hapus">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">

                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <i class="ti ti-school-off fs-1 mb-2"></i>

                                            <h6 class="fw-semibold mb-1">
                                                Data sekolah tidak ditemukan
                                            </h6>

                                            <span class="small">
                                                Silakan tambahkan data sekolah terlebih dahulu.
                                            </span>
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
                        <strong>{{ $sekolah->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $sekolah->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $sekolah->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $sekolah->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        // Import Excel: setelah file dipilih, konfirmasi lalu submit otomatis
        document.getElementById('input-import').addEventListener('change', function () {
            if (this.files.length === 0) return;

            const namaFile = this.files[0].name;
            Swal.fire({
                title: 'Import data sekolah?',
                text: 'File: ' + namaFile,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, import!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengimport data...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                    });
                    document.getElementById('form-import').submit();
                } else {
                    this.value = ''; // reset supaya bisa pilih file yang sama lagi
                }
            });
        });

        // Konfirmasi hapus data sekolah
        document.querySelectorAll('.form-hapus').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin ingin menghapus data ini?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>
