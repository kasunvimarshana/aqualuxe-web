<?php
/**
 * Services Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

// Get services
$services = isset( $services ) ? $services : [];

// Get attributes
$columns = isset( $atts['columns'] ) ? absint( $atts['columns'] ) : 3;
$show_image = isset( $atts['show_image'] ) ? filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN ) : true;
$show_price = isset( $atts['show_price'] ) ? filter_var( $atts['show_price'], FILTER_VALIDATE_BOOLEAN ) : true;
$show_desc = isset( $atts['show_desc'] ) ? filter_var( $atts['show_desc'], FILTER_VALIDATE_BOOLEAN ) : true;
$show_button = isset( $atts['show_button'] ) ? filter_var( $atts['show_button'], FILTER_VALIDATE_BOOLEAN ) : true;
$button_text = isset( $atts['button_text'] ) ? sanitize_text_field( $atts['button_text'] ) : esc_html__( 'Book Now', 'aqualuxe' );
$class = isset( $atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '';

// Check if services exist
if ( empty( $services ) ) {
    echo '<p class="no-services">' . esc_html__( 'No services available.', 'aqualuxe' ) . '</p>';
    return;
}

// Get settings
$settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
$booking_page_url = $settings->get_booking_page_url();
?>

<div class="aqualuxe-services <?php echo esc_attr( $class ); ?>">
    <div class="services-grid columns-<?php echo esc_attr( $columns ); ?>">
        <?php foreach ( $services as $service ) : ?>
            <div class="service-item">
                <?php if ( $show_image && $service->get_thumbnail_url() ) : ?>
                    <div class="service-image">
                        <a href="<?php echo esc_url( $service->get_url() ); ?>">
                            <?php echo $service->get_thumbnail( 'medium' ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="service-content">
                    <h3 class="service-title">
                        <a href="<?php echo esc_url( $service->get_url() ); ?>"><?php echo esc_html( $service->get_title() ); ?></a>
                    </h3>
                    
                    <?php if ( $show_price ) : ?>
                        <div class="service-price"><?php echo wp_kses_post( $service->get_formatted_price() ); ?></div>
                    <?php endif; ?>
                    
                    <div class="service-meta">
                        <div class="service-duration"><?php echo esc_html( $service->get_formatted_duration() ); ?></div>
                        
                        <?php if ( $service->get_location() ) : ?>
                            <div class="service-location"><?php echo esc_html( $service->get_location() ); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ( $show_desc ) : ?>
                        <div class="service-description">
                            <?php echo wp_kses_post( wp_trim_words( $service->get_description(), 20 ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $show_button && $booking_page_url ) : ?>
                        <div class="service-actions">
                            <a href="<?php echo esc_url( add_query_arg( 'service_id', $service->get_id(), $booking_page_url ) ); ?>" class="service-book-button"><?php echo esc_html( $button_text ); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .aqualuxe-services .services-grid {
        display: grid;
        grid-template-columns: repeat(<?php echo esc_attr( $columns ); ?>, 1fr);
        gap: 30px;
    }
    
    @media (max-width: 1024px) {
        .aqualuxe-services .services-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 767px) {
        .aqualuxe-services .services-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .aqualuxe-services .service-item {
        border: 1px solid #eee;
        border-radius: 5px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .aqualuxe-services .service-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }
    
    .aqualuxe-services .service-image {
        position: relative;
        overflow: hidden;
    }
    
    .aqualuxe-services .service-image img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }
    
    .aqualuxe-services .service-item:hover .service-image img {
        transform: scale(1.05);
    }
    
    .aqualuxe-services .service-content {
        padding: 20px;
    }
    
    .aqualuxe-services .service-title {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 1.2em;
    }
    
    .aqualuxe-services .service-title a {
        text-decoration: none;
        color: #333;
    }
    
    .aqualuxe-services .service-price {
        font-size: 1.1em;
        font-weight: bold;
        margin-bottom: 10px;
        color: #0073aa;
    }
    
    .aqualuxe-services .service-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9em;
        color: #666;
    }
    
    .aqualuxe-services .service-description {
        margin-bottom: 15px;
        font-size: 0.9em;
        line-height: 1.5;
    }
    
    .aqualuxe-services .service-actions {
        margin-top: 15px;
    }
    
    .aqualuxe-services .service-book-button {
        display: inline-block;
        padding: 8px 16px;
        background-color: #0073aa;
        color: #fff;
        text-decoration: none;
        border-radius: 3px;
        transition: background-color 0.3s ease;
    }
    
    .aqualuxe-services .service-book-button:hover {
        background-color: #005f8b;
    }
</style>