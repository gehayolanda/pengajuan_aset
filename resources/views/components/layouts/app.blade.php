<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SI-PPASET</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('template') }}/assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="{{ asset('template/assets/css/styles.min.css') }}" />

  <style>
    .btn-primary{
        background-color: #7f2600;
        border: #7f2600;
    }
    .btn-primary:hover {
        background-color: #7f2600;
        border: #7f2600;
    }
    .bg-primary {
        background-color: #7f2600;
    }

  </style>

</head>

<body class="bg-light">
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <x-layout.sidebar/>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <x-layout.header/>
      <!--  Header End -->
      <div class="container-fluid">
        <!--  Row 1 -->
  {{ $slot }}
      </div>
    </div>
  </div>
  <script src="{{ asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('template/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('template/assets/js/dashboard.js') }}"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var Popup = Swal.mixin({
        position: 'center',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
      });

      @if (session('success'))
        Popup.fire({ icon: 'success', title: @json(session('success')) });
      @endif

      @if (session('error'))
        Popup.fire({ icon: 'error', title: @json(session('error')), timer: 3500 });
      @endif

      @if ($errors->any())
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan',
          text: @json($errors->first()),
          confirmButtonColor: '#7f2600',
        });
      @endif

      // Konfirmasi hapus untuk semua form ber-class .js-confirm-delete
      document.querySelectorAll('form.js-confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
          if (form.dataset.confirmed === 'true') return;
          e.preventDefault();
          Swal.fire({
            title: form.dataset.confirmTitle || 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: form.dataset.confirmButton || 'Ya, hapus',
            cancelButtonText: 'Batal',
          }).then(function (result) {
            if (result.isConfirmed) {
              form.dataset.confirmed = 'true';
              form.submit();
            }
          });
        });
      });
    });
  </script>
  @stack('scripts')
</body>

</html>
