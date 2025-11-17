<?php
/**
 * Services Admin
 *
 * @package AquaLuxe
 * @subpackage Modules\Services\Admin
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Services Admin Class
 * 
 * This class handles admin functionality for the Services module.
 */
class Admin {
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
        // Add admin columns
        add_filter( 'manage_aqualuxe_service_posts_columns', [ $this, 'add_service_columns' ] );
        add_action( 'manage_aqualuxe_service_posts_custom_column', [ $this, 'render_service_columns' ], 10, 2 );
        add_filter( 'manage_edit-aqualuxe_service_sortable_columns', [ $this, 'sortable_service_columns' ] );

        // Add admin columns for service packages
        add_filter( 'manage_aqualuxe_service_pkg_posts_columns', [ $this, 'add_service_package_columns' ] );
        add_action( 'manage_aqualuxe_service_pkg_posts_custom_column', [ $this, 'render_service_package_columns' ], 10, 2 );
        add_filter( 'manage_edit-aqualuxe_service_pkg_sortable_columns', [ $this, 'sortable_service_package_columns' ] );

        // Add admin filters
        add_action( 'restrict_manage_posts', [ $this, 'add_admin_filters' ] );
        add_filter( 'parse_query', [ $this, 'filter_services_by_price_range' ] );

        // Add dashboard widgets
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widgets' ] );

        // Add help tabs
        add_action( 'admin_head', [ $this, 'add_help_tabs' ] );

        // Add admin notices
        add_action( 'admin_notices', [ $this, 'admin_notices' ] );

        // Add admin scripts
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
    }

    /**
     * Add service columns
     *
     * @param array $columns
     * @return array
     */
    public function add_service_columns( $columns ) {
        $new_columns = [];

        // Insert columns after title
        foreach ( $columns as $key => $value ) {
            $new_columns[ $key ] = $value;

            if ( 'title' === $key ) {
                $new_columns['price'] = __( 'Price', 'aqualuxe' );
                $new_columns['duration'] = __( 'Duration', 'aqualuxe' );
                $new_columns['bookable'] = __( 'Bookable', 'aqualuxe' );
                $new_columns['location'] = __( 'Location', 'aqualuxe' );
            }
        }

        return $new_columns;
    }

    /**
     * Render service columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_service_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'price':
                $price = get_post_meta( $post_id, '_aqualuxe_service_price', true );
                $price_type = get_post_meta( $post_id, '_aqualuxe_service_price_type', true );
                $sale_price = get_post_meta( $post_id, '_aqualuxe_service_sale_price', true );

                if ( $price ) {
                    echo esc_html( '$' . $price );

                    // Show price type
                    if ( 'starting' === $price_type ) {
                        echo ' <small>' . esc_html__( '(starting from)', 'aqualuxe' ) . '</small>';
                    } elseif ( 'hourly' === $price_type ) {
                        echo ' <small>' . esc_html__( '(per hour)', 'aqualuxe' ) . '</small>';
                    }

                    // Show sale price
                    if ( $sale_price ) {
                        echo ' <span class="sale-price">' . esc_html( '$' . $sale_price ) . '</span>';
                    }
                } elseif ( 'quote' === $price_type ) {
                    echo esc_html__( 'Quote Only', 'aqualuxe' );
                } else {
                    echo '&mdash;';
                }
                break;

            case 'duration':
                $duration = get_post_meta( $post_id, '_aqualuxe_service_duration', true );
                if ( $duration ) {
                    echo esc_html( $duration . ' ' . __( 'minutes', 'aqualuxe' ) );
                } else {
                    echo '&mdash;';
                }
                break;

            case 'bookable':
                $bookable = get_post_meta( $post_id, '_aqualuxe_service_bookable', true );
                if ( 'yes' === $bookable ) {
                    echo '<span class="dashicons dashicons-yes"></span>';
                } else {
                    echo '<span class="dashicons dashicons-no"></span>';
                }
                break;

            case 'location':
                $location = get_post_meta( $post_id, '_aqualuxe_service_location', true );
                if ( $location ) {
                    echo esc_html( $location );
                } else {
                    echo '&mdash;';
                }
                break;
        }
    }

    /**
     * Sortable service columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_service_columns( $columns ) {
        $columns['price'] = 'price';
        $columns['duration'] = 'duration';
        $columns['location'] = 'location';
        return $columns;
    }

    /**
     * Add service package columns
     *
     * @param array $columns
     * @return array
     */
    public function add_service_package_columns( $columns ) {
        $new_columns = [];

        // Insert columns after title
        foreach ( $columns as $key => $value ) {
            $new_columns[ $key ] = $value;

            if ( 'title' === $key ) {
                $new_columns['price'] = __( 'Package Price', 'aqualuxe' );
                $new_columns['services'] = __( 'Included Services', 'aqualuxe' );
                $new_columns['duration'] = __( 'Duration', 'aqualuxe' );
            }
        }

        return $new_columns;
    }

    /**
     * Render service package columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_service_package_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'price':
                $price = get_post_meta( $post_id, '_aqualuxe_service_package_price', true );
                $sale_price = get_post_meta( $post_id, '_aqualuxe_service_package_sale_price', true );

                if ( $price ) {
                    echo esc_html( '$' . $price );

                    // Show sale price
                    if ( $sale_price ) {
                        echo ' <span class="sale-price">' . esc_html( '$' . $sale_price ) . '</span>';
                    }
                } else {
                    echo '&mdash;';
                }
                break;

            case 'services':
                $services = get_post_meta( $post_id, '_aqualuxe_service_package_services', true );
                if ( is_array( $services ) && ! empty( $services ) ) {
                    $service_titles = [];
                    foreach ( $services as $service_id ) {
                        $service_titles[] = get_the_title( $service_id );
                    }
                    echo esc_html( implode( ', ', $service_titles ) );
                } else {
                    echo '&mdash;';
                }
                break;

            case 'duration':
                $duration = get_post_meta( $post_id, '_aqualuxe_service_package_duration', true );
                if ( $duration ) {
                    echo esc_html( $duration . ' ' . __( 'minutes', 'aqualuxe' ) );
                } else {
                    echo '&mdash;';
                }
                break;
        }
    }

    /**
     * Sortable service package columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_service_package_columns( $columns ) {
        $columns['price'] = 'price';
        $columns['duration'] = 'duration';
        return $columns;
    }

    /**
     * Add admin filters
     *
     * @param string $post_type
     * @return void
     */
    public function add_admin_filters( $post_type ) {
        if ( 'aqualuxe_service' !== $post_type ) {
            return;
        }

        // Add price range filter
        $price_ranges = [
            '' => __( 'All Price Ranges', 'aqualuxe' ),
            '0-50' => __( '$0 - $50', 'aqualuxe' ),
            '50-100' => __( '$50 - $100', 'aqualuxe' ),
            '100-200' => __( '$100 - $200', 'aqualuxe' ),
            '200-500' => __( '$200 - $500', 'aqualuxe' ),
            '500+' => __( '$500+', 'aqualuxe' ),
        ];

        $current_price_range = isset( $_GET['price_range'] ) ? sanitize_text_field( $_GET['price_range'] ) : '';

        echo '<select name="price_range">';
        foreach ( $price_ranges as $value => $label ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $value ),
                selected( $current_price_range, $value, false ),
                esc_html( $label )
            );
        }
        echo '</select>';

        // Add bookable filter
        $bookable_options = [
            '' => __( 'All Services', 'aqualuxe' ),
            'yes' => __( 'Bookable Only', 'aqualuxe' ),
            'no' => __( 'Non-Bookable Only', 'aqualuxe' ),
        ];

        $current_bookable = isset( $_GET['bookable'] ) ? sanitize_text_field( $_GET['bookable'] ) : '';

        echo '<select name="bookable">';
        foreach ( $bookable_options as $value => $label ) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr( $value ),
                selected( $current_bookable, $value, false ),
                esc_html( $label )
            );
        }
        echo '</select>';
    }

    /**
     * Filter services by price range
     *
     * @param \WP_Query $query
     * @return void
     */
    public function filter_services_by_price_range( $query ) {
        global $pagenow;

        // Check if we're in the correct screen
        if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || 'aqualuxe_service' !== $query->get( 'post_type' ) ) {
            return;
        }

        // Filter by price range
        if ( isset( $_GET['price_range'] ) && ! empty( $_GET['price_range'] ) ) {
            $price_range = sanitize_text_field( $_GET['price_range'] );
            $meta_query = $query->get( 'meta_query' );
            if ( ! is_array( $meta_query ) ) {
                $meta_query = [];
            }

            if ( '0-50' === $price_range ) {
                $meta_query[] = [
                    'key' => '_aqualuxe_service_price',
                    'value' => 50,
                    'compare' => '<=',
                    'type' => 'NUMERIC',
                ];
            } elseif ( '50-100' === $price_range ) {
                $meta_query[] = [
                    'key' => '_aqualuxe_service_price',
                    'value' => [50, 100],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            } elseif ( '100-200' === $price_range ) {
                $meta_query[] = [
                    'key' => '_aqualuxe_service_price',
                    'value' => [100, 200],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            } elseif ( '200-500' === $price_range ) {
                $meta_query[] = [
                    'key' => '_aqualuxe_service_price',
                    'value' => [200, 500],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
            } elseif ( '500+' === $price_range ) {
                $meta_query[] = [
                    'key' => '_aqualuxe_service_price',
                    'value' => 500,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ];
            }

            $query->set( 'meta_query', $meta_query );
        }

        // Filter by bookable
        if ( isset( $_GET['bookable'] ) && ! empty( $_GET['bookable'] ) ) {
            $bookable = sanitize_text_field( $_GET['bookable'] );
            $meta_query = $query->get( 'meta_query' );
            if ( ! is_array( $meta_query ) ) {
                $meta_query = [];
            }

            $meta_query[] = [
                'key' => '_aqualuxe_service_bookable',
                'value' => $bookable,
                'compare' => '=',
            ];

            $query->set( 'meta_query', $meta_query );
        }
    }

    /**
     * Add dashboard widgets
     *
     * @return void
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'aqualuxe_services_dashboard',
            __( 'AquaLuxe Services', 'aqualuxe' ),
            [ $this, 'render_services_dashboard_widget' ]
        );
    }

    /**
     * Render services dashboard widget
     *
     * @return void
     */
    public function render_services_dashboard_widget() {
        // Get service counts
        $total_services = wp_count_posts( 'aqualuxe_service' )->publish;
        $total_packages = wp_count_posts( 'aqualuxe_service_pkg' )->publish;

        // Get bookable services count
        $bookable_services = new \WP_Query(
            [
                'post_type' => 'aqualuxe_service',
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => '_aqualuxe_service_bookable',
                        'value' => 'yes',
                        'compare' => '=',
                    ],
                ],
            ]
        );

        $bookable_count = $bookable_services->found_posts;

        // Get service categories
        $categories = get_terms(
            [
                'taxonomy' => 'service_category',
                'hide_empty' => false,
            ]
        );

        ?>
        <div class="aqualuxe-dashboard-widget">
            <div class="aqualuxe-dashboard-counts">
                <div class="aqualuxe-dashboard-count">
                    <span class="count"><?php echo esc_html( $total_services ); ?></span>
                    <span class="label"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></span>
                </div>
                <div class="aqualuxe-dashboard-count">
                    <span class="count"><?php echo esc_html( $total_packages ); ?></span>
                    <span class="label"><?php esc_html_e( 'Packages', 'aqualuxe' ); ?></span>
                </div>
                <div class="aqualuxe-dashboard-count">
                    <span class="count"><?php echo esc_html( $bookable_count ); ?></span>
                    <span class="label"><?php esc_html_e( 'Bookable', 'aqualuxe' ); ?></span>
                </div>
            </div>

            <?php if ( ! empty( $categories ) ) : ?>
                <div class="aqualuxe-dashboard-categories">
                    <h4><?php esc_html_e( 'Service Categories', 'aqualuxe' ); ?></h4>
                    <ul>
                        <?php foreach ( $categories as $category ) : ?>
                            <li>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=aqualuxe_service&service_category=' . $category->slug ) ); ?>">
                                    <?php echo esc_html( $category->name ); ?>
                                    <span class="count">(<?php echo esc_html( $category->count ); ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="aqualuxe-dashboard-actions">
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=aqualuxe_service' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Add New Service', 'aqualuxe' ); ?>
                </a>
                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=aqualuxe_service_pkg' ) ); ?>" class="button">
                    <?php esc_html_e( 'Add New Package', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Add help tabs
     *
     * @return void
     */
    public function add_help_tabs() {
        $screen = get_current_screen();

        // Only add help tabs on service screens
        if ( ! $screen || ! in_array( $screen->id, [ 'aqualuxe_service', 'edit-aqualuxe_service', 'aqualuxe_service_pkg', 'edit-aqualuxe_service_pkg' ] ) ) {
            return;
        }

        // Add help tab for services
        $screen->add_help_tab(
            [
                'id'      => 'aqualuxe_service_help',
                'title'   => __( 'Services Help', 'aqualuxe' ),
                'content' => '<h2>' . __( 'Services Module Help', 'aqualuxe' ) . '</h2>' .
                            '<p>' . __( 'The Services module allows you to manage and display services offered by your aquatic business.', 'aqualuxe' ) . '</p>' .
                            '<h3>' . __( 'Service Features', 'aqualuxe' ) . '</h3>' .
                            '<ul>' .
                            '<li>' . __( 'Create and manage individual services', 'aqualuxe' ) . '</li>' .
                            '<li>' . __( 'Organize services into categories and tags', 'aqualuxe' ) . '</li>' .
                            '<li>' . __( 'Set pricing and duration for each service', 'aqualuxe' ) . '</li>' .
                            '<li>' . __( 'Make services bookable for integration with the Bookings module', 'aqualuxe' ) . '</li>' .
                            '<li>' . __( 'Create service packages that include multiple services', 'aqualuxe' ) . '</li>' .
                            '</ul>',
            ]
        );

        // Add help tab for shortcodes
        $screen->add_help_tab(
            [
                'id'      => 'aqualuxe_service_shortcodes',
                'title'   => __( 'Shortcodes', 'aqualuxe' ),
                'content' => '<h2>' . __( 'Available Shortcodes', 'aqualuxe' ) . '</h2>' .
                            '<p>' . __( 'Use these shortcodes to display services on your site:', 'aqualuxe' ) . '</p>' .
                            '<h3>' . __( '[aqualuxe_services]', 'aqualuxe' ) . '</h3>' .
                            '<p>' . __( 'Displays a list or grid of services.', 'aqualuxe' ) . '</p>' .
                            '<p><strong>' . __( 'Parameters:', 'aqualuxe' ) . '</strong></p>' .
                            '<ul>' .
                            '<li><code>category</code> - ' . __( 'Filter by category slug (comma-separated for multiple)', 'aqualuxe' ) . '</li>' .
                            '<li><code>tag</code> - ' . __( 'Filter by tag slug (comma-separated for multiple)', 'aqualuxe' ) . '</li>' .
                            '<li><code>limit</code> - ' . __( 'Number of services to display (-1 for all)', 'aqualuxe' ) . '</li>' .
                            '<li><code>orderby</code> - ' . __( 'Order by field (title, date, etc.)', 'aqualuxe' ) . '</li>' .
                            '<li><code>order</code> - ' . __( 'Order direction (ASC or DESC)', 'aqualuxe' ) . '</li>' .
                            '<li><code>layout</code> - ' . __( 'Display layout (grid or list)', 'aqualuxe' ) . '</li>' .
                            '<li><code>columns</code> - ' . __( 'Number of columns for grid layout (2-4)', 'aqualuxe' ) . '</li>' .
                            '</ul>' .
                            '<h3>' . __( '[aqualuxe_service_packages]', 'aqualuxe' ) . '</h3>' .
                            '<p>' . __( 'Displays service packages.', 'aqualuxe' ) . '</p>' .
                            '<h3>' . __( '[aqualuxe_service_comparison]', 'aqualuxe' ) . '</h3>' .
                            '<p>' . __( 'Displays a comparison table of services.', 'aqualuxe' ) . '</p>' .
                            '<p><strong>' . __( 'Parameters:', 'aqualuxe' ) . '</strong></p>' .
                            '<ul>' .
                            '<li><code>ids</code> - ' . __( 'Service IDs to compare (comma-separated)', 'aqualuxe' ) . '</li>' .
                            '<li><code>category</code> - ' . __( 'Compare all services in a category', 'aqualuxe' ) . '</li>' .
                            '</ul>',
            ]
        );

        // Add help sidebar
        $screen->set_help_sidebar(
            '<p><strong>' . __( 'For more information:', 'aqualuxe' ) . '</strong></p>' .
            '<p><a href="#">' . __( 'Services Documentation', 'aqualuxe' ) . '</a></p>' .
            '<p><a href="#">' . __( 'Support Forum', 'aqualuxe' ) . '</a></p>'
        );
    }

    /**
     * Admin notices
     *
     * @return void
     */
    public function admin_notices() {
        $screen = get_current_screen();

        // Only show notices on service screens
        if ( ! $screen || ! in_array( $screen->id, [ 'aqualuxe_service', 'edit-aqualuxe_service', 'aqualuxe_service_pkg', 'edit-aqualuxe_service_pkg' ] ) ) {
            return;
        }

        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><?php esc_html_e( 'For full functionality, we recommend installing WooCommerce to enable payment processing for services.', 'aqualuxe' ); ?></p>
            </div>
            <?php
        }

        // Check if Bookings module is active
        if ( ! class_exists( 'AquaLuxe\Modules\Bookings\Bookings' ) ) {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><?php esc_html_e( 'Enable the Bookings module to allow customers to book your services online.', 'aqualuxe' ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Admin scripts
     *
     * @param string $hook
     * @return void
     */
    public function admin_scripts( $hook ) {
        $screen = get_current_screen();

        // Only load scripts on service screens
        if ( ! $screen || ! in_array( $screen->id, [ 'aqualuxe_service', 'edit-aqualuxe_service', 'aqualuxe_service_pkg', 'edit-aqualuxe_service_pkg' ] ) ) {
            return;
        }

        // Enqueue admin scripts and styles
        wp_enqueue_style( 'aqualuxe-services-admin', AQUALUXE_MODULES_URL . 'services/assets/css/admin.css', [], AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-services-admin', AQUALUXE_MODULES_URL . 'services/assets/js/admin.js', [ 'jquery' ], AQUALUXE_VERSION, true );

        // Localize script
        wp_localize_script(
            'aqualuxe-services-admin',
            'aqualuxeServicesAdmin',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'aqualuxe-services-admin-nonce' ),
            ]
        );
    }
}

// Initialize the admin class
new Admin();