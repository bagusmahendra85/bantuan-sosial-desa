<?php
/*
* Plugin Name: Bantuan Sosial Desa
* Plugin URI: https://mengwi-badung.desa.id/
* Description: Plugin yang dikembangkan untuk operasi CRUD data Bantuan Sosial di Desa.
* Author: Desa Mengwi Dev
* Version: 1.0.0
* Author URI: https://bagusmahendra85.github.io/
* Requires at least: 5.8
* Requires PHP: 5.6.20
* Text Domain : bansos-desa
*/

// #########################################################################################

// -------------------- [DEFINE ABSOLUTE PLUGIN ROOT PATH] --------------------
// START
define('MGW_CRUD_ABS_PATH', __DIR__);
// END
// -------------------- [DEFINE ABSOLUTE PLUGIN ROOT PATH] --------------------

// -------------------- [ADD MENU ITEM (MAIN)] --------------------
// START

// Add a menu item to the admin dashboard
function bantuan_desa_menu() {
  // Add a top-level menu without sidebar icon
  add_menu_page(
    'Bansos Desa',              // page title
    'Bansos Desa',              // menu name (this will be the plugin name in the sidebar)
    'edit_posts',               // capabilities
    'mgw_bansos',               // slug
    'mgw_display_bansos_lists', // callback
    '',                         // sidebar icon (empty to hide icon)
    2                           // menu place order
  );

  // Add the first submenu item with the desired icon and name
  add_submenu_page(
    'mgw_bansos',               // parent slug
    'Keluarga Penerima Manfaat',              // page title
    'Semua KPM',              // menu name
    'edit_posts',               // capabilities
    'mgw_bansos',               // slug
    'mgw_display_bansos_lists'  // callback
  );
}
add_action('admin_menu', 'bantuan_desa_menu');

// END
// -------------------- [ADD MENU ITEM (MAIN)] --------------------

// -------------------- [LINK MAIN BACKEND PAGE] --------------------
// START

// The callback function for your settings page
function mgw_display_bansos_lists() {
  // Include the template file
  include plugin_dir_path(__FILE__) . 'templates/mgw-bansos-main.php';
 
}

// END
// -------------------- [LINK MAIN BACKEND PAGE] --------------------

// -------------------- [ADD MY CSS] --------------------
// START

// Enqueue styles
function mgw_bansos_enqueue_styles() {
  wp_enqueue_style('mgw-bansos-styles', plugin_dir_url(__FILE__) . 'css/mgw-bansos-styles.css');
}
add_action('admin_enqueue_scripts', 'mgw_bansos_enqueue_styles');

// END
// -------------------- [ADD MY CSS] --------------------

// -------------------- [ADD BOOTSTRAP, FONTAWESOME, AND MY JS] --------------------
// START

// Enqueue scripts and styles
function mgw_bansos_enqueue_scripts() {
  // Enqueue Bootstrap CSS
  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css');

  // Enqueue Font Awesome Kit (consider loading it as a link in the head for styles)
  wp_enqueue_script('font-awesome-kit', 'https://kit.fontawesome.com/9ed35d004b.js', array(), null, false);

  // Enqueue Bootstrap JS (make sure jQuery is loaded before this)
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

  // Enqueue your custom scripts and styles if needed
  // wp_enqueue_script('mgw-bansos-scripts', plugin_dir_url(__FILE__) . 'js/mgw-bansos-scripts.js', array(), null, true);
}
add_action('admin_enqueue_scripts', 'mgw_bansos_enqueue_scripts');

// END
// -------------------- [ADD BOOTSTRAP, FONTAWESOME, AND MY JS] --------------------

// -------------------- [ADD SUB MENU WP ADMIN (TAMBAH KPM)] --------------------
// START

// Sub-menu
// Add sub-menu using add_submenu_page
function mgw_bansos_submenu_page() {
  add_submenu_page(
      'mgw_bansos',  // Parent slug
      'Tambah KPM Bansos',
      'Tambah KPM',
      'edit_posts',
      'mgw-bansos-submenu-insert-new-item',
      'mgw_bansos_submenu_insert_page'
  );
}

add_action('admin_menu', 'mgw_bansos_submenu_page');

// END
// -------------------- [ADD SUB MENU WP ADMIN (TAMBAH KPM)] --------------------

// -------------------- [LINK SUB MENU BACKEND PAGE (TAMBAH KPM) ] --------------------
// START

// Callback function for submenu page content
function mgw_bansos_submenu_insert_page() {
  include plugin_dir_path(__FILE__) . 'templates/mgw-bansos-insert.php';
}

// END
// -------------------- [LINK SUB MENU BACKEND PAGE (TAMBAH KPM) ] --------------------

// -------------------- [SHORTCODE MAIN TABLE ] --------------------
// START

function mgw_bansos_table_shortcode() {
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

  // Generate HTML table
  ob_start();
  ?>
  <div class="wrap">
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
  <?php
  $output = ob_get_clean();

  return $output;
}

// Register the shortcode
add_shortcode('mgw_bansos_table', 'mgw_bansos_table_shortcode');
// [mgw_bansos_table]

// END
// -------------------- [SHORTCODE MAIN TABLE ] --------------------