<footer class="footer py-4">
  <div class="container-fluid">
    <div class="copyright text-center text-sm text-muted">
      © {{ date('Y') }}
      <strong>
        <a href="{{ config('etablissement.site') }}" target="_blank" rel="noopener" class="text-decoration-none">{{ config('etablissement.nom') }} - {{ config('etablissement.ville') }}</a>
      </strong>
      · Tous droits réservés.
    </div>
  </div>
</footer>
