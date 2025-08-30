<?php
/**
 * Template part for displaying the announcement bar
 *
 * @package AquaLuxe
 */

// Check if announcement bar is enabled in customizer
$announcement_enabled = get_theme_mod( 'aqualuxe_announcement_bar_enable', true );
$announcement_text = get_theme_mod( 'aqualuxe_announcement_bar_text', __( 'Free shipping on orders over $100 | International shipping available', 'aqualuxe' ) );
$announcement_link = get_theme_mod( 'aqualuxe_announcement_bar_link', '' );
$announcement_bg = get_theme_mod( 'aqualuxe_announcement_bar_bg', '#0073aa' );
$announcement_color = get_theme_mod( 'aqualuxe_announcement_bar_color', '#ffffff' );

if ( ! $announcement_enabled || empty( $announcement_text ) ) {
    return;
}

$style = sprintf(
    'background-color: %1$s; color: %2$s;',
    esc_attr( $announcement_bg ),
    esc_attr( $announcement_color )
);
?>

<div class="announcement-bar py-2" style="<?php echo esc_attr( $style ); ?>">
    <div class="container mx-auto px-4">
        <div class="text-center text-sm">
            <?php if ( ! empty( $announcement_link ) ) : ?>
                <a href="<?php echo esc_url( $announcement_link ); ?>" class="announcement-link hover:underline">
                    <?php echo wp_kses_post( $announcement_text ); ?>
                </a>
            <?php else : ?>
                <?php echo wp_kses_post( $announcement_text ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>