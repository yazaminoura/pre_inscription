<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('dist/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('dist/assets/img/fst.png') }}">
  <title>PreInscription FST</title>

  @livewireStyles
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="{{ asset('dist/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('dist/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link id="pagestyle" href="{{ asset('dist/assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('dist/assets/css/plus-style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('dist/assets/css/form-styles.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    

   

    #sidenav-main,#nsidenav-main {
      width: 250px;
      transition: all 0.5s ease;
      padding-top: 1rem;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      border-radius: 0 10px 10px 0;
    }

    #sidenav-main.collapsed {
      transform: translateX(-100%);
      width: 0;
      margin-left: 0;
      overflow: hidden;
    }

    #main-content {
      transition: all 0.3s ease;
      width: 100%;
    }

    .nav-link {
      padding: 12px 20px;
      border-radius: 10px;
      margin-bottom: 5px;
      transition: background 0.2s ease;
    }

    .nav-link:hover {
      background-color: #0d3a73;
      color: green !important;
    }

    .navbar,
    .btn-primary,
    .sidebar-header {
      background-color: #0d3a73;
      color: white;
    }

    

    /* Responsive Sidebar */
    @media (max-width: 900px) {
      #sidenav-main,#nsidenav-main {
        position: fixed;
        left: -250px;
        z-index: 1031;
      }

      #sidenav-main.sidebar-visible {
        left: 0;
      }

      .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1030;
        display: none;
      }
    }

    @media (min-width: 901px) {
      #main-content {
        width: calc(100% - 250px);
      }
    }
  </style>
</head>

<body class="bg-gray-100">
  <div class="d-flex">
    <!-- Sidebar -->
    <div id="nsidenav-main" class="nsidenav " style="width: 240px ;min-height: 100vh;" >
      @include('utilisateur.layouts.sidebar')
    </div>

    <!-- Main Content -->
    <main id="main-content" class="flex-grow-1">
      @include('utilisateur.layouts.header')
      @yield('content')
    </main>
  </div>

  <!-- Overlay for mobile -->
  <div class="sidebar-overlay"></div>
  @livewireScripts
  @stack('scripts')
  @include('utilisateur.layouts.script')
  @if (session('toastr'))
      <script>
          toastr.{{ session('toastr.type') }}('{{ session('toastr.message') }}');
      </script>
  @endif
</body>

</html>
