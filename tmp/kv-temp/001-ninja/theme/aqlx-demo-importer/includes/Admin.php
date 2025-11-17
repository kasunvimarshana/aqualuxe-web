<?php
namespace AQLX\DemoImporter;

if (!defined('ABSPATH')) { exit; }

class Admin {
    public static function init(): void {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'assets']);
    }

    public static function menu(): void {
        add_menu_page(
            __('AquaLuxe Importer', 'aqlx-demo-importer'),
            __('Importer', 'aqlx-demo-importer'),
            'manage_options',
            'aqlx-importer',
            [__CLASS__, 'render'],
            'dashicons-database-import',
            59
        );
    }

    public static function assets($hook): void {
        if ($hook !== 'toplevel_page_aqlx-importer') return;
        wp_enqueue_style('aqlxdi', AQLX_DI_URL . 'assets/css/admin.css', [], AQLX_DI_VERSION);
        wp_enqueue_script('aqlxdi', AQLX_DI_URL . 'assets/js/admin.js', ['wp-api-fetch'], AQLX_DI_VERSION, true);
        wp_localize_script('aqlxdi', 'AQLXDI', [
            'nonce' => wp_create_nonce('wp_rest'),
            'rest' => rest_url('aqlxdi/v1/'),
        ]);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) { wp_die(__('Insufficient permissions', 'aqlx-demo-importer')); }
        echo '<div class="wrap aqlxdi">';
        echo '<h1>' . esc_html__('AquaLuxe Demo Importer', 'aqlx-demo-importer') . '</h1>';
        echo '<div id="aqlxdi-app">';
        echo '<div class="aqlxdi-controls">';
        echo '<label><input type="checkbox" data-entity="pages" checked> ' . esc_html__('Pages', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="posts" checked> ' . esc_html__('Posts', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="cpts" checked> ' . esc_html__('Custom Types', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="products" checked> ' . esc_html__('Products', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="media" checked> ' . esc_html__('Media', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="menus" checked> ' . esc_html__('Menus', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="widgets" checked> ' . esc_html__('Widgets', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="options" checked> ' . esc_html__('Options', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="users" checked> ' . esc_html__('Users', 'aqlx-demo-importer') . '</label> ';
        echo '<label><input type="checkbox" data-entity="roles" checked> ' . esc_html__('Roles', 'aqlx-demo-importer') . '</label> ';
        echo '</div>';
        echo '<div class="aqlxdi-params">';
        echo '<label>' . esc_html__('Volume per step', 'aqlx-demo-importer') . ' <input type="number" id="aqlxdi-volume" value="10" min="1" max="100"></label> ';
        echo '<label>' . esc_html__('Conflict policy', 'aqlx-demo-importer') . ' <select id="aqlxdi-policy"><option value="skip">Skip</option><option value="merge">Merge</option><option value="overwrite">Overwrite</option></select></label> ';
        echo '<label>' . esc_html__('Locale', 'aqlx-demo-importer') . ' <input type="text" id="aqlxdi-locale" value="en_US"></label> ';
        echo '<label>' . esc_html__('Asset source', 'aqlx-demo-importer') . ' <select id="aqlxdi-provider"><option value="wikimedia">Wikimedia</option><option value="unsplash">Unsplash (stub)</option><option value="pexels">Pexels (stub)</option><option value="pixabay">Pixabay (stub)</option></select></label> ';
        echo '</div>';
        echo '<div class="aqlxdi-actions">';
        echo '<button class="button button-primary" id="aqlxdi-start">' . esc_html__('Start', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button" id="aqlxdi-preview">' . esc_html__('Preview', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button" id="aqlxdi-export">' . esc_html__('Export', 'aqlx-demo-importer') . '</button> ';
    echo '<button class="button" id="aqlxdi-schedule">' . esc_html__('Schedule Daily', 'aqlx-demo-importer') . '</button> ';
    echo '<button class="button" id="aqlxdi-schedule-clear">' . esc_html__('Clear Schedule', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button" id="aqlxdi-pause">' . esc_html__('Pause', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button" id="aqlxdi-resume">' . esc_html__('Resume', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button" id="aqlxdi-cancel">' . esc_html__('Cancel & Rollback', 'aqlx-demo-importer') . '</button> ';
        echo '<button class="button button-link-delete" id="aqlxdi-flush">' . esc_html__('Secure Flush', 'aqlx-demo-importer') . '</button> ';
        echo '</div>';
        echo '<div class="aqlxdi-progress"><div class="bar"><span id="aqlxdi-progress" style="width:0%"></span></div><div id="aqlxdi-status"></div><div><a id="aqlxdi-audit" href="#" target="_blank" style="display:none">' . esc_html__('View audit log', 'aqlx-demo-importer') . '</a></div></div>';
        echo '</div>';
        echo '</div>';
    }
}
