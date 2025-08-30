<?php
/**
 * Single Service Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

// Create service object
$service = new \AquaLuxe\Modules\Services\Inc\Service( get_the_ID() );
$data = $service->get_data();

?>

<div class="aqualuxe-container">
    <div class="aqualuxe-service-single">
        <div class="aqualuxe-service-header">
            <h1 class="aqualuxe-service-title"><?php the_title(); ?></h1>
            
            <?php if ( ! empty( $data['categories'] ) ) : ?>
                <div class="aqualuxe-service-categories">
                    <?php foreach ( $data['categories'] as $category ) : ?>
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="aqualuxe-service-category"><?php echo esc_html( $category->name ); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( has_post_thumbnail() ) : ?>
            <div class="aqualuxe-service-image">
                <?php the_post_thumbnail( 'large' ); ?>
            </div>
        <?php endif; ?>

        <div class="aqualuxe-service-details">
            <div class="aqualuxe-service-meta">
                <?php if ( $service->get_price() ) : ?>
                    <div class="aqualuxe-service-meta-item">
                        <div class="aqualuxe-service-meta-label"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></div>
                        <div class="aqualuxe-service-meta-value aqualuxe-service-price">
                            <?php echo wp_kses_post( $service->get_formatted_price_with_type() ); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $service->get_duration() ) : ?>
                    <div class="aqualuxe-service-meta-item">
                        <div class="aqualuxe-service-meta-label"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></div>
                        <div class="aqualuxe-service-meta-value">
                            <?php echo esc_html( $service->get_duration( true ) ); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $service->get_location() ) : ?>
                    <div class="aqualuxe-service-meta-item">
                        <div class="aqualuxe-service-meta-label"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></div>
                        <div class="aqualuxe-service-meta-value">
                            <?php echo esc_html( $service->get_location() ); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( $service->is_bookable() ) : ?>
                    <div class="aqualuxe-service-meta-item">
                        <div class="aqualuxe-service-meta-label"><?php esc_html_e( 'Availability', 'aqualuxe' ); ?></div>
                        <div class="aqualuxe-service-meta-value">
                            <span class="aqualuxe-service-available"><?php esc_html_e( 'Available for Booking', 'aqualuxe' ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="aqualuxe-service-content">
            <?php the_content(); ?>
        </div>

        <?php
        // Service features
        $features = $service->get_features();
        if ( ! empty( $features ) ) :
        ?>
            <div class="aqualuxe-service-features">
                <h3><?php esc_html_e( 'Service Features', 'aqualuxe' ); ?></h3>
                <ul class="aqualuxe-service-features-list">
                    <?php foreach ( $features as $feature ) : ?>
                        <li><?php echo esc_html( $feature ); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php
        // Service booking
        if ( $service->is_bookable() ) :
        ?>
            <div class="aqualuxe-service-booking">
                <h3><?php esc_html_e( 'Book This Service', 'aqualuxe' ); ?></h3>
                
                <form class="aqualuxe-service-booking-form" data-service-id="<?php echo esc_attr( $service->get_id() ); ?>">
                    <input type="hidden" name="service_id" value="<?php echo esc_attr( $service->get_id() ); ?>">
                    
                    <div class="aqualuxe-service-booking-fields">
                        <div class="aqualuxe-service-booking-field">
                            <label for="aqualuxe-service-booking-date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
                            <input type="date" id="aqualuxe-service-booking-date" name="booking_date" class="aqualuxe-service-booking-date" required min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>">
                        </div>
                        
                        <div class="aqualuxe-service-booking-field">
                            <label for="aqualuxe-service-booking-time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
                            <select id="aqualuxe-service-booking-time" name="booking_time" class="aqualuxe-service-booking-time" required>
                                <option value=""><?php esc_html_e( 'Select a date first', 'aqualuxe' ); ?></option>
                            </select>
                        </div>
                        
                        <div class="aqualuxe-service-booking-field">
                            <label for="aqualuxe-service-booking-name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?></label>
                            <input type="text" id="aqualuxe-service-booking-name" name="booking_name" required>
                        </div>
                        
                        <div class="aqualuxe-service-booking-field">
                            <label for="aqualuxe-service-booking-email"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?></label>
                            <input type="email" id="aqualuxe-service-booking-email" name="booking_email" required>
                        </div>
                        
                        <div class="aqualuxe-service-booking-field">
                            <label for="aqualuxe-service-booking-phone"><?php esc_html_e( 'Your Phone', 'aqualuxe' ); ?></label>
                            <input type="tel" id="aqualuxe-service-booking-phone" name="booking_phone" required>
                        </div>
                        
                        <div class="aqualuxe-service-booking-field aqualuxe-service-booking-field-full">
                            <label for="aqualuxe-service-booking-notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                            <textarea id="aqualuxe-service-booking-notes" name="booking_notes" rows="4"></textarea>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-service-booking-submit">
                        <button type="submit" class="button"><?php esc_html_e( 'Book Now', 'aqualuxe' ); ?></button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <?php
        // Related services
        $related_services = $service->get_related_services( 3 );
        if ( ! empty( $related_services ) ) :
        ?>
            <div class="aqualuxe-service-related">
                <h3><?php esc_html_e( 'Related Services', 'aqualuxe' ); ?></h3>
                
                <div class="aqualuxe-service-grid">
                    <?php foreach ( $related_services as $related_service ) : 
                        $related_data = $related_service->get_data();
                    ?>
                        <div class="aqualuxe-service-column">
                            <div class="aqualuxe-service-item">
                                <?php if ( ! empty( $related_data['thumbnail'] ) ) : ?>
                                    <div class="aqualuxe-service-thumbnail">
                                        <a href="<?php echo esc_url( $related_data['permalink'] ); ?>">
                                            <img src="<?php echo esc_url( $related_data['thumbnail'] ); ?>" alt="<?php echo esc_attr( $related_data['title'] ); ?>">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-service-content">
                                    <h3 class="aqualuxe-service-title">
                                        <a href="<?php echo esc_url( $related_data['permalink'] ); ?>"><?php echo esc_html( $related_data['title'] ); ?></a>
                                    </h3>
                                    
                                    <div class="aqualuxe-service-price">
                                        <?php echo wp_kses_post( $related_service->get_formatted_price_with_type() ); ?>
                                    </div>
                                    
                                    <div class="aqualuxe-service-button">
                                        <a href="<?php echo esc_url( $related_data['permalink'] ); ?>" class="button"><?php esc_html_e( 'View Service', 'aqualuxe' ); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();