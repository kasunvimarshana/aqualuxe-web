<?php
/**
 * AquaLuxe Demo Content Processor
 *
 * Handles the actual import process for demo content.
 *
 * @package AquaLuxe
 * @subpackage Demo_Importer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Demo Content Processor Class
 */
class AquaLuxe_Demo_Content_Processor {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Demo content directory.
     *
     * @var string
     */
    protected $demo_content_dir;

    /**
     * Demo content URL.
     *
     * @var string
     */
    protected $demo_content_url;

    /**
     * Log of import operations.
     *
     * @var array
     */
    protected $import_log = array();

    /**
     * Error log of import operations.
     *
     * @var array
     */
    protected $error_log = array();

    /**
     * Imported post IDs.
     *
     * @var array
     */
    protected $imported_post_ids = array();

    /**
     * Imported term IDs.
     *
     * @var array
     */
    protected $imported_term_ids = array();

    /**
     * Imported user IDs.
     *
     * @var array
     */
    protected $imported_user_ids = array();

    /**
     * Imported attachment IDs.
     *
     * @var array
     */
    protected $imported_attachment_ids = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->demo_content_dir = AQUALUXE_DIR . 'inc/demo-importer/demo-content/';
        $this->demo_content_url = AQUALUXE_URI . 'inc/demo-importer/demo-content/';
    }

    /**
     * Get instance of this class.
     *
     * @return AquaLuxe_Demo_Content_Processor
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Process AJAX import request.
     */
    public function ajax_import_demo_content() {
        // Check nonce for security
        if ( ! check_ajax_referer( 'aqualuxe_demo_import_nonce', 'nonce', false ) ) {
            wp_send_json_error( array(
                'message' => __( 'Security check failed. Please refresh the page and try again.', 'aqualuxe' ),
            ) );
        }

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array(
                'message' => __( 'You do not have permission to import demo content.', 'aqualuxe' ),
            ) );
        }

        // Get selected content types to import
        $content_types = isset( $_POST['content_types'] ) ? array_map( 'sanitize_text_field', $_POST['content_types'] ) : array();

        if ( empty( $content_types ) ) {
            wp_send_json_error( array(
                'message' => __( 'No content types selected for import.', 'aqualuxe' ),
            ) );
        }

        // Set time limit for long imports
        set_time_limit( 300 ); // 5 minutes

        // Start import process
        $this->import_log[] = __( 'Starting demo content import...', 'aqualuxe' );

        try {
            // Validate content files before starting import
            $this->validate_content_files($content_types);
            
            // Check if WooCommerce is active if products are selected
            if (in_array('products', $content_types) && !$this->is_woocommerce_active()) {
                $this->error_log[] = __( 'WooCommerce is not active. Skipping product import.', 'aqualuxe' );
                $content_types = array_diff($content_types, array('products'));
            }

            // Process each content type
            foreach ( $content_types as $content_type ) {
                $method = 'import_' . $content_type;
                if ( method_exists( $this, $method ) ) {
                    $this->$method();
                } else {
                    $this->error_log[] = sprintf( __( 'Unknown content type: %s', 'aqualuxe' ), $content_type );
                }
            }

            // Final cleanup and processing
            $this->process_post_import_actions();

            // Update option to indicate demo content has been imported
            update_option( 'aqualuxe_demo_content_imported', true );
            update_option( 'aqualuxe_demo_content_import_date', current_time( 'mysql' ) );

            wp_send_json_success( array(
                'message' => __( 'Demo content imported successfully!', 'aqualuxe' ),
                'log'     => $this->import_log,
                'errors'  => $this->error_log,
            ) );
        } catch ( Exception $e ) {
            $this->error_log[] = $e->getMessage();
            wp_send_json_error( array(
                'message' => __( 'Error importing demo content.', 'aqualuxe' ),
                'log'     => $this->import_log,
                'errors'  => $this->error_log,
            ) );
        }
    }
    
    /**
     * Validate content files before starting import.
     *
     * @param array $content_types The content types to validate.
     */
    protected function validate_content_files($content_types) {
        foreach ($content_types as $content_type) {
            $file_path = $this->demo_content_dir . $content_type . '.json';
            if (!file_exists($file_path)) {
                $this->error_log[] = sprintf(__('Content file for %s not found. Make sure %s.json exists in the demo-content directory.', 'aqualuxe'), $content_type, $content_type);
            }
        }
    }
    
    /**
     * Check if WooCommerce is active.
     *
     * @return bool Whether WooCommerce is active.
     */
    protected function is_woocommerce_active() {
        return class_exists('WooCommerce');
    }

    /**
     * Import posts.
     */
    protected function import_posts() {
        $this->import_log[] = __( 'Importing blog posts...', 'aqualuxe' );

        $posts_data = $this->get_demo_data( 'posts' );
        if ( empty( $posts_data ) ) {
            $this->error_log[] = __( 'No post data found.', 'aqualuxe' );
            return;
        }

        foreach ( $posts_data as $post_data ) {
            // Sanitize post data
            $post_data = $this->sanitize_post_data( $post_data );

            // Check if post already exists
            $existing_post = get_page_by_title( $post_data['post_title'], OBJECT, 'post' );
            if ( $existing_post ) {
                $this->import_log[] = sprintf( __( 'Post "%s" already exists, skipping.', 'aqualuxe' ), $post_data['post_title'] );
                $this->imported_post_ids[ $post_data['demo_id'] ] = $existing_post->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $post_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $post_data['featured_image'] );
                unset( $post_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $post_data['demo_id'] ) ? $post_data['demo_id'] : '';
            unset( $post_data['demo_id'] );

            // Insert post
            $post_id = wp_insert_post( $post_data, true );

            if ( is_wp_error( $post_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating post "%s": %s', 'aqualuxe' ), $post_data['post_title'], $post_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $post_id, $featured_image_id );
            }

            // Set post terms
            if ( ! empty( $post_data['terms'] ) ) {
                foreach ( $post_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $post_id, $term_ids, $taxonomy );
                }
            }

            // Set post meta
            if ( ! empty( $post_data['meta'] ) ) {
                foreach ( $post_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $post_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $post_id;
            $this->import_log[] = sprintf( __( 'Created post: %s', 'aqualuxe' ), $post_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d posts.', 'aqualuxe' ), count( $this->imported_post_ids ) );
    }

    /**
     * Import pages.
     */
    protected function import_pages() {
        $this->import_log[] = __( 'Importing pages...', 'aqualuxe' );

        $pages_data = $this->get_demo_data( 'pages' );
        if ( empty( $pages_data ) ) {
            $this->error_log[] = __( 'No page data found.', 'aqualuxe' );
            return;
        }

        foreach ( $pages_data as $page_data ) {
            // Sanitize page data
            $page_data = $this->sanitize_post_data( $page_data );

            // Check if page already exists
            $existing_page = get_page_by_title( $page_data['post_title'], OBJECT, 'page' );
            if ( $existing_page ) {
                $this->import_log[] = sprintf( __( 'Page "%s" already exists, skipping.', 'aqualuxe' ), $page_data['post_title'] );
                $this->imported_post_ids[ $page_data['demo_id'] ] = $existing_page->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $page_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $page_data['featured_image'] );
                unset( $page_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $page_data['demo_id'] ) ? $page_data['demo_id'] : '';
            unset( $page_data['demo_id'] );

            // Set post type to page
            $page_data['post_type'] = 'page';

            // Insert page
            $page_id = wp_insert_post( $page_data, true );

            if ( is_wp_error( $page_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating page "%s": %s', 'aqualuxe' ), $page_data['post_title'], $page_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $page_id, $featured_image_id );
            }

            // Set page template
            if ( ! empty( $page_data['page_template'] ) ) {
                update_post_meta( $page_id, '_wp_page_template', $page_data['page_template'] );
            }

            // Set page meta
            if ( ! empty( $page_data['meta'] ) ) {
                foreach ( $page_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $page_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $page_id;
            $this->import_log[] = sprintf( __( 'Created page: %s', 'aqualuxe' ), $page_data['post_title'] );
        }

        // Set home and blog pages
        $home_page = get_page_by_title( 'Home' );
        $blog_page = get_page_by_title( 'Blog' );

        if ( $home_page ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $home_page->ID );
        }

        if ( $blog_page ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }

        $this->import_log[] = sprintf( __( 'Imported %d pages.', 'aqualuxe' ), count( $pages_data ) );
    }

    /**
     * Import services.
     */
    protected function import_services() {
        $this->import_log[] = __( 'Importing services...', 'aqualuxe' );

        $services_data = $this->get_demo_data( 'services' );
        if ( empty( $services_data ) ) {
            $this->error_log[] = __( 'No service data found.', 'aqualuxe' );
            return;
        }

        foreach ( $services_data as $service_data ) {
            // Sanitize service data
            $service_data = $this->sanitize_post_data( $service_data );

            // Check if service already exists
            $existing_service = get_page_by_title( $service_data['post_title'], OBJECT, 'services' );
            if ( $existing_service ) {
                $this->import_log[] = sprintf( __( 'Service "%s" already exists, skipping.', 'aqualuxe' ), $service_data['post_title'] );
                $this->imported_post_ids[ $service_data['demo_id'] ] = $existing_service->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $service_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $service_data['featured_image'] );
                unset( $service_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $service_data['demo_id'] ) ? $service_data['demo_id'] : '';
            unset( $service_data['demo_id'] );

            // Set post type to service
            $service_data['post_type'] = 'services';

            // Insert service
            $service_id = wp_insert_post( $service_data, true );

            if ( is_wp_error( $service_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating service "%s": %s', 'aqualuxe' ), $service_data['post_title'], $service_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $service_id, $featured_image_id );
            }

            // Set service terms
            if ( ! empty( $service_data['terms'] ) ) {
                foreach ( $service_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $service_id, $term_ids, $taxonomy );
                }
            }

            // Set service meta
            if ( ! empty( $service_data['meta'] ) ) {
                foreach ( $service_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $service_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $service_id;
            $this->import_log[] = sprintf( __( 'Created service: %s', 'aqualuxe' ), $service_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d services.', 'aqualuxe' ), count( $services_data ) );
    }

    /**
     * Import care guides.
     */
    protected function import_care_guides() {
        $this->import_log[] = __( 'Importing care guides...', 'aqualuxe' );

        $care_guides_data = $this->get_demo_data( 'care_guides' );
        if ( empty( $care_guides_data ) ) {
            $this->error_log[] = __( 'No care guide data found.', 'aqualuxe' );
            return;
        }

        foreach ( $care_guides_data as $care_guide_data ) {
            // Sanitize care guide data
            $care_guide_data = $this->sanitize_post_data( $care_guide_data );

            // Check if care guide already exists
            $existing_care_guide = get_page_by_title( $care_guide_data['post_title'], OBJECT, 'care_guide' );
            if ( $existing_care_guide ) {
                $this->import_log[] = sprintf( __( 'Care guide "%s" already exists, skipping.', 'aqualuxe' ), $care_guide_data['post_title'] );
                $this->imported_post_ids[ $care_guide_data['demo_id'] ] = $existing_care_guide->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $care_guide_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $care_guide_data['featured_image'] );
                unset( $care_guide_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $care_guide_data['demo_id'] ) ? $care_guide_data['demo_id'] : '';
            unset( $care_guide_data['demo_id'] );

            // Set post type to care guide
            $care_guide_data['post_type'] = 'care_guide';

            // Insert care guide
            $care_guide_id = wp_insert_post( $care_guide_data, true );

            if ( is_wp_error( $care_guide_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating care guide "%s": %s', 'aqualuxe' ), $care_guide_data['post_title'], $care_guide_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $care_guide_id, $featured_image_id );
            }

            // Set care guide meta
            if ( ! empty( $care_guide_data['meta'] ) ) {
                foreach ( $care_guide_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $care_guide_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $care_guide_id;
            $this->import_log[] = sprintf( __( 'Created care guide: %s', 'aqualuxe' ), $care_guide_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d care guides.', 'aqualuxe' ), count( $care_guides_data ) );
    }

    /**
     * Import auctions.
     */
    protected function import_auctions() {
        $this->import_log[] = __( 'Importing auctions...', 'aqualuxe' );

        $auctions_data = $this->get_demo_data( 'auctions' );
        if ( empty( $auctions_data ) ) {
            $this->error_log[] = __( 'No auction data found.', 'aqualuxe' );
            return;
        }

        foreach ( $auctions_data as $auction_data ) {
            // Sanitize auction data
            $auction_data = $this->sanitize_post_data( $auction_data );

            // Check if auction already exists
            $existing_auction = get_page_by_title( $auction_data['post_title'], OBJECT, 'aqualuxe_auction' );
            if ( $existing_auction ) {
                $this->import_log[] = sprintf( __( 'Auction "%s" already exists, skipping.', 'aqualuxe' ), $auction_data['post_title'] );
                $this->imported_post_ids[ $auction_data['demo_id'] ] = $existing_auction->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $auction_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $auction_data['featured_image'] );
                unset( $auction_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $auction_data['demo_id'] ) ? $auction_data['demo_id'] : '';
            unset( $auction_data['demo_id'] );

            // Set post type to auction
            $auction_data['post_type'] = 'aqualuxe_auction';

            // Insert auction
            $auction_id = wp_insert_post( $auction_data, true );

            if ( is_wp_error( $auction_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating auction "%s": %s', 'aqualuxe' ), $auction_data['post_title'], $auction_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $auction_id, $featured_image_id );
            }

            // Set auction terms
            if ( ! empty( $auction_data['terms'] ) ) {
                foreach ( $auction_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $auction_id, $term_ids, $taxonomy );
                }
            }

            // Set auction meta
            if ( ! empty( $auction_data['meta'] ) ) {
                foreach ( $auction_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $auction_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $auction_id;
            $this->import_log[] = sprintf( __( 'Created auction: %s', 'aqualuxe' ), $auction_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d auctions.', 'aqualuxe' ), count( $auctions_data ) );
    }
    
    /**
     * Import team members.
     */
    protected function import_team() {
        $this->import_log[] = __( 'Importing team members...', 'aqualuxe' );

        $team_data = $this->get_demo_data( 'team' );
        if ( empty( $team_data ) ) {
            $this->error_log[] = __( 'No team data found.', 'aqualuxe' );
            return;
        }

        foreach ( $team_data as $member_data ) {
            // Sanitize team member data
            $member_data = $this->sanitize_post_data( $member_data );

            // Check if team member already exists
            $existing_member = get_page_by_title( $member_data['post_title'], OBJECT, 'team' );
            if ( $existing_member ) {
                $this->import_log[] = sprintf( __( 'Team member "%s" already exists, skipping.', 'aqualuxe' ), $member_data['post_title'] );
                $this->imported_post_ids[ $member_data['demo_id'] ] = $existing_member->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $member_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $member_data['featured_image'] );
                unset( $member_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $member_data['demo_id'] ) ? $member_data['demo_id'] : '';
            unset( $member_data['demo_id'] );

            // Set post type to team
            $member_data['post_type'] = 'team';

            // Insert team member
            $member_id = wp_insert_post( $member_data, true );

            if ( is_wp_error( $member_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating team member "%s": %s', 'aqualuxe' ), $member_data['post_title'], $member_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $member_id, $featured_image_id );
            }

            // Set team member terms
            if ( ! empty( $member_data['terms'] ) ) {
                foreach ( $member_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $member_id, $term_ids, $taxonomy );
                }
            }

            // Set team member meta
            if ( ! empty( $member_data['meta'] ) ) {
                foreach ( $member_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $member_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $member_id;
            $this->import_log[] = sprintf( __( 'Created team member: %s', 'aqualuxe' ), $member_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d team members.', 'aqualuxe' ), count( $team_data ) );
    }
    
    /**
     * Import testimonials.
     */
    protected function import_testimonials() {
        $this->import_log[] = __( 'Importing testimonials...', 'aqualuxe' );

        $testimonials_data = $this->get_demo_data( 'testimonials' );
        if ( empty( $testimonials_data ) ) {
            $this->error_log[] = __( 'No testimonial data found.', 'aqualuxe' );
            return;
        }

        foreach ( $testimonials_data as $testimonial_data ) {
            // Sanitize testimonial data
            $testimonial_data = $this->sanitize_post_data( $testimonial_data );

            // Check if testimonial already exists
            $existing_testimonial = get_page_by_title( $testimonial_data['post_title'], OBJECT, 'testimonials' );
            if ( $existing_testimonial ) {
                $this->import_log[] = sprintf( __( 'Testimonial "%s" already exists, skipping.', 'aqualuxe' ), $testimonial_data['post_title'] );
                $this->imported_post_ids[ $testimonial_data['demo_id'] ] = $existing_testimonial->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $testimonial_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $testimonial_data['featured_image'] );
                unset( $testimonial_data['featured_image'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $testimonial_data['demo_id'] ) ? $testimonial_data['demo_id'] : '';
            unset( $testimonial_data['demo_id'] );

            // Set post type to testimonials
            $testimonial_data['post_type'] = 'testimonials';

            // Insert testimonial
            $testimonial_id = wp_insert_post( $testimonial_data, true );

            if ( is_wp_error( $testimonial_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating testimonial "%s": %s', 'aqualuxe' ), $testimonial_data['post_title'], $testimonial_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $testimonial_id, $featured_image_id );
            }

            // Set testimonial terms
            if ( ! empty( $testimonial_data['terms'] ) ) {
                foreach ( $testimonial_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $testimonial_id, $term_ids, $taxonomy );
                }
            }

            // Set testimonial meta
            if ( ! empty( $testimonial_data['meta'] ) ) {
                foreach ( $testimonial_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $testimonial_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $testimonial_id;
            $this->import_log[] = sprintf( __( 'Created testimonial: %s', 'aqualuxe' ), $testimonial_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d testimonials.', 'aqualuxe' ), count( $testimonials_data ) );
    }
    
    /**
     * Import projects.
     */
    protected function import_projects() {
        $this->import_log[] = __( 'Importing projects...', 'aqualuxe' );

        $projects_data = $this->get_demo_data( 'projects' );
        if ( empty( $projects_data ) ) {
            $this->error_log[] = __( 'No project data found.', 'aqualuxe' );
            return;
        }

        foreach ( $projects_data as $project_data ) {
            // Sanitize project data
            $project_data = $this->sanitize_post_data( $project_data );

            // Check if project already exists
            $existing_project = get_page_by_title( $project_data['post_title'], OBJECT, 'projects' );
            if ( $existing_project ) {
                $this->import_log[] = sprintf( __( 'Project "%s" already exists, skipping.', 'aqualuxe' ), $project_data['post_title'] );
                $this->imported_post_ids[ $project_data['demo_id'] ] = $existing_project->ID;
                continue;
            }

            // Handle featured image
            $featured_image_id = null;
            if ( ! empty( $project_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $project_data['featured_image'] );
                unset( $project_data['featured_image'] );
            }

            // Handle gallery images
            $gallery_image_ids = array();
            if ( ! empty( $project_data['gallery_images'] ) && is_array( $project_data['gallery_images'] ) ) {
                foreach ( $project_data['gallery_images'] as $gallery_image ) {
                    $gallery_image_id = $this->import_image( $gallery_image );
                    if ( $gallery_image_id ) {
                        $gallery_image_ids[] = $gallery_image_id;
                    }
                }
                unset( $project_data['gallery_images'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $project_data['demo_id'] ) ? $project_data['demo_id'] : '';
            unset( $project_data['demo_id'] );

            // Set post type to projects
            $project_data['post_type'] = 'projects';

            // Insert project
            $project_id = wp_insert_post( $project_data, true );

            if ( is_wp_error( $project_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating project "%s": %s', 'aqualuxe' ), $project_data['post_title'], $project_id->get_error_message() );
                continue;
            }

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $project_id, $featured_image_id );
            }

            // Set gallery images
            if ( ! empty( $gallery_image_ids ) ) {
                update_post_meta( $project_id, '_project_gallery', implode( ',', $gallery_image_ids ) );
            }

            // Set project terms
            if ( ! empty( $project_data['terms'] ) ) {
                foreach ( $project_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $project_id, $term_ids, $taxonomy );
                }
            }

            // Set project meta
            if ( ! empty( $project_data['meta'] ) ) {
                foreach ( $project_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $project_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $project_id;
            $this->import_log[] = sprintf( __( 'Created project: %s', 'aqualuxe' ), $project_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d projects.', 'aqualuxe' ), count( $projects_data ) );
    }
    
    /**
     * Import FAQs.
     */
    protected function import_faqs() {
        $this->import_log[] = __( 'Importing FAQs...', 'aqualuxe' );

        $faqs_data = $this->get_demo_data( 'faqs' );
        if ( empty( $faqs_data ) ) {
            $this->error_log[] = __( 'No FAQ data found.', 'aqualuxe' );
            return;
        }

        foreach ( $faqs_data as $faq_data ) {
            // Sanitize FAQ data
            $faq_data = $this->sanitize_post_data( $faq_data );

            // Check if FAQ already exists
            $existing_faq = get_page_by_title( $faq_data['post_title'], OBJECT, 'faqs' );
            if ( $existing_faq ) {
                $this->import_log[] = sprintf( __( 'FAQ "%s" already exists, skipping.', 'aqualuxe' ), $faq_data['post_title'] );
                $this->imported_post_ids[ $faq_data['demo_id'] ] = $existing_faq->ID;
                continue;
            }

            // Store demo ID for reference
            $demo_id = isset( $faq_data['demo_id'] ) ? $faq_data['demo_id'] : '';
            unset( $faq_data['demo_id'] );

            // Set post type to faqs
            $faq_data['post_type'] = 'faqs';

            // Insert FAQ
            $faq_id = wp_insert_post( $faq_data, true );

            if ( is_wp_error( $faq_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating FAQ "%s": %s', 'aqualuxe' ), $faq_data['post_title'], $faq_id->get_error_message() );
                continue;
            }

            // Set FAQ terms
            if ( ! empty( $faq_data['terms'] ) ) {
                foreach ( $faq_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $faq_id, $term_ids, $taxonomy );
                }
            }

            // Set FAQ meta
            if ( ! empty( $faq_data['meta'] ) ) {
                foreach ( $faq_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $faq_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $faq_id;
            $this->import_log[] = sprintf( __( 'Created FAQ: %s', 'aqualuxe' ), $faq_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d FAQs.', 'aqualuxe' ), count( $faqs_data ) );
    }

    /**
     * Import products.
     */
    protected function import_products() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            $this->error_log[] = __( 'WooCommerce is not active. Skipping product import.', 'aqualuxe' );
            return;
        }

        $this->import_log[] = __( 'Importing products...', 'aqualuxe' );

        $products_data = $this->get_demo_data( 'products' );
        if ( empty( $products_data ) ) {
            $this->error_log[] = __( 'No product data found.', 'aqualuxe' );
            return;
        }

        foreach ( $products_data as $product_data ) {
            // Sanitize product data
            $product_data = $this->sanitize_post_data( $product_data );

            // Check if product already exists
            $existing_product = get_page_by_title( $product_data['post_title'], OBJECT, 'product' );
            if ( $existing_product ) {
                $this->import_log[] = sprintf( __( 'Product "%s" already exists, skipping.', 'aqualuxe' ), $product_data['post_title'] );
                $this->imported_post_ids[ $product_data['demo_id'] ] = $existing_product->ID;
                continue;
            }

            // Handle product images
            $featured_image_id = null;
            $gallery_image_ids = array();

            if ( ! empty( $product_data['featured_image'] ) ) {
                $featured_image_id = $this->import_image( $product_data['featured_image'] );
                unset( $product_data['featured_image'] );
            }

            if ( ! empty( $product_data['gallery_images'] ) ) {
                foreach ( $product_data['gallery_images'] as $gallery_image ) {
                    $gallery_image_id = $this->import_image( $gallery_image );
                    if ( $gallery_image_id ) {
                        $gallery_image_ids[] = $gallery_image_id;
                    }
                }
                unset( $product_data['gallery_images'] );
            }

            // Store demo ID for reference
            $demo_id = isset( $product_data['demo_id'] ) ? $product_data['demo_id'] : '';
            unset( $product_data['demo_id'] );

            // Extract product-specific data
            $product_type = isset( $product_data['product_type'] ) ? $product_data['product_type'] : 'simple';
            unset( $product_data['product_type'] );

            $regular_price = isset( $product_data['regular_price'] ) ? $product_data['regular_price'] : '';
            unset( $product_data['regular_price'] );

            $sale_price = isset( $product_data['sale_price'] ) ? $product_data['sale_price'] : '';
            unset( $product_data['sale_price'] );

            $sku = isset( $product_data['sku'] ) ? $product_data['sku'] : '';
            unset( $product_data['sku'] );

            $stock_status = isset( $product_data['stock_status'] ) ? $product_data['stock_status'] : 'instock';
            unset( $product_data['stock_status'] );

            $stock_quantity = isset( $product_data['stock_quantity'] ) ? $product_data['stock_quantity'] : null;
            unset( $product_data['stock_quantity'] );

            $weight = isset( $product_data['weight'] ) ? $product_data['weight'] : '';
            unset( $product_data['weight'] );

            $dimensions = isset( $product_data['dimensions'] ) ? $product_data['dimensions'] : array();
            unset( $product_data['dimensions'] );

            $attributes = isset( $product_data['attributes'] ) ? $product_data['attributes'] : array();
            unset( $product_data['attributes'] );

            // Set post type to product
            $product_data['post_type'] = 'product';

            // Insert product
            $product_id = wp_insert_post( $product_data, true );

            if ( is_wp_error( $product_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating product "%s": %s', 'aqualuxe' ), $product_data['post_title'], $product_id->get_error_message() );
                continue;
            }

            // Set product type
            wp_set_object_terms( $product_id, $product_type, 'product_type' );

            // Set featured image
            if ( $featured_image_id ) {
                set_post_thumbnail( $product_id, $featured_image_id );
            }

            // Set gallery images
            if ( ! empty( $gallery_image_ids ) ) {
                update_post_meta( $product_id, '_product_image_gallery', implode( ',', $gallery_image_ids ) );
            }

            // Set product categories and tags
            if ( ! empty( $product_data['terms'] ) ) {
                foreach ( $product_data['terms'] as $taxonomy => $terms ) {
                    $term_ids = array();
                    foreach ( $terms as $term ) {
                        $term_id = $this->get_or_create_term( $term, $taxonomy );
                        if ( $term_id ) {
                            $term_ids[] = $term_id;
                        }
                    }
                    wp_set_object_terms( $product_id, $term_ids, $taxonomy );
                }
            }

            // Set product meta
            update_post_meta( $product_id, '_sku', $sku );
            update_post_meta( $product_id, '_regular_price', $regular_price );
            update_post_meta( $product_id, '_price', $sale_price ? $sale_price : $regular_price );
            
            if ( $sale_price ) {
                update_post_meta( $product_id, '_sale_price', $sale_price );
            }
            
            update_post_meta( $product_id, '_weight', $weight );
            
            if ( ! empty( $dimensions ) ) {
                if ( isset( $dimensions['length'] ) ) {
                    update_post_meta( $product_id, '_length', $dimensions['length'] );
                }
                if ( isset( $dimensions['width'] ) ) {
                    update_post_meta( $product_id, '_width', $dimensions['width'] );
                }
                if ( isset( $dimensions['height'] ) ) {
                    update_post_meta( $product_id, '_height', $dimensions['height'] );
                }
            }
            
            update_post_meta( $product_id, '_stock_status', $stock_status );
            
            if ( $stock_quantity !== null ) {
                update_post_meta( $product_id, '_manage_stock', 'yes' );
                update_post_meta( $product_id, '_stock', $stock_quantity );
            } else {
                update_post_meta( $product_id, '_manage_stock', 'no' );
            }

            // Set product attributes
            if ( ! empty( $attributes ) ) {
                $product_attributes = array();
                
                foreach ( $attributes as $attribute_name => $attribute_data ) {
                    $attribute_id = 0;
                    
                    // Check if this is a taxonomy attribute
                    if ( ! empty( $attribute_data['taxonomy'] ) ) {
                        $taxonomy = $attribute_data['taxonomy'];
                        
                        // Create terms for the attribute
                        $term_ids = array();
                        foreach ( $attribute_data['terms'] as $term ) {
                            $term_id = $this->get_or_create_term( $term, $taxonomy );
                            if ( $term_id ) {
                                $term_ids[] = $term_id;
                            }
                        }
                        
                        // Add terms to product
                        wp_set_object_terms( $product_id, $term_ids, $taxonomy );
                        
                        $product_attributes[ $taxonomy ] = array(
                            'name'         => $taxonomy,
                            'value'        => '',
                            'position'     => isset( $attribute_data['position'] ) ? $attribute_data['position'] : 0,
                            'is_visible'   => isset( $attribute_data['visible'] ) ? $attribute_data['visible'] : 1,
                            'is_variation' => isset( $attribute_data['variation'] ) ? $attribute_data['variation'] : 0,
                            'is_taxonomy'  => 1,
                        );
                    } else {
                        // This is a custom product attribute
                        $product_attributes[ sanitize_title( $attribute_name ) ] = array(
                            'name'         => $attribute_name,
                            'value'        => implode( ' | ', $attribute_data['terms'] ),
                            'position'     => isset( $attribute_data['position'] ) ? $attribute_data['position'] : 0,
                            'is_visible'   => isset( $attribute_data['visible'] ) ? $attribute_data['visible'] : 1,
                            'is_variation' => isset( $attribute_data['variation'] ) ? $attribute_data['variation'] : 0,
                            'is_taxonomy'  => 0,
                        );
                    }
                }
                
                update_post_meta( $product_id, '_product_attributes', $product_attributes );
            }

            // Set other product meta
            if ( ! empty( $product_data['meta'] ) ) {
                foreach ( $product_data['meta'] as $meta_key => $meta_value ) {
                    update_post_meta( $product_id, $meta_key, $meta_value );
                }
            }

            $this->imported_post_ids[ $demo_id ] = $product_id;
            $this->import_log[] = sprintf( __( 'Created product: %s', 'aqualuxe' ), $product_data['post_title'] );
        }

        $this->import_log[] = sprintf( __( 'Imported %d products.', 'aqualuxe' ), count( $products_data ) );
    }

    /**
     * Import settings.
     */
    protected function import_settings() {
        $this->import_log[] = __( 'Importing theme settings...', 'aqualuxe' );

        $settings_data = $this->get_demo_data( 'settings' );
        if ( empty( $settings_data ) ) {
            $this->error_log[] = __( 'No settings data found.', 'aqualuxe' );
            return;
        }

        // Import customizer settings
        if ( ! empty( $settings_data['customizer'] ) ) {
            foreach ( $settings_data['customizer'] as $option => $value ) {
                set_theme_mod( $option, $value );
            }
            $this->import_log[] = __( 'Imported customizer settings.', 'aqualuxe' );
        }

        // Import other settings
        if ( ! empty( $settings_data['options'] ) ) {
            foreach ( $settings_data['options'] as $option => $value ) {
                update_option( $option, $value );
            }
            $this->import_log[] = __( 'Imported theme options.', 'aqualuxe' );
        }
    }

    /**
     * Import widgets.
     */
    protected function import_widgets() {
        $this->import_log[] = __( 'Importing widgets...', 'aqualuxe' );

        $widgets_data = $this->get_demo_data( 'widgets' );
        if ( empty( $widgets_data ) ) {
            $this->error_log[] = __( 'No widgets data found.', 'aqualuxe' );
            return;
        }

        // Clear existing widgets
        $sidebars_widgets = get_option( 'sidebars_widgets', array() );
        $all_widgets = array();

        // Save widgets in inactive state
        $sidebars_widgets['wp_inactive_widgets'] = array();

        // Import widgets for each sidebar
        foreach ( $widgets_data as $sidebar_id => $widgets ) {
            if ( 'wp_inactive_widgets' === $sidebar_id || ! is_array( $widgets ) ) {
                continue;
            }

            $sidebar_widgets = array();

            foreach ( $widgets as $widget_instance_id => $widget ) {
                $widget_type = $widget['type'];
                $widget_data = isset( $widget['settings'] ) ? $widget['settings'] : array();

                // Get existing widgets of this type
                $widget_options = get_option( 'widget_' . $widget_type, array() );
                
                // Find the next available widget ID
                $next_id = 1;
                while ( isset( $widget_options[ $next_id ] ) ) {
                    $next_id++;
                }

                // Add the widget data
                $widget_options[ $next_id ] = $widget_data;
                
                // Save the widget options
                update_option( 'widget_' . $widget_type, $widget_options );
                
                // Add the widget to the sidebar
                $sidebar_widgets[] = $widget_type . '-' . $next_id;
            }

            // Update sidebar widgets
            $sidebars_widgets[ $sidebar_id ] = $sidebar_widgets;
        }

        // Save sidebars widgets
        update_option( 'sidebars_widgets', $sidebars_widgets );

        $this->import_log[] = __( 'Imported widgets.', 'aqualuxe' );
    }

    /**
     * Import menus.
     */
    protected function import_menus() {
        $this->import_log[] = __( 'Importing menus...', 'aqualuxe' );

        $menus_data = $this->get_demo_data( 'menus' );
        if ( empty( $menus_data ) ) {
            $this->error_log[] = __( 'No menus data found.', 'aqualuxe' );
            return;
        }

        $menu_locations = array();

        foreach ( $menus_data as $menu_name => $menu_data ) {
            // Check if menu already exists
            $existing_menu = wp_get_nav_menu_object( $menu_name );
            
            if ( $existing_menu ) {
                $menu_id = $existing_menu->term_id;
                wp_delete_nav_menu( $menu_id );
            }
            
            // Create menu
            $menu_id = wp_create_nav_menu( $menu_name );
            
            if ( is_wp_error( $menu_id ) ) {
                $this->error_log[] = sprintf( __( 'Error creating menu "%s": %s', 'aqualuxe' ), $menu_name, $menu_id->get_error_message() );
                continue;
            }
            
            // Set menu location
            if ( ! empty( $menu_data['location'] ) ) {
                $menu_locations[ $menu_data['location'] ] = $menu_id;
            }
            
            // Add menu items
            if ( ! empty( $menu_data['items'] ) ) {
                $this->add_menu_items( $menu_id, $menu_data['items'] );
            }
            
            $this->import_log[] = sprintf( __( 'Created menu: %s', 'aqualuxe' ), $menu_name );
        }
        
        // Set menu locations
        if ( ! empty( $menu_locations ) ) {
            set_theme_mod( 'nav_menu_locations', $menu_locations );
        }
        
        $this->import_log[] = sprintf( __( 'Imported %d menus.', 'aqualuxe' ), count( $menus_data ) );
    }

    /**
     * Add menu items recursively.
     *
     * @param int   $menu_id The menu ID.
     * @param array $items   The menu items.
     * @param int   $parent  The parent menu item ID.
     */
    protected function add_menu_items( $menu_id, $items, $parent = 0 ) {
        foreach ( $items as $item ) {
            $item_data = array(
                'menu-item-title'     => isset( $item['title'] ) ? $item['title'] : '',
                'menu-item-status'    => 'publish',
                'menu-item-parent-id' => $parent,
            );
            
            // Set menu item type and object
            if ( ! empty( $item['type'] ) ) {
                $item_data['menu-item-type'] = $item['type'];
                
                switch ( $item['type'] ) {
                    case 'post_type':
                        if ( ! empty( $item['object'] ) ) {
                            $item_data['menu-item-object'] = $item['object'];
                            
                            if ( ! empty( $item['object_id'] ) ) {
                                // Check if this is a demo ID reference
                                if ( isset( $this->imported_post_ids[ $item['object_id'] ] ) ) {
                                    $item_data['menu-item-object-id'] = $this->imported_post_ids[ $item['object_id'] ];
                                } else {
                                    // Try to find by title
                                    $post = get_page_by_title( $item['title'], OBJECT, $item['object'] );
                                    if ( $post ) {
                                        $item_data['menu-item-object-id'] = $post->ID;
                                    } else {
                                        $item_data['menu-item-object-id'] = $item['object_id'];
                                    }
                                }
                            }
                        }
                        break;
                        
                    case 'taxonomy':
                        if ( ! empty( $item['object'] ) ) {
                            $item_data['menu-item-object'] = $item['object'];
                            
                            if ( ! empty( $item['object_id'] ) ) {
                                // Check if this is a demo ID reference
                                if ( isset( $this->imported_term_ids[ $item['object_id'] ] ) ) {
                                    $item_data['menu-item-object-id'] = $this->imported_term_ids[ $item['object_id'] ];
                                } else {
                                    // Try to find by name
                                    $term = get_term_by( 'name', $item['title'], $item['object'] );
                                    if ( $term ) {
                                        $item_data['menu-item-object-id'] = $term->term_id;
                                    } else {
                                        $item_data['menu-item-object-id'] = $item['object_id'];
                                    }
                                }
                            }
                        }
                        break;
                        
                    case 'custom':
                        $item_data['menu-item-url'] = isset( $item['url'] ) ? $item['url'] : '#';
                        break;
                }
            }
            
            // Set menu item position
            if ( isset( $item['position'] ) ) {
                $item_data['menu-item-position'] = $item['position'];
            }
            
            // Set menu item classes
            if ( ! empty( $item['classes'] ) ) {
                $item_data['menu-item-classes'] = $item['classes'];
            }
            
            // Set menu item target
            if ( ! empty( $item['target'] ) ) {
                $item_data['menu-item-target'] = $item['target'];
            }
            
            // Set menu item description
            if ( ! empty( $item['description'] ) ) {
                $item_data['menu-item-description'] = $item['description'];
            }
            
            // Set menu item attr title
            if ( ! empty( $item['attr_title'] ) ) {
                $item_data['menu-item-attr-title'] = $item['attr_title'];
            }
            
            // Set menu item xfn
            if ( ! empty( $item['xfn'] ) ) {
                $item_data['menu-item-xfn'] = $item['xfn'];
            }
            
            // Add menu item
            $item_id = wp_update_nav_menu_item( $menu_id, 0, $item_data );
            
            // Add child items
            if ( ! is_wp_error( $item_id ) && ! empty( $item['children'] ) ) {
                $this->add_menu_items( $menu_id, $item['children'], $item_id );
            }
        }
    }

    /**
     * Process post-import actions.
     */
    protected function process_post_import_actions() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Update permalink structure
        update_option( 'permalink_structure', '/%postname%/' );

        // Regenerate CSS files if needed
        if ( function_exists( 'aqualuxe_generate_css_files' ) ) {
            aqualuxe_generate_css_files();
        }

        $this->import_log[] = __( 'Post-import actions completed.', 'aqualuxe' );
    }

    /**
     * Get demo data from JSON file.
     *
     * @param string $data_type The type of data to get.
     * @return array The demo data.
     */
    protected function get_demo_data( $data_type ) {
        $file_path = $this->demo_content_dir . $data_type . '.json';
        
        if ( ! file_exists( $file_path ) ) {
            return array();
        }
        
        $file_content = file_get_contents( $file_path );
        if ( ! $file_content ) {
            return array();
        }
        
        $data = json_decode( $file_content, true );
        if ( ! $data ) {
            return array();
        }
        
        return $data;
    }

    /**
     * Sanitize post data.
     *
     * @param array $post_data The post data to sanitize.
     * @return array The sanitized post data.
     */
    protected function sanitize_post_data( $post_data ) {
        $sanitized_data = array();
        
        // Basic post fields
        $text_fields = array(
            'post_title',
            'post_name',
            'post_excerpt',
            'post_status',
            'post_type',
            'post_author',
            'ping_status',
            'comment_status',
            'menu_order',
            'page_template',
        );
        
        foreach ( $text_fields as $field ) {
            if ( isset( $post_data[ $field ] ) ) {
                $sanitized_data[ $field ] = sanitize_text_field( $post_data[ $field ] );
            }
        }
        
        // Content field
        if ( isset( $post_data['post_content'] ) ) {
            $sanitized_data['post_content'] = wp_kses_post( $post_data['post_content'] );
        }
        
        // Date fields
        $date_fields = array(
            'post_date',
            'post_date_gmt',
            'post_modified',
            'post_modified_gmt',
        );
        
        foreach ( $date_fields as $field ) {
            if ( isset( $post_data[ $field ] ) ) {
                $sanitized_data[ $field ] = sanitize_text_field( $post_data[ $field ] );
            }
        }
        
        // Special fields
        if ( isset( $post_data['demo_id'] ) ) {
            $sanitized_data['demo_id'] = sanitize_text_field( $post_data['demo_id'] );
        }
        
        if ( isset( $post_data['featured_image'] ) ) {
            $sanitized_data['featured_image'] = sanitize_text_field( $post_data['featured_image'] );
        }
        
        if ( isset( $post_data['gallery_images'] ) && is_array( $post_data['gallery_images'] ) ) {
            $sanitized_data['gallery_images'] = array_map( 'sanitize_text_field', $post_data['gallery_images'] );
        }
        
        // Terms
        if ( isset( $post_data['terms'] ) && is_array( $post_data['terms'] ) ) {
            $sanitized_data['terms'] = array();
            
            foreach ( $post_data['terms'] as $taxonomy => $terms ) {
                $sanitized_data['terms'][ sanitize_text_field( $taxonomy ) ] = array_map( 'sanitize_text_field', $terms );
            }
        }
        
        // Meta
        if ( isset( $post_data['meta'] ) && is_array( $post_data['meta'] ) ) {
            $sanitized_data['meta'] = array();
            
            foreach ( $post_data['meta'] as $meta_key => $meta_value ) {
                $sanitized_key = sanitize_text_field( $meta_key );
                
                if ( is_array( $meta_value ) ) {
                    $sanitized_data['meta'][ $sanitized_key ] = $this->sanitize_array( $meta_value );
                } else {
                    $sanitized_data['meta'][ $sanitized_key ] = sanitize_text_field( $meta_value );
                }
            }
        }
        
        return $sanitized_data;
    }

    /**
     * Sanitize an array recursively.
     *
     * @param array $array The array to sanitize.
     * @return array The sanitized array.
     */
    protected function sanitize_array( $array ) {
        $sanitized = array();
        
        foreach ( $array as $key => $value ) {
            if ( is_array( $value ) ) {
                $sanitized[ sanitize_text_field( $key ) ] = $this->sanitize_array( $value );
            } else {
                $sanitized[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
            }
        }
        
        return $sanitized;
    }

    /**
     * Get or create a term.
     *
     * @param string $term     The term name.
     * @param string $taxonomy The taxonomy name.
     * @return int|false The term ID or false on failure.
     */
    protected function get_or_create_term( $term, $taxonomy ) {
        // Check if taxonomy exists
        if ( ! taxonomy_exists( $taxonomy ) ) {
            return false;
        }
        
        // Check if term exists
        $existing_term = get_term_by( 'name', $term, $taxonomy );
        
        if ( $existing_term ) {
            return $existing_term->term_id;
        }
        
        // Create term
        $result = wp_insert_term( $term, $taxonomy );
        
        if ( is_wp_error( $result ) ) {
            $this->error_log[] = sprintf( __( 'Error creating term "%s" in taxonomy "%s": %s', 'aqualuxe' ), $term, $taxonomy, $result->get_error_message() );
            return false;
        }
        
        return $result['term_id'];
    }

    /**
     * Import an image.
     *
     * @param string $image_url The image URL or path.
     * @return int|false The attachment ID or false on failure.
     */
    protected function import_image( $image_url ) {
        // Check if image is a URL or a local path
        if ( strpos( $image_url, 'http' ) === 0 ) {
            // Remote image
            $image_path = $image_url;
        } else {
            // Local image in demo content directory
            $image_path = $this->demo_content_dir . 'images/' . $image_url;
            
            if ( ! file_exists( $image_path ) ) {
                $this->error_log[] = sprintf( __( 'Image file not found: %s', 'aqualuxe' ), $image_path );
                return false;
            }
        }
        
        // Get image data
        $image_data = file_get_contents( $image_path );
        
        if ( ! $image_data ) {
            $this->error_log[] = sprintf( __( 'Could not get image data: %s', 'aqualuxe' ), $image_path );
            return false;
        }
        
        // Get image filename
        $filename = basename( $image_path );
        
        // Upload image to WordPress
        $upload = wp_upload_bits( $filename, null, $image_data );
        
        if ( $upload['error'] ) {
            $this->error_log[] = sprintf( __( 'Error uploading image: %s', 'aqualuxe' ), $upload['error'] );
            return false;
        }
        
        // Get file type
        $wp_filetype = wp_check_filetype( $filename, null );
        
        // Prepare attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        
        // Insert attachment
        $attachment_id = wp_insert_attachment( $attachment, $upload['file'] );
        
        if ( ! $attachment_id ) {
            $this->error_log[] = sprintf( __( 'Error creating attachment for image: %s', 'aqualuxe' ), $filename );
            return false;
        }
        
        // Generate attachment metadata
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        
        // Add attribution meta
        update_post_meta( $attachment_id, '_demo_image', 'yes' );
        update_post_meta( $attachment_id, '_demo_image_source', $image_url );
        
        $this->imported_attachment_ids[] = $attachment_id;
        
        return $attachment_id;
    }
}