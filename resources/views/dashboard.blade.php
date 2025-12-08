@extends('utilisateur.Layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="row mb-4"> {{-- Added mb-4 for spacing below the header --}}
        <div class="col-lg-12"> {{-- Ensured the header takes full width --}}
            <h3 class="mb-0 h4 font-weight-bolder" style="color: #1a4b8c">Tableau de Bord</h3>
            <p class="mb-0" style="margin-bottom: 22px ;color: black "> {{-- Used text-secondary for lighter text color --}}
                Aperçu des statistiques et activités récentes.
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row" style="margin-top: 53px">
        {{-- Formation Card --}}
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2"> {{-- Adjusted padding to match screenshot more closely --}}
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute"> {{-- Larger icon, absolute positioning, and larger border radius --}}
                        <i class="material-symbols-rounded opacity-10">school</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Formations</p>
                        <h4 class="mb-0">{{ $stats['total_formations'] ?? 0 }}</h4> {{-- Added null coalescing for safety --}}
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3"> {{-- Adjusted padding --}}
                    <p class="mb-0 text-sm">
                        <span class="text-success text-sm font-weight-bolder">+{{ $stats['today_inscriptions'] ?? 0 }}</span> inscriptions aujourd'hui
                    </p>
                </div>
            </div>
        </div>

        {{-- Administrateurs Card --}}
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">admin_panel_settings</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Administrateurs</p>
                        <h4 class="mb-0">{{ $stats['total_admins'] ?? 0 }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    <p class="mb-0 text-sm">Administrateurs système</p>
                </div>
            </div>
        </div>

       
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                        <i class="material-symbols-rounded opacity-10">assignment</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Inscriptions</p>
                        <h4 class="mb-0">{{ $stats['total_inscriptions'] ?? 0 }}</h4>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-3">
                    @php
                        $todayInscriptions = $stats['today_inscriptions'] ?? 0;
                        $lastweekInscriptions = $stats['lastweek_inscriptions'] ?? 0;
                        $change = 0;
                        if ($lastweekInscriptions > 0) {
                            $change = round(($todayInscriptions - $lastweekInscriptions) / $lastweekInscriptions * 100);
                        }
                        $isPositive = $change >= 0;
                    @endphp
                    <p class="mb-0 text-sm">
                        <span class="{{ $isPositive ? 'text-success' : 'text-danger' }} text-sm font-weight-bolder">
                            {{ $isPositive ? '+' : '' }}{{ $change }}%
                        </span> vs semaine dernière
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Section --}}
    <div class="row mt-4">
        {{-- Recent Formations --}}
        <div class="col-lg-12 col-md-12 mb-4" style="margin-bottom: 93px">
            <div class="card h-100" >
                <div class="card-header pb-0 p-3" style="color: #1a4b8c;"> 
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="color: #1a4b8c;">Dernières Formations</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ $recentFormations->count() ?? 0 }} ajoutées</span> récemment
                        </p>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Titre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Inscriptions</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentFormations as $formation)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $formation->titre }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $formation->type_formation == 'Licence' ? 'info' : 'success' }}">
                                            {{ $formation->type_formation }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $formation->inscriptions_count ?? 0 }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune formation récente trouvée.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Inscriptions --}}
        <div class="col-lg-12 col-md-12 mb-4" style="margin-top: 53px;">
            <div class="card h-100">
                <div class="card-header pb-0 p-3"  style="color:  #1a4b8c;"> 
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"  style="color: #1a4b8c;">Dernières Inscriptions</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ $recentInscriptions->count() ?? 0 }} nouvelles</span> inscriptions
                        </p>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Candidat</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Formation</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Ville</th>
                                </tr>
                            </thead>
                            <tbody>
                               @forelse($recentInscriptions as $inscription)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $inscription->candidat?->photo ? asset('storage/'.$inscription->candidat->photo) : asset('assets/img/default-user.png') }}" 
                                                    class="avatar avatar-sm me-3" 
                                                    alt="{{ $inscription->candidat?->prenom ?? 'Candidat' }}">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">
                                        @if($inscription->candidat)
                                            {{ $inscription->candidat->nom }} {{ $inscription->candidat->prenom }}
                                        @else
                                            N/A
                                        @endif
                                    </h6>
                                    <p class="text-xs text-secondary mb-0">
                                        {{ $inscription->candidat->email ?? 'N/A' }}
                                    </p>
                                </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ optional($inscription->formation)->titre ?? 'Formation inconnue' }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0">
                                            {{ optional($inscription->formation)->type_formation ?? '' }}
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $inscription->created_at->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                         {{ $inscription->candidat->ville ?? 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune inscription récente trouvée.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    // Charts initialization code would go here
    // Same as in your original file, if any.
</script>
@endpush
@endsection