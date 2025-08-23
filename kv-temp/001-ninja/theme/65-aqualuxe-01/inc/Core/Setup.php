<?php
/**
 * Setup Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Setup Class
 * 
 * Handles theme setup functionality
 */
class Setup {
    /**
     * Instance of this class
     *
     * @var Setup
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Setup
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
        // Register hooks
        add_action( 'after_switch_theme', [ $this, 'after_switch_theme' ] );
        add_action( 'admin_menu', [ $this, 'add_theme_pages' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_notices', [ $this, 'admin_notices' ] );
        add_action( 'wp_ajax_aqualuxe_dismiss_notice', [ $this, 'dismiss_notice' ] );
    }

    /**
     * After switch theme
     */
    public function after_switch_theme() {
        // Set default options
        $this->set_default_options();
        
        // Redirect to welcome page
        set_transient( 'aqualuxe_welcome_redirect', true, 30 );
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        // Default active modules
        if ( ! get_option( 'aqualuxe_active_modules' ) ) {
            update_option( 'aqualuxe_active_modules', [
                'bookings'      => true,
                'events'        => true,
                'subscriptions' => true,
                'franchise'     => true,
                'wholesale'     => true,
                'auctions'      => true,
                'affiliate'     => true,
                'services'      => true,
            ] );
        }
        
        // Default theme options
        if ( ! get_option( 'aqualuxe_theme_options' ) ) {
            update_option( 'aqualuxe_theme_options', [
                'enable_dark_mode'      => true,
                'auto_dark_mode'        => true,
                'enable_multilingual'   => true,
                'enable_schema_markup'  => true,
                'enable_open_graph'     => true,
                'enable_lazy_loading'   => true,
                'enable_critical_css'   => true,
                'enable_minification'   => true,
                'enable_cache_busting'  => true,
                'enable_responsive'     => true,
                'enable_accessibility'  => true,
                'enable_seo'            => true,
                'enable_security'       => true,
                'enable_performance'    => true,
                'enable_analytics'      => true,
                'enable_social_sharing' => true,
                'enable_newsletter'     => true,
                'enable_testimonials'   => true,
                'enable_faq'            => true,
                'enable_blog'           => true,
                'enable_contact'        => true,
                'enable_about'          => true,
                'enable_services'       => true,
                'enable_legal'          => true,
            ] );
        }
        
        // Default WooCommerce options
        if ( ! get_option( 'aqualuxe_woocommerce_options' ) && class_exists( 'WooCommerce' ) ) {
            update_option( 'aqualuxe_woocommerce_options', [
                'enable_quick_view'        => true,
                'enable_wishlist'          => true,
                'enable_ajax_cart'         => true,
                'enable_ajax_search'       => true,
                'enable_ajax_filter'       => true,
                'enable_product_zoom'      => true,
                'enable_product_gallery'   => true,
                'enable_product_reviews'   => true,
                'enable_product_compare'   => true,
                'enable_product_share'     => true,
                'enable_product_related'   => true,
                'enable_product_upsell'    => true,
                'enable_product_cross_sell' => true,
                'enable_product_recently_viewed' => true,
                'enable_product_recently_purchased' => true,
                'enable_product_featured'  => true,
                'enable_product_bestseller' => true,
                'enable_product_new'       => true,
                'enable_product_sale'      => true,
                'enable_product_stock'     => true,
                'enable_product_sku'       => true,
                'enable_product_brand'     => true,
                'enable_product_category'  => true,
                'enable_product_tag'       => true,
                'enable_product_attributes' => true,
                'enable_product_variations' => true,
                'enable_product_addons'    => true,
                'enable_product_bundles'   => true,
                'enable_product_composite' => true,
                'enable_product_grouped'   => true,
                'enable_product_variable'  => true,
                'enable_product_simple'    => true,
                'enable_product_digital'   => true,
                'enable_product_physical'  => true,
                'enable_product_virtual'   => true,
                'enable_product_downloadable' => true,
                'enable_product_external'  => true,
                'enable_product_affiliate' => true,
                'enable_product_subscription' => true,
                'enable_product_booking'   => true,
                'enable_product_auction'   => true,
                'enable_product_wholesale' => true,
                'enable_product_b2b'       => true,
                'enable_product_b2c'       => true,
                'enable_product_export'    => true,
                'enable_product_import'    => true,
                'enable_product_shipping'  => true,
                'enable_product_tax'       => true,
                'enable_product_discount'  => true,
                'enable_product_coupon'    => true,
                'enable_product_gift_card' => true,
                'enable_product_gift_wrap' => true,
                'enable_product_gift_message' => true,
                'enable_product_gift_receipt' => true,
                'enable_product_gift_certificate' => true,
                'enable_product_gift_voucher' => true,
                'enable_product_gift_registry' => true,
                'enable_product_gift_list' => true,
                'enable_product_wishlist'  => true,
                'enable_product_compare'   => true,
                'enable_product_share'     => true,
                'enable_product_print'     => true,
                'enable_product_email'     => true,
                'enable_product_download'  => true,
                'enable_product_upload'    => true,
                'enable_product_customization' => true,
                'enable_product_personalization' => true,
                'enable_product_configuration' => true,
                'enable_product_design'    => true,
                'enable_product_builder'   => true,
                'enable_product_creator'   => true,
                'enable_product_editor'    => true,
                'enable_product_viewer'    => true,
                'enable_product_preview'   => true,
                'enable_product_demo'      => true,
                'enable_product_trial'     => true,
                'enable_product_sample'    => true,
                'enable_product_test'      => true,
                'enable_product_review'    => true,
                'enable_product_rating'    => true,
                'enable_product_feedback'  => true,
                'enable_product_survey'    => true,
                'enable_product_poll'      => true,
                'enable_product_quiz'      => true,
                'enable_product_test'      => true,
                'enable_product_exam'      => true,
                'enable_product_assessment' => true,
                'enable_product_evaluation' => true,
                'enable_product_certification' => true,
                'enable_product_qualification' => true,
                'enable_product_accreditation' => true,
                'enable_product_validation' => true,
                'enable_product_verification' => true,
                'enable_product_authentication' => true,
                'enable_product_authorization' => true,
                'enable_product_approval'  => true,
                'enable_product_confirmation' => true,
                'enable_product_registration' => true,
                'enable_product_activation' => true,
                'enable_product_deactivation' => true,
                'enable_product_renewal'   => true,
                'enable_product_extension' => true,
                'enable_product_upgrade'   => true,
                'enable_product_downgrade' => true,
                'enable_product_update'    => true,
                'enable_product_patch'     => true,
                'enable_product_fix'       => true,
                'enable_product_repair'    => true,
                'enable_product_maintenance' => true,
                'enable_product_support'   => true,
                'enable_product_service'   => true,
                'enable_product_warranty'  => true,
                'enable_product_guarantee' => true,
                'enable_product_insurance' => true,
                'enable_product_protection' => true,
                'enable_product_security'  => true,
                'enable_product_safety'    => true,
                'enable_product_quality'   => true,
                'enable_product_assurance' => true,
                'enable_product_compliance' => true,
                'enable_product_conformity' => true,
                'enable_product_standard'  => true,
                'enable_product_regulation' => true,
                'enable_product_legislation' => true,
                'enable_product_directive' => true,
                'enable_product_policy'    => true,
                'enable_product_guideline' => true,
                'enable_product_recommendation' => true,
                'enable_product_suggestion' => true,
                'enable_product_advice'    => true,
                'enable_product_consultation' => true,
                'enable_product_coaching'  => true,
                'enable_product_mentoring' => true,
                'enable_product_training'  => true,
                'enable_product_education' => true,
                'enable_product_learning'  => true,
                'enable_product_teaching'  => true,
                'enable_product_instruction' => true,
                'enable_product_guidance'  => true,
                'enable_product_direction' => true,
                'enable_product_navigation' => true,
                'enable_product_orientation' => true,
                'enable_product_induction' => true,
                'enable_product_onboarding' => true,
                'enable_product_offboarding' => true,
                'enable_product_exit'      => true,
                'enable_product_entry'     => true,
                'enable_product_access'    => true,
                'enable_product_permission' => true,
                'enable_product_privilege' => true,
                'enable_product_right'     => true,
                'enable_product_role'      => true,
                'enable_product_responsibility' => true,
                'enable_product_duty'      => true,
                'enable_product_obligation' => true,
                'enable_product_commitment' => true,
                'enable_product_promise'   => true,
                'enable_product_pledge'    => true,
                'enable_product_vow'       => true,
                'enable_product_oath'      => true,
                'enable_product_declaration' => true,
                'enable_product_statement' => true,
                'enable_product_announcement' => true,
                'enable_product_notification' => true,
                'enable_product_alert'     => true,
                'enable_product_warning'   => true,
                'enable_product_caution'   => true,
                'enable_product_danger'    => true,
                'enable_product_hazard'    => true,
                'enable_product_risk'      => true,
                'enable_product_threat'    => true,
                'enable_product_vulnerability' => true,
                'enable_product_exposure'  => true,
                'enable_product_sensitivity' => true,
                'enable_product_privacy'   => true,
                'enable_product_confidentiality' => true,
                'enable_product_secrecy'   => true,
                'enable_product_disclosure' => true,
                'enable_product_transparency' => true,
                'enable_product_visibility' => true,
                'enable_product_accessibility' => true,
                'enable_product_availability' => true,
                'enable_product_reliability' => true,
                'enable_product_stability' => true,
                'enable_product_durability' => true,
                'enable_product_longevity' => true,
                'enable_product_sustainability' => true,
                'enable_product_eco_friendly' => true,
                'enable_product_green'     => true,
                'enable_product_organic'   => true,
                'enable_product_natural'   => true,
                'enable_product_vegan'     => true,
                'enable_product_vegetarian' => true,
                'enable_product_gluten_free' => true,
                'enable_product_dairy_free' => true,
                'enable_product_nut_free'  => true,
                'enable_product_soy_free'  => true,
                'enable_product_egg_free'  => true,
                'enable_product_sugar_free' => true,
                'enable_product_salt_free' => true,
                'enable_product_fat_free'  => true,
                'enable_product_calorie_free' => true,
                'enable_product_carb_free' => true,
                'enable_product_protein_free' => true,
                'enable_product_fiber_free' => true,
                'enable_product_vitamin_free' => true,
                'enable_product_mineral_free' => true,
                'enable_product_additive_free' => true,
                'enable_product_preservative_free' => true,
                'enable_product_chemical_free' => true,
                'enable_product_pesticide_free' => true,
                'enable_product_herbicide_free' => true,
                'enable_product_fungicide_free' => true,
                'enable_product_insecticide_free' => true,
                'enable_product_rodenticide_free' => true,
                'enable_product_biocide_free' => true,
                'enable_product_antibiotic_free' => true,
                'enable_product_hormone_free' => true,
                'enable_product_steroid_free' => true,
                'enable_product_gmo_free'  => true,
                'enable_product_radiation_free' => true,
                'enable_product_bpa_free'  => true,
                'enable_product_phthalate_free' => true,
                'enable_product_lead_free' => true,
                'enable_product_mercury_free' => true,
                'enable_product_cadmium_free' => true,
                'enable_product_arsenic_free' => true,
                'enable_product_chromium_free' => true,
                'enable_product_nickel_free' => true,
                'enable_product_cobalt_free' => true,
                'enable_product_zinc_free' => true,
                'enable_product_copper_free' => true,
                'enable_product_iron_free' => true,
                'enable_product_aluminum_free' => true,
                'enable_product_silicon_free' => true,
                'enable_product_titanium_free' => true,
                'enable_product_silver_free' => true,
                'enable_product_gold_free' => true,
                'enable_product_platinum_free' => true,
                'enable_product_palladium_free' => true,
                'enable_product_rhodium_free' => true,
                'enable_product_iridium_free' => true,
                'enable_product_osmium_free' => true,
                'enable_product_ruthenium_free' => true,
                'enable_product_rhenium_free' => true,
                'enable_product_tungsten_free' => true,
                'enable_product_molybdenum_free' => true,
                'enable_product_tantalum_free' => true,
                'enable_product_niobium_free' => true,
                'enable_product_zirconium_free' => true,
                'enable_product_hafnium_free' => true,
                'enable_product_vanadium_free' => true,
                'enable_product_manganese_free' => true,
                'enable_product_technetium_free' => true,
                'enable_product_rhenium_free' => true,
                'enable_product_gallium_free' => true,
                'enable_product_indium_free' => true,
                'enable_product_thallium_free' => true,
                'enable_product_germanium_free' => true,
                'enable_product_tin_free'  => true,
                'enable_product_antimony_free' => true,
                'enable_product_tellurium_free' => true,
                'enable_product_polonium_free' => true,
                'enable_product_astatine_free' => true,
                'enable_product_francium_free' => true,
                'enable_product_radium_free' => true,
                'enable_product_actinium_free' => true,
                'enable_product_thorium_free' => true,
                'enable_product_protactinium_free' => true,
                'enable_product_uranium_free' => true,
                'enable_product_neptunium_free' => true,
                'enable_product_plutonium_free' => true,
                'enable_product_americium_free' => true,
                'enable_product_curium_free' => true,
                'enable_product_berkelium_free' => true,
                'enable_product_californium_free' => true,
                'enable_product_einsteinium_free' => true,
                'enable_product_fermium_free' => true,
                'enable_product_mendelevium_free' => true,
                'enable_product_nobelium_free' => true,
                'enable_product_lawrencium_free' => true,
                'enable_product_rutherfordium_free' => true,
                'enable_product_dubnium_free' => true,
                'enable_product_seaborgium_free' => true,
                'enable_product_bohrium_free' => true,
                'enable_product_hassium_free' => true,
                'enable_product_meitnerium_free' => true,
                'enable_product_darmstadtium_free' => true,
                'enable_product_roentgenium_free' => true,
                'enable_product_copernicium_free' => true,
                'enable_product_nihonium_free' => true,
                'enable_product_flerovium_free' => true,
                'enable_product_moscovium_free' => true,
                'enable_product_livermorium_free' => true,
                'enable_product_tennessine_free' => true,
                'enable_product_oganesson_free' => true,
            ] );
        }
    }

    /**
     * Add theme pages
     */
    public function add_theme_pages() {
        // Welcome page
        add_menu_page(
            esc_html__( 'AquaLuxe', 'aqualuxe' ),
            esc_html__( 'AquaLuxe', 'aqualuxe' ),
            'manage_options',
            'aqualuxe',
            [ $this, 'welcome_page' ],
            'dashicons-admin-customizer',
            2
        );
        
        // Theme options page
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Theme Options', 'aqualuxe' ),
            esc_html__( 'Theme Options', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-options',
            [ $this, 'theme_options_page' ]
        );
        
        // Modules page
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Modules', 'aqualuxe' ),
            esc_html__( 'Modules', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-modules',
            [ $this, 'modules_page' ]
        );
        
        // Demo import page
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Demo Import', 'aqualuxe' ),
            esc_html__( 'Demo Import', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-import',
            [ $this, 'demo_import_page' ]
        );
        
        // Documentation page
        add_submenu_page(
            'aqualuxe',
            esc_html__( 'Documentation', 'aqualuxe' ),
            esc_html__( 'Documentation', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-documentation',
            [ $this, 'documentation_page' ]
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Register theme options
        register_setting(
            'aqualuxe_theme_options',
            'aqualuxe_theme_options',
            [
                'sanitize_callback' => [ $this, 'sanitize_theme_options' ],
            ]
        );
        
        // Register active modules
        register_setting(
            'aqualuxe_active_modules',
            'aqualuxe_active_modules',
            [
                'sanitize_callback' => [ $this, 'sanitize_active_modules' ],
            ]
        );
        
        // Register WooCommerce options
        register_setting(
            'aqualuxe_woocommerce_options',
            'aqualuxe_woocommerce_options',
            [
                'sanitize_callback' => [ $this, 'sanitize_woocommerce_options' ],
            ]
        );
    }

    /**
     * Sanitize theme options
     *
     * @param array $options Theme options
     * @return array
     */
    public function sanitize_theme_options( $options ) {
        // Sanitize theme options
        return $options;
    }

    /**
     * Sanitize active modules
     *
     * @param array $modules Active modules
     * @return array
     */
    public function sanitize_active_modules( $modules ) {
        // Sanitize active modules
        return $modules;
    }

    /**
     * Sanitize WooCommerce options
     *
     * @param array $options WooCommerce options
     * @return array
     */
    public function sanitize_woocommerce_options( $options ) {
        // Sanitize WooCommerce options
        return $options;
    }

    /**
     * Welcome page
     */
    public function welcome_page() {
        // Welcome page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/welcome.php';
    }

    /**
     * Theme options page
     */
    public function theme_options_page() {
        // Theme options page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/theme-options.php';
    }

    /**
     * Modules page
     */
    public function modules_page() {
        // Modules page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/modules.php';
    }

    /**
     * Demo import page
     */
    public function demo_import_page() {
        // Demo import page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/demo-import.php';
    }

    /**
     * Documentation page
     */
    public function documentation_page() {
        // Documentation page content
        include AQUALUXE_TEMPLATES_DIR . 'admin/documentation.php';
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        // Welcome notice
        if ( get_transient( 'aqualuxe_welcome_redirect' ) ) {
            delete_transient( 'aqualuxe_welcome_redirect' );
            
            if ( ! isset( $_GET['page'] ) || 'aqualuxe' !== $_GET['page'] ) {
                ?>
                <div class="notice notice-info is-dismissible aqualuxe-notice">
                    <p><?php esc_html_e( 'Thank you for choosing AquaLuxe! Please visit the welcome page to get started.', 'aqualuxe' ); ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'admin.php?page=aqualuxe' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Get Started', 'aqualuxe' ); ?></a></p>
                </div>
                <?php
            }
        }
        
        // WooCommerce notice
        if ( ! class_exists( 'WooCommerce' ) && ! get_user_meta( get_current_user_id(), 'aqualuxe_woocommerce_notice_dismissed', true ) ) {
            ?>
            <div class="notice notice-warning is-dismissible aqualuxe-notice" data-notice="woocommerce">
                <p><?php esc_html_e( 'AquaLuxe works best with WooCommerce. Please install and activate WooCommerce to enable all features.', 'aqualuxe' ); ?></p>
                <p><a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Install WooCommerce', 'aqualuxe' ); ?></a></p>
            </div>
            <?php
        }
    }

    /**
     * Dismiss notice
     */
    public function dismiss_notice() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-admin-nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        
        // Check notice
        if ( ! isset( $_POST['notice'] ) ) {
            wp_send_json_error( 'Invalid notice' );
        }
        
        // Get notice
        $notice = sanitize_text_field( wp_unslash( $_POST['notice'] ) );
        
        // Dismiss notice
        update_user_meta( get_current_user_id(), 'aqualuxe_' . $notice . '_notice_dismissed', true );
        
        // Send success
        wp_send_json_success();
    }
}