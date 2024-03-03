<?php 
// Include the database connection file
global $wpdb;

// Your table names
$kpm_table = $wpdb->prefix . 'mgw_kpm_bansos';
$keluarga_table = $wpdb->prefix . 'mgw_keluarga';
$banjar_table = $wpdb->prefix . 'mgw_ref_banjar';
$bansos_table = $wpdb->prefix . 'mgw_ref_bansos';

// SQL query to join tables and fetch data
$sql_query = "
    SELECT
        $keluarga_table.nomor_kk,
        $kpm_table.nik,
        $kpm_table.nama,
        CONCAT('Br. ', $banjar_table.banjar, ', Mengwi') AS banjar,
        $bansos_table.bansos
    FROM $kpm_table
    JOIN $keluarga_table USING (kk_id)
    JOIN $banjar_table USING (banjar_id)
    JOIN $bansos_table USING (bansos_id)
    ORDER BY $bansos_table.bansos_id;
";

$results = $wpdb->get_results($sql_query);



?>

<!-- front -->
<div class="wrap">
    <h2> KPM Bansos Desa Mengwi</h2>
    <p>Daftar Keluarga Penerima Manfaat Bantuan Sosial di Desa Mengwi</p>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nomor KK</th>
          <th scope="col">NIK</th>
          <th scope="col">Nama Nasabah</th>
          <th scope="col">Alamat</th>
          <th scope="col">Bansos</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        foreach ($results as $result) : 
        ?>
        <tr>
          <th scope="row"><?= $i++ ?></th>
          <td><?= $result -> nomor_kk; ?></td>
          <td><?= $result -> nik; ?></td>
          <td><?= $result -> nama; ?></td>
          <td><?= $result -> banjar; ?></td>
          <td><?= $result -> bansos; ?></td>
        </tr>
        <?php endforeach ?>
        
      </tbody>
    </table>
</div>


<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'css/mgw-crud-styles.css'; ?>">