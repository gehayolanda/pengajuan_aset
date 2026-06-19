<x-layouts.app> <div class="container-fluid">

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex align-items-center gap-3">

            <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning"
                style="width: 56px; height:56px;">
                <i class="ti ti-edit fs-3"></i>
            </div>

            <div>
                <h4 class="mb-1 fw-bold">Edit Operator</h4>
                <p class="text-muted mb-0">
                    Ubah data operator sistem
                </p>
            </div>

        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('operator.update', $operator->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Nama Operator
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $operator->name) }}">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $operator->email) }}">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Login ID
                        </label>

                        <input
                            type="text"
                            name="login_id"
                            class="form-control @error('login_Id') is-invalid @enderror"
                            value="{{ old('login_id', $operator->login_id) }}">

                        @error('login_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti password
                        </small>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control">
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('operator') }}"
                        class="btn btn-light border">
                        <i class="ti ti-arrow-left"></i>
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-device-floppy"></i>
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

</x-layouts.app>
