{{-- Photo du candidat ou ses initiales : @include('utilisateur.partials.avatar', ['candidat' => $c, 'size' => 40]) --}}
@php $size = $size ?? 40; @endphp
@if ($candidat->photo && \Illuminate\Support\Facades\Storage::disk('dossiers')->exists($candidat->photo))
  <img src="{{ route('documents.voir', $candidat->photo) }}" class="avatar-photo" style="width: {{ $size }}px; height: {{ $size }}px;" alt="">
@else
  <span class="avatar-initials" style="width: {{ $size }}px; height: {{ $size }}px; font-size: {{ $size / 2.6 }}px;">{{ mb_strtoupper(mb_substr($candidat->prenom, 0, 1) . mb_substr($candidat->nom, 0, 1)) }}</span>
@endif
