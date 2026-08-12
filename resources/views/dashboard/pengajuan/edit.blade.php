<x-layouts.app>
<div class="container-fluid">

  <div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-sm btn-light">
      <i class="ti ti-arrow-left"></i>
    </a>
    <div>
      <h4 class="mb-0 fw-semibold">Edit Pengajuan</h4>
      <small class="text-muted">{{ $pengajuan->nomor_pengajuan }}</small>
    </div>
  </div>

  <div class="card shadow-none border">
    <div class="card-body p-4">
      <form action="{{ route('pengajuan-penghapusan-asset.update', $pengajuan) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="alert alert-info border-0 shadow-sm mb-4">
          <strong>Aturan pemusnahan:</strong> hanya aset dengan tahun pengadaan maksimal {{ $batasTahunPemusnahan }} yang bisa diajukan.
        </div>

        <div class="row g-3">

          {{-- Sekolah --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
            @if(count($sekolahs) === 1)
              <input type="text" class="form-control" value="{{ $sekolahs->first()->nama_sekolah ?? '' }}" readonly>
              <input type="hidden" name="sekolah_id" value="{{ $sekolahs->first()->id ?? '' }}">
            @else
              <select name="sekolah_id" class="form-select @error('sekolah_id') is-invalid @enderror">
                <option value="">-- Pilih Sekolah --</option>
                @foreach($sekolahs as $s)
                  <option value="{{ $s->id }}" {{ old('sekolah_id', $pengajuan->sekolah_id) == $s->id ? 'selected' : '' }}>
                    {{ $s->nama_sekolah }}
                  </option>
                @endforeach
              </select>
              @error('sekolah_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @endif
          </div>

          {{-- Aset --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Aset <span class="text-danger">*</span></label>
            <select name="aset_id" class="form-select @error('aset_id') is-invalid @enderror">
              <option value="">-- Pilih Aset --</option>
              @forelse($asets as $a)
                <option value="{{ $a->id }}" {{ old('aset_id', $pengajuan->aset_id) == $a->id ? 'selected' : '' }}>
                  {{ $a->nama_aset }}
                  @if($a->kode_aset) ({{ $a->kode_aset }}) @endif
                  — Stok: {{ $a->jumlah }} {{ $a->satuan }}
                  @if($a->tahun_pengadaan)
                    — Tahun {{ $a->tahun_pengadaan }}
                  @endif
                </option>
              @empty
                <option value="" disabled>Tidak ada aset yang memenuhi syarat usia 5 tahun.</option>
              @endforelse
            </select>
            @error('aset_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Metode Penghapusan --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Metode Penghapusan <span class="text-danger">*</span></label>
            <select name="metode_penghapusan" class="form-select @error('metode_penghapusan') is-invalid @enderror">
              <option value="">-- Pilih Metode --</option>
              @foreach($metodes as $key => $label)
                <option value="{{ $key }}" {{ old('metode_penghapusan', $pengajuan->metode_penghapusan) === $key ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('metode_penghapusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Jumlah Diajukan --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Jumlah Diajukan <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_diajukan" min="1"
                   class="form-control @error('jumlah_diajukan') is-invalid @enderror"
                   value="{{ old('jumlah_diajukan', $pengajuan->jumlah_diajukan) }}">
            @error('jumlah_diajukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Alasan Penghapusan --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Alasan Penghapusan <span class="text-danger">*</span></label>
            <input type="text" name="alasan_penghapusan"
                   class="form-control @error('alasan_penghapusan') is-invalid @enderror"
                   value="{{ old('alasan_penghapusan', $pengajuan->alasan_penghapusan) }}">
            @error('alasan_penghapusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Keterangan --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Keterangan Tambahan</label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                      rows="3">{{ old('keterangan', $pengajuan->keterangan) }}</textarea>
            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Dokumen Pendukung --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Surat Pengajuan <span class="text-danger">*</span></label>
            @if($pengajuan->surat_pengajuan)
              <div class="mb-2">
                <a href="{{ asset('storage/' . $pengajuan->surat_pengajuan) }}" target="_blank"
                   class="btn btn-sm btn-outline-info">
                  <i class="ti ti-file me-1"></i> Lihat Surat Pengajuan Saat Ini
                </a>
              </div>
            @endif
            <input type="file" name="surat_pengajuan"
                   class="form-control @error('surat_pengajuan') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png">
            <div class="form-text">Kosongkan jika tidak ingin mengubah dokumen. Format: PDF, JPG, PNG. Maks 2 MB.</div>
            @error('surat_pengajuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Berita Acara <span class="text-danger">*</span></label>
            @if($pengajuan->berita_acara)
              <div class="mb-2">
                <a href="{{ asset('storage/' . $pengajuan->berita_acara) }}" target="_blank"
                   class="btn btn-sm btn-outline-info">
                  <i class="ti ti-file me-1"></i> Lihat Berita Acara Saat Ini
                </a>
              </div>
            @endif
            <input type="file" name="berita_acara"
                   class="form-control @error('berita_acara') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png">
            <div class="form-text">Kosongkan jika tidak ingin mengubah dokumen. Format: PDF, JPG, PNG. Maks 2 MB.</div>
            @error('berita_acara') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Dokumen Pendukung --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Dokumen Pendukung</label>
            @if($pengajuan->dokumen_pendukung)
              <div class="mb-2">
                <a href="{{ asset('storage/' . $pengajuan->dokumen_pendukung) }}" target="_blank"
                   class="btn btn-sm btn-outline-info">
                  <i class="ti ti-file me-1"></i> Lihat Dokumen Saat Ini
                </a>
              </div>
            @endif
            <input type="file" name="dokumen_pendukung"
                   class="form-control @error('dokumen_pendukung') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png">
            <div class="form-text">Kosongkan jika tidak ingin mengubah dokumen. Format: PDF, JPG, PNG. Maks 2 MB.</div>
            @error('dokumen_pendukung') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Simpan Perubahan
          </button>
          <a href="{{ route('pengajuan-penghapusan-asset.show', $pengajuan) }}" class="btn btn-light">Batal</a>
        </div>

      </form>
    </div>
  </div>

</div>
</x-layouts.app>
