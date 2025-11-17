<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\LanguageService;

/**
 * Multilingual Module.
 *
 * Provides a language switcher that integrates with Polylang or WPML.
 */
class Multilingual implements ModuleInterface
{
    private ?LanguageService $language_service = null;

    public function boot(): void
    {
        $this->language_service = new LanguageService();

        if (!$this->language_service->is_active()) {
            return;
        }

        \add_action('customize_register', [$this, 'register_customizer_settings']);
        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        \add_shortcode('aqualuxe_language_switcher', [$this, 'render_switcher_shortcode']);
        \add_action('aqualuxe_header_actions', [$this, 'render_header_switcher']);
    }

    public function register_customizer_settings($wp_customize): void
    {
        $wp_customize->add_section('aqualuxe_multilingual', [
            'title' => __('Language Switcher', 'aqualuxe'),
            'priority' => 161,
        ]);

        $wp_customize->add_setting('aqualuxe_language_switcher_display', [
            'default' => 'dropdown',
            'sanitize_callback' => fn($v) => in_array($v, ['dropdown', 'list']) ? $v : 'dropdown',
        ]);

        $wp_customize->add_control('aqualuxe_language_switcher_display', [
            'label' => __('Display Style', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'list' => __('Inline List', 'aqualuxe'),
            ],
        ]);
    }

    public function enqueue_assets(): void
    {
        $css_path = '/modules/multilingual/assets/dist/multilingual.css';
        $css_file = AQUALUXE_DIR . $css_path;
        if (file_exists($css_file)) {
            \wp_enqueue_style(
                'aqualuxe-multilingual',
                AQUALUXE_URI . $css_path,
                [],
                filemtime($css_file)
            );
        }
    }

    public function render_switcher_shortcode($atts): string
    {
        $atts = shortcode_atts(['display' => null], $atts, 'aqualuxe_language_switcher');
        return $this->get_switcher_html($atts['display']);
    }

    public function render_header_switcher(): void
    {
        echo $this->get_switcher_html();
    }

    private function get_switcher_html(?string $display_override = null): string
    {
        if (!$this->language_service) {
            return '';
        }

        $languages = $this->language_service->get_languages();
        if (empty($languages) || count($languages) < 2) {
            return '';
        }

        $display_mode = $display_override ?? get_theme_mod('aqualuxe_language_switcher_display', 'dropdown');

        ob_start();
        get_template_part('modules/multilingual/templates/switcher', null, [
            'languages' => $languages,
            'display_mode' => $display_mode,
        ]);
        return ob_get_clean();
    }
}
