<?php

namespace App\Modules\DemoImporter\Admin;

/**
 * Class AdminManager
 *
 * Manages the admin UI for the demo importer.
 *
 * @package App\Modules\DemoImporter\Admin
 */
class AdminManager
{
    /**
     * AdminManager constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    /**
     * Add the importer page to the admin menu.
     */
    public function add_admin_page()
    {
        add_theme_page(
            __('Aqualuxe Demo Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-importer',
            [$this, 'render_admin_page']
        );
    }

    /**
     * Render the admin page content.
     */
    public function render_admin_page()
    {
        include_once AQUALUXE_DIR . '/app/modules/demo-importer/admin/admin-page.php';
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook
     */
    public function enqueue_admin_scripts(string $hook)
    {
        if ($hook !== 'appearance_page_aqualuxe-importer') {
            return;
        }

        $css_url = AQUALUXE_URL . '/app/modules/demo-importer/admin/css/admin.css';
        $js_url = AQUALUXE_URL . '/app/modules/demo-importer/admin/js/admin.js';

        wp_enqueue_style('aqualuxe-importer-admin', $css_url, [], AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-importer-admin', $js_url, ['jquery'], AQUALUXE_VERSION, true);

        wp_localize_script('aqualuxe-importer-admin', 'aqualuxeImporter', [
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }
}
