<?php
/**
 * Template part for displaying social media links
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get social media links from theme options
$facebook = get_theme_mod( 'aqualuxe_facebook', '#' );
$twitter = get_theme_mod( 'aqualuxe_twitter', '#' );
$instagram = get_theme_mod( 'aqualuxe_instagram', '#' );
$youtube = get_theme_mod( 'aqualuxe_youtube', '' );
$pinterest = get_theme_mod( 'aqualuxe_pinterest', '' );
$linkedin = get_theme_mod( 'aqualuxe_linkedin', '' );

// Check if any social media links are set
if ( $facebook || $twitter || $instagram || $youtube || $pinterest || $linkedin ) :
?>
<div class="social-links">
    <ul class="social-icons">
        <?php if ( $facebook ) : ?>
            <li><a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
        <?php endif; ?>
        
        <?php if ( $twitter ) : ?>
            <li><a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a></li>
        <?php endif; ?>
        
        <?php if ( $instagram ) : ?>
            <li><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a></li>
        <?php endif; ?>
        
        <?php if ( $youtube ) : ?>
            <li><a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
        <?php endif; ?>
        
        <?php if ( $pinterest ) : ?>
            <li><a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-pinterest-p"></i></a></li>
        <?php endif; ?>
        
        <?php if ( $linkedin ) : ?>
            <li><a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a></li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>