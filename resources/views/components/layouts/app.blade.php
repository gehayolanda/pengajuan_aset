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
  @stack('scripts')
</body>

</html>
