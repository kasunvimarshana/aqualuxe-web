<?php
namespace AquaLuxe\Modules\DarkMode;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_action('wp_footer', [__CLASS__, 'render_toggle']);
        add_filter('body_class', [__CLASS__, 'body_classes']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
    }

    public static function assets(): void {
        wp_add_inline_script('aqualuxe-app', "(function(){try{var k='aqlx-dark';var d=document.documentElement;var v=localStorage.getItem(k);if(v==='1'){d.classList.add('dark')}else if(v==='0'){d.classList.remove('dark')}var t=document.body && document.body.querySelector('[data-dark-toggle]');function setDark(on){if(on){d.classList.add('dark');localStorage.setItem(k,'1')}else{d.classList.remove('dark');localStorage.setItem(k,'0')}}document.addEventListener('click',function(e){var el=e.target.closest('[data-dark-toggle]');if(!el)return;e.preventDefault();setDark(!d.classList.contains('dark'));});}catch(e){}})();", 'after');
    }

    public static function render_toggle(): void {
        echo '<button class="aqlx-dark-toggle fixed bottom-4 right-4 p-3 rounded-full bg-slate-800 text-white shadow-lg" data-dark-toggle aria-label="Toggle dark mode">\u263E</button>';
    }

    public static function body_classes(array $classes): array {
        $default = get_theme_mod('aqualuxe_dark_mode_default', false);
        if ($default) $classes[] = 'dark';
        return $classes;
    }
}
