<?php
/**
 * Services List Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Get settings
$settings = get_option( 'aqualuxe_service_settings', [] );
$show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

// Start list
echo '<div class="aqualuxe-service-list">';

foreach ( $services as $post ) {
    // Create service object
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    $data = $service->get_data();
    
    if ( empty( $data ) ) {
        continue;
    }
    
    // Start item
    echo '<div class="aqualuxe-service-item">';
    
    // Service thumbnail
    if ( ! empty( $data['thumbnail'] ) ) {
        echo '<div class="aqualuxe-service-thumbnail">';
        echo '<a href="' . esc_url( $data['permalink'] ) . '">';
        echo '<img src="' . esc_url( $data['thumbnail'] ) . '" alt="' . esc_attr( $data['title'] ) . '">';
        echo '</a>';
        echo '</div>';
    }
    
    // Service content
    echo '<div class="aqualuxe-service-content">';
    
    // Service title
    echo '<h3 class="aqualuxe-service-title">';
    echo '<a href="' . esc_url( $data['permalink'] ) . '">' . esc_html( $data['title'] ) . '</a>';
    echo '</h3>';
    
    // Service price
    if ( $show_pricing ) {
        echo '<div class="aqualuxe-service-price">';
        echo wp_kses_post( $service->get_formatted_price_with_type() );
        echo '</div>';
    }
    
    // Service meta
    echo '<div class="aqualuxe-service-meta">';
    
    // Duration
    if ( $service->get_duration() ) {
        echo '<div class="aqualuxe-service-duration">';
        echo '<span class="meta-icon dashicons dashicons-clock"></span> ';
        echo esc_html( $service->get_duration( true ) );
        echo '</div>';
    }
    
    // Location
    if ( $service->get_location() ) {
        echo '<div class="aqualuxe-service-location">';
        echo '<span class="meta-icon dashicons dashicons-location"></span> ';
        echo esc_html( $service->get_location() );
        echo '</div>';
    }
    
    // Bookable
    if ( $service->is_bookable() ) {
        echo '<div class="aqualuxe-service-bookable">';
        echo '<span class="meta-icon dashicons dashicons-calendar-alt"></span> ';
        echo esc_html__( 'Bookable', 'aqualuxe' );
        echo '</div>';
    }
    
    echo '</div>'; // End service meta
    
    // Service excerpt
    if ( ! empty( $data['excerpt'] ) ) {
        echo '<div class="aqualuxe-service-excerpt">';
        echo wp_kses_post( $data['excerpt'] );
        echo '</div>';
    }
    
    // Service features
    $features = $service->get_features();
    if ( ! empty( $features ) ) {
        echo '<div class="aqualuxe-service-features">';
        echo '<h4>' . esc_html__( 'Features', 'aqualuxe' ) . '</h4>';
        echo '<ul class="aqualuxe-service-features-list">';
        
        // Show up to 3 features
        $feature_count = min( count( $features ), 3 );
        for ( $i = 0; $i < $feature_count; $i++ ) {
            echo '<li>' . esc_html( $features[$i] ) . '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
    
    // Service actions
    echo '<div class="aqualuxe-service-actions">';
    echo '<a href="' . esc_url( $data['permalink'] ) . '" class="button">' . esc_html__( 'View Details', 'aqualuxe' ) . '</a>';
    
    if ( $service->is_bookable() ) {
        echo '<a href="' . esc_url( $service->get_booking_url() ) . '" class="button button-secondary">' . esc_html__( 'Book Now', 'aqualuxe' ) . '</a>';
    }
    
    echo '<a href="#" class="add-to-comparison" data-service-id="' . esc_attr( $service->get_id() ) . '" data-service-title="' . esc_attr( $service->get_title() ) . '">';
    echo esc_html__( 'Compare', 'aqualuxe' );
    echo '</a>';
    
    echo '</div>'; // End service actions
    
    echo '</div>'; // End service content
    echo '</div>'; // End service item
}

echo '</div>'; // End list

// Show comparison bar if there are services
if ( ! empty( $services ) ) {
    echo '<div class="aqualuxe-service-comparison-bar">';
    echo '<div class="comparison-count">0</div>';
    echo '<div class="comparison-text">' . esc_html__( 'Services Selected', 'aqualuxe' ) . '</div>';
    echo '<a href="' . esc_url( home_url( '/service-comparison/' ) ) . '" class="button view-comparison">' . esc_html__( 'Compare Services', 'aqualuxe' ) . '</a>';
    echo '</div>';
}