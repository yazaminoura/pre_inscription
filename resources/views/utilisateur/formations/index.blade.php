@extends('utilisateur.Layouts.app')
@section('title', 'Les Formations')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="border-radius-lg pt-4 pb-3" style="background-color: #1a4b8c; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <h6 class="text-white text-capitalize ps-3">Liste des Formations</h6>
                    </div>
                </div>
                
                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-end mx-3 mb-3">
                        <a href="{{ route('formations.create') }}" class="btn btn-sm text-white" style="background-color: #1a4b8c;">
                            <i class="material-symbols-rounded me-1">add</i>
                            <span>Ajouter une formation</span>
                        </a>
                    </div>
                    
                    @php
                        $placeholder = 'Rechercher un Formation...'; 
                    @endphp
                    <div class="table-responsive p-3">
                        <table id="searshTable" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Titre</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date Début de Pre Insciption</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Date Fin de Pre Insciption</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Responsable</th>
                                    <th class="text-secondary opacity-7 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($formations as $formation)
                                <tr>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $formation->type_formation }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $formation->titre }}</p>
                                    </td>
                                    <td>           
                                        <p class="text-xs font-weight-bold mb-0">{{ $formation->date_debut }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $formation->date_fin }}</p>
                                    </td>
                                   <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $formation->user->name ?? 'Non défini' }}
                                        </p>
                                    </td>
                                    <td class="align-center text-end pe-4">
                                         <a href="{{ route('formations.edit', $formation->id) }}" class="btn btn-link text-info p-0">
                                            <i class="material-symbols-rounded">edit</i>
                                        </a>
                                        <form id="delete-form-{{ $formation->id }}" 
                                            action="{{ route('formations.destroy', $formation->id) }}" 
                                            method="POST" 
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')                                            
                                            <button type="button" 
                                                onclick="confirmDelete({{ $formation->id }}, this, 'cette formation')" 
                                                class="btn btn-link text-danger font-weight-bold text-xs p-0" >
                                                <i class="material-symbols-rounded">delete</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection