<?php
namespace AquaLuxe\Modules\Events;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_shortcode('aqlx_events', [__CLASS__, 'render']);
    }

    public static function render(): string {
        $html = '<div class="grid md:grid-cols-3 gap-6">';
        for ($i=1;$i<=3;$i++) {
            $html .= '<div class="p-4 border rounded"><h3>Event ' . $i . '</h3><p>' . esc_html__('Details coming soon.', 'aqualuxe') . '</p></div>';
        }
        $html .= '</div>';
        return $html;
    }
}
