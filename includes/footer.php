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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
  // DataTables init
  $('.dt').DataTable({ pageLength: 10, language: { search: 'Cari:', lengthMenu: 'Tampil _MENU_', paginate: { previous: '‹', next: '›' }, info: 'Menampilkan _START_-_END_ dari _TOTAL_', infoEmpty: 'Tidak ada data', emptyTable: 'Tidak ada data' } });
  $('#btnSidebar').on('click', function(){
    $('.app-sidebar').toggleClass('open');
    $('#sidebarBackdrop').toggleClass('show');
  });
  $('#sidebarBackdrop').on('click', function(){
    $('.app-sidebar').removeClass('open');
    $(this).removeClass('show');
  });
  // Close sidebar on nav click (mobile)
  $('.app-sidebar .nav-link').on('click', function(){
    if (window.innerWidth < 992) {
      $('.app-sidebar').removeClass('open');
      $('#sidebarBackdrop').removeClass('show');
    }
  });

  // ==== Ganti popup confirm() native dengan SweetAlert2 ====
  document.querySelectorAll('[onclick*="confirm("]').forEach(function(el){
    var m = (el.getAttribute('onclick')||'').match(/confirm\(['"]([^'"]+)['"]\)/);
    if (!m) return;
    var msg = m[1];
    var href = el.getAttribute('href');
    el.removeAttribute('onclick');
    el.addEventListener('click', function(ev){
      ev.preventDefault();
      var isDelete = /hapus|tolak/i.test(msg);
      Swal.fire({
        title: isDelete ? 'Konfirmasi Hapus' : 'Konfirmasi',
        text: msg,
        icon: isDelete ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: isDelete ? '<i class="fa-solid fa-trash"></i> Ya, Lanjutkan' : '<i class="fa-solid fa-check"></i> Ya',
        cancelButtonText: '<i class="fa-solid fa-times"></i> Batal',
        confirmButtonColor: isDelete ? '#dc2626' : '#0f4c81',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        focusCancel: true,
        customClass: { popup: 'siwarga-swal' }
      }).then(function(result){
        if (result.isConfirmed && href) {
          window.location.href = href;
        }
      });
    });
  });

  // ==== Auto-convert Bootstrap alert flash menjadi toast cantik ====
  var flashAlerts = document.querySelectorAll('.content > .alert');
  if (flashAlerts.length) {
    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 3500, timerProgressBar: true,
      didOpen: function(t){ t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
    });
    flashAlerts.forEach(function(a){
      var icon = a.classList.contains('alert-success') ? 'success'
               : a.classList.contains('alert-danger')  ? 'error'
               : a.classList.contains('alert-warning') ? 'warning' : 'info';
      var msg = a.textContent.trim().replace(/\s+/g,' ');
      a.style.display = 'none';
      Toast.fire({ icon: icon, title: msg });
    });
  }
});
</script>
<style>
.siwarga-swal{ border-radius:14px !important; }
.siwarga-swal .swal2-title{ font-size:1.25rem; color:#0f172a; }
.siwarga-swal .swal2-html-container{ color:#475569; }
.swal2-styled{ border-radius:8px !important; padding:8px 18px !important; font-weight:500 !important; }
.swal2-icon{ margin-top:1.2em !important; }
</style>
</body>
</html>
