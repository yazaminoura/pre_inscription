<header class="admin-topbar">
  <button type="button" class="menu-btn" onclick="toggleSidebar()" aria-label="Menu">
    <span class="material-symbols-rounded">menu</span>
  </button>
  <h1>@yield('title', 'Espace administration')</h1>

  @auth
    <div class="dropdown ms-auto">
      <button class="user-chip dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="user-name">{{ Auth::user()->name }}</span>
        <span class="avatar-initials">{{ mb_strtoupper(mb_substr(trim(Auth::user()->name), 0, 1)) }}</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px; min-width: 220px;">
        <li class="px-3 py-2">
          <div class="fw-semibold">{{ Auth::user()->name }}</div>
          <div class="text-muted small">{{ Auth::user()->email }}</div>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('administrateurs.edit', Auth::user()) }}">
            <span class="material-symbols-rounded">manage_accounts</span> Mon compte
          </a>
        </li>
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
              <span class="material-symbols-rounded">logout</span> Déconnexion
            </button>
          </form>
        </li>
      </ul>
    </div>
  @endauth
</header>
