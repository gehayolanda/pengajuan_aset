<x-layouts.app>
    <div class="container-fluid">

        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-sm btn-light">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-semibold">Buat Pengajuan Penghapusan Aset</h4>
                <small class="text-muted">Isi form berikut untuk mengajukan penghapusan / pemusnahan aset</small>
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm mb-4">
            <strong>Aturan pemusnahan:</strong> hanya aset dengan tahun pengadaan maksimal {{ $batasTahunPemusnahan }} yang bisa diajukan.
        </div>

        <div class="card shadow-none border">
            <div class="card-body p-4">
                <form id="formPengajuan" action="{{ route('pengajuan-penghapusan-asset.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- Sekolah --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
                            @if (count($sekolahs) === 1)
                                <input type="text" class="form-control"
                                    value="{{ $sekolahs->first()->nama_sekolah }}" readonly>
                                <input type="hidden" name="sekolah_id" id="sekolahIdHidden"
                                    value="{{ old('sekolah_id', $sekolahs->first()->id) }}">
                            @else
                                <select name="sekolah_id" class="form-select @error('sekolah_id') is-invalid @enderror"
                                    id="selectSekolah" required>
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach ($sekolahs as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_sekolah }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sekolah_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        {{-- Aset --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aset <span class="text-danger">*</span></label>
                            <select name="aset_id" id="selectAset"
                                class="form-select @error('aset_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Aset --</option>
                                @forelse ($asets as $a)
                                    <option value="{{ $a->id }}" data-sekolah="{{ $a->sekolah_id }}"
                                        data-stok="{{ $a->jumlah }}" data-satuan="{{ $a->satuan }}"
                                        {{ old('aset_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->nama_aset }}
                                        @if ($a->kode_aset)
                                            ({{ $a->kode_aset }})
                                        @endif
                                        @if ($a->tahun_pengadaan)
                                            - Tahun {{ $a->tahun_pengadaan }}
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada aset yang memenuhi syarat usia 5 tahun.</option>
                                @endforelse
                            </select>
                            @error('aset_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text" id="stokInfo"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Aset</label>
                            <input type="text" id="stockAset" class="form-control" readonly>
                        </div>

                        {{-- Metode Penghapusan (fixed: pemusnahan) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Metode Penghapusan</label>
                            <input type="text" class="form-control" value="Pemusnahan" readonly>
                            <input type="hidden" name="metode_penghapusan" value="pemusnahan">
                        </div>

                        {{-- Jumlah Diajukan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah Diajukan <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="jumlah_diajukan" id="jumlahDiajukan" min="1"
                                class="form-control @error('jumlah_diajukan') is-invalid @enderror"
                                value="{{ old('jumlah_diajukan') }}" placeholder="Contoh: 5" required>
                            <div class="invalid-feedback" id="jumlahClientError">Jumlah melebihi stok yang tersedia.
                            </div>
                            @error('jumlah_diajukan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alasan Penghapusan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alasan Penghapusan <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="alasan_penghapusan"
                                class="form-control @error('alasan_penghapusan') is-invalid @enderror"
                                value="{{ old('alasan_penghapusan') }}"
                                placeholder="Contoh: Kondisi rusak berat, tidak dapat diperbaiki" required>
                            @error('alasan_penghapusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keterangan Tambahan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                placeholder="Opsional…">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dokumen Pendukung --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Surat Pengajuan <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="surat_pengajuan"
                                class="form-control @error('surat_pengajuan') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text">Format: PDF, JPG, PNG. Maks 2 MB.</div>
                            @error('surat_pengajuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Berita Acara <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="berita_acara"
                                class="form-control @error('berita_acara') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text">Format: PDF, JPG, PNG. Maks 2 MB.</div>
                            @error('berita_acara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dokumen Pendukung --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Dokumen Pendukung</label>
                            <input type="file" name="dokumen_pendukung"
                                class="form-control @error('dokumen_pendukung') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Format: PDF, JPG, PNG. Maks 2 MB.</div>
                            @error('dokumen_pendukung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="ti ti-send me-1"></i> Kirim Pengajuan
                        </button>
                        <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-light">Batal</a>
                    </div>

                </form>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const selectSekolah = document.getElementById('selectSekolah');
                const sekolahIdHidden = document.getElementById('sekolahIdHidden');

                const selectAset = document.getElementById('selectAset');
                const stockAset = document.getElementById('stockAset');

                const jumlahInput = document.getElementById('jumlahDiajukan');
                const stokInfo = document.getElementById('stokInfo');
                const jumlahClientError = document.getElementById('jumlahClientError');

                const form = document.getElementById('formPengajuan');
                const btnSubmit = document.getElementById('btnSubmit');

                const allAsetOptions = Array.from(selectAset.options).slice(1);

                function currentSekolahId() {
                    if (selectSekolah) return selectSekolah.value;
                    if (sekolahIdHidden) return sekolahIdHidden.value;
                    return '';
                }

                function filterAsetBySekolah() {

                    const sekolahId = currentSekolahId();
                    const previousValue = selectAset.value;

                    selectAset.innerHTML = '<option value="">-- Pilih Aset --</option>';

                    if (sekolahId) {
                        allAsetOptions.forEach(function(opt) {
                            if (opt.dataset.sekolah === sekolahId) {
                                selectAset.appendChild(opt.cloneNode(true));
                            }
                        });
                    }

                    if ([...selectAset.options].some(o => o.value === previousValue)) {
                        selectAset.value = previousValue;
                    } else {
                        selectAset.value = '';
                    }

                    updateStokInfoAndMax(true);
                }

                function updateStokInfoAndMax(isInit = false) {

                    const selected = selectAset.options[selectAset.selectedIndex];

                    if (selected && selected.value !== '') {

                        const stok = parseInt(selected.dataset.stok || '0', 10);
                        const satuan = selected.dataset.satuan || '';

                        stokInfo.textContent = `Stok tersedia: ${stok} ${satuan}`;

                        // isi textbox stok
                        stockAset.value = `${stok} ${satuan}`;

                        jumlahInput.max = stok;

                    } else {

                        stokInfo.textContent = '';
                        stockAset.value = '';

                        jumlahInput.removeAttribute('max');
                    }

                    validateJumlah(isInit);
                }

                function validateJumlah(isInit = false) {

                    const max = jumlahInput.max ?
                        parseInt(jumlahInput.max, 10) :
                        null;

                    const val = parseInt(jumlahInput.value || '0', 10);

                    if (max !== null && val > max) {

                        jumlahInput.classList.add('is-invalid');
                        jumlahClientError.classList.add('d-block');

                        return false;

                    } else {

                        if (!isInit) {
                            jumlahInput.classList.remove('is-invalid');
                            jumlahClientError.classList.remove('d-block');
                        }

                        return true;
                    }
                }

                if (selectSekolah) {
                    selectSekolah.addEventListener('change', filterAsetBySekolah);
                }

                selectAset.addEventListener('change', function() {
                    updateStokInfoAndMax(false);
                });

                jumlahInput.addEventListener('input', function() {
                    validateJumlah(false);
                });

                filterAsetBySekolah();

                form.addEventListener('submit', function(e) {

                    if (!validateJumlah(false)) {
                        e.preventDefault();
                        jumlahInput.focus();
                        return;
                    }

                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';

                });

            });
        </script>
    @endpush

</x-layouts.app>
