<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
            {{-- card header start --}}
            <div class="card-header" style="background-color: #7f2600;">
                <h3 class="card-title mb-0 text-white">Tambah Kecamatan Baru</h3>
            </div>
            {{-- card header stop --}}

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('kecamatan.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kecamatan</label>
                                <input type="text" class="form-control @error('nama_kecamatan') is-invalid @enderror"
                                       placeholder="Masukan nama kecamatan" name="nama_kecamatan"
                                       value="{{ old('nama_kecamatan') }}">
                                @error('nama_kecamatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kabupaten</label>
                                <select name="kabupaten_id" class="form-select @error('kabupaten_id') is-invalid @enderror">
                                    <option value="" disabled {{ old('kabupaten_id') ? '' : 'selected' }}>Pilih Kabupaten</option>
                                    @forelse ($kecamatan->unique('kabupaten_id') as $k)
                                        <option value="{{ $k->kabupaten->id }}"
                                            {{ old('kabupaten_id') == $k->kabupaten->id ? 'selected' : '' }}>
                                            {{ $k->kabupaten->nama_kabupaten }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Data tidak ditemukan</option>
                                    @endforelse
                                </select>
                                @error('kabupaten_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check"></i> Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
