
<!-- Existing scripts -->
<script src="dist/assets/js/core/popper.min.js"></script>
<script src="dist/assets/js/core/bootstrap.min.js"></script>
<script src="dist/assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="dist/assets/js/plugins/smooth-scrollbar.min.js"></script>

<!-- Add these new script dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Material Dashboard JS -->
<script src="{{ asset('dist/assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>

{{-- toastr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "timeOut": "1500"
        
    };
</script>




<!-- Your custom scripts -->
<script>
    // Initialize scrollbar (existing code)
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

    // Initialize DataTables with Material Dashboard styling
   // Global delete confirmation with SweetAlert
   // Github buttons (existing)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof GithubButtons !== 'undefined') {
            GithubButtons.init();
        }
    });
</script>
{{-- for the searsh  --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function () {
        $('#searshTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "{{ $placeholder ?? 'Rechercher...' }}"
            },
            dom: '<"d-flex justify-content-start align-items-center mb-3"f>t',
        });

        const $searchInput = $('.dataTables_filter input');
        
        $searchInput
            .addClass('form-control ps-3 shadow-sm custom-search')
            .css({
                'width': '400px',
                'border-radius': '12px',
                'transition': 'all 0.3s ease'
            });

        $('.dataTables_filter label').contents().filter(function() {
            return this.nodeType === 3;
        }).remove();
    });
</script>


<script>
/**
 * Global delete confirmation
 * @param {number} id - Item ID
 * @param {HTMLElement} button - Clicked button
 * @param {string} [itemName] - Custom item name for confirmation text
 */
function confirmDelete(id, button, itemName = 'cet élément') {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Voulez-vous vraiment supprimer ${itemName} ? Cette action est irréversible !`,
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor:'#d33', 
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = button.closest('form');
            button.disabled = true;
            button.innerHTML = '<i class="material-symbols-rounded">hourglass_top</i>';
            form.submit();
        }
    });
}

</script>


<script>
document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
  const sidebar = document.getElementById('sidenav-main');
  const sidebar2 = document.getElementById('nsidenav-main');
  sidebar.classList.toggle('d-none'); // Hide/show sidebar
    sidebar2.classList.toggle('d-none'); // Hide/show sidebar

  
  // Toggle content width
  document.getElementById('main-content').classList.toggle('content-full-width');
});
</script>
{{-- for the menu  --}}

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('toggleSidebarBtn');
      const sidebar = document.getElementById('sidenav-main');
      const overlay = document.querySelector('.sidebar-overlay');

      if (window.innerWidth <= 900) {
        sidebar.classList.remove('sidebar-visible');
      }

      function toggleSidebar() {
        sidebar.classList.toggle('sidebar-visible');
        overlay.style.display = sidebar.classList.contains('sidebar-visible') ? 'block' : 'none';
      }

      toggleBtn?.addEventListener('click', toggleSidebar);
      overlay?.addEventListener('click', toggleSidebar);

      window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
          sidebar.classList.add('sidebar-visible');
          overlay.style.display = 'none';
        }
      });
    });
  </script>







<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Confirm logout function
    window.confirmLogout = function() {
      const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
      logoutModal.show();
    };

    // Perform logout function
    window.performLogout = function() {
      document.getElementById('logout-form').submit();
    };
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- copy le cnadidats email  --}}
<script>
function copyEmail(email) {
    navigator.clipboard.writeText(email).then(() => {
        alert('Email copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy email:', err);
    });
}
</script>
