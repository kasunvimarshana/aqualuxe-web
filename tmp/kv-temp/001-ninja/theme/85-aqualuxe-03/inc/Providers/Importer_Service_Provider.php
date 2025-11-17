<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;
use Aqualuxe\Importer\Importer;

class Importer_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('admin_menu', function () {
            add_management_page(__('Aqualuxe Demo Importer','aqualuxe'), __('Aqualuxe Demo','aqualuxe'), 'manage_options', 'aqualuxe-demo', [$this, 'render']);
        });
        add_action('admin_post_aqlx_import_demo', [$this, 'handle_import']);
        add_action('admin_post_aqlx_flush_demo', [$this, 'handle_flush']);
    }

    public function boot(Container $c): void {}

    public function render(): void
    {
        echo '<div class="wrap"><h1>'.esc_html__('Aqualuxe Demo Importer','aqualuxe').'</h1>';
        echo '<p>'.esc_html__('Import demo content or flush existing demo data.','aqualuxe').'</p>';
        echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
        wp_nonce_field('aqlx_demo');
        echo '<input type="hidden" name="action" value="aqlx_import_demo" />';
        submit_button(__('Import Demo','aqualuxe'));
        echo '</form>';
        echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'" style="margin-top:1rem;">';
        wp_nonce_field('aqlx_demo');
        echo '<input type="hidden" name="action" value="aqlx_flush_demo" />';
        submit_button(__('Flush Demo','aqualuxe'), 'delete');
        echo '</form>';
        echo '</div>';
    }

    public function handle_import(): void
    {
        check_admin_referer('aqlx_demo');
        $importer = new Importer();
        $log = $importer->import();
        wp_safe_redirect(add_query_arg(['page' => 'aqualuxe-demo', 'imported' => 1, 'log' => urlencode(wp_json_encode($log))], admin_url('tools.php')));
        exit;
    }

    public function handle_flush(): void
    {
        check_admin_referer('aqlx_demo');
        $importer = new Importer();
        $log = $importer->flush();
        wp_safe_redirect(add_query_arg(['page' => 'aqualuxe-demo', 'flushed' => 1, 'log' => urlencode(wp_json_encode($log))], admin_url('tools.php')));
        exit;
    }
}
