<?php if (!defined('ABSPATH')) exit; ?>
<div class="wrap">
  <h1><?php esc_html_e('AquaLuxe Dashboard','aqualuxe'); ?></h1>
  <p><?php esc_html_e('Welcome to AquaLuxe. Manage settings, modules, and demo content here.','aqualuxe'); ?></p>
  <hr>
  <h2><?php esc_html_e('Modules','aqualuxe'); ?></h2>
  <p><?php esc_html_e('Modules can be toggled via modules/modules.json.','aqualuxe'); ?></p>
  <h2><?php esc_html_e('Demo Content','aqualuxe'); ?></h2>
  <p><a class="button button-primary" href="<?php echo esc_url( admin_url('admin.php?page=aqualuxe-importer') ); ?>"><?php esc_html_e('Open Importer','aqualuxe'); ?></a></p>
</div>
