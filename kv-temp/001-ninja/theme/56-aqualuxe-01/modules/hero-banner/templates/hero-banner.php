<?php
/**
 * Hero Banner Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Extract variables
extract( $args );

// Set CSS classes based on settings
$hero_classes = [
    'hero-banner',
    'hero-banner--' . $height,
    'hero-banner--align-' . $alignment,
    'hero-banner--bg-' . $background_type,
];

if ( 'none' !== $animation ) {
    $hero_classes[] = 'hero-banner--animate-' . $animation;
}

$hero_class = implode( ' ', $hero_classes );

// Set inline styles
$hero_style = '';
$content_style = '';

if ( 'color' === $background_type ) {
    $hero_style .= 'background-color: ' . esc_attr( $background_color ) . ';';
} elseif ( 'image' === $background_type && ! empty( $background_image ) ) {
    $hero_style .= 'background-image: url(' . esc_url( $background_image ) . ');';
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
?>

<section class="<?php echo esc_attr( $hero_class ); ?>" style="<?php echo esc_attr( $hero_style ); ?>">
    <?php if ( 'video' === $background_type && ! empty( $background_video ) ) : ?>
        <div class="hero-banner__video-container">
            <?php if ( strpos( $background_video, 'youtube.com' ) !== false || strpos( $background_video, 'youtu.be' ) !== false ) : ?>
                <?php
                // Extract YouTube video ID
                $video_id = '';
                if ( preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $background_video, $matches ) ) {
                    $video_id = $matches[1];
                }
                ?>
                <?php if ( ! empty( $video_id ) ) : ?>
                    <div class="hero-banner__youtube-video" data-video-id="<?php echo esc_attr( $video_id ); ?>"></div>
                <?php endif; ?>
            <?php else : ?>
                <video class="hero-banner__video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url( $background_video ); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ( 'image' === $background_type || 'video' === $background_type ) : ?>
        <div class="hero-banner__overlay" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <?php endif; ?>

    <div class="hero-banner__container container">
        <div class="hero-banner__content" style="<?php echo esc_attr( $content_style ); ?>">
            <?php if ( ! empty( $title ) ) : ?>
                <h1 class="hero-banner__title"><?php echo esc_html( $title ); ?></h1>
            <?php endif; ?>

            <?php if ( ! empty( $subtitle ) ) : ?>
                <h2 class="hero-banner__subtitle"><?php echo esc_html( $subtitle ); ?></h2>
            <?php endif; ?>

            <?php if ( ! empty( $description ) ) : ?>
                <div class="hero-banner__description">
                    <?php echo wp_kses_post( $description ); ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $button_primary ) || ! empty( $button_secondary ) ) : ?>
                <div class="hero-banner__buttons">
                    <?php if ( ! empty( $button_primary ) ) : ?>
                        <a href="<?php echo esc_url( $button_primary_url ); ?>" class="hero-banner__button hero-banner__button--primary btn btn-primary">
                            <?php echo esc_html( $button_primary ); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $button_secondary ) ) : ?>
                        <a href="<?php echo esc_url( $button_secondary_url ); ?>" class="hero-banner__button hero-banner__button--secondary btn btn-secondary">
                            <?php echo esc_html( $button_secondary ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>