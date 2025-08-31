<?php
/** Admin UI for newsletter signups: list, export CSV, clear */

if (!defined('ABSPATH')) { exit; }

add_action('admin_menu', function(){
    add_theme_page(
        __('AquaLuxe Newsletter','aqualuxe'),
        __('AquaLuxe Newsletter','aqualuxe'),
        'manage_options',
        'aqualuxe-newsletter',
        'aqlx_newsletter_page'
    );
});

function aqlx_newsletter_page(){
    if (!current_user_can('manage_options')) return;
    $list = get_option('aqlx_newsletters', []);
    if (!is_array($list)) $list = [];

    echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Newsletter','aqualuxe') . '</h1>';
    echo '<p>' . esc_html__('Manage collected newsletter emails. Export as CSV or clear the list.','aqualuxe') . '</p>';

    echo '<p>';
    echo '<a class="button button-primary" href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqlx_export_newsletters'), 'aqlx_newsletter_admin')) . '">' . esc_html__('Export CSV','aqualuxe') . '</a> ';
    echo '<a class="button" href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqlx_export_newsletters_json'), 'aqlx_newsletter_admin')) . '">' . esc_html__('Export JSON','aqualuxe') . '</a> ';
    echo '<a class="button" href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqlx_clear_newsletters'), 'aqlx_newsletter_admin')) . '" onclick="return confirm(\'' . esc_js(__('Clear all emails?', 'aqualuxe')) . '\')">' . esc_html__('Clear','aqualuxe') . '</a>';
    echo '</p>';

    if (empty($list)) {
        echo '<p>' . esc_html__('No emails collected yet.','aqualuxe') . '</p>';
        echo '</div>';
        return;
    }

    echo '<table class="widefat striped"><thead><tr><th>' . esc_html__('Email','aqualuxe') . '</th><th>' . esc_html__('Date','aqualuxe') . '</th><th>' . esc_html__('Consent','aqualuxe') . '</th></tr></thead><tbody>';
    foreach ($list as $row) {
        $email = isset($row['email']) ? $row['email'] : '';
        $time = isset($row['time']) ? (int)$row['time'] : time();
        $consent = !empty($row['consent']) ? 'yes' : 'no';
        echo '<tr><td>' . esc_html($email) . '</td><td>' . esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $time)) . '</td><td>' . esc_html($consent) . '</td></tr>';
    }
    echo '</tbody></table></div>';
}

add_action('admin_post_aqlx_export_newsletters', function(){
    if (!current_user_can('manage_options')) wp_die(__('Forbidden','aqualuxe'));
    check_admin_referer('aqlx_newsletter_admin');
    $list = get_option('aqlx_newsletters', []);
    if (!is_array($list)) $list = [];

    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=aqualuxe-newsletters.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['email','date']);
    foreach ($list as $row) {
        $email = isset($row['email']) ? $row['email'] : '';
        $time = isset($row['time']) ? (int)$row['time'] : time();
        fputcsv($out, [$email, date('c', $time)]);
    }
    fclose($out);
    exit;
});

add_action('admin_post_aqlx_clear_newsletters', function(){
    if (!current_user_can('manage_options')) wp_die(__('Forbidden','aqualuxe'));
    check_admin_referer('aqlx_newsletter_admin');
    update_option('aqlx_newsletters', []);
    wp_safe_redirect(admin_url('themes.php?page=aqualuxe-newsletter'));
    exit;
});

add_action('admin_post_aqlx_export_newsletters_json', function(){
    if (!current_user_can('manage_options')) wp_die(__('Forbidden','aqualuxe'));
    check_admin_referer('aqlx_newsletter_admin');
    $list = get_option('aqlx_newsletters', []);
    if (!is_array($list)) $list = [];
    // Do not expose original IPs, only the stored hash
    foreach ($list as &$row) {
        if (isset($row['ip']) && strlen($row['ip']) > 64) { $row['ip'] = substr($row['ip'], 0, 64); }
    }
    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=aqualuxe-newsletters.json');
    echo wp_json_encode($list, JSON_PRETTY_PRINT);
    exit;
});
