<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="{{ asset(config('etablissement.logo')) }}">
  <title>@hasSection('title')@yield('title') · @endif Préinscription - {{ config('etablissement.court') }}</title>

  @livewireStyles
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..24,400..600,0..1,0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
  <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
  <style>
    :root { --brand: {{ config('etablissement.couleur') }}; }
  </style>
  @stack('styles')
</head>

<body>
  <div class="admin-shell" id="adminShell">
    @include('utilisateur.layouts.sidebar')
    <div class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <div class="admin-main">
      @include('utilisateur.layouts.header')

      <main class="admin-content">
        @yield('content')
      </main>

      @include('utilisateur.layouts.footer')
    </div>
  </div>

  @livewireScripts
  @include('utilisateur.layouts.script')
  @stack('scripts')
</body>

</html>
