<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>KLINIK KECANTIKAN TONG FANG</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://fip.unp.ac.id/img/logofipicon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!-- Config -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <style>
      .bg-menu-theme .menu-inner > .menu-item.active > .menu-link{
        background-color: #FFA700 !important;
        color: #fff !important;
      }

      .bg-menu-theme .menu-inner > .menu-item.active:before{
        background-color: #FFA700 !important;
      }

      .dataTables_filter{
        margin-bottom: 15px !important;
      }

      .dt-input{
        margin-right: 15px !important;
      }

      .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        max-width: 300px;
        padding: 12px 15px;
        border-radius: 4px;
        font-weight: 500;
        z-index: 9999;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .custom-alert.show {
        opacity: 1;
        transform: translateY(0);
      }

      .custom-alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
      }

      .custom-alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
      }

      .custom-alert-content {
        display: flex;
        align-items: center;
        flex-grow: 1;
        margin-right: 10px;
      }

      .custom-alert-icon {
        margin-right: 10px;
        font-size: 1.2em;
      }

      .custom-alert-message {
        line-height: 1.4;
        font-size: 14px;
      }

      .custom-alert-close {
        font-size: 1.2em;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
        cursor: pointer;
        padding-left: 10px;
      }

      .custom-alert-close:hover {
        opacity: .75;
      }
    </style>
  </head>

  <body>
    @if(Session::has('success') || Session::has('error'))
      <div id="customAlert" class="custom-alert {{ Session::has('success') ? 'custom-alert-success' : 'custom-alert-error' }}">
        <div class="custom-alert-content">
          <span class="custom-alert-icon">
            @if(Session::has('success'))
              <i class="bx bx-check-circle"></i>
            @else
              <i class="bx bx-error-circle"></i>
            @endif
          </span>
          <span class="custom-alert-message">{{ Session::get('success') ?? Session::get('error') }}</span>
        </div>
        <span class="custom-alert-close" onclick="closeAlert()">&times;</span>
      </div>
    @endif

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('layouts.dashboard.navbar')

        <!-- Layout container -->
        <div class="layout-page">
          @include('layouts.dashboard.topbar')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            @yield('content')
            @include('layouts.dashboard.footer')
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>

    <!-- Custom Scripts -->
    <script>
      $(document).ready(function () {
        // Hanya inisialisasi DataTables jika tabel dengan id 'example' ada
        if ($('#example').length) {
          $('#example').DataTable({
            responsive: true,
            language: {
              url: 'https://cdn.datatables.net/plug-ins/2.0.0/i18n/id.json'
            }
          });
        }
      });

      // Alert functionality
      document.addEventListener('DOMContentLoaded', function() {
        var alert = document.getElementById('customAlert');
        if (alert) {
          setTimeout(function() {
            alert.classList.add('show');
          }, 100);
          setTimeout(function() {
            closeAlert();
          }, 5000);
        }
      });

      function closeAlert() {
        var alert = document.getElementById('customAlert');
        if (alert) {
          alert.classList.remove('show');
          setTimeout(function() {
            alert.remove();
          }, 300);
        }
      }
    </script>

    @stack('scripts')
  </body>
</html>
