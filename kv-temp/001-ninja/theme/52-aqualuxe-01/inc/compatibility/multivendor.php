<?php
/**
 * Multivendor Compatibility File
 *
 * @package AquaLuxe
 */

/**
 * Make theme compatible with multivendor plugins
 */
class AquaLuxe_Multivendor {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Multivendor
     */
    private static $instance;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Multivendor
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize multivendor compatibility
        $this->init();
    }

    /**
     * Initialize multivendor compatibility
     */
    private function init() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // WC Marketplace compatibility
        if ( class_exists( 'WCMp' ) ) {
            $this->setup_wcmp();
        }
        
        // Dokan compatibility
        if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'Dokan_Pro' ) ) {
            $this->setup_dokan();
        }
        
        // WC Vendors compatibility
        if ( class_exists( 'WC_Vendors' ) ) {
            $this->setup_wc_vendors();
        }
        
        // WCFM compatibility
        if ( class_exists( 'WCFM' ) ) {
            $this->setup_wcfm();
        }
        
        // Add vendor information to product page
        add_action( 'woocommerce_single_product_summary', array( $this, 'add_vendor_info' ), 7 );
        
        // Add vendor tab to product tabs
        add_filter( 'woocommerce_product_tabs', array( $this, 'add_vendor_tab' ) );
        
        // Add vendor filter to shop page
        add_action( 'woocommerce_before_shop_loop', array( $this, 'add_vendor_filter' ), 30 );
        
        // Add vendor dashboard link to my account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'add_vendor_dashboard_link' ) );
        
        // Register vendor dashboard endpoint
        add_action( 'init', array( $this, 'register_vendor_dashboard_endpoint' ) );
        
        // Add vendor dashboard content
        add_action( 'woocommerce_account_vendor-dashboard_endpoint', array( $this, 'vendor_dashboard_content' ) );
        
        // Add vendor registration form to my account page
        add_action( 'woocommerce_after_my_account', array( $this, 'add_vendor_registration_form' ) );
        
        // Process vendor registration
        add_action( 'wp_ajax_aqualuxe_vendor_registration', array( $this, 'process_vendor_registration' ) );
        
        // Add vendor store header
        add_action( 'aqualuxe_before_main_content', array( $this, 'add_vendor_store_header' ) );
        
        // Add vendor store sidebar
        add_action( 'aqualuxe_sidebar', array( $this, 'add_vendor_store_sidebar' ) );
        
        // Add vendor store footer
        add_action( 'aqualuxe_after_main_content', array( $this, 'add_vendor_store_footer' ) );
    }

    /**
     * Setup WC Marketplace
     */
    private function setup_wcmp() {
        // Add theme support for WC Marketplace
        add_theme_support( 'wcmp' );
        
        // Remove default vendor store header
        remove_action( 'woocommerce_before_main_content', array( 'WCMp_Frontend', 'wcmp_vendor_store_header' ), 10 );
        
        // Remove default vendor tabs
        remove_filter( 'woocommerce_product_tabs', array( 'WCMp_Frontend', 'wcmp_vendor_tab' ) );
        
        // Add custom vendor store header
        add_action( 'aqualuxe_before_main_content', array( $this, 'wcmp_vendor_store_header' ), 20 );
        
        // Add custom vendor tab
        add_filter( 'woocommerce_product_tabs', array( $this, 'wcmp_vendor_tab' ) );
        
        // Add vendor dashboard link to my account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'wcmp_add_vendor_dashboard_link' ) );
        
        // Add vendor registration form
        add_action( 'woocommerce_after_my_account', array( $this, 'wcmp_add_vendor_registration_form' ) );
    }

    /**
     * WC Marketplace vendor store header
     */
    public function wcmp_vendor_store_header() {
        global $WCMp;
        
        if ( ! function_exists( 'wcmp_get_vendor' ) ) {
            return;
        }
        
        $vendor_id = 0;
        $vendor = false;
        
        if ( is_tax( $WCMp->taxonomy->taxonomy_name ) ) {
            $vendor_id = get_queried_object()->term_id;
            $vendor = get_wcmp_vendor_by_term( $vendor_id );
        }
        
        if ( ! $vendor ) {
            return;
        }
        
        $banner = $vendor->get_image( 'banner' );
        $logo = $vendor->get_image();
        $store_name = $vendor->page_title;
        $store_description = $vendor->description;
        
        ?>
        <div class="vendor-store-header">
            <?php if ( $banner ) : ?>
                <div class="vendor-banner" style="background-image: url(<?php echo esc_url( $banner ); ?>);">
                    <div class="vendor-banner-overlay"></div>
                </div>
            <?php endif; ?>
            
            <div class="container mx-auto px-4">
                <div class="vendor-store-info">
                    <?php if ( $logo ) : ?>
                        <div class="vendor-logo">
                            <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $store_name ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="vendor-details">
                        <h1 class="vendor-name"><?php echo esc_html( $store_name ); ?></h1>
                        
                        <?php if ( $store_description ) : ?>
                            <div class="vendor-description">
                                <?php echo wp_kses_post( $store_description ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="vendor-stats">
                            <?php
                            $vendor_products = $vendor->get_products();
                            $product_count = count( $vendor_products );
                            $rating_info = wcmp_get_vendor_review_info( $vendor->term_id );
                            $rating = $rating_info['avg_rating'];
                            ?>
                            
                            <span class="vendor-product-count">
                                <?php printf( _n( '%s product', '%s products', $product_count, 'aqualuxe' ), number_format_i18n( $product_count ) ); ?>
                            </span>
                            
                            <?php if ( $rating ) : ?>
                                <span class="vendor-rating">
                                    <?php echo wc_get_rating_html( $rating ); ?>
                                    <span class="rating-count">(<?php echo esc_html( $rating_info['total_rating'] ); ?>)</span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * WC Marketplace vendor tab
     *
     * @param array $tabs Product tabs.
     * @return array
     */
    public function wcmp_vendor_tab( $tabs ) {
        global $product;
        
        if ( ! $product || ! function_exists( 'get_wcmp_product_vendors' ) ) {
            return $tabs;
        }
        
        $vendor = get_wcmp_product_vendors( $product->get_id() );
        
        if ( ! $vendor ) {
            return $tabs;
        }
        
        $tabs['vendor'] = array(
            'title'    => __( 'Vendor', 'aqualuxe' ),
            'priority' => 20,
            'callback' => array( $this, 'wcmp_vendor_tab_content' ),
        );
        
        return $tabs;
    }

    /**
     * WC Marketplace vendor tab content
     */
    public function wcmp_vendor_tab_content() {
        global $product;
        
        if ( ! $product || ! function_exists( 'get_wcmp_product_vendors' ) ) {
            return;
        }
        
        $vendor = get_wcmp_product_vendors( $product->get_id() );
        
        if ( ! $vendor ) {
            return;
        }
        
        $vendor_term = get_term( $vendor->term_id );
        $vendor_link = get_term_link( $vendor_term );
        $store_name = $vendor->page_title;
        $store_description = $vendor->description;
        $logo = $vendor->get_image();
        
        ?>
        <div class="vendor-tab-content">
            <div class="vendor-header">
                <?php if ( $logo ) : ?>
                    <div class="vendor-logo">
                        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( $store_name ); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="vendor-info">
                    <h3 class="vendor-name">
                        <a href="<?php echo esc_url( $vendor_link ); ?>"><?php echo esc_html( $store_name ); ?></a>
                    </h3>
                    
                    <?php
                    $rating_info = wcmp_get_vendor_review_info( $vendor->term_id );
                    $rating = $rating_info['avg_rating'];
                    
                    if ( $rating ) :
                    ?>
                        <div class="vendor-rating">
                            <?php echo wc_get_rating_html( $rating ); ?>
                            <span class="rating-count">(<?php echo esc_html( $rating_info['total_rating'] ); ?>)</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ( $store_description ) : ?>
                <div class="vendor-description">
                    <?php echo wp_kses_post( $store_description ); ?>
                </div>
            <?php endif; ?>
            
            <div class="vendor-products">
                <h4><?php esc_html_e( 'More Products from this Vendor', 'aqualuxe' ); ?></h4>
                
                <?php
                $vendor_products = $vendor->get_products( array(
                    'posts_per_page' => 4,
                    'post__not_in'   => array( $product->get_id() ),
                ) );
                
                if ( $vendor_products ) :
                    woocommerce_product_loop_start();
                    
                    foreach ( $vendor_products as $vendor_product ) {
                        $post_object = get_post( $vendor_product->get_id() );
                        setup_postdata( $GLOBALS['post'] =& $post_object );
                        wc_get_template_part( 'content', 'product' );
                    }
                    
                    woocommerce_product_loop_end();
                    wp_reset_postdata();
                else :
                    echo '<p>' . esc_html__( 'No products found', 'aqualuxe' ) . '</p>';
                endif;
                ?>
                
                <div class="vendor-store-link">
                    <a href="<?php echo esc_url( $vendor_link ); ?>" class="button"><?php esc_html_e( 'Visit Store', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * WC Marketplace add vendor dashboard link
     *
     * @param array $items Menu items.
     * @return array
     */
    public function wcmp_add_vendor_dashboard_link( $items ) {
        if ( ! function_exists( 'is_user_wcmp_vendor' ) ) {
            return $items;
        }
        
        if ( is_user_wcmp_vendor( get_current_user_id() ) ) {
            $items['wcmp-vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        } elseif ( function_exists( 'get_wcmp_vendor_settings' ) && get_wcmp_vendor_settings( 'vendor_registration', 'general' ) ) {
            $items['vendor-registration'] = __( 'Become a Vendor', 'aqualuxe' );
        }
        
        return $items;
    }

    /**
     * WC Marketplace add vendor registration form
     */
    public function wcmp_add_vendor_registration_form() {
        if ( ! function_exists( 'get_wcmp_vendor_settings' ) || ! get_wcmp_vendor_settings( 'vendor_registration', 'general' ) ) {
            return;
        }
        
        if ( is_user_logged_in() && ! is_user_wcmp_vendor( get_current_user_id() ) && ! is_user_wcmp_pending_vendor( get_current_user_id() ) && ! is_user_wcmp_rejected_vendor( get_current_user_id() ) ) {
            echo do_shortcode( '[wcmp_vendor_registration]' );
        }
    }

    /**
     * Setup Dokan
     */
    private function setup_dokan() {
        // Add theme support for Dokan
        add_theme_support( 'dokan' );
        
        // Remove default Dokan store header
        remove_action( 'dokan_store_profile_frame_after', array( 'WeDevs\Dokan\Vendor\StoreListsFilter', 'store_header_widgets' ), 10 );
        
        // Add custom store header
        add_action( 'dokan_store_profile_frame_after', array( $this, 'dokan_store_header_widgets' ), 10 );
        
        // Add vendor dashboard link to my account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'dokan_add_vendor_dashboard_link' ) );
        
        // Add vendor registration form
        add_action( 'woocommerce_after_my_account', array( $this, 'dokan_add_vendor_registration_form' ) );
    }

    /**
     * Dokan store header widgets
     */
    public function dokan_store_header_widgets() {
        if ( ! function_exists( 'dokan_get_store_info' ) ) {
            return;
        }
        
        $store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
        $store_info   = $store_user->get_shop_info();
        $store_tabs   = dokan_get_store_tabs( $store_user->get_id() );
        $social_info  = $store_user->get_social_profiles();
        $store_url    = $store_user->get_shop_url();
        $store_name   = $store_user->get_shop_name();
        $store_rating = $store_user->get_rating();
        
        ?>
        <div class="dokan-store-tabs">
            <ul class="dokan-list-inline">
                <?php foreach ( $store_tabs as $key => $tab ) : ?>
                    <?php if ( $tab['url'] ) : ?>
                        <li><a href="<?php echo esc_url( $tab['url'] ); ?>"><?php echo esc_html( $tab['title'] ); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="dokan-store-support">
            <?php if ( ! empty( $social_info ) ) : ?>
                <div class="store-social-wrapper">
                    <ul class="store-social">
                        <?php foreach ( $social_info as $key => $value ) : ?>
                            <?php if ( ! empty( $value ) ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( $value ); ?>" target="_blank">
                                        <i class="fab fa-<?php echo esc_attr( $key ); ?>"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php if ( function_exists( 'dokan_store_support_button' ) ) : ?>
                <div class="store-support-wrapper">
                    <?php dokan_store_support_button(); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Dokan add vendor dashboard link
     *
     * @param array $items Menu items.
     * @return array
     */
    public function dokan_add_vendor_dashboard_link( $items ) {
        if ( ! function_exists( 'dokan_is_user_seller' ) ) {
            return $items;
        }
        
        if ( dokan_is_user_seller( get_current_user_id() ) ) {
            $items['dokan-vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        } elseif ( function_exists( 'dokan_is_seller_enabled' ) && dokan_is_seller_enabled( get_current_user_id() ) ) {
            $items['vendor-registration'] = __( 'Become a Vendor', 'aqualuxe' );
        }
        
        return $items;
    }

    /**
     * Dokan add vendor registration form
     */
    public function dokan_add_vendor_registration_form() {
        if ( ! function_exists( 'dokan_is_seller_enabled' ) ) {
            return;
        }
        
        if ( is_user_logged_in() && ! dokan_is_user_seller( get_current_user_id() ) && dokan_is_seller_enabled( get_current_user_id() ) ) {
            echo do_shortcode( '[dokan-vendor-registration]' );
        }
    }

    /**
     * Setup WC Vendors
     */
    private function setup_wc_vendors() {
        // Add theme support for WC Vendors
        add_theme_support( 'wc-vendors' );
        
        // Remove default vendor shop header
        remove_action( 'woocommerce_before_main_content', array( 'WCV_Vendor_Shop', 'shop_description' ), 30 );
        
        // Add custom vendor shop header
        add_action( 'aqualuxe_before_main_content', array( $this, 'wcv_shop_description' ), 30 );
        
        // Add vendor dashboard link to my account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'wcv_add_vendor_dashboard_link' ) );
        
        // Add vendor registration form
        add_action( 'woocommerce_after_my_account', array( $this, 'wcv_add_vendor_registration_form' ) );
    }

    /**
     * WC Vendors shop description
     */
    public function wcv_shop_description() {
        if ( ! function_exists( 'WCV_Vendors' ) || ! function_exists( 'get_user_meta' ) ) {
            return;
        }
        
        $vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
        $vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
        
        if ( ! $vendor_id ) {
            return;
        }
        
        $shop_name        = get_user_meta( $vendor_id, 'pv_shop_name', true );
        $shop_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
        $shop_image       = get_user_meta( $vendor_id, 'pv_shop_image', true );
        $shop_image_url   = $shop_image ? wp_get_attachment_url( $shop_image ) : '';
        
        ?>
        <div class="vendor-shop-header">
            <div class="container mx-auto px-4">
                <div class="vendor-shop-info">
                    <?php if ( $shop_image_url ) : ?>
                        <div class="vendor-shop-image">
                            <img src="<?php echo esc_url( $shop_image_url ); ?>" alt="<?php echo esc_attr( $shop_name ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="vendor-shop-details">
                        <h1 class="vendor-shop-name"><?php echo esc_html( $shop_name ); ?></h1>
                        
                        <?php if ( $shop_description ) : ?>
                            <div class="vendor-shop-description">
                                <?php echo wp_kses_post( $shop_description ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * WC Vendors add vendor dashboard link
     *
     * @param array $items Menu items.
     * @return array
     */
    public function wcv_add_vendor_dashboard_link( $items ) {
        if ( ! function_exists( 'WCV_Vendors' ) ) {
            return $items;
        }
        
        if ( WCV_Vendors::is_vendor( get_current_user_id() ) ) {
            $items['wcv-vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        } elseif ( function_exists( 'get_option' ) && get_option( 'wcvendors_vendor_allow_registration' ) ) {
            $items['vendor-registration'] = __( 'Become a Vendor', 'aqualuxe' );
        }
        
        return $items;
    }

    /**
     * WC Vendors add vendor registration form
     */
    public function wcv_add_vendor_registration_form() {
        if ( ! function_exists( 'WCV_Vendors' ) || ! function_exists( 'get_option' ) ) {
            return;
        }
        
        if ( is_user_logged_in() && ! WCV_Vendors::is_vendor( get_current_user_id() ) && get_option( 'wcvendors_vendor_allow_registration' ) ) {
            echo do_shortcode( '[wcv_vendor_registration]' );
        }
    }

    /**
     * Setup WCFM
     */
    private function setup_wcfm() {
        // Add theme support for WCFM
        add_theme_support( 'wcfm' );
        
        // Remove default store header
        remove_action( 'wcfmmp_before_store_page', array( 'WCFMmp_Store_Page', 'wcfmmp_store_page_header' ), 10 );
        
        // Add custom store header
        add_action( 'wcfmmp_before_store_page', array( $this, 'wcfm_store_page_header' ), 10 );
        
        // Add vendor dashboard link to my account menu
        add_filter( 'woocommerce_account_menu_items', array( $this, 'wcfm_add_vendor_dashboard_link' ) );
        
        // Add vendor registration form
        add_action( 'woocommerce_after_my_account', array( $this, 'wcfm_add_vendor_registration_form' ) );
    }

    /**
     * WCFM store page header
     */
    public function wcfm_store_page_header() {
        global $WCFM, $WCFMmp;
        
        if ( ! function_exists( 'wcfmmp_get_store' ) ) {
            return;
        }
        
        $store_id = 0;
        $store_user = array();
        
        if ( isset( $WCFMmp->wcfmmp_marketplace_options['store_name_position'] ) ) {
            $store_name_position = $WCFMmp->wcfmmp_marketplace_options['store_name_position'];
        } else {
            $store_name_position = 'on_header';
        }
        
        if ( isset( $_GET['wcfm_store'] ) && ! empty( $_GET['wcfm_store'] ) ) {
            $store_user = get_user_by( 'slug', sanitize_text_field( $_GET['wcfm_store'] ) );
            $store_id = $store_user->ID;
        }
        
        if ( ! $store_id ) {
            return;
        }
        
        $store_info = wcfmmp_get_store( $store_id );
        
        if ( ! $store_info ) {
            return;
        }
        
        $store_name = $store_info->get_shop_name();
        $store_banner = $store_info->get_banner();
        $store_logo = $store_info->get_avatar();
        $store_description = $store_info->get_shop_description();
        
        ?>
        <div class="wcfm-store-header">
            <?php if ( $store_banner ) : ?>
                <div class="wcfm-store-banner" style="background-image: url(<?php echo esc_url( $store_banner ); ?>);">
                    <div class="wcfm-store-banner-overlay"></div>
                </div>
            <?php endif; ?>
            
            <div class="container mx-auto px-4">
                <div class="wcfm-store-info">
                    <?php if ( $store_logo ) : ?>
                        <div class="wcfm-store-logo">
                            <img src="<?php echo esc_url( $store_logo ); ?>" alt="<?php echo esc_attr( $store_name ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="wcfm-store-details">
                        <?php if ( 'on_header' === $store_name_position ) : ?>
                            <h1 class="wcfm-store-name"><?php echo esc_html( $store_name ); ?></h1>
                        <?php endif; ?>
                        
                        <?php if ( $store_description ) : ?>
                            <div class="wcfm-store-description">
                                <?php echo wp_kses_post( $store_description ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="wcfm-store-stats">
                            <?php
                            $store_rating = $store_info->get_avg_review_rating();
                            $review_count = $store_info->get_total_review_count();
                            ?>
                            
                            <?php if ( $store_rating ) : ?>
                                <span class="wcfm-store-rating">
                                    <?php echo wc_get_rating_html( $store_rating ); ?>
                                    <span class="rating-count">(<?php echo esc_html( $review_count ); ?>)</span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * WCFM add vendor dashboard link
     *
     * @param array $items Menu items.
     * @return array
     */
    public function wcfm_add_vendor_dashboard_link( $items ) {
        if ( ! function_exists( 'wcfm_is_vendor' ) ) {
            return $items;
        }
        
        if ( wcfm_is_vendor() ) {
            $items['wcfm-vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        } elseif ( function_exists( 'get_wcfm_membership_url' ) ) {
            $items['vendor-registration'] = __( 'Become a Vendor', 'aqualuxe' );
        }
        
        return $items;
    }

    /**
     * WCFM add vendor registration form
     */
    public function wcfm_add_vendor_registration_form() {
        if ( ! function_exists( 'wcfm_is_vendor' ) || ! function_exists( 'get_wcfm_membership_url' ) ) {
            return;
        }
        
        if ( is_user_logged_in() && ! wcfm_is_vendor() ) {
            echo '<div class="wcfm-vendor-registration">';
            echo '<h2>' . esc_html__( 'Become a Vendor', 'aqualuxe' ) . '</h2>';
            echo '<p>' . esc_html__( 'Want to sell your products? Register as a vendor now!', 'aqualuxe' ) . '</p>';
            echo '<a href="' . esc_url( get_wcfm_membership_url() ) . '" class="button">' . esc_html__( 'Register Now', 'aqualuxe' ) . '</a>';
            echo '</div>';
        }
    }

    /**
     * Add vendor information to product page
     */
    public function add_vendor_info() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $vendor_id = 0;
        $vendor_name = '';
        $vendor_link = '';
        
        // WC Marketplace
        if ( function_exists( 'get_wcmp_product_vendors' ) ) {
            $vendor = get_wcmp_product_vendors( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->id;
                $vendor_name = $vendor->page_title;
                $vendor_term = get_term( $vendor->term_id );
                $vendor_link = get_term_link( $vendor_term );
            }
        }
        // Dokan
        elseif ( function_exists( 'dokan_get_vendor_by_product' ) ) {
            $vendor = dokan_get_vendor_by_product( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->get_id();
                $vendor_name = $vendor->get_shop_name();
                $vendor_link = $vendor->get_shop_url();
            }
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) ) {
            $vendor_id = WCV_Vendors::get_vendor_from_product( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = WCV_Vendors::get_vendor_shop_name( $vendor_id );
                $vendor_link = WCV_Vendors::get_vendor_shop_page( $vendor_id );
            }
        }
        // WCFM
        elseif ( function_exists( 'wcfm_get_vendor_id_by_post' ) ) {
            $vendor_id = wcfm_get_vendor_id_by_post( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = get_user_meta( $vendor_id, 'wcfmmp_store_name', true );
                $vendor_link = wcfmmp_get_store_url( $vendor_id );
            }
        }
        
        if ( $vendor_id && $vendor_name && $vendor_link ) {
            ?>
            <div class="product-vendor-info">
                <span class="vendor-label"><?php esc_html_e( 'Sold by:', 'aqualuxe' ); ?></span>
                <a href="<?php echo esc_url( $vendor_link ); ?>" class="vendor-name"><?php echo esc_html( $vendor_name ); ?></a>
            </div>
            <?php
        }
    }

    /**
     * Add vendor tab to product tabs
     *
     * @param array $tabs Product tabs.
     * @return array
     */
    public function add_vendor_tab( $tabs ) {
        global $product;
        
        if ( ! $product ) {
            return $tabs;
        }
        
        $vendor_id = 0;
        $vendor_name = '';
        
        // WC Marketplace
        if ( function_exists( 'get_wcmp_product_vendors' ) ) {
            $vendor = get_wcmp_product_vendors( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->id;
                $vendor_name = $vendor->page_title;
            }
        }
        // Dokan
        elseif ( function_exists( 'dokan_get_vendor_by_product' ) ) {
            $vendor = dokan_get_vendor_by_product( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->get_id();
                $vendor_name = $vendor->get_shop_name();
            }
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) ) {
            $vendor_id = WCV_Vendors::get_vendor_from_product( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = WCV_Vendors::get_vendor_shop_name( $vendor_id );
            }
        }
        // WCFM
        elseif ( function_exists( 'wcfm_get_vendor_id_by_post' ) ) {
            $vendor_id = wcfm_get_vendor_id_by_post( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = get_user_meta( $vendor_id, 'wcfmmp_store_name', true );
            }
        }
        
        if ( $vendor_id && $vendor_name ) {
            $tabs['vendor'] = array(
                'title'    => __( 'Vendor', 'aqualuxe' ),
                'priority' => 20,
                'callback' => array( $this, 'vendor_tab_content' ),
            );
        }
        
        return $tabs;
    }

    /**
     * Vendor tab content
     */
    public function vendor_tab_content() {
        global $product;
        
        if ( ! $product ) {
            return;
        }
        
        $vendor_id = 0;
        $vendor_name = '';
        $vendor_link = '';
        $vendor_description = '';
        $vendor_logo = '';
        
        // WC Marketplace
        if ( function_exists( 'get_wcmp_product_vendors' ) ) {
            $vendor = get_wcmp_product_vendors( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->id;
                $vendor_name = $vendor->page_title;
                $vendor_term = get_term( $vendor->term_id );
                $vendor_link = get_term_link( $vendor_term );
                $vendor_description = $vendor->description;
                $vendor_logo = $vendor->get_image();
            }
        }
        // Dokan
        elseif ( function_exists( 'dokan_get_vendor_by_product' ) ) {
            $vendor = dokan_get_vendor_by_product( $product->get_id() );
            
            if ( $vendor ) {
                $vendor_id = $vendor->get_id();
                $vendor_name = $vendor->get_shop_name();
                $vendor_link = $vendor->get_shop_url();
                $vendor_description = $vendor->get_shop_info();
                $vendor_logo = $vendor->get_avatar();
            }
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) ) {
            $vendor_id = WCV_Vendors::get_vendor_from_product( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = WCV_Vendors::get_vendor_shop_name( $vendor_id );
                $vendor_link = WCV_Vendors::get_vendor_shop_page( $vendor_id );
                $vendor_description = get_user_meta( $vendor_id, 'pv_shop_description', true );
                $shop_image = get_user_meta( $vendor_id, 'pv_shop_image', true );
                $vendor_logo = $shop_image ? wp_get_attachment_url( $shop_image ) : '';
            }
        }
        // WCFM
        elseif ( function_exists( 'wcfm_get_vendor_id_by_post' ) ) {
            $vendor_id = wcfm_get_vendor_id_by_post( $product->get_id() );
            
            if ( $vendor_id ) {
                $vendor_name = get_user_meta( $vendor_id, 'wcfmmp_store_name', true );
                $vendor_link = wcfmmp_get_store_url( $vendor_id );
                $vendor_description = get_user_meta( $vendor_id, 'wcfmmp_store_description', true );
                $vendor_logo = get_user_meta( $vendor_id, 'wcfmmp_store_logo', true );
            }
        }
        
        if ( $vendor_id && $vendor_name && $vendor_link ) {
            ?>
            <div class="vendor-tab-content">
                <div class="vendor-header">
                    <?php if ( $vendor_logo ) : ?>
                        <div class="vendor-logo">
                            <img src="<?php echo esc_url( $vendor_logo ); ?>" alt="<?php echo esc_attr( $vendor_name ); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <div class="vendor-info">
                        <h3 class="vendor-name">
                            <a href="<?php echo esc_url( $vendor_link ); ?>"><?php echo esc_html( $vendor_name ); ?></a>
                        </h3>
                    </div>
                </div>
                
                <?php if ( $vendor_description ) : ?>
                    <div class="vendor-description">
                        <?php echo wp_kses_post( $vendor_description ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="vendor-store-link">
                    <a href="<?php echo esc_url( $vendor_link ); ?>" class="button"><?php esc_html_e( 'Visit Store', 'aqualuxe' ); ?></a>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Add vendor filter to shop page
     */
    public function add_vendor_filter() {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $vendors = array();
        
        // WC Marketplace
        if ( function_exists( 'get_wcmp_vendors' ) ) {
            $vendor_args = array(
                'number' => -1,
                'orderby' => 'name',
                'order' => 'ASC',
            );
            
            $wcmp_vendors = get_wcmp_vendors( $vendor_args );
            
            if ( $wcmp_vendors ) {
                foreach ( $wcmp_vendors as $vendor ) {
                    $vendors[] = array(
                        'id'   => $vendor->term_id,
                        'name' => $vendor->page_title,
                        'url'  => get_term_link( get_term( $vendor->term_id ) ),
                    );
                }
            }
        }
        // Dokan
        elseif ( function_exists( 'dokan_get_sellers' ) ) {
            $seller_args = array(
                'number' => -1,
                'status' => 'approved',
            );
            
            $sellers = dokan_get_sellers( $seller_args );
            
            if ( $sellers['users'] ) {
                foreach ( $sellers['users'] as $seller ) {
                    $vendor = dokan()->vendor->get( $seller->ID );
                    
                    $vendors[] = array(
                        'id'   => $vendor->get_id(),
                        'name' => $vendor->get_shop_name(),
                        'url'  => $vendor->get_shop_url(),
                    );
                }
            }
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) && function_exists( 'get_users' ) ) {
            $vendor_args = array(
                'role'   => 'vendor',
                'number' => -1,
            );
            
            $vendor_users = get_users( $vendor_args );
            
            if ( $vendor_users ) {
                foreach ( $vendor_users as $vendor_user ) {
                    $vendors[] = array(
                        'id'   => $vendor_user->ID,
                        'name' => WCV_Vendors::get_vendor_shop_name( $vendor_user->ID ),
                        'url'  => WCV_Vendors::get_vendor_shop_page( $vendor_user->ID ),
                    );
                }
            }
        }
        // WCFM
        elseif ( function_exists( 'wcfm_get_vendor_list' ) ) {
            $vendor_args = array(
                'status' => 'approved',
            );
            
            $wcfm_vendors = wcfm_get_vendor_list( $vendor_args );
            
            if ( $wcfm_vendors ) {
                foreach ( $wcfm_vendors as $vendor_id => $vendor_name ) {
                    $vendors[] = array(
                        'id'   => $vendor_id,
                        'name' => $vendor_name,
                        'url'  => wcfmmp_get_store_url( $vendor_id ),
                    );
                }
            }
        }
        
        if ( ! empty( $vendors ) ) {
            ?>
            <div class="vendor-filter">
                <select name="vendor_filter" id="vendor-filter" class="vendor-filter-select">
                    <option value=""><?php esc_html_e( 'Filter by Vendor', 'aqualuxe' ); ?></option>
                    <?php foreach ( $vendors as $vendor ) : ?>
                        <option value="<?php echo esc_attr( $vendor['url'] ); ?>"><?php echo esc_html( $vendor['name'] ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const vendorFilter = document.getElementById('vendor-filter');
                    
                    if (vendorFilter) {
                        vendorFilter.addEventListener('change', function() {
                            const url = this.value;
                            
                            if (url) {
                                window.location.href = url;
                            }
                        });
                    }
                });
            </script>
            <?php
        }
    }

    /**
     * Register vendor dashboard endpoint
     */
    public function register_vendor_dashboard_endpoint() {
        add_rewrite_endpoint( 'vendor-dashboard', EP_ROOT | EP_PAGES );
    }

    /**
     * Add vendor dashboard link to my account menu
     *
     * @param array $items Menu items.
     * @return array
     */
    public function add_vendor_dashboard_link( $items ) {
        // WC Marketplace
        if ( function_exists( 'is_user_wcmp_vendor' ) && is_user_wcmp_vendor( get_current_user_id() ) ) {
            $items['vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        }
        // Dokan
        elseif ( function_exists( 'dokan_is_user_seller' ) && dokan_is_user_seller( get_current_user_id() ) ) {
            $items['vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) && WCV_Vendors::is_vendor( get_current_user_id() ) ) {
            $items['vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        }
        // WCFM
        elseif ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) {
            $items['vendor-dashboard'] = __( 'Vendor Dashboard', 'aqualuxe' );
        }
        
        return $items;
    }

    /**
     * Vendor dashboard content
     */
    public function vendor_dashboard_content() {
        $dashboard_url = '';
        
        // WC Marketplace
        if ( function_exists( 'is_user_wcmp_vendor' ) && is_user_wcmp_vendor( get_current_user_id() ) ) {
            $dashboard_url = wcmp_get_vendor_dashboard_endpoint_url();
        }
        // Dokan
        elseif ( function_exists( 'dokan_is_user_seller' ) && dokan_is_user_seller( get_current_user_id() ) ) {
            $dashboard_url = dokan_get_navigation_url();
        }
        // WC Vendors
        elseif ( function_exists( 'WCV_Vendors' ) && WCV_Vendors::is_vendor( get_current_user_id() ) ) {
            $dashboard_url = get_permalink( get_option( 'wcvendors_vendor_dashboard_page_id' ) );
        }
        // WCFM
        elseif ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) {
            $dashboard_url = get_wcfm_url();
        }
        
        if ( $dashboard_url ) {
            echo '<p>' . esc_html__( 'Redirecting to vendor dashboard...', 'aqualuxe' ) . '</p>';
            echo '<script>window.location.href = "' . esc_url( $dashboard_url ) . '";</script>';
        } else {
            echo '<p>' . esc_html__( 'You are not registered as a vendor.', 'aqualuxe' ) . '</p>';
        }
    }

    /**
     * Add vendor registration form to my account page
     */
    public function add_vendor_registration_form() {
        if ( is_user_logged_in() ) {
            // WC Marketplace
            if ( function_exists( 'get_wcmp_vendor_settings' ) && get_wcmp_vendor_settings( 'vendor_registration', 'general' ) && ! is_user_wcmp_vendor( get_current_user_id() ) && ! is_user_wcmp_pending_vendor( get_current_user_id() ) && ! is_user_wcmp_rejected_vendor( get_current_user_id() ) ) {
                echo do_shortcode( '[wcmp_vendor_registration]' );
            }
            // Dokan
            elseif ( function_exists( 'dokan_is_seller_enabled' ) && dokan_is_seller_enabled( get_current_user_id() ) && ! dokan_is_user_seller( get_current_user_id() ) ) {
                echo do_shortcode( '[dokan-vendor-registration]' );
            }
            // WC Vendors
            elseif ( function_exists( 'WCV_Vendors' ) && ! WCV_Vendors::is_vendor( get_current_user_id() ) && get_option( 'wcvendors_vendor_allow_registration' ) ) {
                echo do_shortcode( '[wcv_vendor_registration]' );
            }
            // WCFM
            elseif ( function_exists( 'wcfm_is_vendor' ) && ! wcfm_is_vendor() && function_exists( 'get_wcfm_membership_url' ) ) {
                echo '<div class="wcfm-vendor-registration">';
                echo '<h2>' . esc_html__( 'Become a Vendor', 'aqualuxe' ) . '</h2>';
                echo '<p>' . esc_html__( 'Want to sell your products? Register as a vendor now!', 'aqualuxe' ) . '</p>';
                echo '<a href="' . esc_url( get_wcfm_membership_url() ) . '" class="button">' . esc_html__( 'Register Now', 'aqualuxe' ) . '</a>';
                echo '</div>';
            }
            // Custom vendor registration
            elseif ( ! $this->is_user_vendor( get_current_user_id() ) && get_theme_mod( 'aqualuxe_enable_vendor_registration', true ) ) {
                $this->custom_vendor_registration_form();
            }
        }
    }

    /**
     * Custom vendor registration form
     */
    private function custom_vendor_registration_form() {
        ?>
        <div class="aqualuxe-vendor-registration">
            <h2><?php esc_html_e( 'Become a Vendor', 'aqualuxe' ); ?></h2>
            
            <form id="aqualuxe-vendor-registration-form" class="aqualuxe-vendor-form">
                <div class="form-row">
                    <label for="vendor_shop_name"><?php esc_html_e( 'Shop Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="text" id="vendor_shop_name" name="vendor_shop_name" required>
                </div>
                
                <div class="form-row">
                    <label for="vendor_shop_description"><?php esc_html_e( 'Shop Description', 'aqualuxe' ); ?></label>
                    <textarea id="vendor_shop_description" name="vendor_shop_description" rows="5"></textarea>
                </div>
                
                <div class="form-row">
                    <label for="vendor_phone"><?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="tel" id="vendor_phone" name="vendor_phone" required>
                </div>
                
                <div class="form-row">
                    <label for="vendor_address"><?php esc_html_e( 'Address', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="text" id="vendor_address" name="vendor_address" required>
                </div>
                
                <div class="form-row">
                    <input type="checkbox" id="vendor_terms" name="vendor_terms" required>
                    <label for="vendor_terms"><?php esc_html_e( 'I agree to the vendor terms and conditions', 'aqualuxe' ); ?> <span class="required">*</span></label>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="button"><?php esc_html_e( 'Register as Vendor', 'aqualuxe' ); ?></button>
                </div>
                
                <div class="vendor-registration-message"></div>
                
                <?php wp_nonce_field( 'aqualuxe_vendor_registration', 'vendor_registration_nonce' ); ?>
            </form>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('aqualuxe-vendor-registration-form');
                    const message = document.querySelector('.vendor-registration-message');
                    
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            
                            const formData = new FormData(form);
                            formData.append('action', 'aqualuxe_vendor_registration');
                            
                            // Show loading message
                            message.innerHTML = '<?php esc_html_e( 'Processing your registration...', 'aqualuxe' ); ?>';
                            message.className = 'vendor-registration-message info';
                            
                            fetch(aqualuxeVars.ajaxurl, {
                                method: 'POST',
                                credentials: 'same-origin',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(response => {
                                if (response.success) {
                                    message.innerHTML = response.data.message;
                                    message.className = 'vendor-registration-message success';
                                    form.reset();
                                    
                                    // Redirect if needed
                                    if (response.data.redirect) {
                                        setTimeout(function() {
                                            window.location.href = response.data.redirect;
                                        }, 2000);
                                    }
                                } else {
                                    message.innerHTML = response.data.message;
                                    message.className = 'vendor-registration-message error';
                                }
                            })
                            .catch(error => {
                                message.innerHTML = '<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>';
                                message.className = 'vendor-registration-message error';
                            });
                        });
                    }
                });
            </script>
        </div>
        <?php
    }

    /**
     * Process vendor registration
     */
    public function process_vendor_registration() {
        if ( ! isset( $_POST['vendor_registration_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vendor_registration_nonce'] ) ), 'aqualuxe_vendor_registration' ) ) {
            wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
        }
        
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'You must be logged in to register as a vendor', 'aqualuxe' ) ) );
        }
        
        $user_id = get_current_user_id();
        
        if ( $this->is_user_vendor( $user_id ) ) {
            wp_send_json_error( array( 'message' => __( 'You are already registered as a vendor', 'aqualuxe' ) ) );
        }
        
        $shop_name = isset( $_POST['vendor_shop_name'] ) ? sanitize_text_field( wp_unslash( $_POST['vendor_shop_name'] ) ) : '';
        $shop_description = isset( $_POST['vendor_shop_description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['vendor_shop_description'] ) ) : '';
        $phone = isset( $_POST['vendor_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['vendor_phone'] ) ) : '';
        $address = isset( $_POST['vendor_address'] ) ? sanitize_text_field( wp_unslash( $_POST['vendor_address'] ) ) : '';
        
        if ( empty( $shop_name ) ) {
            wp_send_json_error( array( 'message' => __( 'Shop name is required', 'aqualuxe' ) ) );
        }
        
        if ( empty( $phone ) ) {
            wp_send_json_error( array( 'message' => __( 'Phone number is required', 'aqualuxe' ) ) );
        }
        
        if ( empty( $address ) ) {
            wp_send_json_error( array( 'message' => __( 'Address is required', 'aqualuxe' ) ) );
        }
        
        // Save vendor data
        update_user_meta( $user_id, 'aqualuxe_vendor_shop_name', $shop_name );
        update_user_meta( $user_id, 'aqualuxe_vendor_shop_description', $shop_description );
        update_user_meta( $user_id, 'aqualuxe_vendor_phone', $phone );
        update_user_meta( $user_id, 'aqualuxe_vendor_address', $address );
        update_user_meta( $user_id, 'aqualuxe_vendor_status', 'pending' );
        
        // Add vendor role
        $user = new WP_User( $user_id );
        $user->add_role( 'aqualuxe_vendor' );
        
        // Send notification to admin
        $admin_email = get_option( 'admin_email' );
        $subject = sprintf( __( 'New Vendor Registration: %s', 'aqualuxe' ), $shop_name );
        $message = sprintf( __( 'A new vendor has registered on your site.\n\nVendor: %1$s\nShop Name: %2$s\nPhone: %3$s\n\nPlease review this application in the admin dashboard.', 'aqualuxe' ), wp_get_current_user()->display_name, $shop_name, $phone );
        
        wp_mail( $admin_email, $subject, $message );
        
        // Send notification to vendor
        $vendor_email = wp_get_current_user()->user_email;
        $vendor_subject = __( 'Your Vendor Application', 'aqualuxe' );
        $vendor_message = sprintf( __( 'Thank you for registering as a vendor on our site.\n\nYour application is currently under review. We will notify you once your application is approved.\n\nShop Name: %s', 'aqualuxe' ), $shop_name );
        
        wp_mail( $vendor_email, $vendor_subject, $vendor_message );
        
        wp_send_json_success( array(
            'message'  => __( 'Your vendor application has been submitted successfully. We will review your application and notify you once it is approved.', 'aqualuxe' ),
            'redirect' => null,
        ) );
    }

    /**
     * Check if user is a vendor
     *
     * @param int $user_id User ID.
     * @return bool
     */
    private function is_user_vendor( $user_id ) {
        // WC Marketplace
        if ( function_exists( 'is_user_wcmp_vendor' ) && is_user_wcmp_vendor( $user_id ) ) {
            return true;
        }
        
        // Dokan
        if ( function_exists( 'dokan_is_user_seller' ) && dokan_is_user_seller( $user_id ) ) {
            return true;
        }
        
        // WC Vendors
        if ( function_exists( 'WCV_Vendors' ) && WCV_Vendors::is_vendor( $user_id ) ) {
            return true;
        }
        
        // WCFM
        if ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor( $user_id ) ) {
            return true;
        }
        
        // Custom vendor
        $vendor_status = get_user_meta( $user_id, 'aqualuxe_vendor_status', true );
        if ( $vendor_status && ( 'approved' === $vendor_status || 'pending' === $vendor_status ) ) {
            return true;
        }
        
        return false;
    }

    /**
     * Add vendor store header
     */
    public function add_vendor_store_header() {
        // Custom implementation for vendor store header
        // This is a placeholder for custom vendor store header implementation
    }

    /**
     * Add vendor store sidebar
     */
    public function add_vendor_store_sidebar() {
        // Custom implementation for vendor store sidebar
        // This is a placeholder for custom vendor store sidebar implementation
    }

    /**
     * Add vendor store footer
     */
    public function add_vendor_store_footer() {
        // Custom implementation for vendor store footer
        // This is a placeholder for custom vendor store footer implementation
    }
}

// Initialize multivendor compatibility
AquaLuxe_Multivendor::get_instance();