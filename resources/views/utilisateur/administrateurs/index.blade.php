@extends('utilisateur.layouts.app')
@section('title', 'Administrateurs')

@section('content')
<div class="page-head">
  <div>
    <h2>Administrateurs</h2>
    <p>Les personnes qui peuvent se connecter à l'espace d'administration.</p>
  </div>
  <a href="{{ route('administrateurs.create') }}" class="btn btn-brand">
    <span class="material-symbols-rounded">person_add</span> Nouvel administrateur
  </a>
</div>

<div class="panel">
  <div class="table-responsive">
    <table class="table table-clean">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Créé le</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr class="row-link" data-href="{{ route('administrateurs.edit', $user) }}">
            <td>
              <div class="person">
                <span class="avatar-initials">{{ mb_strtoupper(mb_substr(trim($user->name), 0, 1)) }}</span>
                <div>
                  <div class="person-name">{{ $user->name }}</div>
                  @if ($user->id === auth()->id())<div class="person-sub">C'est vous</div>@endif
                  @if ($user->id === 1)<div class="person-sub">Administrateur principal</div>@endif
                </div>
              </div>
            </td>
            <td>{{ $user->email }}</td>
            <td class="text-muted">{{ $user->created_at?->format('d/m/Y') }}</td>
            <td class="text-end text-nowrap">
              <a href="{{ route('administrateurs.edit', $user) }}" class="btn btn-sm btn-icon btn-soft" title="Modifier">
                <span class="material-symbols-rounded">edit</span>
              </a>
              @if ($user->id !== 1 && $user->id !== auth()->id())
                <form action="{{ route('administrateurs.destroy', $user) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-icon btn-outline-danger" title="Supprimer"
                          onclick="confirmDelete({{ $user->id }}, this, 'le compte de {{ addslashes($user->name) }}')">
                    <span class="material-symbols-rounded">delete</span>
                  </button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
