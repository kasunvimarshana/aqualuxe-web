<?php
/**
 * Archive Service Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

// Get settings
$settings = get_option( 'aqualuxe_service_settings', [] );
$columns = isset( $settings['service_columns'] ) ? (int) $settings['service_columns'] : 3;

// Get categories
$categories = get_terms( [
    'taxonomy' => 'service_category',
    'hide_empty' => true,
] );

?>

<div class="aqualuxe-container">
    <div class="aqualuxe-service-archive">
        <header class="aqualuxe-archive-header">
            <h1 class="aqualuxe-archive-title"><?php post_type_archive_title(); ?></h1>
            
            <?php if ( ! empty( $categories ) ) : ?>
                <div class="aqualuxe-service-filter">
                    <select>
                        <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                        <?php foreach ( $categories as $category ) : ?>
                            <option value="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_html( $category->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="aqualuxe-service-sort">
                <select>
                    <option value="title-asc"><?php esc_html_e( 'Name (A-Z)', 'aqualuxe' ); ?></option>
                    <option value="title-desc"><?php esc_html_e( 'Name (Z-A)', 'aqualuxe' ); ?></option>
                    <option value="price-asc"><?php esc_html_e( 'Price (Low to High)', 'aqualuxe' ); ?></option>
                    <option value="price-desc"><?php esc_html_e( 'Price (High to Low)', 'aqualuxe' ); ?></option>
                    <option value="date-desc"><?php esc_html_e( 'Newest First', 'aqualuxe' ); ?></option>
                    <option value="date-asc"><?php esc_html_e( 'Oldest First', 'aqualuxe' ); ?></option>
                </select>
            </div>
            
            <div class="aqualuxe-service-search">
                <input type="text" placeholder="<?php esc_attr_e( 'Search services...', 'aqualuxe' ); ?>">
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="aqualuxe-service-grid">
                <?php while ( have_posts() ) : the_post(); 
                    // Create service object
                    $service = new \AquaLuxe\Modules\Services\Inc\Service( get_the_ID() );
                    $data = $service->get_data();
                ?>
                    <div class="aqualuxe-service-column <?php echo esc_attr( 'aqualuxe-service-column-' . $columns ); ?>">
                        <div class="aqualuxe-service-item">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="aqualuxe-service-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium_large' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="aqualuxe-service-content">
                                <h2 class="aqualuxe-service-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <?php if ( isset( $settings['show_pricing'] ) && $settings['show_pricing'] ) : ?>
                                    <div class="aqualuxe-service-price">
                                        <?php echo wp_kses_post( $service->get_formatted_price_with_type() ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-service-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <div class="aqualuxe-service-meta">
                                    <?php if ( $service->get_duration() ) : ?>
                                        <div class="aqualuxe-service-duration">
                                            <span class="meta-icon dashicons dashicons-clock"></span>
                                            <?php echo esc_html( $service->get_duration( true ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $service->is_bookable() ) : ?>
                                        <div class="aqualuxe-service-bookable">
                                            <span class="meta-icon dashicons dashicons-calendar-alt"></span>
                                            <?php esc_html_e( 'Bookable', 'aqualuxe' ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="aqualuxe-service-actions">
                                    <a href="<?php the_permalink(); ?>" class="button"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
                                    
                                    <?php if ( $service->is_bookable() ) : ?>
                                        <a href="<?php echo esc_url( $service->get_booking_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Book Now', 'aqualuxe' ); ?></a>
                                    <?php endif; ?>
                                    
                                    <a href="#" class="add-to-comparison" data-service-id="<?php echo esc_attr( $service->get_id() ); ?>" data-service-title="<?php echo esc_attr( $service->get_title() ); ?>">
                                        <?php esc_html_e( 'Compare', 'aqualuxe' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="aqualuxe-pagination">
                <?php
                the_posts_pagination( [
                    'prev_text' => '&larr; ' . __( 'Previous', 'aqualuxe' ),
                    'next_text' => __( 'Next', 'aqualuxe' ) . ' &rarr;',
                ] );
                ?>
            </div>
            
            <div class="aqualuxe-service-comparison-bar">
                <div class="comparison-count">0</div>
                <div class="comparison-text"><?php esc_html_e( 'Services Selected', 'aqualuxe' ); ?></div>
                <a href="<?php echo esc_url( home_url( '/service-comparison/' ) ); ?>" class="button view-comparison"><?php esc_html_e( 'Compare Services', 'aqualuxe' ); ?></a>
            </div>
        <?php else : ?>
            <div class="aqualuxe-no-services">
                <p><?php esc_html_e( 'No services found.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();