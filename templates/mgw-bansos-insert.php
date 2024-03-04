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
$listBanjar = $wpdb -> get_results ("SELECT * FROM $banjar_table");
$listBansos = $wpdb -> get_results ("SELECT * FROM $bansos_table");

// INSERT LOGIC
if ( isset($_POST["mgw-bansos-insert-button"]) ) {
  // ambil data dikirim dan simpan ke variabel
  $submitted_nomor_kk = sanitize_text_field($_POST['mgw-bansos-insert-nomor_kk']);
  $submitted_nik = sanitize_text_field($_POST['mgw-bansos-insert-nik']);
  $submitted_nama = sanitize_text_field($_POST['mgw-bansos-insert-nama']);
  $submitted_alamat = sanitize_text_field($_POST['mgw-bansos-insert-alamat']);
  $submitted_bantuan = sanitize_text_field($_POST['mgw-bansos-insert-bantuan']);
  // cek apakah nomor kk sudah terdaftar ?
  $existing_nomor_kk = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $keluarga_table WHERE nomor_kk = %s", $submitted_nomor_kk));
  // cek apakah nik sudah terdaftar ?
  $existing_nik = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $kpm_table WHERE nik = %s", $submitted_nik));
  
  if ($existing_nomor_kk > 0) {
    $error = true;
  } elseif ($existing_nik > 0) {
    $error = true;
  } else {
    // Nomor KK and NIK are not registered, proceed with the insertion
    
    $insertKeluarga = array (
      'nomor_kk' => $submitted_nomor_kk,
      'nama_kk' => $submitted_nama
    );
    $wpdb->insert($keluarga_table, $insertKeluarga);
    $submitted_kk_id = $wpdb->get_var($wpdb->prepare("SELECT kk_id FROM $keluarga_table WHERE nomor_kk = %s", $submitted_nomor_kk));
    
    $insertKPM = array(
      'kk_id' => $submitted_kk_id,
      'nik' => $submitted_nik,
      'nama' => $submitted_nama,
      'banjar_id' => $_POST['mgw-bansos-insert-alamat'], // Assuming 'mgw-bansos-insert-alamat' contains banjar_id
      'bansos_id' => $submitted_bantuan
    );
    $wpdb->insert($kpm_table, $insertKPM);

    // Redirect or display success message, as needed
    // wp_redirect( 'URL_TO_REDIRECT_TO' );
    // exit;

 }
}

?>
<!-- INSERT CONTENT -->
<div class="container-fluid" style="width: 50%; float: left;">
  <h2>Tambah KPM</h2>
  <form action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" method="post">
    <div class="row">
      <div class="col">
        <!-- Insert Nomor KK -->
        <div class="mb-3">
          <label for="mgw-bansos-insert-nomor_kk" class="form-label">Nomor KK</label>
          <input type="text" class="form-control" name="mgw-bansos-insert-nomor_kk" id="mgw-bansos-insert-nomor_kk">
        </div>
      </div>
      <div class="col">
        <!-- Insert NIK -->
        <div class="mb-3">
          <label for="mgw-bansos-insert-nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
          <input type="text" class="form-control" name="mgw-bansos-insert-nik" id="mgw-bansos-insert-nik">
        </div>
      </div>
    </div>
    <!-- Insert Nomor KK -->
    <div class="mb-3">
      <label for="mgw-bansos-insert-nama" class="form-label">Nama</label>
      <input type="text" class="form-control" name="mgw-bansos-insert-nama" id="mgw-bansos-insert-nama">
    </div>
    <!-- Insert Alamat -->
    <div class="mb-3">
      <label for="mgw-bansos-insert-alamat" class="form-label">Alamat</label>
      <table>
        <tr class="row">
          <td class="col">
            <label class="form-label">Banjar</label>
            <select class="form-select" aria-label="Default select example" name="mgw-bansos-insert-alamat" id="mgw-bansos-insert-alamat">
              <option selected>- Pilih Banjar -</option>
              <?php foreach ($listBanjar as $banjar) : ?>
              <option value="<?= $banjar -> banjar_id; ?>" name="mgw-bansos-insert-alamat" ><?= $banjar -> banjar; ?></option>
              <?php endforeach ?>
            </select>
          </td>
          <td class="col">
            <label class="form-label">Desa</label>
            <input type="text" id="disabledTextInput" class="form-control" disabled placeholder="Mengwi">
          </td>
          <td class="col">
            <label class="form-label">Kecamatan</label>
            <input type="text" id="disabledTextInput" class="form-control" disabled placeholder="Mengwi">
          </td>
          <td class="col">
            <label class="form-label">Kabupaten</label>
            <input type="text" id="disabledTextInput" class="form-control" disabled placeholder="Badung">
          </td>
        </tr>
      </table>
    </div>
    <!-- Insert Bansos -->
    <div class="mb-3">
      <label for="mgw-bansos-insert-bantuan" class="form-label">Bantuan Didaftarkan</label>
      <select class="form-select" aria-label="Default select example" name="mgw-bansos-insert-bantuan" id="mgw-bansos-insert-bantuan">
        <option selected>- Pilih Bantuan -</option>
        <?php foreach ($listBansos as $bansos) : ?>
          <option value="<?= $bansos -> bansos_id ?>" name="mgw-bansos-insert-bantuan" > <?= $bansos -> bansos ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <!-- submit button -->
    <button type="submit" name="mgw-bansos-insert-button" class="btn btn-primary">Tambah</button>
  </form>

</div>

<?php if (isset($error) && $error == true) : ?>
  <script>

    // Show the Bootstrap modal if an error occurs
    jQuery(document).ready(function ($) {
      // Your jQuery code here
      $('#errorKpm').modal('show');
    });
  </script>
<?php endif; ?>


<!-- INSERT CONTENT END-->