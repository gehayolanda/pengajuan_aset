<x-layouts.app>
<div class="container-fluid">

    {{-- button tambah --}}
    <div class="row mb-3 d-flex justify-content-between align-items-center">
        <div class="col-12 col-md-2 mb-2">
            <a href="{{ route('sekolah.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Tambah Sekolah
            </a>
        </div>
    </div>

    {{-- table --}}
    <div class="px-3 py-3 table-responsive text-center overflow-auto">
        <table class="table table-bordered align-middle">
           <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Sekolah</th>
                <th>NPSN</th>
                <th>Operator</th>
                <th>Aksi</th>
            </tr>
           </thead>
           <body>
            @forelse ($sekolah as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->nama_sekolah }}</td>
                    <td>{{ $s->npsn_sekolah }}</td>
                    <td>{{ $s->operator->name ?? 'N/A' }}</td>
                    <td class="d-flex justify-content-center align-items-center btn-group-lg">
                        {{-- <form action="{{ route('kecamatan.destroy', $k->id) }}" method="POST">
                            @csrf
                            @method("DELETE")

                            <button class="btn btn-danger btn-sm" type="submit">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form> --}}
                    </td>
                </tr>

            @empty
                 <tr>
                    <td colspan="4">Data tidak ditemukan</td>
                 </tr>

            @endforelse
           </body>
        </table>
    </div>
</div>
</x-layouts.app>
