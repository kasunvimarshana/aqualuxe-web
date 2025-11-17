<?php
/**
 * Template for displaying search forms
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$unique_id = wp_unique_id( 'search-form-' );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
        <?php echo esc_html_x( 'Search for:', 'label', 'aqualuxe' ); ?>
    </label>
    <div class="search-form-inner">
        <input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit">
            <?php echo aqualuxe_get_icon( 'search' ); ?>
            <span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
        </button>
    </div>
</form>