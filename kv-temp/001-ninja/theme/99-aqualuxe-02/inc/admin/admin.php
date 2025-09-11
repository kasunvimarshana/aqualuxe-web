<?php
namespace AquaLuxe\Admin;

use function add_action;
use function add_menu_page;
use function add_submenu_page;
use function esc_html__;
use function wp_nonce_field;
use function admin_url;
use function wp_create_nonce;
use function submit_button;

class Admin
{
    public function boot(): void
    {
        add_action('admin_menu', [$this, 'register_menus']);
    }

    public function register_menus(): void
    {
        add_menu_page(
            esc_html__('AquaLuxe', 'aqualuxe'),
            esc_html__('AquaLuxe', 'aqualuxe'),
            'manage_options',
            'aqualuxe',
            [$this, 'render_dashboard'],
            'dashicons-water',
            58
        );

        add_submenu_page('aqualuxe', esc_html__('Importer', 'aqualuxe'), esc_html__('Importer', 'aqualuxe'), 'manage_options', 'aqualuxe-importer', [$this, 'render_importer']);
    }

    public function render_dashboard(): void
    {
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Dashboard', 'aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Manage theme settings, modules, and tools.', 'aqualuxe') . '</p>';
        echo '</div>';
    }

    public function render_importer(): void
    {
        $ajax = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('aqlx_import');
        echo '<div class="wrap"><h1>' . esc_html__('Demo Content Importer', 'aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Populate your site with demo pages, posts, products, media, menus, and widgets. Supports reset.', 'aqualuxe') . '</p>';
        echo '<div id="aqlx-importer" data-ajax="' . esc_attr($ajax) . '" data-nonce="' . esc_attr($nonce) . '"></div>';
        echo '<form id="aqlx-import-form" method="post">';
        wp_nonce_field('aqlx_import');
        echo '<label><input type="checkbox" name="reset" value="1"> ' . esc_html__('Full reset before import (destructive)', 'aqualuxe') . '</label><br>';
        echo '<label>' . esc_html__('Items to import', 'aqualuxe') . ': ';
        echo '<select name="scope"><option value="all">' . esc_html__('All', 'aqualuxe') . '</option><option value="content">' . esc_html__('Content only', 'aqualuxe') . '</option><option value="products">' . esc_html__('Products', 'aqualuxe') . '</option></select></label><br>';
        submit_button(esc_html__('Start Import', 'aqualuxe'));
        echo '</form>';
        echo '<progress id="aqlx-progress" value="0" max="100" style="width: 100%"></progress>';
        echo '<pre id="aqlx-log" style="max-height: 300px; overflow:auto"></pre>';
        echo '</div>';
    }
}
