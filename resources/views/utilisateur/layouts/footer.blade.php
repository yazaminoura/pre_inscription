<footer class="admin-footer">
  © {{ date('Y') }}
  @if (config('etablissement.site'))
    <a href="{{ config('etablissement.site') }}" target="_blank" rel="noopener" class="text-decoration-none fw-semibold">{{ config('etablissement.nom') }}</a>
  @else
    <span class="fw-semibold">{{ config('etablissement.nom') }}</span>
  @endif
  · Tous droits réservés.
</footer>
