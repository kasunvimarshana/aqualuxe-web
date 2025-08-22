<?php
/**
 * Call to Action Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Extract variables
extract( $args );

// Set CSS classes based on settings
$cta_classes = [
    'call-to-action',
    'call-to-action--' . $layout,
    'call-to-action--' . $width,
    'call-to-action--padding-' . $padding,
    'call-to-action--align-' . $alignment,
    'call-to-action--bg-' . $background_type,
];

if ( 'none' !== $animation ) {
    $cta_classes[] = 'call-to-action--animate-' . $animation;
}

$cta_class = implode( ' ', $cta_classes );

// Set inline styles
$cta_style = '';
$content_style = '';

if ( 'color' === $background_type ) {
    $cta_style .= 'background-color: ' . esc_attr( $background_color ) . ';';
} elseif ( 'image' === $background_type && ! empty( $background_image ) ) {
    $cta_style .= 'background-image: url(' . esc_url( $background_image ) . ');';
} elseif ( 'gradient' === $background_type ) {
    $cta_style .= 'background: linear-gradient(135deg, ' . esc_attr( $background_color ) . ' 0%, ' . esc_attr( adjust_brightness( $background_color, 30 ) ) . ' 100%);';
}

if ( ! empty( $text_color ) ) {
    $content_style .= 'color: ' . esc_attr( $text_color ) . ';';
}

// Set overlay opacity
$overlay_style = '';
if ( 'image' === $background_type || 'video' === $background_type ) {
    $overlay_opacity = absint( $overlay_opacity ) / 100;
    $overlay_style = 'opacity: ' . $overlay_opacity . ';';
}

/**
 * Helper function to adjust brightness of a hex color
 *
 * @param string $hex Hex color code.
 * @param int    $steps Steps to adjust brightness (positive for lighter, negative for darker).
 * @return string
 */
function adjust_brightness( $hex, $steps ) {
    // Remove # if present
    $hex = ltrim( $hex, '#' );
    
    // Convert to RGB
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    
    // Adjust brightness
    $r = max( 0, min( 255, $r + $steps ) );
    $g = max( 0, min( 255, $g + $steps ) );
    $b = max( 0, min( 255, $b + $steps ) );
    
    // Convert back to hex
    return '#' . sprintf( '%02x%02x%02x', $r, $g, $b );
}
?>

<section class="<?php echo esc_attr( $cta_class ); ?>" style="<?php echo esc_attr( $cta_style ); ?>">
    <?php if ( 'video' === $background_type && ! empty( $background_video ) ) : ?>
        <div class="call-to-action__video-container">
            <?php if ( strpos( $background_video, 'youtube.com' ) !== false || strpos( $background_video, 'youtu.be' ) !== false ) : ?>
                <?php
                // Extract YouTube video ID
                $video_id = '';
                if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $background_video, $matches ) ) {
                    $video_id = $matches[1];
                }
                ?>
                <?php if ( ! empty( $video_id ) ) : ?>
                    <div class="call-to-action__youtube-video" data-video-id="<?php echo esc_attr( $video_id ); ?>"></div>
                <?php endif; ?>
            <?php else : ?>
                <video class="call-to-action__video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url( $background_video ); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ( 'image' === $background_type || 'video' === $background_type ) : ?>
        <div class="call-to-action__overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <?php endif; ?>

    <div class="call-to-action__container container">
        <div class="call-to-action__content" style="<?php echo esc_attr( $content_style ); ?>">
            <?php if ( 'split' === $layout ) : ?>
                <div class="call-to-action__row">
                    <div class="call-to-action__col call-to-action__col--text">
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <h3 class="call-to-action__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
                        <?php endif; ?>

                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="call-to-action__title"><?php echo esc_html( $title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( ! empty( $description ) ) : ?>
                            <div class="call-to-action__description">
                                <?php echo wp_kses_post( $description ); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="call-to-action__col call-to-action__col--buttons">
                        <?php if ( ! empty( $button_primary ) || ! empty( $button_secondary ) ) : ?>
                            <div class="call-to-action__buttons">
                                <?php if ( ! empty( $button_primary ) ) : ?>
                                    <a href="<?php echo esc_url( $button_primary_url ); ?>" class="call-to-action__button call-to-action__button--primary btn btn-primary">
                                        <?php echo esc_html( $button_primary ); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ( ! empty( $button_secondary ) ) : ?>
                                    <a href="<?php echo esc_url( $button_secondary_url ); ?>" class="call-to-action__button call-to-action__button--secondary btn btn-secondary">
                                        <?php echo esc_html( $button_secondary ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <?php if ( ! empty( $subtitle ) ) : ?>
                    <h3 class="call-to-action__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="call-to-action__title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="call-to-action__description">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $button_primary ) || ! empty( $button_secondary ) ) : ?>
                    <div class="call-to-action__buttons">
                        <?php if ( ! empty( $button_primary ) ) : ?>
                            <a href="<?php echo esc_url( $button_primary_url ); ?>" class="call-to-action__button call-to-action__button--primary btn btn-primary">
                                <?php echo esc_html( $button_primary ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( ! empty( $button_secondary ) ) : ?>
                            <a href="<?php echo esc_url( $button_secondary_url ); ?>" class="call-to-action__button call-to-action__button--secondary btn btn-secondary">
                                <?php echo esc_html( $button_secondary ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>