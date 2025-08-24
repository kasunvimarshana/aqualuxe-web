<?php
/**
 * Service Comparison Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Get services
$services = [];

// If IDs are provided, use them
if ( ! empty( $atts['ids'] ) ) {
    $service_ids = explode( ',', $atts['ids'] );
    
    foreach ( $service_ids as $service_id ) {
        $post = get_post( $service_id );
        
        if ( $post && 'aqualuxe_service' === $post->post_type ) {
            $services[] = $post;
        }
    }
}
// Otherwise, use category
elseif ( ! empty( $atts['category'] ) ) {
    $args = [
        'post_type'      => 'aqualuxe_service',
        'posts_per_page' => -1,
        'tax_query'      => [
            [
                'taxonomy' => 'service_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            ],
        ],
    ];
    
    $services = get_posts( $args );
}

// If no services found, show message
if ( empty( $services ) ) {
    echo '<div class="aqualuxe-service-comparison-empty">';
    echo '<p>' . esc_html__( 'No services found for comparison.', 'aqualuxe' ) . '</p>';
    echo '</div>';
    return;
}

// Get settings
$settings = get_option( 'aqualuxe_service_settings', [] );
$show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

// Start comparison table
echo '<div class="aqualuxe-service-comparison">';
echo '<table class="aqualuxe-comparison-table">';

// Table header
echo '<thead>';
echo '<tr>';
echo '<th>' . esc_html__( 'Service', 'aqualuxe' ) . '</th>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    echo '<th>' . esc_html( $service->get_title() ) . '</th>';
}

echo '</tr>';
echo '</thead>';

// Table body
echo '<tbody>';

// Image row
echo '<tr class="comparison-image-row">';
echo '<td>' . esc_html__( 'Image', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    $thumbnail = $service->get_thumbnail( 'medium' );
    
    echo '<td>';
    if ( $thumbnail ) {
        echo '<img src="' . esc_url( $thumbnail ) . '" alt="' . esc_attr( $service->get_title() ) . '" class="comparison-image">';
    } else {
        echo '&mdash;';
    }
    echo '</td>';
}

echo '</tr>';

// Price row
if ( $show_pricing ) {
    echo '<tr>';
    echo '<td>' . esc_html__( 'Price', 'aqualuxe' ) . '</td>';
    
    foreach ( $services as $post ) {
        $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
        echo '<td>' . wp_kses_post( $service->get_formatted_price_with_type() ) . '</td>';
    }
    
    echo '</tr>';
}

// Duration row
echo '<tr>';
echo '<td>' . esc_html__( 'Duration', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    echo '<td>' . esc_html( $service->get_duration( true ) ) . '</td>';
}

echo '</tr>';

// Location row
echo '<tr>';
echo '<td>' . esc_html__( 'Location', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    echo '<td>' . esc_html( $service->get_location() ) . '</td>';
}

echo '</tr>';

// Features row
echo '<tr>';
echo '<td>' . esc_html__( 'Features', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    $features = $service->get_features();
    
    echo '<td>';
    if ( ! empty( $features ) ) {
        echo '<ul class="aqualuxe-service-features-list">';
        foreach ( $features as $feature ) {
            echo '<li>' . esc_html( $feature ) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '&mdash;';
    }
    echo '</td>';
}

echo '</tr>';

// Bookable row
echo '<tr>';
echo '<td>' . esc_html__( 'Bookable', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    echo '<td>';
    if ( $service->is_bookable() ) {
        echo '<span class="dashicons dashicons-yes"></span>';
    } else {
        echo '<span class="dashicons dashicons-no"></span>';
    }
    echo '</td>';
}

echo '</tr>';

// Categories row
echo '<tr>';
echo '<td>' . esc_html__( 'Categories', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    $categories = $service->get_categories();
    
    echo '<td>';
    if ( ! empty( $categories ) ) {
        $category_names = [];
        foreach ( $categories as $category ) {
            $category_names[] = $category->name;
        }
        echo esc_html( implode( ', ', $category_names ) );
    } else {
        echo '&mdash;';
    }
    echo '</td>';
}

echo '</tr>';

// Action row
echo '<tr>';
echo '<td>' . esc_html__( 'Action', 'aqualuxe' ) . '</td>';

foreach ( $services as $post ) {
    $service = new \AquaLuxe\Modules\Services\Inc\Service( $post->ID );
    echo '<td>';
    echo '<a href="' . esc_url( $service->get_permalink() ) . '" class="button">' . esc_html__( 'View Details', 'aqualuxe' ) . '</a>';
    
    if ( $service->is_bookable() ) {
        echo '<a href="' . esc_url( $service->get_booking_url() ) . '" class="button button-secondary">' . esc_html__( 'Book Now', 'aqualuxe' ) . '</a>';
    }
    
    echo '<a href="#" class="remove-from-comparison" data-service-id="' . esc_attr( $service->get_id() ) . '">' . esc_html__( 'Remove', 'aqualuxe' ) . '</a>';
    echo '</td>';
}

echo '</tr>';

echo '</tbody>';
echo '</table>';
echo '</div>'; // End comparison