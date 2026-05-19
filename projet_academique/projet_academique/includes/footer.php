        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Gestion Académique ISI 2026</div>
                    <div>Projet PHP L2 GL &mdash; Aicha J. Diagne</div>
                </div>
            </div>
        </footer>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="<?= $dossier_public ?>js/scripts.js"></script>
    <script>
        // Activer DataTables
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.datatable');
            tables.forEach(function(t) { new simpleDatatables.DataTable(t); });

            // Toggle sidebar
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                });
            }
        });
    </script>
</html>
