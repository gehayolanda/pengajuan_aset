<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown d-flex align-items-center">
                    <span class="d-flex align-items-center flex-wrap fw-semibold me-2">
                        {{ Auth::user()->name }}
                        @role('admin')<span class="badge bg-primary ms-1" style="font-size:10px;">Admin</span>@endrole
                        @role('operator_sekolah')<span class="badge bg-success ms-1" style="font-size:10px;">Operator</span>@endrole
                        @role('kepala_dinas')<span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Kadis</span>@endrole
                    </span>

                    <a class="nav-link nav-icon" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @if (Auth::user()->foto_profil)
                            <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="" width="50"
                                height="50" class="rounded-circle object-fit-cover">
                        @else
                            <span class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white fw-bold"
                                style="width:50px;height:50px;font-size:1.2rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                                <i class="ti ti-user fs-6 text-muted"></i>
                                <div>
                                    <p class="mb-0 fw-semibold fs-3">{{ Auth::user()->name }}</p>
                                    <p class="mb-0 text-muted fs-2">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf

                                <button class="btn btn-outline-danger" type="submit">
                                    <i class="ti ti-power"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
