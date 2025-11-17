<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

add_action('admin_menu', static function (): void {
    add_theme_page(
        __('AquaLuxe Settings', 'aqualuxe'),
        __('AquaLuxe', 'aqualuxe'),
        'manage_options',
        'aqualuxe-settings',
        __NAMESPACE__ . '\\render_settings_page'
    );
});

add_action('admin_init', static function (): void {
    register_setting('aqualuxe_options', 'aqualuxe_options', [
        'type' => 'array',
        'sanitize_callback' => function ($value) {
            $value = is_array($value) ? $value : [];
            $value['modules'] = array_map('sanitize_text_field', $value['modules'] ?? []);
            return $value;
        }
    ]);
});

function render_settings_page(): void
{
    if (! current_user_can('manage_options')) {
        return;
    }
    $opts = get_option('aqualuxe_options', []);
    $modules = $opts['modules'] ?? ['multilingual','dark-mode','services','events','subscriptions','wholesale','auctions','affiliate','importer'];
    $all = ['multilingual','dark-mode','services','events','subscriptions','wholesale','auctions','affiliate','importer'];
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('AquaLuxe Settings', 'aqualuxe'); ?></h1>
      <form method="post" action="options.php">
        <?php settings_fields('aqualuxe_options'); ?>
        <h2><?php esc_html_e('Modules', 'aqualuxe'); ?></h2>
        <p><?php esc_html_e('Enable or disable feature modules.', 'aqualuxe'); ?></p>
        <fieldset>
          <?php foreach ($all as $mod): $checked = in_array($mod, $modules, true); ?>
            <label><input type="checkbox" name="aqualuxe_options[modules][]" value="<?php echo esc_attr($mod); ?>" <?php checked($checked); ?>> <?php echo esc_html(ucwords(str_replace('-', ' ', $mod))); ?></label><br>
          <?php endforeach; ?>
        </fieldset>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
}

// Filter module list based on settings
add_filter('aqualuxe/modules', static function (array $modules): array {
    $opts = get_option('aqualuxe_options', []);
    if (! empty($opts['modules']) && is_array($opts['modules'])) {
        return array_values(array_intersect($modules, $opts['modules']));
    }
    return $modules;
});
