<x-layouts.app> <div class="container-fluid">

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                    style="width: 56px; height:56px;">
                    <i class="ti ti-user-cog fs-3"></i>
                </div>

                <div>
                    <h4 class="mb-1 fw-bold">Data Operator</h4>
                    <p class="text-muted mb-0">
                        Kelola Data Operator Sistem
                    </p>
                </div>
            </div>

            <a href="{{ route('operator.create') }}" class="btn btn-primary px-4">
                <i class="ti ti-plus me-1"></i>
                Tambah Operator
            </a>

        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">

            <form action="{{ route('operator') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nama atau email..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i>
                            Cari
                        </button>
                    </div>

                    <div class="col-md-auto">
                        <a href="{{ route('operator') }}"
                            class="btn btn-light border">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="70">No</th>
                            <th>Nama Operator</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($operators as $operator)
                            <tr>

                                <td class="text-center">
                                    {{ $operators->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <div class="text-center text-dark">
                                        {{ $operator->name }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    {{ $operator->email }}
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ $operator->getRoleNames()->first() }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('operator.edit', $operator->id) }}"
                                            class="btn btn-warning btn-sm"
                                            title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <form
                                            action="{{ route('operator.destroy', $operator->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus">

                                                <i class="ti ti-trash"></i>

                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-5">

                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <i class="ti ti-users-off fs-1 mb-2"></i>

                                        <span class="small">
                                            Belum ada data operator
                                        </span>
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        @if ($operators->hasPages())
            <div class="card-footer bg-white">
                {{ $operators->links() }}
            </div>
        @endif

    </div>

</div>

</x-layouts.app>
