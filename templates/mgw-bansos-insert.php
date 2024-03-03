<?php 
global $wpdb;
$kpm_table = $wpdb->prefix . 'mgw_kpm_bansos';
$keluarga_table = $wpdb->prefix . 'mgw_keluarga';
$banjar_table = $wpdb->prefix . 'mgw_ref_banjar';
$bansos_table = $wpdb->prefix . 'mgw_ref_bansos';

$query = "SELECT
          $keluarga_table.nomor_kk,
          $kpm_table.nik
          
          FROM
          $kpm_table

          JOIN $keluarga_table USING (kk_id)
          ";

$kpmTerdaftar = $wpdb -> get_results ($query);

$error = false;
$passed = false;

if (isset($_POST["verifyBtn"])) {
  foreach ($kpmTerdaftar as $kpm) {
    if ( $_POST["verifyNIK"] == $kpm -> nik ) {
      $error = true;
      break;
    }
  }
}

if (!$error) {
  $passed = true;
}


?>


<!-- INSERT CONTENT -->
<div class="wrap">
  <div class="mb-3">
    <h2>Tambah KPM</h2>
  </div>
  <form action="" method="post">
    <div class="mb-1">
      <label for="verifyNIK">Cek NIK</label>
    </div>
    <div class="mb-1">
      <input type="text" name="verifyNIK" id="verifyNIK">
    </div>
    <button class="btn btn-primary" type="submit" name="verifyBtn">Cek NIK</button>
  </form>
</div>

<!-- Bootstrap Modal for Error -->
<!-- Modal -->
<div class="modal fade" id="errorKpm" tabindex="-1" aria-labelledby="errorKpmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorKpm">Sudah Terdaftar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        NIK sudah terdaftar!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mengerti</button>
      </div>
    </div>
  </div>
</div>

<?php if ($error) : ?>
  <script>
    // Show the Bootstrap modal if an error occurs
    
    jQuery(document).ready(function ($) {
      // Your jQuery code here
      $('#errorKpm').modal('show');
    });
  </script>
<?php endif; ?>


<!-- INSERT CONTENT END-->