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

  @if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  @endif

  <div class="card shadow-none border">
    <div class="card-body p-4">
      <form action="{{ route('pengajuan-penghapusan-asset.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

          {{-- Sekolah --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Sekolah <span class="text-danger">*</span></label>
            @if(count($sekolahs) === 1)
              <input type="text" class="form-control" value="{{ $sekolahs->first()->nama_sekolah ?? '' }}" readonly>
              <input type="hidden" name="sekolah_id" value="{{ $sekolahs->first()->id ?? '' }}">
            @else
              <select name="sekolah_id" class="form-select @error('sekolah_id') is-invalid @enderror" id="selectSekolah">
                <option value="">-- Pilih Sekolah --</option>
                @foreach($sekolahs as $s)
                  <option value="{{ $s->id }}" {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>
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
              @foreach($asets as $a)
                <option value="{{ $a->id }}" {{ old('aset_id') == $a->id ? 'selected' : '' }}>
                  {{ $a->nama_aset }}
                  @if($a->kode_aset) ({{ $a->kode_aset }}) @endif
                  — Stok: {{ $a->jumlah }} {{ $a->satuan }}
                </option>
              @endforeach
            </select>
            @error('aset_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Metode Penghapusan --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold">Metode Penghapusan <span class="text-danger">*</span></label>
            <select name="metode_penghapusan" class="form-select @error('metode_penghapusan') is-invalid @enderror">
              <option value="">-- Pilih Metode --</option>
              @foreach($metodes as $key => $label)
                <option value="{{ $key }}" {{ old('metode_penghapusan') === $key ? 'selected' : '' }}>
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
                   value="{{ old('jumlah_diajukan') }}" placeholder="Contoh: 5">
            @error('jumlah_diajukan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Alasan Penghapusan --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Alasan Penghapusan <span class="text-danger">*</span></label>
            <input type="text" name="alasan_penghapusan"
                   class="form-control @error('alasan_penghapusan') is-invalid @enderror"
                   value="{{ old('alasan_penghapusan') }}"
                   placeholder="Contoh: Kondisi rusak berat, tidak dapat diperbaiki">
            @error('alasan_penghapusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Keterangan --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Keterangan Tambahan</label>
            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                      rows="3" placeholder="Opsional…">{{ old('keterangan') }}</textarea>
            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Dokumen Pendukung --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Dokumen Pendukung</label>
            <input type="file" name="dokumen_pendukung"
                   class="form-control @error('dokumen_pendukung') is-invalid @enderror"
                   accept=".pdf,.jpg,.jpeg,.png">
            <div class="form-text">Format: PDF, JPG, PNG. Maks 2 MB.</div>
            @error('dokumen_pendukung') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

        </div>

        <hr class="my-4">

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-send me-1"></i> Kirim Pengajuan
          </button>
          <a href="{{ route('pengajuan-penghapusan-asset.index') }}" class="btn btn-light">Batal</a>
        </div>

      </form>
    </div>
  </div>

</div>
</x-layouts.app>
