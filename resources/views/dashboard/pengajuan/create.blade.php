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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="ti ti-alert-circle me-1"></i>Terdapat kesalahan input:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                                @foreach ($asets as $a)
                                    <option value="{{ $a->id }}" data-sekolah="{{ $a->sekolah_id }}"
                                        data-stok="{{ $a->jumlah }}" data-satuan="{{ $a->satuan }}"
                                        {{ old('aset_id') == $a->id ? 'selected' : '' }}>
                                        {{ $a->nama_aset }}
                                        @if ($a->kode_aset)
                                            ({{ $a->kode_aset }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('aset_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok Tersedia --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Tersedia</label>
                            <input type="text" class="form-control" id="stokTersedia" value=""
                                placeholder="Pilih aset terlebih dahulu" readonly>
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
                const jumlahInput = document.getElementById('jumlahDiajukan');
                const stokTersedia = document.getElementById('stokTersedia');
                const jumlahClientError = document.getElementById('jumlahClientError');
                const form = document.getElementById('formPengajuan');
                const btnSubmit = document.getElementById('btnSubmit');

                const allAsetOptions = Array.from(selectAset.options).slice(1); // exclude placeholder

                function currentSekolahId() {
                    if (selectSekolah) return selectSekolah.value;
                    if (sekolahIdHidden) return sekolahIdHidden.value;
                    return '';
                }

                function filterAsetBySekolah() {
                    const sekolahId = currentSekolahId();
                    const previousValue = selectAset.value;

                    // reset options
                    selectAset.innerHTML = '<option value="">-- Pilih Aset --</option>';

                    // PERBAIKAN 1: Jika sekolah belum dipilih (kosong), jangan tampilkan aset apa pun
                    if (sekolahId) {
                        allAsetOptions.forEach(function(opt) {
                            if (opt.dataset.sekolah === sekolahId) {
                                selectAset.appendChild(opt.cloneNode(true));
                            }
                        });
                    }

                    // restore selection if still valid
                    if ([...selectAset.options].some(o => o.value === previousValue)) {
                        selectAset.value = previousValue;
                    } else {
                        selectAset.value = '';
                    }

                    // Panggil update stok (kirim parameter 'true' sebagai tanda inisialisasi awal)
                    updateStokInfoAndMax(true);
                }

                function updateStokInfoAndMax(isInit = false) {
                    const selected = selectAset.options[selectAset.selectedIndex];
                    if (selected && selected.value !== '') {
                        const stok = parseInt(selected.dataset.stok || '0', 10);
                        const satuan = selected.dataset.satuan || '';
                        stokTersedia.value = stok + ' ' + satuan;
                        jumlahInput.max = stok;
                    } else {
                        stokTersedia.value = '';
                        jumlahInput.removeAttribute('max');
                    }
                    validateJumlah(isInit);
                }

                // Tambahkan parameter isInit
                function validateJumlah(isInit = false) {
                    const max = jumlahInput.max ? parseInt(jumlahInput.max, 10) : null;
                    const val = parseInt(jumlahInput.value || '0', 10);

                    if (max !== null && val > max) {
                        jumlahInput.classList.add('is-invalid');
                        jumlahClientError.classList.add('d-block');
                        return false;
                    } else {
                        // PERBAIKAN 2: Jangan hapus is-invalid jika ini adalah proses load awal halaman
                        // agar pesan error/border merah dari Laravel tidak terhapus paksa.
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

                // Saat user mengubah aset, validasi jalan normal (bukan inisialisasi awal)
                selectAset.addEventListener('change', () => updateStokInfoAndMax(false));

                // Saat user mengetik jumlah, validasi jalan normal
                jumlahInput.addEventListener('input', () => validateJumlah(false));

                // Jalankan fungsi filter pertama kali saat halaman dimuat
                filterAsetBySekolah();

                // Mencegah klik tombol 2x & validasi sebelum kirim
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
