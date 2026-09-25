{{-- Champ de formulaire en grille : <x-champ name="nom" label="Nom" :value="$data['nom'] ?? ''" required /> --}}
@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'col' => 'col-md-6',
    'placeholder' => '',
    'aide' => null,
    'dir' => null,
    'options' => null,
])
@php
    $id = str_replace(['[', ']', '.'], ['_', '', '_'], $name);
    $cleErreur = str_replace(['[', ']'], ['.', ''], $name);
    $valeur = old($cleErreur, $value);
@endphp
<div class="{{ $col }}" @if ($dir) dir="{{ $dir }}" @endif>
    <label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="req">*</span>@endif</label>
    @if ($options !== null)
        <select name="{{ $name }}" id="{{ $id }}" class="form-select @error($cleErreur) is-invalid @enderror" @if ($required) required @endif {{ $attributes }}>
            <option value="">{{ $placeholder ?: __('Choisir…') }}</option>
            @foreach ($options as $cle => $libelle)
                <option value="{{ $cle }}" @selected((string) $valeur === (string) $cle)>{{ $libelle }}</option>
            @endforeach
        </select>
    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $attributes->get('rows', 2) }}" class="form-control @error($cleErreur) is-invalid @enderror" placeholder="{{ $placeholder }}" @if ($required) required @endif {{ $attributes->except('rows') }}>{{ $valeur }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $type === 'file' ? '' : $valeur }}"
               class="form-control @error($cleErreur) is-invalid @enderror" placeholder="{{ $placeholder }}"
               @if ($required) required @endif {{ $attributes }}>
    @endif
    @if ($aide)<div class="form-text-soft">{{ $aide }}</div>@endif
    {{ $slot }}
    @error($cleErreur)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
