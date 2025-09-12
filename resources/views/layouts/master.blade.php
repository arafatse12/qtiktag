<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'QLIKTAG')</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('materialm/src/src/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('materialm/src/assets/css/styles.min.css') }}" />
 <meta name="csrf-token" content="{{ csrf_token() }}">


  @stack('styles') {{-- Optional additional styles --}}
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!-- Topstrip -->
    @include('layouts.topstrip')

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main wrapper -->
    <div class="body-wrapper">
      <!-- Header -->
      @include('layouts.header')

      <!-- Body Content -->
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('materialm/src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('materialm/src/assets/js/dashboard.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  @stack('scripts') {{-- Optional additional scripts --}}
</body>

</html>
