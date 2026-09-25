@php
  $enAttente = \App\Models\Inscription::where('statut', 'en_attente')->count();
  $menu = [
      ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => 'space_dashboard', 'label' => 'Tableau de bord'],
      ['route' => 'candidats.index', 'match' => 'candidats.*', 'icon' => 'folder_shared', 'label' => 'Candidatures', 'count' => $enAttente],
      ['route' => 'formations.index', 'match' => 'formations.*', 'icon' => 'school', 'label' => 'Formations'],
      ['route' => 'formation-stats', 'match' => 'formation-stats', 'icon' => 'insights', 'label' => 'Statistiques & exports'],
  ];
@endphp
<aside class="admin-sidebar">
  <a href="{{ route('dashboard') }}" class="admin-brand">
    <img src="{{ asset(config('etablissement.logo')) }}" alt="{{ config('etablissement.nom') }}">
    <span class="brand-name">{{ config('etablissement.court') }}</span>
    <span class="brand-sub">Préinscription {{ date('Y') }}</span>
  </a>

  <nav class="admin-nav">
    <div class="nav-section">Gestion</div>
    @foreach ($menu as $item)
      <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['match']) ? 'active' : '' }}">
        <span class="material-symbols-rounded">{{ $item['icon'] }}</span>
        {{ $item['label'] }}
        @if (!empty($item['count']))
          <span class="count" title="Dossiers en attente">{{ $item['count'] }}</span>
        @endif
      </a>
    @endforeach

    <div class="nav-section">Paramètres</div>
    <a href="{{ route('etablissement.edit') }}" class="{{ request()->routeIs('etablissement.*') ? 'active' : '' }}">
      <span class="material-symbols-rounded">apartment</span>
      Établissement
    </a>
    <a href="{{ route('administrateurs.index') }}" class="{{ request()->routeIs('administrateurs.*') ? 'active' : '' }}">
      <span class="material-symbols-rounded">admin_panel_settings</span>
      Administrateurs
    </a>
    <a href="{{ route('accueil') }}" target="_blank">
      <span class="material-symbols-rounded">open_in_new</span>
      Voir le site public
    </a>
  </nav>

  <div class="admin-sidebar-foot">
    {{ config('etablissement.nom') }}
  </div>
</aside>
