<x-layouts.app>
    <div class="container-fluid">

        {{-- Header Card --}}
       <div class="card shadow-sm mb-4">
    <div class="card-header d-flex align-items-center justify-content-between"
         style="background-color: #7f2600;">
        <h5 class="mb-0 text-white fw-semibold">Detail Aset</h5>
        <a href="{{ route('aset.index') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
            <i class="ti ti-arrow-left fs-5"></i>
            <span>Kembali</span>
        </a>
        </div>
    </div>

        <div class="row g-4">

            {{-- Kolom Kiri: Ringkasan --}}
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">

                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 border border-primary border-opacity-25 mb-3"
                            style="width: 90px; height: 90px;">
                            <i class="ti ti-box text-primary" style="font-size: 2.2rem;"></i>
                        </div>

                        <h5 class="fw-bold mb-1">{{ $aset->nama_aset }}</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-1">
                            {{ $aset->kategoriLabel() }}
                        </span>
                        <p class="text-muted small mb-0">
                            {{ $aset->kode_aset ?? '-' }}
                        </p>

                        <hr class="w-100 my-3">

                        <div class="row w-100 text-center g-0">
                            <div class="col-6 border-end py-2">
                                <div class="text-muted small mb-1">Jumlah</div>
                                <div class="fw-semibold small">{{ $aset->jumlah }} {{ $aset->satuan }}</div>
                            </div>
                            <div class="col-6 py-2">
                                <div class="text-muted small mb-1">Harga</div>
                                <div class="fw-semibold small">{{ $aset->hargaFormat() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Informasi Lengkap --}}
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <span class="bg-primary bg-opacity-10 text-primary rounded p-1 d-flex">
                            <i class="ti ti-info-circle fs-5"></i>
                        </span>
                        <h5 class="mb-0 fw-semibold">Informasi Lengkap</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-light">

                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Kode Barang</td>
                                        <td class="py-3 fw-medium">{{ $aset->kode_aset ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Nama Barang</td>
                                        <td class="py-3 fw-medium">{{ $aset->nama_aset }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Kategori</td>
                                        <td class="py-3">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                {{ $aset->kategoriLabel() }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Lokasi</td>
                                        <td class="py-3 fw-medium">{{ $aset->lokasi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Sekolah</td>
                                        <td class="py-3 fw-medium">{{ $aset->sekolah->nama_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Jumlah</td>
                                        <td class="py-3 fw-medium">{{ $aset->jumlah }} {{ $aset->satuan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Harga Perolehan</td>
                                        <td class="py-3 fw-medium">{{ $aset->hargaFormat() }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">Gambar</td>
                                        <td class="py-3">
                                            @if($aset->foto_bukti)
                                                <a href="{{ url('public/' . $aset->foto_bukti) }}" class="btn btn-primary btn-sm" target="_blank">
                                                    <i class="ti ti-file"></i> Lihat Foto Barang
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <a href="{{ route('aset.edit', $aset) }}" class="btn btn-warning d-flex align-items-center gap-2">
                        <i class="ti ti-pencil fs-5"></i> Edit Data
                    </a>
                    <form action="{{ route('aset.destroy', $aset) }}" method="POST"
                        class="js-confirm-delete"
                        data-confirm-title="Yakin ingin menghapus data ini?"
                        data-confirm-text="Aset akan dipindahkan ke tempat sampah.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
                            <i class="ti ti-trash fs-5"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('aset.index') }}" class="btn btn-light ms-auto d-flex align-items-center gap-2">
                        <i class="ti ti-list fs-5"></i> Semua Aset
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
