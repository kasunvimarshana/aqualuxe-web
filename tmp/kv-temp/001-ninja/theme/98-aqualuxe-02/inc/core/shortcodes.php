<?php
namespace AquaLuxe\Core;

use function add_shortcode;
use function esc_html;
use function get_posts;

class Shortcodes
{
    public function register(): void
    {
        add_shortcode('aqlx_testimonials', [$this, 'testimonials']);
        add_shortcode('aqlx_year', fn() => (string) gmdate('Y'));
    }

    public function testimonials($atts = []): string
    {
        $atts = shortcode_atts([
            'count' => 3,
        ], $atts, 'aqlx_testimonials');

        $items = get_posts([
            'post_type'      => 'testimonial',
            'posts_per_page' => (int) $atts['count'],
        ]);

        if (! $items) {
            return '';
        }

        ob_start();
        echo '<div class="aqlx-testimonials grid gap-6 md:grid-cols-3" role="list">';
        foreach ($items as $item) {
            echo '<article role="listitem" class="p-6 rounded-lg bg-slate-50 dark:bg-slate-800">';
            echo '<h3 class="font-semibold text-slate-800 dark:text-slate-100">' . esc_html($item->post_title) . '</h3>';
            echo '<div class="prose dark:prose-invert">' . wp_kses_post(wpautop($item->post_content)) . '</div>';
            echo '</article>';
        }
        echo '</div>';
        return (string) ob_get_clean();
    }
}
