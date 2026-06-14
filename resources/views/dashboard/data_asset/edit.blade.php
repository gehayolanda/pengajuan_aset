<x-layouts.app>

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Edit Aset</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('aset.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('aset.update', $aset) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">

                                {{-- Sekolah --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium">Sekolah <span class="text-danger">*</span></label>
                                    @if($sekolahOperator)
                                        <input type="text" class="form-control bg-light"
                                               value="{{ $sekolahOperator->nama_sekolah }}" readonly>
                                        <input type="hidden" name="sekolah_id" value="{{ $sekolahOperator->id }}">
                                    @else
                                        <select name="sekolah_id" class="form-select @error('sekolah_id') is-invalid @enderror">
                                            <option value="">-- Pilih Sekolah --</option>
                                            @foreach ($sekolahList as $sekolah)
                                                <option value="{{ $sekolah->id }}"
                                                    {{ old('sekolah_id', $aset->sekolah_id) == $sekolah->id ? 'selected' : '' }}>
                                                    {{ $sekolah->nama_sekolah }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sekolah_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>

                                {{-- Nama Aset --}}
                                <div class="col-md-8">
                                    <label class="form-label fw-medium">Nama Aset <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="nama_aset"
                                           class="form-control @error('nama_aset') is-invalid @enderror"
                                           value="{{ old('nama_aset', $aset->nama_aset) }}"
                                           placeholder="contoh: Meja Belajar Siswa">
                                    @error('nama_aset')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kode Aset --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Kode Aset</label>
                                    <input type="text"
                                           name="kode_aset"
                                           class="form-control @error('kode_aset') is-invalid @enderror"
                                           value="{{ old('kode_aset', $aset->kode_aset) }}"
                                           placeholder="contoh: AST-001" disabled>
                                    @error('kode_aset')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kondisi --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Kondisi <span class="text-danger">*</span></label>
                                    <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror">
                                        <option value="">-- Pilih --</option>
                                        <option value="baik"         {{ old('kondisi', $aset->kondisi) === 'baik'         ? 'selected' : '' }}>Baik</option>
                                        <option value="rusak_ringan" {{ old('kondisi', $aset->kondisi) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="rusak_berat"  {{ old('kondisi', $aset->kondisi) === 'rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Jumlah --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number"
                                           name="jumlah"
                                           class="form-control @error('jumlah') is-invalid @enderror"
                                           value="{{ old('jumlah', $aset->jumlah) }}"
                                           min="1">
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Satuan --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Satuan <span class="text-danger">*</span></label>
                                    <select name="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                        @foreach (['unit', 'buah', 'set', 'lembar', 'pasang', 'lusin'] as $s)
                                            <option value="{{ $s }}" {{ old('satuan', $aset->satuan) === $s ? 'selected' : '' }}>
                                                {{ ucfirst($s) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tahun Pengadaan --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Tahun Pengadaan</label>
                                    <input type="number"
                                           name="tahun_pengadaan"
                                           class="form-control @error('tahun_pengadaan') is-invalid @enderror"
                                           value="{{ old('tahun_pengadaan', $aset->tahun_pengadaan) }}"
                                           min="1900" max="{{ date('Y') }}">
                                    @error('tahun_pengadaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Harga Perolehan --}}
                                <div class="col-md-8">
                                    <label class="form-label fw-medium">Harga Perolehan (Rp)</label>
                                    <input type="number"
                                           name="harga_perolehan"
                                           class="form-control @error('harga_perolehan') is-invalid @enderror"
                                           value="{{ old('harga_perolehan', $aset->harga_perolehan) }}"
                                           min="0" step="1000">
                                    @error('harga_perolehan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Lokasi --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium">Lokasi</label>
                                    <input type="text"
                                           name="lokasi"
                                           class="form-control @error('lokasi') is-invalid @enderror"
                                           value="{{ old('lokasi', $aset->lokasi) }}"
                                           placeholder="contoh: Ruang Kelas 1, Lab Komputer">
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Keterangan --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium">Keterangan</label>
                                    <textarea name="keterangan"
                                              class="form-control @error('keterangan') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $aset->keterangan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Foto Bukti --}}
                                <div class="col-12">
                                    <label class="form-label fw-medium">Foto Bukti Aset</label>
                                    @if($aset->foto_bukti)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $aset->foto_bukti) }}"
                                                 alt="Foto saat ini" class="rounded border"
                                                 style="max-height:160px; object-fit:cover;">
                                            <p class="form-text mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                                        </div>
                                    @endif
                                    <input type="file" name="foto_bukti"
                                           class="form-control @error('foto_bukti') is-invalid @enderror"
                                           accept="image/jpg,image/jpeg,image/png,image/webp"
                                           onchange="previewFoto(this)">
                                    <div class="form-text">Format: JPG, PNG, WEBP. Maks 2 MB. Kosongkan jika tidak ingin mengubah.</div>
                                    @error('foto_bukti')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="fotoPreview" class="mt-2 d-none">
                                        <img src="" alt="Preview baru" class="rounded border"
                                             style="max-height:160px; object-fit:cover;">
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                                    <a href="{{ route('aset.index') }}" class="btn btn-outline-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('fotoPreview');
    const img = preview.querySelector('img');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    } else {
        preview.classList.add('d-none');
    }
}
</script>
@endpush

</x-layouts.app>
