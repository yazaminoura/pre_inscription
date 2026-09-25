@extends('utilisateur.layouts.app')
@section('title', 'Nouvelle formation')

@section('content')
<div class="mb-3">
  <a href="{{ route('formations.index') }}" class="text-decoration-none fw-semibold">
    <span class="material-symbols-rounded">arrow_back</span> Formations
  </a>
</div>

<div class="panel" style="max-width: 880px;">
  <div class="panel-head"><h3><span class="material-symbols-rounded">add_circle</span> Nouvelle formation</h3></div>
  <div class="panel-body">
    <form action="{{ route('formations.store') }}" method="POST">
      @csrf
      @include('utilisateur.formations._form')
    </form>
  </div>
</div>
@endsection
