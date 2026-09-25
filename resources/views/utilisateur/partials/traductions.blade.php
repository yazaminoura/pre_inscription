{{--
  Onglets de traduction EN / AR pour des textes saisis en français.
  @include('utilisateur.partials.traductions', ['modele' => $formation, 'champs' => ['titre' => ['Intitulé', 'text'], ...]])
--}}
@php
  $langues = ['en' => ['English', 'ltr'], 'ar' => ['العربية', 'rtl']];
  $id = 'trad-' . uniqid();
  $traductions = old('traductions', $modele?->traductions ?? []);
  $remplis = fn ($l) => collect($traductions[$l] ?? [])->filter(fn ($v) => filled($v))->count();
@endphp
<div class="fieldset-title mt-4">Traductions <span class="optional">· facultatif : si un champ est vide, le site affiche le texte français</span></div>
<ul class="nav nav-pills gap-2 mb-3" role="tablist">
  @foreach ($langues as $code => [$nomLangue, $dir])
    <li class="nav-item" role="presentation">
      <button class="nav-link btn-sm {{ $loop->first ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#{{ $id }}-{{ $code }}" type="button" role="tab">
        {{ $nomLangue }} <span class="badge rounded-pill ms-1 {{ $remplis($code) ? 'text-bg-success' : 'text-bg-light' }}">{{ $remplis($code) }}/{{ count($champs) }}</span>
      </button>
    </li>
  @endforeach
</ul>
<div class="tab-content">
  @foreach ($langues as $code => [$nomLangue, $dir])
    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $id }}-{{ $code }}" role="tabpanel">
      <div class="row g-3">
        @foreach ($champs as $champ => [$libelle, $type])
          <x-champ :name="'traductions[' . $code . '][' . $champ . ']'" :label="$libelle . ' (' . strtoupper($code) . ')'" :type="$type"
                   :value="$traductions[$code][$champ] ?? ''" :dir="$dir" rows="3"
                   :col="$type === 'textarea' ? 'col-12' : 'col-md-6'" :placeholder="\Illuminate\Support\Str::limit((string) ($modele?->$champ ?? ''), 80)" />
        @endforeach
      </div>
    </div>
  @endforeach
</div>
