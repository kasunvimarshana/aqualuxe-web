<?php
/**
 * Template for displaying search forms
 *
 * @package AquaLuxe
 */

$aqualuxe_unique_id = wp_unique_id( 'search-form-' );
$aqualuxe_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>

<form role="search" <?php echo $aqualuxe_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="relative">
        <label for="<?php echo esc_attr( $aqualuxe_unique_id ); ?>" class="sr-only">
            <?php esc_html_e( 'Search for:', 'aqualuxe' ); ?>
        </label>
        <div class="flex">
            <input type="search" id="<?php echo esc_attr( $aqualuxe_unique_id ); ?>" class="search-field w-full px-4 py-2 rounded-l border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            
            <?php if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_search_products_only', false ) ) : ?>
                <input type="hidden" name="post_type" value="product" />
            <?php endif; ?>
            
            <button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r">
                <span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
    </div>
</form>