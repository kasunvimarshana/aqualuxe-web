<?php
/**
 * Template part for displaying header before content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="header-before">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <?php
        // Display top bar if enabled
        if ( get_theme_mod( 'aqualuxe_top_bar', true ) ) :
            ?>
            <div class="top-bar">
                <div class="top-bar-left">
                    <?php
                    // Display top bar content
                    echo wp_kses_post( get_theme_mod( 'aqualuxe_top_bar_content', '' ) );
                    ?>
                </div>
                <div class="top-bar-right">
                    <?php
                    // Display language switcher
                    aqualuxe_language_switcher();
                    
                    // Display currency switcher
                    aqualuxe_currency_switcher();
                    
                    // Display secondary navigation
                    aqualuxe_secondary_navigation();
                    ?>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div>
</div>