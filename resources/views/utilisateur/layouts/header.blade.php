<nav id="navbarBlur" class="navbar navbar-main navbar-expand-lg px-3 mx-3 shadow-none border-radius-xl" style="background: white; margin-top: 5px; margin-bottom: 15px;" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <button id="toggleSidebarBtn" class="btn btn-link text-dark">
        <i class="material-symbols-rounded" id="menuIcon">menu</i>
      </button>
    </nav>
    
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
      
      <ul class="navbar-nav d-flex align-items-center justify-content-end">
        <li class="nav-item d-flex align-items-center">
          <a href="#" class="nav-link text-body font-weight-bold px-2 d-flex align-items-center" style="color: #344767;" onclick="confirmLogout()">
            @auth
              <span>{{ Auth::user()->name }}</span> 
            @endauth
            <i class="material-symbols-rounded ms-1" style="font-size: 28px; cursor: pointer; color: #344767;">account_circle</i>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px;">
      <div class="modal-body text-center p-4">
        <i class="material-symbols-rounded text-warning" style="font-size: 48px;">logout</i>
        <h5 class="mt-3">Êtes-vous sûr de vouloir vous déconnecter?</h5>
      </div>
      <div class="modal-footer justify-content-center border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Annuler</button>
        <button type="button" class="btn btn-warning px-4 text-white" onclick="performLogout()" style="border-radius: 8px;">Déconnexion</button>
      </div>
    </div>
  </div>
</div>
