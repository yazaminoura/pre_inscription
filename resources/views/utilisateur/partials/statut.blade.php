{{-- Badge de statut d'une inscription : @include('utilisateur.partials.statut', ['inscription' => $i]) --}}
@if ($inscription)
  <span class="status-badge" style="color: {{ $inscription->statut_color }}; background: {{ $inscription->statut_color }}1a;">{{ $inscription->statut_label }}</span>
@endif
