<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
           <div class="card-header">
                <h3 class="card-title p-4 rounded mb-0 text-white"
                style="background-color: #7f2600;">
                Tambah Sekolah Baru
                </h3>
            </div>

            <div class="card-body bg-light">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('sekolah.store') }}" method="POST">
                    @csrf


                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        Akun operator dibuat otomatis.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control" name="nama_sekolah" value="{{ old('nama_sekolah') }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NPSN Sekolah</label>
                        <input type="text" class="form-control" name="npsn_sekolah" value="{{ old('npsn_sekolah') }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Sekolah</label>
                        <input type="text" class="form-control" name="alamat_sekolah" value="{{ old('alamat_sekolah') }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <select class="form-select" name="kabupaten_id">
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach ($kabupaten as $k )
                                <option value="{{ $k->id }}" {{ old('kabupaten_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select" name="kecamatan_id">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $k )
                                <option value="{{ $k->id }}" {{ old('kecamatan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kecamatan}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Jenjang Sekolah</label>
                        <select class="form-select" name="jenjang_sekolah">
                            <option value="">-- Pilih Jenjang Sekolah --</option>
                            <option value="PAUD" {{ old('jenjang_sekolah') == 'PAUD' ? 'selected' : '' }}>PAUD</option>
                            <option value="SD" {{ old('jenjang_sekolah') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('jenjang_sekolah') == 'SMP' ? 'selected' : '' }}>SMP</option>
                        </select>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Tingkat Pengelola</label>
                        <select class="form-select" name="scope_pengelola">
                            <option value="">-- Pilih Tingkat Pengelola --</option>
                            <option value="kabupaten" {{ old('scope_pengelola') == 'kabupaten' ? 'selected' : '' }}>KABUPATEN</option>
                            <option value="kecamatan" {{ old('scope_pengelola') == 'kecamatan' ? 'selected' : '' }}>KECAMATAN</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check"></i> Simpan
                    </button>

                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
