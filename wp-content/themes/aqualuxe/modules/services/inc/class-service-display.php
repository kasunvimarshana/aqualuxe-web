<?php
/**
 * Service Display Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Services\Inc
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services\Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Service Display Class
 * 
 * This class handles the display of services on the frontend.
 */
class Service_Display {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add single service template
        add_filter( 'single_template', [ $this, 'service_single_template' ] );
        
        // Add archive template
        add_filter( 'archive_template', [ $this, 'service_archive_template' ] );
        
        // Add taxonomy template
        add_filter( 'taxonomy_template', [ $this, 'service_taxonomy_template' ] );
        
        // Add body classes
        add_filter( 'body_class', [ $this, 'service_body_classes' ] );
        
        // Add content filters
        add_filter( 'the_content', [ $this, 'service_content_filter' ] );
        
        // Add service schema
        add_action( 'wp_footer', [ $this, 'add_service_schema' ] );
    }

    /**
     * Service single template
     *
     * @param string $template
     * @return string
     */
    public function service_single_template( $template ) {
        global $post;

    if ( 'aqlx_service' === $post->post_type ) {
            $custom_template = locate_template( 'aqualuxe/services/single-service.php' );
            
            if ( $custom_template ) {
                return $custom_template;
            }

            $plugin_template = AQUALUXE_MODULES_DIR . 'services/templates/single-service.php';
            
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
    } elseif ( 'aqlx_service_pkg' === $post->post_type ) {
            $custom_template = locate_template( 'aqualuxe/services/single-service-package.php' );
            
            if ( $custom_template ) {
                return $custom_template;
            }

            $plugin_template = AQUALUXE_MODULES_DIR . 'services/templates/single-service-package.php';
            
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }

    /**
     * Service archive template
     *
     * @param string $template
     * @return string
     */
    public function service_archive_template( $template ) {
    if ( is_post_type_archive( 'aqlx_service' ) ) {
            $custom_template = locate_template( 'aqualuxe/services/archive-service.php' );
            
            if ( $custom_template ) {
                return $custom_template;
            }

            $plugin_template = AQUALUXE_MODULES_DIR . 'services/templates/archive-service.php';
            
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
    } elseif ( is_post_type_archive( 'aqlx_service_pkg' ) ) {
            $custom_template = locate_template( 'aqualuxe/services/archive-service-package.php' );
            
            if ( $custom_template ) {
                return $custom_template;
            }

            $plugin_template = AQUALUXE_MODULES_DIR . 'services/templates/archive-service-package.php';
            
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }

    /**
     * Service taxonomy template
     *
     * @param string $template
     * @return string
     */
    public function service_taxonomy_template( $template ) {
        if ( is_tax( 'service_category' ) || is_tax( 'service_tag' ) ) {
            $custom_template = locate_template( 'aqualuxe/services/taxonomy-service.php' );
            
            if ( $custom_template ) {
                return $custom_template;
            }

            $plugin_template = AQUALUXE_MODULES_DIR . 'services/templates/taxonomy-service.php';
            
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }

    /**
     * Service body classes
     *
     * @param array $classes
     * @return array
     */
    public function service_body_classes( $classes ) {
        global $post;

        if ( ! $post ) {
            return $classes;
        }

    if ( 'aqlx_service' === $post->post_type ) {
            $classes[] = 'aqualuxe-service';
            $classes[] = 'aqualuxe-service-single';

            // Add bookable class
            $bookable = get_post_meta( $post->ID, '_aqualuxe_service_bookable', true );
            if ( 'yes' === $bookable ) {
                $classes[] = 'aqualuxe-service-bookable';
            }

            // Add price type class
            $price_type = get_post_meta( $post->ID, '_aqualuxe_service_price_type', true );
            if ( $price_type ) {
                $classes[] = 'aqualuxe-service-price-' . $price_type;
            }
    } elseif ( 'aqlx_service_pkg' === $post->post_type ) {
            $classes[] = 'aqualuxe-service-package';
            $classes[] = 'aqualuxe-service-package-single';
    } elseif ( is_post_type_archive( 'aqlx_service' ) ) {
            $classes[] = 'aqualuxe-service-archive';
    } elseif ( is_post_type_archive( 'aqlx_service_pkg' ) ) {
            $classes[] = 'aqualuxe-service-package-archive';
        } elseif ( is_tax( 'service_category' ) || is_tax( 'service_tag' ) ) {
            $classes[] = 'aqualuxe-service-taxonomy';
        }

        return $classes;
    }

    /**
     * Service content filter
     *
     * @param string $content
     * @return string
     */
    public function service_content_filter( $content ) {
        global $post;

        if ( ! $post || ! is_singular() ) {
            return $content;
        }

    if ( 'aqlx_service' === $post->post_type ) {
            return $this->get_service_content( $post, $content );
    } elseif ( 'aqlx_service_pkg' === $post->post_type ) {
            return $this->get_service_package_content( $post, $content );
        }

        return $content;
    }

    /**
     * Get service content
     *
     * @param \WP_Post $post
     * @param string $content
     * @return string
     */
    private function get_service_content( $post, $content ) {
        // Create service object
        $service = new Service( $post->ID );
        
        // Start output buffer
        ob_start();

        // Include service details before content
        include AQUALUXE_MODULES_DIR . 'services/templates/parts/service-details.php';

        // Add original content
        echo $content;

        // Include service features after content
        include AQUALUXE_MODULES_DIR . 'services/templates/parts/service-features.php';

        // Include booking form if service is bookable
        if ( $service->is_bookable() ) {
            include AQUALUXE_MODULES_DIR . 'services/templates/parts/service-booking.php';
        }

        // Include related services
        include AQUALUXE_MODULES_DIR . 'services/templates/parts/service-related.php';

        // Return output
        return ob_get_clean();
    }

    /**
     * Get service package content
     *
     * @param \WP_Post $post
     * @param string $content
     * @return string
     */
    private function get_service_package_content( $post, $content ) {
        // Get package details
        $price = get_post_meta( $post->ID, '_aqualuxe_service_package_price', true );
        $sale_price = get_post_meta( $post->ID, '_aqualuxe_service_package_sale_price', true );
        $duration = get_post_meta( $post->ID, '_aqualuxe_service_package_duration', true );
        $services = get_post_meta( $post->ID, '_aqualuxe_service_package_services', true );
        
        // Start output buffer
        ob_start();

        // Include package details before content
        include AQUALUXE_MODULES_DIR . 'services/templates/parts/package-details.php';

        // Add original content
        echo $content;

        // Include included services
        include AQUALUXE_MODULES_DIR . 'services/templates/parts/package-services.php';

        // Return output
        return ob_get_clean();
    }

    /**
     * Add service schema
     *
     * @return void
     */
    public function add_service_schema() {
        global $post;

    if ( ! $post || ! is_singular( 'aqlx_service' ) ) {
            return;
        }

        // Create service object
        $service = new Service( $post->ID );
        
        // Get service data
        $data = $service->get_data();
        
        if ( empty( $data ) ) {
            return;
        }

        // Create schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $data['title'],
            'description' => $data['description'],
            'url' => $data['permalink'],
        ];

        // Add image if available
        if ( ! empty( $data['thumbnail'] ) ) {
            $schema['image'] = $data['thumbnail'];
        }

        // Add price if available
        if ( ! empty( $data['meta']['price'] ) ) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $data['meta']['price'],
                'priceCurrency' => 'USD',
            ];

            // Add sale price if available
            if ( ! empty( $data['meta']['sale_price'] ) ) {
                $schema['offers']['price'] = $data['meta']['sale_price'];
            }
        }

        // Add service area if location is available
        if ( ! empty( $data['meta']['location'] ) ) {
            $schema['areaServed'] = $data['meta']['location'];
        }

        // Output schema
        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
    }

    /**
     * Render service grid
     *
     * @param array $services
     * @param array $args
     * @return void
     */
    public static function render_service_grid( $services, $args = [] ) {
        // Default arguments
        $defaults = [
            'columns' => 3,
            'show_price' => true,
            'show_excerpt' => true,
            'show_button' => true,
            'button_text' => __( 'View Service', 'aqualuxe' ),
        ];

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Get settings
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

        // Set column class
        $column_class = 'aqualuxe-service-column';
        if ( 2 === $args['columns'] ) {
            $column_class .= ' aqualuxe-service-column-2';
        } elseif ( 4 === $args['columns'] ) {
            $column_class .= ' aqualuxe-service-column-4';
        } else {
            $column_class .= ' aqualuxe-service-column-3';
        }

        // Start output
        echo '<div class="aqualuxe-service-grid">';

        foreach ( $services as $post ) {
            // Create service object
            $service = new Service( $post->ID );
            $data = $service->get_data();

            if ( empty( $data ) ) {
                continue;
            }

            // Start column
            echo '<div class="' . esc_attr( $column_class ) . '">';
            echo '<div class="aqualuxe-service-item">';

            // Service thumbnail
            if ( ! empty( $data['thumbnail'] ) ) {
                echo '<div class="aqualuxe-service-thumbnail">';
                echo '<a href="' . esc_url( $data['permalink'] ) . '">';
                echo '<img src="' . esc_url( $data['thumbnail'] ) . '" alt="' . esc_attr( $data['title'] ) . '" />';
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
            if ( $args['show_price'] && $show_pricing ) {
                echo '<div class="aqualuxe-service-price">';
                echo wp_kses_post( $service->get_formatted_price_with_type() );
                echo '</div>';
            }

            // Service excerpt
            if ( $args['show_excerpt'] && ! empty( $data['excerpt'] ) ) {
                echo '<div class="aqualuxe-service-excerpt">';
                echo wp_kses_post( $data['excerpt'] );
                echo '</div>';
            }

            // Service button
            if ( $args['show_button'] ) {
                echo '<div class="aqualuxe-service-button">';
                echo '<a href="' . esc_url( $data['permalink'] ) . '" class="button">' . esc_html( $args['button_text'] ) . '</a>';
                echo '</div>';
            }

            echo '</div>'; // End service content
            echo '</div>'; // End service item
            echo '</div>'; // End column
        }

        echo '</div>'; // End grid
    }

    /**
     * Render service list
     *
     * @param array $services
     * @param array $args
     * @return void
     */
    public static function render_service_list( $services, $args = [] ) {
        // Default arguments
        $defaults = [
            'show_price' => true,
            'show_excerpt' => true,
            'show_button' => true,
            'button_text' => __( 'View Service', 'aqualuxe' ),
        ];

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Get settings
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

        // Start output
        echo '<div class="aqualuxe-service-list">';

        foreach ( $services as $post ) {
            // Create service object
            $service = new Service( $post->ID );
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
                echo '<img src="' . esc_url( $data['thumbnail'] ) . '" alt="' . esc_attr( $data['title'] ) . '" />';
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
            if ( $args['show_price'] && $show_pricing ) {
                echo '<div class="aqualuxe-service-price">';
                echo wp_kses_post( $service->get_formatted_price_with_type() );
                echo '</div>';
            }

            // Service excerpt
            if ( $args['show_excerpt'] && ! empty( $data['excerpt'] ) ) {
                echo '<div class="aqualuxe-service-excerpt">';
                echo wp_kses_post( $data['excerpt'] );
                echo '</div>';
            }

            // Service button
            if ( $args['show_button'] ) {
                echo '<div class="aqualuxe-service-button">';
                echo '<a href="' . esc_url( $data['permalink'] ) . '" class="button">' . esc_html( $args['button_text'] ) . '</a>';
                echo '</div>';
            }

            echo '</div>'; // End service content
            echo '</div>'; // End service item
        }

        echo '</div>'; // End list
    }

    /**
     * Render service comparison table
     *
     * @param array $services
     * @return void
     */
    public static function render_service_comparison( $services ) {
        if ( empty( $services ) ) {
            return;
        }

        // Get settings
        $settings = get_option( 'aqualuxe_service_settings', [] );
        $show_pricing = isset( $settings['show_pricing'] ) ? $settings['show_pricing'] : true;

        // Start output
        echo '<div class="aqualuxe-service-comparison">';
        echo '<table class="aqualuxe-comparison-table">';

        // Table header
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__( 'Service', 'aqualuxe' ) . '</th>';
        
        foreach ( $services as $post ) {
            $service = new Service( $post->ID );
            echo '<th>' . esc_html( $service->get_title() ) . '</th>';
        }
        
        echo '</tr>';
        echo '</thead>';

        // Table body
        echo '<tbody>';

        // Price row
        if ( $show_pricing ) {
            echo '<tr>';
            echo '<td>' . esc_html__( 'Price', 'aqualuxe' ) . '</td>';
            
            foreach ( $services as $post ) {
                $service = new Service( $post->ID );
                echo '<td>' . wp_kses_post( $service->get_formatted_price_with_type() ) . '</td>';
            }
            
            echo '</tr>';
        }

        // Duration row
        echo '<tr>';
        echo '<td>' . esc_html__( 'Duration', 'aqualuxe' ) . '</td>';
        
        foreach ( $services as $post ) {
            $service = new Service( $post->ID );
            echo '<td>' . esc_html( $service->get_duration( true ) ) . '</td>';
        }
        
        echo '</tr>';

        // Location row
        echo '<tr>';
        echo '<td>' . esc_html__( 'Location', 'aqualuxe' ) . '</td>';
        
        foreach ( $services as $post ) {
            $service = new Service( $post->ID );
            echo '<td>' . esc_html( $service->get_location() ) . '</td>';
        }
        
        echo '</tr>';

        // Features row
        echo '<tr>';
        echo '<td>' . esc_html__( 'Features', 'aqualuxe' ) . '</td>';
        
        foreach ( $services as $post ) {
            $service = new Service( $post->ID );
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
            $service = new Service( $post->ID );
            echo '<td>';
            if ( $service->is_bookable() ) {
                echo '<span class="dashicons dashicons-yes"></span>';
            } else {
                echo '<span class="dashicons dashicons-no"></span>';
            }
            echo '</td>';
        }
        
        echo '</tr>';

        // Action row
        echo '<tr>';
        echo '<td>' . esc_html__( 'Action', 'aqualuxe' ) . '</td>';
        
        foreach ( $services as $post ) {
            $service = new Service( $post->ID );
            echo '<td>';
            echo '<a href="' . esc_url( $service->get_permalink() ) . '" class="button">' . esc_html__( 'View Details', 'aqualuxe' ) . '</a>';
            echo '</td>';
        }
        
        echo '</tr>';

        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // End comparison
    }
}

// Initialize the class
new Service_Display();