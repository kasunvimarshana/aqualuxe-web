<?php
/**
 * Testimonials Slider Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Extract variables
extract( $args );

// Set CSS classes based on settings
$testimonials_classes = [
    'testimonials-slider',
    'testimonials-slider--' . $layout,
    'testimonials-slider--' . $style,
];

$testimonials_class = implode( ' ', $testimonials_classes );

// Get column class based on columns setting
$column_class = 'testimonials-slider__item';
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
    default:
        $column_class .= ' col-md-6 col-lg-4';
}

// Set testimonial item class based on style
$testimonial_item_class = 'testimonials-slider__testimonial';
if ( 'cards' === $style ) {
    $testimonial_item_class .= ' testimonials-slider__testimonial--card';
} elseif ( 'minimal' === $style ) {
    $testimonial_item_class .= ' testimonials-slider__testimonial--minimal';
} elseif ( 'bordered' === $style ) {
    $testimonial_item_class .= ' testimonials-slider__testimonial--bordered';
} elseif ( 'quotes' === $style ) {
    $testimonial_item_class .= ' testimonials-slider__testimonial--quotes';
} elseif ( 'modern' === $style ) {
    $testimonial_item_class .= ' testimonials-slider__testimonial--modern';
}

// Check if we have testimonials
if ( empty( $testimonials ) ) {
    return;
}

// Set data attributes for slider
$slider_data = [
    'data-autoplay="' . ( $autoplay ? 'true' : 'false' ) . '"',
    'data-autoplay-speed="' . esc_attr( $autoplay_speed ) . '"',
    'data-animation="' . esc_attr( $animation ) . '"',
];

$slider_data_attr = implode( ' ', $slider_data );
?>

<section class="<?php echo esc_attr( $testimonials_class ); ?>">
    <div class="container">
        <?php if ( ! empty( $title ) || ! empty( $subtitle ) || ! empty( $description ) ) : ?>
            <div class="testimonials-slider__header">
                <?php if ( ! empty( $subtitle ) ) : ?>
                    <h3 class="testimonials-slider__subtitle"><?php echo esc_html( $subtitle ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="testimonials-slider__title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $description ) ) : ?>
                    <div class="testimonials-slider__description">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( 'slider' === $layout ) : ?>
            <div class="testimonials-slider__carousel swiper" <?php echo $slider_data_attr; ?>>
                <div class="swiper-wrapper">
                    <?php foreach ( $testimonials as $testimonial ) : ?>
                        <div class="swiper-slide">
                            <div class="<?php echo esc_attr( $testimonial_item_class ); ?>">
                                <?php if ( 'quotes' === $style ) : ?>
                                    <div class="testimonials-slider__quote-icon">
                                        <i class="fas fa-quote-left" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="testimonials-slider__content">
                                    <?php echo wp_kses_post( $testimonial['content'] ); ?>
                                </div>

                                <?php if ( $show_rating && ! empty( $testimonial['rating'] ) ) : ?>
                                    <div class="testimonials-slider__rating">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <?php if ( $i <= $testimonial['rating'] ) : ?>
                                                <i class="fas fa-star" aria-hidden="true"></i>
                                            <?php else : ?>
                                                <i class="far fa-star" aria-hidden="true"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="testimonials-slider__author">
                                    <?php if ( $show_image && ! empty( $testimonial['image'] ) ) : ?>
                                        <div class="testimonials-slider__author-image">
                                            <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                                        </div>
                                    <?php endif; ?>

                                    <div class="testimonials-slider__author-info">
                                        <?php if ( ! empty( $testimonial['name'] ) ) : ?>
                                            <h4 class="testimonials-slider__author-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                        <?php endif; ?>

                                        <?php if ( $show_company && ( ! empty( $testimonial['position'] ) || ! empty( $testimonial['company'] ) ) ) : ?>
                                            <p class="testimonials-slider__author-position">
                                                <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                                    <span class="testimonials-slider__author-title"><?php echo esc_html( $testimonial['position'] ); ?></span>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $testimonial['position'] ) && ! empty( $testimonial['company'] ) ) : ?>
                                                    <span class="testimonials-slider__author-separator">, </span>
                                                <?php endif; ?>

                                                <?php if ( ! empty( $testimonial['company'] ) ) : ?>
                                                    <span class="testimonials-slider__author-company"><?php echo esc_html( $testimonial['company'] ); ?></span>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="testimonials-slider__pagination swiper-pagination"></div>
                <div class="testimonials-slider__navigation">
                    <div class="testimonials-slider__button-prev swiper-button-prev"></div>
                    <div class="testimonials-slider__button-next swiper-button-next"></div>
                </div>
            </div>
        <?php else : ?>
            <div class="testimonials-slider__container row">
                <?php foreach ( $testimonials as $testimonial ) : ?>
                    <div class="<?php echo esc_attr( $column_class ); ?>">
                        <div class="<?php echo esc_attr( $testimonial_item_class ); ?>">
                            <?php if ( 'quotes' === $style ) : ?>
                                <div class="testimonials-slider__quote-icon">
                                    <i class="fas fa-quote-left" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>

                            <div class="testimonials-slider__content">
                                <?php echo wp_kses_post( $testimonial['content'] ); ?>
                            </div>

                            <?php if ( $show_rating && ! empty( $testimonial['rating'] ) ) : ?>
                                <div class="testimonials-slider__rating">
                                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                        <?php if ( $i <= $testimonial['rating'] ) : ?>
                                            <i class="fas fa-star" aria-hidden="true"></i>
                                        <?php else : ?>
                                            <i class="far fa-star" aria-hidden="true"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>

                            <div class="testimonials-slider__author">
                                <?php if ( $show_image && ! empty( $testimonial['image'] ) ) : ?>
                                    <div class="testimonials-slider__author-image">
                                        <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="testimonials-slider__author-info">
                                    <?php if ( ! empty( $testimonial['name'] ) ) : ?>
                                        <h4 class="testimonials-slider__author-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                                    <?php endif; ?>

                                    <?php if ( $show_company && ( ! empty( $testimonial['position'] ) || ! empty( $testimonial['company'] ) ) ) : ?>
                                        <p class="testimonials-slider__author-position">
                                            <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                                <span class="testimonials-slider__author-title"><?php echo esc_html( $testimonial['position'] ); ?></span>
                                            <?php endif; ?>

                                            <?php if ( ! empty( $testimonial['position'] ) && ! empty( $testimonial['company'] ) ) : ?>
                                                <span class="testimonials-slider__author-separator">, </span>
                                            <?php endif; ?>

                                            <?php if ( ! empty( $testimonial['company'] ) ) : ?>
                                                <span class="testimonials-slider__author-company"><?php echo esc_html( $testimonial['company'] ); ?></span>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>