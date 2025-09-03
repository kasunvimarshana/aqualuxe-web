<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Admin_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function boot(Container $c): void {}

    public function add_menu(): void
    {
        add_options_page(__('Aqualuxe Settings', 'aqualuxe'), __('Aqualuxe', 'aqualuxe'), 'manage_options', 'aqualuxe-settings', [$this, 'render_page']);
    }

    public function register_settings(): void
    {
        register_setting('aqualuxe', 'aqualuxe_currency_code');
        register_setting('aqualuxe', 'aqualuxe_currency_symbol');
        register_setting('aqualuxe', 'aqualuxe_skin');

        add_settings_section('aqualuxe_general', __('General', 'aqualuxe'), '__return_false', 'aqualuxe');

        add_settings_field('aqualuxe_currency_code', __('Currency Code', 'aqualuxe'), function () {
            $val = esc_attr(get_option('aqualuxe_currency_code', 'USD'));
            echo '<input name="aqualuxe_currency_code" value="' . $val . '" class="regular-text" />';
        }, 'aqualuxe', 'aqualuxe_general');

        add_settings_field('aqualuxe_currency_symbol', __('Currency Symbol', 'aqualuxe'), function () {
            $val = esc_attr(get_option('aqualuxe_currency_symbol', '$'));
            echo '<input name="aqualuxe_currency_symbol" value="' . $val . '" class="regular-text" />';
        }, 'aqualuxe', 'aqualuxe_general');

        add_settings_field('aqualuxe_skin', __('Active Skin', 'aqualuxe'), function () {
            $val = esc_attr(get_option('aqualuxe_skin', 'default'));
            echo '<input name="aqualuxe_skin" value="' . $val . '" class="regular-text" placeholder="default|dark" />';
        }, 'aqualuxe', 'aqualuxe_general');
    }

    public function render_page(): void
    {
        echo '<div class="wrap"><h1>' . esc_html__('Aqualuxe Settings', 'aqualuxe') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields('aqualuxe');
        do_settings_sections('aqualuxe');
        submit_button();
        echo '</form></div>';
    }
}
