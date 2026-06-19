<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between px-3">
            <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center">

                <img src="{{ asset('template/assets/images/logo-kabupaten-ketapang.jpeg') }}" alt="Logo Kabupaten Ketapang"
                    class="brand-logo-img">

                <div class="ms-2">
                    <h5 class="text-white fw-bold mb-0">
                        SI-PPASET
                    </h5>

                    <small class="brand-subtitle">
                        DINAS PENDIDIKAN
                    </small>
                </div>
            </a>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-white"></i>
            </div>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                {{-- HOME --}}
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">HOME</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                {{-- MASTER DATA (admin only) --}}
                @role('admin')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">MASTER DATA</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('dashboard/kecamatan*') ? 'active' : '' }}"
                            href="{{ route('kecamatan') }}" aria-expanded="false">
                            <span><i class="ti ti-map"></i></span>
                            <span class="hide-menu">Kecamatan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('dashboard/sekolah*') ? 'active' : '' }}"
                            href="{{ route('sekolah') }}" aria-expanded="false">
                            <span><i class="ti ti-school"></i></span>
                            <span class="hide-menu">Sekolah</span>
                        </a>
                    </li>
                @endrole

                {{-- PENGELOLAAN ASET (admin & operator_sekolah) --}}
                @hasanyrole('admin|operator_sekolah')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">PENGELOLAAN ASET</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('dashboard/aset*') ? 'active' : '' }}"
                            href="{{ route('aset.index') }}" aria-expanded="false">
                            <span><i class="ti ti-package"></i></span>
                            <span class="hide-menu">Data Aset</span>
                        </a>
                    </li>
                @endhasanyrole

                {{-- VALIDASI (kepala_dinas) --}}
                @role('kepala_dinas')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">VALIDASI</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('dashboard/pengajuan*') ? 'active' : '' }}"
                            href="{{ route('pengajuan-penghapusan-asset.index') }}" aria-expanded="false">
                            <span><i class="ti ti-clipboard-check"></i></span>
                            <span class="hide-menu">Validasi Pengajuan</span>
                        </a>
                    </li>
                @endrole

                {{-- PENGAJUAN (admin & operator_sekolah) --}}
                @hasanyrole('admin|operator_sekolah')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">PENGAJUAN</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link {{ request()->is('dashboard/pengajuan*') ? 'active' : '' }}"
                            href="{{ route('pengajuan-penghapusan-asset.index') }}" aria-expanded="false">
                            <span><i class="ti ti-file-description"></i></span>
                            <span class="hide-menu">Pengajuan Pemusnahan</span>
                        </a>
                    </li>
                @endhasanyrole

            </ul>
        </nav>
    </div>
</aside>

<style>
        .left-sidebar {
            background-color: #7f2600 !important;
        }

        .left-sidebar .sidebar-link {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .left-sidebar .sidebar-link i {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .left-sidebar .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }

        .left-sidebar .sidebar-link.active,
        .left-sidebar .sidebar-item.selected > .sidebar-link,
        .left-sidebar .sidebar-nav ul .sidebar-item > .sidebar-link.active,
        .left-sidebar .sidebar-nav ul .sidebar-item.selected > .sidebar-link {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            border-left: 3px solid rgba(255, 255, 255, 0.8) !important;
        }

        .left-sidebar .sidebar-link.active i,
        .left-sidebar .sidebar-item.selected > .sidebar-link i,
        .left-sidebar .sidebar-link:hover i {
            color: #ffffff !important;
        }

        .left-sidebar .nav-small-cap {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .left-sidebar .brand-logo h3 {
            color: #ffffff !important;
        }

        .left-sidebar .sidebartoggler i {
            color: #ffffff !important;
        }

        .brand-logo {
            min-height: 80px;
            background-color: #7f2600 !important;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
            padding: 3px;
        }

        .brand-logo h5 {
            font-size: 20px;
            letter-spacing: .5px;
        }

        .brand-subtitle {
            color: #b8bef5;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
{{-- <style>
    /* ── Sidebar background ── */
    .left-sidebar {
        background-color: #7f2600 !important;
        border-right: 1px solid #5a1b00 !important;
    }

    /* ── Brand logo area ── */
    .brand-logo {
        background-color: #6b1f00 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* ── Section label ── */
    .nav-small-cap {
        color: #f0a07a !important;
        font-weight: 700;
    }

    /* ── Nav link default ── */
    .sidebar-nav ul .sidebar-item .sidebar-link {
        color: #f5cbb8 !important;
    }

    /* ── Nav link icon ── */
    .sidebar-nav ul .sidebar-item .sidebar-link .ti {
        color: #f5cbb8 !important;
    }

    /* ── Nav link hover ── */
    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover .ti {
        color: #ffffff !important;
    }

    /* ── Nav link active ── */
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link,
    .sidebar-nav ul .sidebar-item.selected>.sidebar-link.active,
    .sidebar-nav ul .sidebar-item>.sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    .sidebar-nav ul .sidebar-item>.sidebar-link.active .ti {
        color: #ffffff !important;
    }

    /* ── Scrollbar ── */
    .simplebar-scrollbar:before {
        background: rgba(255, 255, 255, 0.3) !important;
    }


.brand-logo {
    min-height: 80px;
    background-color: #6b1f00 !important;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.brand-logo-img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    border-radius: 8px;
    background: #fff;
    padding: 3px;
}

.brand-logo h5 {
    font-size: 20px;
    letter-spacing: .5px;
}

.brand-subtitle {
    color: #f5cbb8;
    font-size: 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
}
</style> --}}
