<?php
/**
 * Template part for displaying the site footer
 *
 * @package AquaLuxe
 */

// Get footer layout from theme options
$footer_layout = get_theme_mod('aqualuxe_footer_layout', 'four-columns');
?>

<footer id="colophon" class="site-footer bg-secondary-900 text-white py-12" role="contentinfo">
    <div class="container mx-auto px-4">
        <?php 
        // Load the appropriate footer layout
        switch ($footer_layout) {
            case 'four-columns':
                get_template_part('template-parts/footer/footer', 'four-columns');
                break;
            case 'three-columns':
                get_template_part('template-parts/footer/footer', 'three-columns');
                break;
            case 'two-columns':
                get_template_part('template-parts/footer/footer', 'two-columns');
                break;
            default:
                get_template_part('template-parts/footer/footer', 'four-columns');
        }
        ?>

        <div class="site-info text-center text-secondary-400 text-sm mt-8 pt-8 border-t border-secondary-800">
            <?php
            $footer_text = get_theme_mod('aqualuxe_footer_text', '');
            if ($footer_text) {
                echo wp_kses_post($footer_text);
            } else {
                printf(
                    /* translators: %1$s: Theme name, %2$s: Theme author website */
                    esc_html__('%1$s theme by %2$s', 'aqualuxe'),
                    'AquaLuxe',
                    '<a href="https://example.com/" class="text-primary-400 hover:text-primary-300">NinjaTech AI</a>'
                );
            }
            ?>
        </div><!-- .site-info -->
    </div>
</footer><!-- #colophon -->

<?php if (get_theme_mod('aqualuxe_enable_back_to_top', true)) : ?>
    <button id="scroll-to-top" class="scroll-to-top fixed bottom-8 right-8 bg-primary-500 text-white p-2 rounded-full shadow-md opacity-0 invisible transition-all duration-300 z-40" aria-label="<?php esc_attr_e('Scroll to top', 'aqualuxe'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>
<?php endif; ?>