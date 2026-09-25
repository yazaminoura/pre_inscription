<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

<script>
  function toggleSidebar() {
    document.getElementById('adminShell').classList.toggle('sidebar-open');
  }

  toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-bottom-right', timeOut: 3000 };
  @if (session('toastr'))
    toastr[@json(session('toastr.type'))](@json(session('toastr.message')));
  @endif

  // Tableaux avec recherche / tri / pagination : ajouter la classe .js-datatable
  $(function () {
    $('.js-datatable').each(function () {
      $(this).DataTable({
        pageLength: 25,
        order: [],
        columnDefs: [{ targets: 'no-sort', orderable: false }],
        language: {
          search: '',
          searchPlaceholder: 'Rechercher…',
          lengthMenu: '_MENU_ par page',
          info: '_START_ à _END_ sur _TOTAL_',
          infoEmpty: 'Aucun résultat',
          infoFiltered: '(filtré sur _MAX_)',
          zeroRecords: 'Aucun résultat',
          emptyTable: 'Aucune donnée',
          paginate: { previous: '‹', next: '›' }
        },
        dom: '<"d-flex flex-wrap justify-content-between align-items-center"fl>t<"d-flex flex-wrap justify-content-between align-items-center"ip>'
      });
    });

    // Ligne cliquable -> ouvre le lien data-href (sauf clic sur un bouton/lien)
    $(document).on('click', 'tr[data-href]', function (e) {
      if (!$(e.target).closest('a, button, form, input, select').length) {
        window.location = $(this).data('href');
      }
    });
  });

  /**
   * Confirmation de suppression commune
   */
  function confirmDelete(id, button, itemName = 'cet élément') {
    Swal.fire({
      title: 'Supprimer ?',
      text: `Voulez-vous vraiment supprimer ${itemName} ? Cette action est irréversible.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#c62828',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if (result.isConfirmed) {
        button.disabled = true;
        button.closest('form').submit();
      }
    });
  }
</script>
