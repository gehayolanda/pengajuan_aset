<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title p-4 rounded mb-0 text-white bg-primary">Tambah Sekolah Baru</h3>
            </div>

            <div class="card-body bg-light">
                <form action="{{ route('sekolah.store') }}" method="POST">
                    @csrf


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control" name="nama_sekolah"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NPSN Sekolah</label>
                        <input type="text" class="form-control" name="npsn_sekolah"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Sekolah</label>
                        <input type="text" class="form-control" name="alamat_sekolah"/>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kabupaten</label>
                        <select class="form-select" name="kabupaten_id">
                            <option value="">-- Pilih Kabupaten --</option>
                            @foreach ($kabupaten as $k )
                        <option value="{{ $k->id }}">{{ $k->nama_kabupaten }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan</label>
                        <select class="form-select" name="kecamatan_id">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $k )
                                <option value="{{ $k->id }}">{{ $k->nama_kecamatan}}</option>
                            @endforeach
                        </select>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Jenjang Sekolah</label>
                        <select class="form-select" name="jenjang_sekolah">
                            <option value="">-- Pilih Jenjang Sekolah --</option>
                            <option value="PAUD">PAUD</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                        </select>
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Tingkat Pengelola</label>
                        <select class="form-select" name="scope_pengelola">
                            <option value="">-- Pilih Tingkat Pengelola --</option>
                            <option value="kabupaten">Kabupaten</option>
                            <option value="kecamatan">Kecamatan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Operator Sekolah</label>
                        <select class="form-select" name="operator_id">
                            <option value="">-- Pilih Operator Sekolah --</option>
                            @foreach ($operators as $operator )
                                <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                            @endforeach
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
