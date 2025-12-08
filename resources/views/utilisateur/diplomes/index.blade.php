@extends('utilisateur.Layouts.app')
@section('title', 'Les Diplômes')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="border-radius-lg pt-4 pb-3" style="background-color: #1a4b8c; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h6 class="text-white text-capitalize ps-3">Liste des Diplômes</h6>
                    </div>
                </div>
                
                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-end mx-3 mb-3">
                        <a href="{{ route('diplomes.create') }}" class="btn btn-sm text-white" style="background-color: #1a4b8c;">
                            <i class="material-symbols-rounded me-1">add</i>
                            <span>Ajouter un diplôme</span>
                        </a>
                    </div>
                    @php
                        $placeholder = 'Rechercher un   diplôme...';
                    @endphp
                    
                    <div class="table-responsive p-3">
                        <table id="searshTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Candidat</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Type Bac+2</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Année</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Filière</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Établissement</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Scan</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Type Bac+3</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Année</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Filière</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Établissement</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Scan</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diplomes as $diplome)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->candidat->nom ?? 'Inconnu' }} {{ $diplome->candidat->prenom ?? '' }}</p>
                                    </td>
                                    <td>

            
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'type_diplome_bac_2'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'annee_diplome_bac_2'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'filiere_diplome_bac_2'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'etablissement_bac_2'} }}</p>
                                    </td>
                                    <td>
                                        @if ($diplome->{'scan_bac_2'})
                                        <a href="{{ asset('storage/' . $diplome->{'scan_bac_2'}) }}" 
                                           target="_blank" 
                                           class="badge badge-sm text-white" 
                                           style="background-color: #1a4b8c; padding: 4px 8px;">
                                           Voir
                                        </a>
                                        @else
                                        <p class="text-xs text-secondary mb-0">Aucun</p>
                                        @endif
                                    </td>
                                    <td>
                                      
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'type_diplome_bac_3'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'annee_diplome_bac_3'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'filiere_diplome_bac_3'} }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $diplome->{'etablissement_bac_3'} }}</p>
                                    </td>
                                    <td>
                                        @if ($diplome->{'scan_bac_3'})
                                        <a href="{{ asset('storage/' . $diplome->{'scan_bac_3'}) }}" 
                                           target="_blank" 
                                           class="badge badge-sm text-white" 
                                           style="background-color: #1a4b8c; padding: 4px 8px;">
                                           Voir
                                        </a>
                                        @else
                                        <p class="text-xs text-secondary mb-0">Aucun</p>
                                        @endif
                                    </td>
                                    <td class="align-center text-end pe-4">
                                        <form id="delete-form-{{ $diplome->id }}" 
                                            action="{{ route('diplomes.destroy', $diplome->id) }}" 
                                            method="POST" 
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete({{ $diplome->id }}, this, 'ce diplôme')" 
                                                    class="btn btn-link text-danger font-weight-bold text-xs p-0">
                                                <i class="material-symbols-rounded">delete</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                
                                @if ($diplomes->isEmpty())
                                <tr>
                                    <td colspan="13" class="text-center text-muted py-4">Aucun diplôme trouvé</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection