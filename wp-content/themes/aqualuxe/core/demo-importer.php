<?php
add_action('admin_menu', function() {
    add_theme_page(__('Demo Import', 'aqualuxe'), __('Demo Import', 'aqualuxe'), 'manage_options', 'aqualuxe-demo-import', function() {
        if (isset($_POST['aqualuxe_import_demo']) && check_admin_referer('aqualuxe_import_demo')) {
            // Import WXR
            require_once ABSPATH . 'wp-admin/includes/import.php';
            $importer = 'wordpress';
            if (!class_exists('WP_Import')) {
                $importer_path = get_template_directory() . '/inc/compat/wordpress-importer.php';
                if (file_exists($importer_path)) {
                    require_once $importer_path;
                }
            }
            $importer = new WP_Import();
            $importer->fetch_attachments = true;
            $importer->import(get_template_directory() . '/demo-content.xml');
            echo '<div class="updated"><p>Demo content imported.</p></div>';
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Import Demo Content', 'aqualuxe'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('aqualuxe_import_demo'); ?>
                <input type="submit" name="aqualuxe_import_demo" class="button button-primary" value="<?php esc_attr_e('Import Demo', 'aqualuxe'); ?>">
            </form>
        </div>
        <?php
    });
});
