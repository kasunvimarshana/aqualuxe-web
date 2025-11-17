<?php
/**
 * Demo Content Importer
 *
 * Handles importing demo content for the theme
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Demo_Importer
 *
 * Provides demo content import functionality
 *
 * @since 1.0.0
 */
class Demo_Importer {

    /**
     * Demo content configuration
     *
     * @var array
     */
    private $demo_config = array();

    /**
     * Initialize the demo importer
     *
     * @since 1.0.0
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'import_demo_content' ) );
        
        $this->setup_demo_config();
    }

    /**
     * Setup demo configuration
     *
     * @since 1.0.0
     */
    private function setup_demo_config() {
        $this->demo_config = array(
            'default' => array(
                'name'        => esc_html__( 'AquaLuxe Default', 'aqualuxe' ),
                'description' => esc_html__( 'Complete AquaLuxe demo with sample pages, posts, and products.', 'aqualuxe' ),
                'preview'     => AQUALUXE_THEME_URI . '/assets/images/demo-preview.jpg',
                'content'     => array(
                    'posts'      => true,
                    'pages'      => true,
                    'products'   => true,
                    'menus'      => true,
                    'widgets'    => true,
                    'customizer' => true,
                ),
            ),
        );
    }

    /**
     * Add admin menu
     *
     * @since 1.0.0
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__( 'Demo Importer', 'aqualuxe' ),
            esc_html__( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Admin page content
     *
     * @since 1.0.0
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-admin">
            <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
            
            <div class="demo-importer">
                <div class="notice notice-info">
                    <p><?php esc_html_e( 'This will import demo content including posts, pages, menus, widgets, and theme settings. It may take a few minutes to complete.', 'aqualuxe' ); ?></p>
                    <p><strong><?php esc_html_e( 'Warning:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'This will overwrite existing content. Please backup your site before proceeding.', 'aqualuxe' ); ?></p>
                </div>

                <div class="demo-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0;">
                    <?php foreach ( $this->demo_config as $demo_id => $demo ) : ?>
                        <div class="demo-card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff;">
                            <?php if ( ! empty( $demo['preview'] ) ) : ?>
                                <div class="demo-preview">
                                    <img src="<?php echo esc_url( $demo['preview'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                            
                            <div style="padding: 20px;">
                                <h3 style="margin: 0 0 10px;"><?php echo esc_html( $demo['name'] ); ?></h3>
                                <p style="color: #666; margin: 0 0 20px;"><?php echo esc_html( $demo['description'] ); ?></p>
                                
                                <div class="demo-content-types" style="margin-bottom: 20px;">
                                    <h4 style="margin: 0 0 10px; font-size: 14px;"><?php esc_html_e( 'Includes:', 'aqualuxe' ); ?></h4>
                                    <ul style="margin: 0; padding-left: 20px;">
                                        <?php foreach ( $demo['content'] as $type => $enabled ) : ?>
                                            <?php if ( $enabled ) : ?>
                                                <li><?php echo esc_html( ucfirst( str_replace( '_', ' ', $type ) ) ); ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                
                                <button type="button" class="button button-primary import-demo-btn" data-demo="<?php echo esc_attr( $demo_id ); ?>">
                                    <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="import-progress" style="display: none; margin: 20px 0;">
                    <h3><?php esc_html_e( 'Importing Demo Content...', 'aqualuxe' ); ?></h3>
                    <div class="progress-bar" style="background: #f1f1f1; border-radius: 10px; overflow: hidden; height: 10px;">
                        <div class="progress-fill" style="height: 100%; background: #0073aa; width: 0%; transition: width 0.3s ease;"></div>
                    </div>
                    <p class="progress-text" style="margin: 10px 0 0;"><?php esc_html_e( 'Please wait...', 'aqualuxe' ); ?></p>
                </div>

                <div class="import-status"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Import demo content via AJAX
     *
     * @since 1.0.0
     */
    public function import_demo_content() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_admin_nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
        }

        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Insufficient permissions.', 'aqualuxe' ) ) );
        }

        // Get demo ID
        $demo_id = sanitize_key( $_POST['demo'] ?? 'default' );

        if ( ! isset( $this->demo_config[ $demo_id ] ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid demo selection.', 'aqualuxe' ) ) );
        }

        // Import demo content
        $result = $this->import_demo_data( $demo_id );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( array( 'message' => esc_html__( 'Demo content imported successfully!', 'aqualuxe' ) ) );
    }

    /**
     * Import demo data
     *
     * @since 1.0.0
     * @param string $demo_id Demo ID
     * @return bool|WP_Error
     */
    private function import_demo_data( $demo_id ) {
        $demo = $this->demo_config[ $demo_id ];

        try {
            // Import posts
            if ( $demo['content']['posts'] ) {
                $this->import_sample_posts();
            }

            // Import pages
            if ( $demo['content']['pages'] ) {
                $this->import_sample_pages();
            }

            // Import products (if WooCommerce is active)
            if ( $demo['content']['products'] && class_exists( 'WooCommerce' ) ) {
                $this->import_sample_products();
            }

            // Import menus
            if ( $demo['content']['menus'] ) {
                $this->import_sample_menus();
            }

            // Import customizer settings
            if ( $demo['content']['customizer'] ) {
                $this->import_customizer_settings();
            }

            return true;

        } catch ( Exception $e ) {
            return new WP_Error( 'import_failed', $e->getMessage() );
        }
    }

    /**
     * Import sample posts
     *
     * @since 1.0.0
     */
    private function import_sample_posts() {
        $sample_posts = array(
            array(
                'title'   => 'Welcome to AquaLuxe',
                'content' => 'This is a sample blog post. You can edit or delete this post and start creating your own amazing content.',
                'status'  => 'publish',
                'type'    => 'post',
            ),
            array(
                'title'   => 'Aquarium Care Tips',
                'content' => 'Learn essential tips for maintaining a healthy aquarium environment for your fish.',
                'status'  => 'publish',
                'type'    => 'post',
            ),
            array(
                'title'   => 'Choosing the Right Fish',
                'content' => 'A comprehensive guide to selecting the perfect fish for your aquarium setup.',
                'status'  => 'publish',
                'type'    => 'post',
            ),
        );

        foreach ( $sample_posts as $post_data ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_status'  => $post_data['status'],
                'post_type'    => $post_data['type'],
                'post_author'  => 1,
            ) );

            if ( is_wp_error( $post_id ) ) {
                throw new Exception( 'Failed to create post: ' . $post_data['title'] );
            }
        }
    }

    /**
     * Import sample pages
     *
     * @since 1.0.0
     */
    private function import_sample_pages() {
        $sample_pages = array(
            array(
                'title'   => 'About Us',
                'content' => 'Welcome to AquaLuxe - your premier destination for elegant aquatic solutions.',
                'status'  => 'publish',
                'slug'    => 'about',
            ),
            array(
                'title'   => 'Services',
                'content' => 'We offer comprehensive aquarium design, maintenance, and consultation services.',
                'status'  => 'publish',
                'slug'    => 'services',
            ),
            array(
                'title'   => 'Contact',
                'content' => 'Get in touch with our team of aquatic experts.',
                'status'  => 'publish',
                'slug'    => 'contact',
            ),
        );

        foreach ( $sample_pages as $page_data ) {
            $page_id = wp_insert_post( array(
                'post_title'   => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status'  => $page_data['status'],
                'post_type'    => 'page',
                'post_name'    => $page_data['slug'],
                'post_author'  => 1,
            ) );

            if ( is_wp_error( $page_id ) ) {
                throw new Exception( 'Failed to create page: ' . $page_data['title'] );
            }
        }
    }

    /**
     * Import sample products
     *
     * @since 1.0.0
     */
    private function import_sample_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $sample_products = array(
            array(
                'title'       => 'Premium Aquarium Kit',
                'description' => 'Complete aquarium setup with all essential components.',
                'price'       => '299.99',
                'sku'         => 'AQL-001',
            ),
            array(
                'title'       => 'Tropical Fish Collection',
                'description' => 'Beautiful assortment of tropical fish species.',
                'price'       => '89.99',
                'sku'         => 'AQL-002',
            ),
        );

        foreach ( $sample_products as $product_data ) {
            $product = new \WC_Product_Simple();
            $product->set_name( $product_data['title'] );
            $product->set_description( $product_data['description'] );
            $product->set_regular_price( $product_data['price'] );
            $product->set_sku( $product_data['sku'] );
            $product->set_status( 'publish' );
            
            $product_id = $product->save();

            if ( ! $product_id ) {
                throw new Exception( 'Failed to create product: ' . $product_data['title'] );
            }
        }
    }

    /**
     * Import sample menus
     *
     * @since 1.0.0
     */
    private function import_sample_menus() {
        // Create primary menu
        $menu_id = wp_create_nav_menu( 'Primary Menu' );
        
        if ( is_wp_error( $menu_id ) ) {
            throw new Exception( 'Failed to create menu' );
        }

        // Add menu items
        $menu_items = array(
            array( 'title' => 'Home', 'url' => home_url( '/' ) ),
            array( 'title' => 'About', 'url' => home_url( '/about/' ) ),
            array( 'title' => 'Services', 'url' => home_url( '/services/' ) ),
            array( 'title' => 'Blog', 'url' => home_url( '/blog/' ) ),
            array( 'title' => 'Contact', 'url' => home_url( '/contact/' ) ),
        );

        foreach ( $menu_items as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'  => $item['title'],
                'menu-item-url'    => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-type'   => 'custom',
            ) );
        }

        // Assign menu to location
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    /**
     * Import customizer settings
     *
     * @since 1.0.0
     */
    private function import_customizer_settings() {
        $settings = array(
            'aqualuxe_primary_color'   => '#0ea5e9',
            'aqualuxe_secondary_color' => '#64748b',
            'aqualuxe_accent_color'    => '#22c55e',
        );

        foreach ( $settings as $setting => $value ) {
            set_theme_mod( $setting, $value );
        }
    }
}