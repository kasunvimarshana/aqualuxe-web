<?php
namespace AquaLuxe;

class Shortcodes {
    public static function init(): void {
        \add_shortcode('aqualuxe_home', [self::class, 'home']);
        \add_shortcode('aqualuxe_services', [self::class, 'services']);
    }

    public static function home(): string {
        \ob_start();
        ?>
        <section class="container mx-auto px-4 py-12">
          <h2 class="text-2xl font-bold mb-6"><?php echo \esc_html__('Featured Products', 'aqualuxe'); ?></h2>
          <?php if (is_wc_active()) { echo \do_shortcode('[products limit="8" columns="4" visibility="featured"]'); } else { echo '<p>'.\esc_html__('WooCommerce is not active.', 'aqualuxe').'</p>'; } ?>
        </section>
        <?php
        return \ob_get_clean();
    }

    public static function services(): string {
        $q = new \WP_Query(['post_type' => 'service', 'posts_per_page' => 6]);
        \ob_start();
        echo '<div class="grid md:grid-cols-3 gap-6">';
        if ($q->have_posts()) {
            while ($q->have_posts()) { $q->the_post();
                echo '<a class="p-4 rounded-lg border border-gray-100 dark:border-gray-800 block" href="'.\esc_url(\get_permalink()).'">';
                if (\has_post_thumbnail()) { \the_post_thumbnail('medium', ['class' => 'rounded mb-3', 'loading' => 'lazy']); }
                echo '<h3 class="text-xl font-semibold mb-1">'.\esc_html(\get_the_title()).'</h3>';
                echo '<p class="opacity-80">'.\esc_html(\wp_trim_words(\get_the_excerpt(), 20)).'</p>';
                echo '</a>';
            }
            \wp_reset_postdata();
        } else {
            echo '<p>'.\esc_html__('No services found.', 'aqualuxe').'</p>';
        }
        echo '</div>';
        return \ob_get_clean();
    }
}

Shortcodes::init();
