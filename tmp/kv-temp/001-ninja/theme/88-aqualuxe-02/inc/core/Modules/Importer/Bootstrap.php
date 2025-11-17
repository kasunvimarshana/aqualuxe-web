<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\Importer;

use AquaLuxe\Core\Helpers;

class Bootstrap
{
    public static function init(): void
    {
        // Admin menu.
        Helpers::wp('add_action', ['admin_menu', [self::class, 'register_menu']]);
        // REST route for async import.
        Helpers::wp('add_action', ['rest_api_init', [self::class, 'routes']]);
    }

    public static function register_menu(): void
    {
        Helpers::wp('add_menu_page', [
            __('AquaLuxe Importer', 'aqualuxe'),
            __('Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-importer',
            [self::class, 'render'],
            'dashicons-update-alt',
            58
        ]);
    }

    public static function render(): void
    {
    $nonce = wp_create_nonce('wp_rest');
    echo '<div class="wrap aqualuxe-admin">';
    echo '<h1>' . esc_html__('AquaLuxe Demo Importer', 'aqualuxe') . '</h1>';
    echo '<p>' . esc_html__('Import demo content or reset site. REST-based with progress.', 'aqualuxe') . '</p>';
    $rest = Helpers::wp('rest_url', ['aqualuxe/v1']) ?? '';
    echo '<div id="alx-importer" data-nonce="' . esc_attr($nonce) . '" data-rest="' . esc_url_raw((string) $rest) . '">';
    echo '<div class="card" style="padding:16px;max-width:760px;">';
    echo '<h2>' . esc_html__('Reset (Flush)', 'aqualuxe') . '</h2>';
    echo '<label><input type="checkbox" id="alx-flush-posts" checked> ' . esc_html__('Posts/Pages/CPTs', 'aqualuxe') . '</label><br>';
    echo '<label><input type="checkbox" id="alx-flush-media"> ' . esc_html__('Media Library', 'aqualuxe') . '</label><br>';
    echo '<label><input type="checkbox" id="alx-flush-tax"> ' . esc_html__('Taxonomies', 'aqualuxe') . '</label><br>';
    echo '<button class="button button-secondary" id="alx-do-flush">' . esc_html__('Flush Selected', 'aqualuxe') . '</button>';
    echo '<hr><h2>' . esc_html__('Import', 'aqualuxe') . '</h2>';
    echo '<label>' . esc_html__('Items to create', 'aqualuxe') . ' <input id="alx-volume" type="number" value="20" min="1" max="500"></label><br>';
    echo '<label><input type="checkbox" id="alx-products" checked> ' . esc_html__('Include WooCommerce products (if active)', 'aqualuxe') . '</label><br>';
    echo '<button class="button button-primary" id="alx-start">' . esc_html__('Start Import', 'aqualuxe') . '</button>';
    echo '<div style="margin-top:12px;">';
    echo '<progress id="alx-progress" max="100" value="0" style="width:100%;"></progress>';
    echo '<div id="alx-status" aria-live="polite" style="margin-top:8px;"></div>';
    echo '</div>';
    echo '</div></div>';
    echo '</div>';
    }

    public static function routes(): void
    {
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/import/flush', [
            'methods'  => 'POST',
            'callback' => [self::class, 'handle_flush'],
            'permission_callback' => static fn() => current_user_can('manage_options'),
            'args' => [],
        ]]);
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/import/start', [
            'methods'  => 'POST',
            'callback' => [self::class, 'handle_start'],
            'permission_callback' => static fn() => current_user_can('manage_options'),
        ]]);
        Helpers::wp('register_rest_route', ['aqualuxe/v1', '/import/step', [
            'methods'  => 'POST',
            'callback' => [self::class, 'handle_step'],
            'permission_callback' => static fn() => current_user_can('manage_options'),
        ]]);
    }

    public static function handle_flush($request) { return Service::flush((array) $request->get_json_params()); }
    public static function handle_start($request) { return Service::start((array) $request->get_json_params()); }
    public static function handle_step($request) { return Service::step(); }
}
