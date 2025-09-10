<?php

namespace App\Modules\DemoImporter;

/**
 * Class AdminPage
 *
 * Creates the admin page for the demo importer.
 *
 * @package App\Modules\DemoImporter
 */
class AdminPage
{
    /**
     * AdminPage constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Add the admin menu item.
     */
    public function add_admin_menu()
    {
        add_theme_page(
            __('AquaLuxe Demo Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            [$this, 'render_page']
        );
    }

    /**
     * Render the admin page.
     */
    public function render_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div id="aqualuxe-demo-importer-app"></div>
        </div>
        <?php
    }

    /**
     * Enqueue scripts and styles for the admin page.
     *
     * @param string $hook
     */
    public function enqueue_scripts($hook)
    {
        if ('appearance_page_aqualuxe-demo-importer' !== $hook) {
            return;
        }

        // Enqueue your compiled React/Vue/etc. app here
        // For now, we'll just add a placeholder script
        wp_enqueue_script(
            'aqualuxe-demo-importer-admin',
            get_template_directory_uri() . '/assets/dist/js/demo-importer-admin.js',
            ['wp-element', 'wp-components', 'wp-api-fetch'],
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-demo-importer-admin', 'aqualuxeDemoImporter', [
            'nonce' => wp_create_nonce('wp_rest'),
            'api_url' => rest_url('aqualuxe/v1/import'),
        ]);
    }
}
