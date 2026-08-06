<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
           <div class="card-header">
                <h3 class="card-title p-4 rounded mb-0 text-white" style="background-color: #7f2600;">
                    Detail Sekolah {{ $sekolah->nama_sekolah }}
                </h3>
            </div>

            <div class="card-body bg-light">

                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control" disabled value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NPSN Sekolah</label>
                        <input type="text" class="form-control" disabled value="{{ old('npsn_sekolah', $sekolah->npsn_sekolah) }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Sekolah</label>
                        <input type="text" class="form-control" disabled value="{{ old('alamat_sekolah', $sekolah->alamat_sekolah) }}"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <select class="form-select" disabled name="kabupaten_id">
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach ($kabupaten as $k )
                        <option value="{{ $k->id }}" @selected($sekolah->kabupaten_id == $k->id)>{{ $k->nama_kabupaten }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select" disabled name="kecamatan_id">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $k )
                                <option value="{{ $k->id }}" @selected($sekolah->kecamatan_id == $k->id)>{{ $k->nama_kecamatan}}</option>
                            @endforeach
                        </select>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Jenjang Sekolah</label>
                        <input type="text" class="form-control" disabled value="{{ old('jenjang_sekolah', $sekolah->jenjang_sekolah) }}">
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Tingkat Pengelola</label>
                         <input type="text" class="form-control text-uppercase" disabled value="{{ old('scope_pengelola', $sekolah->scope_pengelola) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Operator Sekolah</label>
                        <select class="form-select" disabled name="operator_id">
                            <option value="">-- Pilih Operator Sekolah --</option>
                            @foreach ($operators as $operator )
                                <option value="{{ $operator->id }}" @selected($sekolah->operator_id == $operator->id)>{{ $operator->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route('sekolah') }}" class="btn btn-primary">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
            </div>
        </div>
    </div>
</x-layouts.app>
