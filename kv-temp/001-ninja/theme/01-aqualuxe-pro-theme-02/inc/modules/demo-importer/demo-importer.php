<?php
/**
 * Demo Importer functionality
 *
 * @package AquaLuxe
 * @subpackage AquaLuxe/inc/modules/demo-importer
 */

class AquaLuxe_Demo_Importer {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_importer_page'));
        add_action('admin_init', array($this, 'handle_import'));
    }

    public function add_importer_page() {
        add_theme_page(
            'AquaLuxe Demo Importer',
            'Demo Importer',
            'manage_options',
            'aqualuxe-demo-importer',
            array($this, 'render_importer_page')
        );
    }

    public function render_importer_page() {
        ?>
        <div class="wrap">
            <h1>AquaLuxe Demo Importer</h1>
            <p>Import demo content to get started with the theme.</p>
            <form method="post">
                <?php wp_nonce_field('aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce'); ?>
                <p>
                    <label>
                        <input type="checkbox" name="flush_data" value="1">
                        Flush all existing data before importing. This will delete all posts, pages, products, etc.
                    </label>
                </p>
                <p>
                    <button type="submit" name="import_demo" class="button button-primary">Import Demo Content</button>
                </p>
            </form>
        </div>
        <?php
    }

    public function handle_import() {
        if (isset($_POST['import_demo']) && check_admin_referer('aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce')) {
            if (isset($_POST['flush_data']) && $_POST['flush_data'] == 1) {
                $this->flush_data();
            }
            $this->import_data();
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>Demo content imported successfully.</p></div>';
            });
        }
    }

    private function flush_data() {
        global $wpdb;
        $tables_to_truncate = [
            'posts', 'postmeta', 'comments', 'commentmeta', 'terms', 'term_taxonomy', 'term_relationships', 'termmeta'
        ];
        foreach ($tables_to_truncate as $table) {
            $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}{$table}");
        }
    }

    private function import_data() {
        $demo_file = AQUALUXE_THEME_DIR . '/demo-content/demo-content.xml';
        if (file_exists($demo_file)) {
            if (!defined('WP_LOAD_IMPORTERS')) {
                define('WP_LOAD_IMPORTERS', true);
            }
            require_once ABSPATH . 'wp-admin/includes/import.php';
            $importer_error = false;
            if (!class_exists('WP_Importer')) {
                $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                if (file_exists($class_wp_importer)) {
                    require_once $class_wp_importer;
                } else {
                    $importer_error = true;
                }
            }
            if (!class_exists('WP_Import')) {
                $class_wp_import = AQUALUXE_THEME_DIR . '/inc/plugins/wordpress-importer/wordpress-importer.php';
                if (file_exists($class_wp_import)) {
                    require_once $class_wp_import;
                } else {
                    $importer_error = true;
                }
            }
            if ($importer_error) {
                die("Error on import");
            } else {
                $wp_import = new WP_Import();
                $wp_import->fetch_attachments = true;
                $wp_import->import($demo_file);
            }
        }
    }
}

new AquaLuxe_Demo_Importer();
