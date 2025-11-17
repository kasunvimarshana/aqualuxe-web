<?php
/**
 * Features Grid Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Extract variables
extract( $args );

// Set CSS classes based on settings
$features_classes = [
    'features-grid',
    'features-grid--' . $layout,
    'features-grid--' . $style,
    'features-grid--cols-' . $columns,
];

if ( 'none' !== $animation ) {
    $features_classes[] = 'features-grid--animate-' . $animation;
}

$features_class = implode( ' ', $features_classes );

// Get column class based on columns setting
$column_class = 'features-grid__item';
switch ( $columns ) {
    case 1:
        $column_class .= ' col-12';
        break;
    case 2:
        $column_class .= ' col-md-6';
        break;
    case 3:
        $column_class .= ' col-md-6 col-lg-4';
        break;
    case 4:
        $column_class .= ' col-md-6 col-lg-3';
        break;
    case 6:
        $column_class .= ' col-6 col-md-4 col-lg-2';
        break;
    default:
        $column_class .= ' col-md-6 col-lg-4';
}

// Set feature item class based on style
$feature_item_class = 'features-grid__feature';
if ( 'boxed' === $style ) {
    $feature_item_class .= ' features-grid__feature--boxed';
} elseif ( 'bordered' === $style ) {
    $feature_item_class .= ' features-grid__feature--bordered';
} elseif ( 'minimal' === $style ) {
    $feature_item_class .= ' features-grid__feature--minimal';
} elseif ( 'icon-top' === $style ) {
    $feature_item_class .= ' features-grid__feature--icon-top';
} elseif ( 'icon-left' === $style ) {
    $feature_item_class .= ' features-grid__feature--icon-left';
}

// Check if we have features
if ( empty( $features ) ) {
    return;
}
?>

<section class="<?php echo esc_attr( $features_class ); ?>">
    <div class="container">
        <?php if ( ! empty( $title ) || ! empty( $subtitle ) || ! empty( $description ) ) : ?>
            <div class="features-grid__header">
                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="features-grid__title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $subtitle ) ) : ?>
                    <h3 class="features-grid__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="features-grid__description">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( 'carousel' === $layout ) : ?>
            <div class="features-grid__carousel swiper">
                <div class="swiper-wrapper">
                    <?php foreach ( $features as $index => $feature ) : ?>
                        <div class="swiper-slide">
                            <div class="<?php echo esc_attr( $feature_item_class ); ?>">
                                <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                    <div class="features-grid__feature-icon">
                                        <i class="fas fa-<?php echo esc_attr( $feature['icon'] ); ?>" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $feature['title'] ) ) : ?>
                                    <h4 class="features-grid__feature-title"><?php echo esc_html( $feature['title'] ); ?></h4>
                                <?php endif; ?>

                                <?php if ( ! empty( $feature['description'] ) ) : ?>
                                    <div class="features-grid__feature-description">
                                        <?php echo wp_kses_post( $feature['description'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $feature['link'] ) && ! empty( $feature['link_text'] ) ) : ?>
                                    <a href="<?php echo esc_url( $feature['link'] ); ?>" class="features-grid__feature-link">
                                        <?php echo esc_html( $feature['link_text'] ); ?>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        <?php else : ?>
            <div class="features-grid__container row">
                <?php foreach ( $features as $index => $feature ) : ?>
                    <div class="<?php echo esc_attr( $column_class ); ?>" data-aos="<?php echo esc_attr( $animation ); ?>" data-aos-delay="<?php echo esc_attr( $index * 100 ); ?>">
                        <div class="<?php echo esc_attr( $feature_item_class ); ?>">
                            <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                <div class="features-grid__feature-icon">
                                    <i class="fas fa-<?php echo esc_attr( $feature['icon'] ); ?>" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>

                            <div class="features-grid__feature-content">
                                <?php if ( ! empty( $feature['title'] ) ) : ?>
                                    <h4 class="features-grid__feature-title"><?php echo esc_html( $feature['title'] ); ?></h4>
                                <?php endif; ?>

                                <?php if ( ! empty( $feature['description'] ) ) : ?>
                                    <div class="features-grid__feature-description">
                                        <?php echo wp_kses_post( $feature['description'] ); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ( ! empty( $feature['link'] ) && ! empty( $feature['link_text'] ) ) : ?>
                                    <a href="<?php echo esc_url( $feature['link'] ); ?>" class="features-grid__feature-link">
                                        <?php echo esc_html( $feature['link_text'] ); ?>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>