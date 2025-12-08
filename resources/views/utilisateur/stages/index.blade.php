@extends('utilisateur.Layouts.app')
@section('title', 'Les Stages')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="border-radius-lg pt-4 pb-3" style="background-color: #1a4b8c; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h6 class="text-white text-capitalize ps-3">Liste des Stages</h6>
                    </div>
                </div>                
                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-end mx-3 mb-3">
                        <a href="{{ route('stages.create') }}" class="btn btn-sm text-white" style="background-color: #1a4b8c;">
                            <i class="material-symbols-rounded me-1">add</i>
                            <span>Ajouter un stage</span>
                        </a>
                    </div>
                     @php
                        $placeholder = 'Rechercher un Stages...'; 
                    @endphp
                    <div class="table-responsive p-3">
                        <table id="searshTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Candidat</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Fonction</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Période</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Attestation</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Établissement</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stages as $stage)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $stage->id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $stage->candidat->nom ?? 'N/A' }} {{ $stage->candidat->prenom ?? '' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $stage->fonction }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $stage->periode }}</p>
                                    </td>
                                    <td>
                                        @if ($stage->attestation)
                                        <a href="{{ asset('storage/' . $stage->attestation) }}" 
                                           target="_blank" 
                                           class="badge badge-sm text-white" 
                                           style="background-color: #1a4b8c; padding: 4px 8px;">
                                           Voir
                                        </a>
                                        @else
                                        <span class="badge badge-sm bg-secondary">Aucune</span>
                                        @endif
                                    </td>                                
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $stage->etablissement }}</p>
                                    </td>
                                    <td class="align-center text-end pe-4">                                       
                                        <form id="delete-form-{{ $stage->id }}" 
                                            action="{{ route('stages.destroy', $stage->id) }}" 
                                            method="POST" 
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    onclick="confirmDelete({{ $stage->id }}, this, 'ce stage')" 
                                                    class="btn btn-link text-danger p-0">
                                                <i class="material-symbols-rounded">delete</i>
                                            </button>
                                        </form>                     
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Aucun stage trouvé</td>
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
@endsection
