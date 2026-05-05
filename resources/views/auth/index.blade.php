<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('template/assets/images/logos/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}">
</head>

<body>
    <!-- Body Wrapper -->
    <div
        id="main-wrapper"
        class="page-wrapper"
        data-layout="vertical"
        data-navbarbg="skin6"
        data-sidebartype="full"
        data-sidebar-position="fixed"
        data-header-position="fixed"
    >
        <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="w-100 d-flex align-items-center justify-content-center">
                <div class="row w-100 justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xxl-3">

                        <div class="card mb-0">
                            <div class="card-body">

                                <!-- Logo -->
                                <a href="{{ route('login') }}" class="logo-img text-center d-block py-3 w-100">
                                    <img
                                        src="{{ asset('template/assets/images/logos/dark-logo.svg') }}"
                                        width="180"
                                        alt="Logo"
                                    >
                                </a>

                                <p class="text-center">Your Social Campaigns</p>

                                <!-- Form -->
                                <form action="{{ route('login-proses') }}" method="POST">
                                    @csrf

                                    <!-- User ID -->
                                    <div class="mb-3">
                                        <label for="login_id" class="form-label">User ID</label>
                                        <input
                                            type="text"
                                            id="login_id"
                                            name="login_id"
                                            class="form-control"
                                            value="{{ old('login_id') }}"
                                        >
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input
                                            type="password"
                                            id="password"
                                            name="password"
                                            class="form-control"
                                        >
                                    </div>

                                    <!-- Remember + Forgot -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input
                                                type="checkbox"
                                                id="remember"
                                                class="form-check-input primary"
                                                checked
                                            >
                                            <label for="remember" class="form-check-label text-dark">
                                                Remember this Device
                                            </label>
                                        </div>

                                        <a href="#" class="text-primary fw-bold">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    <!-- Submit -->
                                    <button type="submit" class="btn btn-primary w-100">
                                        Sign In
                                    </button>

                                    <!-- Register -->
                                    <div class="d-flex align-items-center justify-content-center mt-3">
                                        <p class="fs-4 mb-0 fw-bold">New to Modernize?</p>
                                        <a href="#" class="text-primary fw-bold ms-2">
                                            Create an account
                                        </a>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
