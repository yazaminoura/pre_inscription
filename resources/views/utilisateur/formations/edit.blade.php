@extends('utilisateur.layouts.app')
@section('title', 'Modifier la formation')

@section('content')
<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
  <a href="{{ route('formations.index') }}" class="text-decoration-none fw-semibold me-auto">
    <span class="material-symbols-rounded">arrow_back</span> Formations
  </a>
  <a href="{{ route('candidats.index', ['formation' => $formation->id]) }}" class="btn btn-sm btn-soft">
    <span class="material-symbols-rounded">folder_shared</span> Candidatures ({{ $formation->inscriptions()->count() }})
  </a>
  @if (today()->lte(\Carbon\Carbon::parse($formation->date_fin)))
    <a href="{{ route('formation.public', $formation) }}" target="_blank" class="btn btn-sm btn-light border">
      <span class="material-symbols-rounded">open_in_new</span> Fiche publique
    </a>
  @endif
</div>

<div class="panel">
  <div class="panel-head"><h3><span class="material-symbols-rounded">edit</span> {{ $formation->titre }}</h3></div>
  <div class="panel-body">
    <form action="{{ route('formations.update', $formation) }}" method="POST">
      @csrf
      @method('PUT')
      @include('utilisateur.formations._form')
    </form>
  </div>
</div>
@endsection
