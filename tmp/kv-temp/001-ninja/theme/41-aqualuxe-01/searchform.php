<?php
/**
 * The template for displaying search forms
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$unique_id = wp_unique_id( 'search-form-' );
$aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>

<form role="search" <?php echo $aria_label; ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo esc_attr( $unique_id ); ?>" class="sr-only">
        <?php echo esc_html_x( 'Search for:', 'label', 'aqualuxe' ); ?>
    </label>
    <div class="relative">
        <input 
            type="search" 
            id="<?php echo esc_attr( $unique_id ); ?>" 
            class="search-field form-input w-full pl-10 pr-4 py-2 rounded-md" 
            placeholder="<?php echo esc_attr_x( 'Search…', 'placeholder', 'aqualuxe' ); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s" 
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-dark-400 dark:text-dark-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <button type="submit" class="search-submit absolute inset-y-0 right-0 px-3 flex items-center bg-primary-600 hover:bg-primary-700 text-white rounded-r-md transition-colors">
            <span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </div>
    <?php if ( aqualuxe_is_woocommerce_active() && ! is_admin() ) : ?>
        <div class="search-options mt-2">
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-primary-600 rounded" name="post_type" value="product" <?php checked( get_query_var( 'post_type' ) === 'product' ); ?>>
                <span class="ml-2 text-sm text-dark-600 dark:text-dark-400"><?php esc_html_e( 'Search products only', 'aqualuxe' ); ?></span>
            </label>
        </div>
    <?php endif; ?>
</form>