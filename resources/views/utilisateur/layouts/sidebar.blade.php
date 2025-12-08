
<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2">
   <div class="sidenav-header text-center py-4 d-flex flex-column align-items-center justify-content-center">
    <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    
    <div class="d-flex flex-column align-items-center">
  <a href="{{ route('dashboard') }}" style="text-decoration: none;">
    <img 
      src="{{ asset('dist/assets/img/logo-fsdm-fes.png') }}" 
      class="navbar-brand-img mb-2" 
      style="max-height: 50px; width: auto;"  
      alt="main_logo">
    <p class="font-weight-bold mb-0" style="color: red; font-size: 0.9rem;"> <!-- Smaller font -->
      Pré-Inscription
      <span style="color: green; font-size: 0.85rem; font-weight: bold;">FST FES</span> <!-- Changed <P> to <span> -->
    </p>
  </a>
</div>
  </div>

  <hr class="horizontal dark mt-0 mb-2" >
  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-dark" href="{{ route('administrateurs.index') }}">
          <i class="material-symbols-rounded opacity-5">admin_panel_settings</i>
          <span class="nav-link-text ms-1">Administrateurs</span>
        </a>
      </li> 
      <li class="nav-item">
        <a class="nav-link text-dark " href="{{ route('formations.index') }}">
          <i class="material-symbols-rounded opacity-5">receipt_long</i>          <span class="nav-link-text ms-1">Formations</span>
        </a>
      </li> 
      <li class="nav-item">
        <a class="nav-link text-dark  " href="{{ route('candidats.index') }}">
          <i class="material-symbols-rounded opacity-5">group</i>
          <span class="nav-link-text ms-1">Candidats</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark " href="{{ route('formation-stats') }}">
          <i class="material-symbols-rounded opacity-5">bar_chart</i>
          <span class="nav-link-text ms-1">Statistiques des Formations</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark " href="{{ route('diplomes.index') }}">
          <i class="material-symbols-rounded opacity-5">school</i>
          <span class="nav-link-text ms-1">Diplômes</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark " href="{{ route('experiences.index') }}">
          <i class="material-symbols-rounded opacity-5">work</i>
          <span class="nav-link-text ms-1">Expériences</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark " href="{{ route('stages.index') }}">
          <i class="material-symbols-rounded opacity-5">business_center</i>
          <span class="nav-link-text ms-1">Stages</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-dark  " href="{{ route('attestations.index') }}">
          <i class="material-symbols-rounded opacity-5">description</i>
          <span class="nav-link-text ms-1">Attestations</span>
        </a>
      </li>
    </ul>
  </div>
</aside>
