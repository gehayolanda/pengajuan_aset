<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <h3>SIPASET DISDIK</h3>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

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
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('dashboard/operator*') ? 'active' : '' }}"
                        href="{{ route('operator') }}" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Operator</span>
                    </a>
                </li>
                @endrole

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

                @role('kepala_dinas')
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">VALIDASI</span>
                </li>
                @endrole

                @hasanyrole('admin|operator_sekolah')
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PENGAJUAN</span>
                </li>
                @endhasanyrole

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('dashboard/pengajuan*') ? 'active' : '' }}"
                        href="{{ route('pengajuan-penghapusan-asset.index') }}" aria-expanded="false">
                        @role('kepala_dinas')
                            <span><i class="ti ti-clipboard-check"></i></span>
                            <span class="hide-menu">Validasi Pengajuan</span>
                        @else
                            <span><i class="ti ti-file-description"></i></span>
                            <span class="hide-menu">Pengajuan Pemusnahan</span>
                        @endrole
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
