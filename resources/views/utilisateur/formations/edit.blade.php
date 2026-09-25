@extends('utilisateur.layouts.app')
@section('title', 'Modifier la formation')

@section('content')
<div class="mb-3">
  <a href="{{ route('formations.index') }}" class="text-decoration-none fw-semibold">
    <span class="material-symbols-rounded">arrow_back</span> Formations
  </a>
</div>

<div class="panel" style="max-width: 760px;">
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
