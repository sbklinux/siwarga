  </div>
  <footer class="app-footer">
    <span>© <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?></span>
    <span class="text-muted">Login sebagai <b><?= e(user()['nama']) ?></b> (<?= e(role_label(role())) ?>)</span>
  </footer>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
$(function(){
  $('.dt').DataTable({ pageLength: 10, language: { search: 'Cari:', lengthMenu: 'Tampil _MENU_', paginate: { previous: '‹', next: '›' }, info: 'Menampilkan _START_-_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', emptyTable: 'Tidak ada data' } });
  $('#btnSidebar').on('click', () => $('.app-sidebar').toggleClass('open'));
});
</script>
</body>
</html>
