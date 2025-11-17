<?php
/**
 * AquaLuxe SEO Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe SEO Module Class
 */
class AquaLuxe_SEO_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'seo';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'SEO';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Enhances the SEO capabilities of the AquaLuxe theme with meta tags, schema markup, and social media integration.';

    /**
     * Module version
     *
     * @var string
     */
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Initialize module
     *
     * @return void
     */
    public function init() {
        // Include required files
        $this->includes();
        
        // Register hooks
        $this->register_hooks();
        
        // Add meta tags to head
        add_action( 'wp_head', array( $this, 'add_meta_tags' ), 1 );
        
        // Add schema markup
        add_action( 'wp_footer', array( $this, 'add_schema_markup' ) );
        
        // Add social media meta tags
        add_action( 'wp_head', array( $this, 'add_social_meta_tags' ), 2 );
        
        // Add customizer settings
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
        
        // Add meta boxes
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        
        // Save meta box data
        add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
        
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        
        // Register admin settings
        add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
        
        // Filter document title
        add_filter( 'document_title_parts', array( $this, 'filter_document_title' ) );
        
        // Filter document title separator
        add_filter( 'document_title_separator', array( $this, 'filter_document_title_separator' ) );
        
        // Add robots meta tag
        add_action( 'wp_head', array( $this, 'add_robots_meta' ), 3 );
        
        // Add canonical URL
        add_action( 'wp_head', array( $this, 'add_canonical_url' ), 4 );
        
        // Add breadcrumbs
        add_action( 'aqualuxe_before_main_content', array( $this, 'add_breadcrumbs' ) );
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include helper functions
        require_once $this->get_module_dir() . 'inc/helpers.php';
        
        // Include schema functions
        require_once $this->get_module_dir() . 'inc/schema.php';
        
        // Include social media functions
        require_once $this->get_module_dir() . 'inc/social.php';
        
        // Include breadcrumbs functions
        require_once $this->get_module_dir() . 'inc/breadcrumbs.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-seo-admin',
            $this->get_module_uri() . 'assets/css/seo-admin.css',
            array(),
            $this->get_module_version()
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-seo-admin',
            $this->get_module_uri() . 'assets/js/seo-admin.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        // Only enqueue on post edit screen and SEO settings page
        if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'settings_page_aqualuxe-seo' ), true ) ) {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-seo-admin' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-seo-admin' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-seo-admin',
            'aqualuxeSEO',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe_seo' ),
                'i18n' => array(
                    'good' => __( 'Good', 'aqualuxe' ),
                    'ok' => __( 'OK', 'aqualuxe' ),
                    'bad' => __( 'Needs Improvement', 'aqualuxe' ),
                    'titleTooShort' => __( 'Title is too short', 'aqualuxe' ),
                    'titleTooLong' => __( 'Title is too long', 'aqualuxe' ),
                    'descTooShort' => __( 'Description is too short', 'aqualuxe' ),
                    'descTooLong' => __( 'Description is too long', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Add meta tags
     *
     * @return void
     */
    public function add_meta_tags() {
        // Get meta description
        $description = $this->get_meta_description();
        
        // Get meta keywords
        $keywords = $this->get_meta_keywords();
        
        // Output meta description
        if ( ! empty( $description ) ) {
            echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        }
        
        // Output meta keywords
        if ( ! empty( $keywords ) && $this->get_setting( 'use_keywords', false ) ) {
            echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '" />' . "\n";
        }
        
        // Output viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
    }

    /**
     * Add schema markup
     *
     * @return void
     */
    public function add_schema_markup() {
        // Check if schema markup is enabled
        if ( ! $this->get_setting( 'enable_schema', true ) ) {
            return;
        }
        
        // Get schema markup
        $schema = aqualuxe_seo_get_schema_markup();
        
        // Output schema markup
        if ( ! empty( $schema ) ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
        }
    }

    /**
     * Add social media meta tags
     *
     * @return void
     */
    public function add_social_meta_tags() {
        // Check if social media meta tags are enabled
        if ( ! $this->get_setting( 'enable_social', true ) ) {
            return;
        }
        
        // Get social media meta tags
        $social_tags = aqualuxe_seo_get_social_meta_tags();
        
        // Output social media meta tags
        if ( ! empty( $social_tags ) ) {
            echo $social_tags;
        }
    }

    /**
     * Add robots meta tag
     *
     * @return void
     */
    public function add_robots_meta() {
        // Check if robots meta tag is enabled
        if ( ! $this->get_setting( 'enable_robots', true ) ) {
            return;
        }
        
        // Get robots meta tag
        $robots = $this->get_robots_meta();
        
        // Output robots meta tag
        if ( ! empty( $robots ) ) {
            echo '<meta name="robots" content="' . esc_attr( $robots ) . '" />' . "\n";
        }
    }

    /**
     * Add canonical URL
     *
     * @return void
     */
    public function add_canonical_url() {
        // Check if canonical URL is enabled
        if ( ! $this->get_setting( 'enable_canonical', true ) ) {
            return;
        }
        
        // Get canonical URL
        $canonical = $this->get_canonical_url();
        
        // Output canonical URL
        if ( ! empty( $canonical ) ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
        }
    }

    /**
     * Add breadcrumbs
     *
     * @return void
     */
    public function add_breadcrumbs() {
        // Check if breadcrumbs are enabled
        if ( ! $this->get_setting( 'enable_breadcrumbs', true ) ) {
            return;
        }
        
        // Output breadcrumbs
        aqualuxe_seo_breadcrumbs();
    }

    /**
     * Filter document title
     *
     * @param array $title Title parts
     * @return array
     */
    public function filter_document_title( $title ) {
        // Check if custom title is enabled
        if ( ! $this->get_setting( 'enable_custom_title', true ) ) {
            return $title;
        }
        
        // Get custom title
        $custom_title = $this->get_custom_title();
        
        // If custom title exists, use it
        if ( ! empty( $custom_title ) ) {
            $title['title'] = $custom_title;
        }
        
        return $title;
    }

    /**
     * Filter document title separator
     *
     * @param string $sep Title separator
     * @return string
     */
    public function filter_document_title_separator( $sep ) {
        // Get custom separator
        $custom_sep = $this->get_setting( 'title_separator', '-' );
        
        // If custom separator exists, use it
        if ( ! empty( $custom_sep ) ) {
            return $custom_sep;
        }
        
        return $sep;
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Get post types
        $post_types = get_post_types( array( 'public' => true ) );
        
        // Add meta box to all public post types
        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'aqualuxe_seo',
                __( 'SEO Settings', 'aqualuxe' ),
                array( $this, 'render_meta_box' ),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render meta box
     *
     * @param WP_Post $post Post object
     * @return void
     */
    public function render_meta_box( $post ) {
        // Add nonce field
        wp_nonce_field( 'aqualuxe_seo_meta_box', 'aqualuxe_seo_meta_box_nonce' );
        
        // Get meta values
        $title = get_post_meta( $post->ID, '_aqualuxe_seo_title', true );
        $description = get_post_meta( $post->ID, '_aqualuxe_seo_description', true );
        $keywords = get_post_meta( $post->ID, '_aqualuxe_seo_keywords', true );
        $robots = get_post_meta( $post->ID, '_aqualuxe_seo_robots', true );
        $canonical = get_post_meta( $post->ID, '_aqualuxe_seo_canonical', true );
        
        // Get default robots value
        if ( empty( $robots ) ) {
            $robots = 'index,follow';
        }
        
        ?>
        <div class="aqualuxe-seo-meta-box">
            <div class="aqualuxe-seo-field">
                <label for="aqualuxe_seo_title"><?php esc_html_e( 'SEO Title', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_seo_title" name="aqualuxe_seo_title" value="<?php echo esc_attr( $title ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter a custom SEO title. Leave blank to use the default title.', 'aqualuxe' ); ?></p>
                <div class="aqualuxe-seo-analysis aqualuxe-seo-title-analysis"></div>
            </div>
            
            <div class="aqualuxe-seo-field">
                <label for="aqualuxe_seo_description"><?php esc_html_e( 'Meta Description', 'aqualuxe' ); ?></label>
                <textarea id="aqualuxe_seo_description" name="aqualuxe_seo_description" rows="3" class="widefat"><?php echo esc_textarea( $description ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Enter a custom meta description. Leave blank to use the default description.', 'aqualuxe' ); ?></p>
                <div class="aqualuxe-seo-analysis aqualuxe-seo-description-analysis"></div>
            </div>
            
            <div class="aqualuxe-seo-field">
                <label for="aqualuxe_seo_keywords"><?php esc_html_e( 'Meta Keywords', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_seo_keywords" name="aqualuxe_seo_keywords" value="<?php echo esc_attr( $keywords ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter meta keywords separated by commas.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-seo-field">
                <label for="aqualuxe_seo_robots"><?php esc_html_e( 'Robots Meta', 'aqualuxe' ); ?></label>
                <select id="aqualuxe_seo_robots" name="aqualuxe_seo_robots" class="widefat">
                    <option value="index,follow" <?php selected( $robots, 'index,follow' ); ?>><?php esc_html_e( 'Index, Follow', 'aqualuxe' ); ?></option>
                    <option value="index,nofollow" <?php selected( $robots, 'index,nofollow' ); ?>><?php esc_html_e( 'Index, No Follow', 'aqualuxe' ); ?></option>
                    <option value="noindex,follow" <?php selected( $robots, 'noindex,follow' ); ?>><?php esc_html_e( 'No Index, Follow', 'aqualuxe' ); ?></option>
                    <option value="noindex,nofollow" <?php selected( $robots, 'noindex,nofollow' ); ?>><?php esc_html_e( 'No Index, No Follow', 'aqualuxe' ); ?></option>
                </select>
                <p class="description"><?php esc_html_e( 'Select how search engines should handle this page.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-seo-field">
                <label for="aqualuxe_seo_canonical"><?php esc_html_e( 'Canonical URL', 'aqualuxe' ); ?></label>
                <input type="url" id="aqualuxe_seo_canonical" name="aqualuxe_seo_canonical" value="<?php echo esc_url( $canonical ); ?>" class="widefat">
                <p class="description"><?php esc_html_e( 'Enter a custom canonical URL. Leave blank to use the default URL.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     * @return void
     */
    public function save_meta_box_data( $post_id ) {
        // Check if nonce is set
        if ( ! isset( $_POST['aqualuxe_seo_meta_box_nonce'] ) ) {
            return;
        }
        
        // Verify nonce
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_seo_meta_box_nonce'] ) ), 'aqualuxe_seo_meta_box' ) ) {
            return;
        }
        
        // Check if autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        
        // Check permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        
        // Save meta values
        if ( isset( $_POST['aqualuxe_seo_title'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_seo_title', sanitize_text_field( wp_unslash( $_POST['aqualuxe_seo_title'] ) ) );
        }
        
        if ( isset( $_POST['aqualuxe_seo_description'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_seo_description', sanitize_textarea_field( wp_unslash( $_POST['aqualuxe_seo_description'] ) ) );
        }
        
        if ( isset( $_POST['aqualuxe_seo_keywords'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_seo_keywords', sanitize_text_field( wp_unslash( $_POST['aqualuxe_seo_keywords'] ) ) );
        }
        
        if ( isset( $_POST['aqualuxe_seo_robots'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_seo_robots', sanitize_text_field( wp_unslash( $_POST['aqualuxe_seo_robots'] ) ) );
        }
        
        if ( isset( $_POST['aqualuxe_seo_canonical'] ) ) {
            update_post_meta( $post_id, '_aqualuxe_seo_canonical', esc_url_raw( wp_unslash( $_POST['aqualuxe_seo_canonical'] ) ) );
        }
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        add_options_page(
            __( 'SEO Settings', 'aqualuxe' ),
            __( 'SEO Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-seo',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Register admin settings
     *
     * @return void
     */
    public function register_admin_settings() {
        // Register settings
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_custom_title' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_title_separator' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_schema' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_social' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_robots' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_canonical' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_enable_breadcrumbs' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_use_keywords' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_home_title' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_home_description' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_home_keywords' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_facebook_app_id' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_facebook_page' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_twitter_username' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_twitter_card_type' );
        register_setting( 'aqualuxe_seo', 'aqualuxe_seo_default_image' );
        
        // Add settings sections
        add_settings_section(
            'aqualuxe_seo_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'settings_section_general' ),
            'aqualuxe_seo'
        );
        
        add_settings_section(
            'aqualuxe_seo_home',
            __( 'Homepage Settings', 'aqualuxe' ),
            array( $this, 'settings_section_home' ),
            'aqualuxe_seo'
        );
        
        add_settings_section(
            'aqualuxe_seo_social',
            __( 'Social Media Settings', 'aqualuxe' ),
            array( $this, 'settings_section_social' ),
            'aqualuxe_seo'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_seo_enable_custom_title',
            __( 'Enable Custom Titles', 'aqualuxe' ),
            array( $this, 'settings_field_enable_custom_title' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_title_separator',
            __( 'Title Separator', 'aqualuxe' ),
            array( $this, 'settings_field_title_separator' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_enable_schema',
            __( 'Enable Schema Markup', 'aqualuxe' ),
            array( $this, 'settings_field_enable_schema' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_enable_social',
            __( 'Enable Social Media Meta Tags', 'aqualuxe' ),
            array( $this, 'settings_field_enable_social' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_enable_robots',
            __( 'Enable Robots Meta Tag', 'aqualuxe' ),
            array( $this, 'settings_field_enable_robots' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_enable_canonical',
            __( 'Enable Canonical URL', 'aqualuxe' ),
            array( $this, 'settings_field_enable_canonical' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_enable_breadcrumbs',
            __( 'Enable Breadcrumbs', 'aqualuxe' ),
            array( $this, 'settings_field_enable_breadcrumbs' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_use_keywords',
            __( 'Use Meta Keywords', 'aqualuxe' ),
            array( $this, 'settings_field_use_keywords' ),
            'aqualuxe_seo',
            'aqualuxe_seo_general'
        );
        
        add_settings_field(
            'aqualuxe_seo_home_title',
            __( 'Homepage Title', 'aqualuxe' ),
            array( $this, 'settings_field_home_title' ),
            'aqualuxe_seo',
            'aqualuxe_seo_home'
        );
        
        add_settings_field(
            'aqualuxe_seo_home_description',
            __( 'Homepage Description', 'aqualuxe' ),
            array( $this, 'settings_field_home_description' ),
            'aqualuxe_seo',
            'aqualuxe_seo_home'
        );
        
        add_settings_field(
            'aqualuxe_seo_home_keywords',
            __( 'Homepage Keywords', 'aqualuxe' ),
            array( $this, 'settings_field_home_keywords' ),
            'aqualuxe_seo',
            'aqualuxe_seo_home'
        );
        
        add_settings_field(
            'aqualuxe_seo_facebook_app_id',
            __( 'Facebook App ID', 'aqualuxe' ),
            array( $this, 'settings_field_facebook_app_id' ),
            'aqualuxe_seo',
            'aqualuxe_seo_social'
        );
        
        add_settings_field(
            'aqualuxe_seo_facebook_page',
            __( 'Facebook Page URL', 'aqualuxe' ),
            array( $this, 'settings_field_facebook_page' ),
            'aqualuxe_seo',
            'aqualuxe_seo_social'
        );
        
        add_settings_field(
            'aqualuxe_seo_twitter_username',
            __( 'Twitter Username', 'aqualuxe' ),
            array( $this, 'settings_field_twitter_username' ),
            'aqualuxe_seo',
            'aqualuxe_seo_social'
        );
        
        add_settings_field(
            'aqualuxe_seo_twitter_card_type',
            __( 'Twitter Card Type', 'aqualuxe' ),
            array( $this, 'settings_field_twitter_card_type' ),
            'aqualuxe_seo',
            'aqualuxe_seo_social'
        );
        
        add_settings_field(
            'aqualuxe_seo_default_image',
            __( 'Default Social Image', 'aqualuxe' ),
            array( $this, 'settings_field_default_image' ),
            'aqualuxe_seo',
            'aqualuxe_seo_social'
        );
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_seo' );
                do_settings_sections( 'aqualuxe_seo' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Settings section general
     *
     * @return void
     */
    public function settings_section_general() {
        echo '<p>' . esc_html__( 'Configure general SEO settings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section home
     *
     * @return void
     */
    public function settings_section_home() {
        echo '<p>' . esc_html__( 'Configure SEO settings for the homepage.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section social
     *
     * @return void
     */
    public function settings_section_social() {
        echo '<p>' . esc_html__( 'Configure social media settings for SEO.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings field enable custom title
     *
     * @return void
     */
    public function settings_field_enable_custom_title() {
        $value = get_option( 'aqualuxe_seo_enable_custom_title', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_custom_title" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable custom titles for pages and posts', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, you can set custom titles for pages and posts.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field title separator
     *
     * @return void
     */
    public function settings_field_title_separator() {
        $value = get_option( 'aqualuxe_seo_title_separator', '-' );
        ?>
        <select name="aqualuxe_seo_title_separator">
            <option value="-" <?php selected( $value, '-' ); ?>>-</option>
            <option value="|" <?php selected( $value, '|' ); ?>>|</option>
            <option value="&raquo;" <?php selected( $value, '&raquo;' ); ?>>&raquo;</option>
            <option value="&laquo;" <?php selected( $value, '&laquo;' ); ?>>&laquo;</option>
            <option value="&middot;" <?php selected( $value, '&middot;' ); ?>>&middot;</option>
            <option value="&bull;" <?php selected( $value, '&bull;' ); ?>>&bull;</option>
            <option value="&ndash;" <?php selected( $value, '&ndash;' ); ?>>&ndash;</option>
            <option value="&mdash;" <?php selected( $value, '&mdash;' ); ?>>&mdash;</option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the separator for the document title.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable schema
     *
     * @return void
     */
    public function settings_field_enable_schema() {
        $value = get_option( 'aqualuxe_seo_enable_schema', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_schema" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable schema markup', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, schema markup will be added to your site.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable social
     *
     * @return void
     */
    public function settings_field_enable_social() {
        $value = get_option( 'aqualuxe_seo_enable_social', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_social" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable social media meta tags', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, social media meta tags will be added to your site.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable robots
     *
     * @return void
     */
    public function settings_field_enable_robots() {
        $value = get_option( 'aqualuxe_seo_enable_robots', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_robots" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable robots meta tag', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, robots meta tag will be added to your site.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable canonical
     *
     * @return void
     */
    public function settings_field_enable_canonical() {
        $value = get_option( 'aqualuxe_seo_enable_canonical', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_canonical" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable canonical URL', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, canonical URL will be added to your site.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable breadcrumbs
     *
     * @return void
     */
    public function settings_field_enable_breadcrumbs() {
        $value = get_option( 'aqualuxe_seo_enable_breadcrumbs', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_enable_breadcrumbs" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable breadcrumbs', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, breadcrumbs will be added to your site.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field use keywords
     *
     * @return void
     */
    public function settings_field_use_keywords() {
        $value = get_option( 'aqualuxe_seo_use_keywords', false );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_seo_use_keywords" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Use meta keywords', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'If enabled, meta keywords will be added to your site. Note: Most search engines ignore meta keywords.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field home title
     *
     * @return void
     */
    public function settings_field_home_title() {
        $value = get_option( 'aqualuxe_seo_home_title', '' );
        ?>
        <input type="text" name="aqualuxe_seo_home_title" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php esc_html_e( 'Enter a custom title for the homepage. Leave blank to use the default title.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field home description
     *
     * @return void
     */
    public function settings_field_home_description() {
        $value = get_option( 'aqualuxe_seo_home_description', '' );
        ?>
        <textarea name="aqualuxe_seo_home_description" rows="3" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Enter a custom meta description for the homepage. Leave blank to use the default description.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field home keywords
     *
     * @return void
     */
    public function settings_field_home_keywords() {
        $value = get_option( 'aqualuxe_seo_home_keywords', '' );
        ?>
        <input type="text" name="aqualuxe_seo_home_keywords" value="<?php echo esc_attr( $value ); ?>" class="large-text">
        <p class="description"><?php esc_html_e( 'Enter meta keywords for the homepage separated by commas.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field Facebook App ID
     *
     * @return void
     */
    public function settings_field_facebook_app_id() {
        $value = get_option( 'aqualuxe_seo_facebook_app_id', '' );
        ?>
        <input type="text" name="aqualuxe_seo_facebook_app_id" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php esc_html_e( 'Enter your Facebook App ID.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field Facebook Page
     *
     * @return void
     */
    public function settings_field_facebook_page() {
        $value = get_option( 'aqualuxe_seo_facebook_page', '' );
        ?>
        <input type="url" name="aqualuxe_seo_facebook_page" value="<?php echo esc_url( $value ); ?>" class="regular-text">
        <p class="description"><?php esc_html_e( 'Enter your Facebook Page URL.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field Twitter Username
     *
     * @return void
     */
    public function settings_field_twitter_username() {
        $value = get_option( 'aqualuxe_seo_twitter_username', '' );
        ?>
        <input type="text" name="aqualuxe_seo_twitter_username" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
        <p class="description"><?php esc_html_e( 'Enter your Twitter username without the @ symbol.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field Twitter Card Type
     *
     * @return void
     */
    public function settings_field_twitter_card_type() {
        $value = get_option( 'aqualuxe_seo_twitter_card_type', 'summary_large_image' );
        ?>
        <select name="aqualuxe_seo_twitter_card_type">
            <option value="summary" <?php selected( $value, 'summary' ); ?>><?php esc_html_e( 'Summary', 'aqualuxe' ); ?></option>
            <option value="summary_large_image" <?php selected( $value, 'summary_large_image' ); ?>><?php esc_html_e( 'Summary with Large Image', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'Select the Twitter card type.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field Default Image
     *
     * @return void
     */
    public function settings_field_default_image() {
        $value = get_option( 'aqualuxe_seo_default_image', '' );
        ?>
        <div class="aqualuxe-seo-image-upload">
            <input type="url" name="aqualuxe_seo_default_image" value="<?php echo esc_url( $value ); ?>" class="regular-text">
            <button type="button" class="button aqualuxe-seo-upload-image"><?php esc_html_e( 'Upload Image', 'aqualuxe' ); ?></button>
            <?php if ( ! empty( $value ) ) : ?>
                <div class="aqualuxe-seo-image-preview">
                    <img src="<?php echo esc_url( $value ); ?>" alt="">
                </div>
            <?php endif; ?>
        </div>
        <p class="description"><?php esc_html_e( 'Upload or enter the URL of the default image for social media sharing.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Get meta description
     *
     * @return string
     */
    private function get_meta_description() {
        // Get post ID
        $post_id = get_queried_object_id();
        
        // Check if we're on a single post or page
        if ( is_singular() ) {
            // Get custom description
            $description = get_post_meta( $post_id, '_aqualuxe_seo_description', true );
            
            // If custom description exists, use it
            if ( ! empty( $description ) ) {
                return $description;
            }
            
            // Get post excerpt
            $post = get_post( $post_id );
            if ( ! empty( $post->post_excerpt ) ) {
                return $post->post_excerpt;
            }
            
            // Get post content
            $content = $post->post_content;
            
            // Strip shortcodes and tags
            $content = strip_shortcodes( $content );
            $content = wp_strip_all_tags( $content );
            
            // Trim to 160 characters
            $content = wp_trim_words( $content, 30, '...' );
            
            return $content;
        }
        
        // Check if we're on the homepage
        if ( is_home() || is_front_page() ) {
            // Get custom description
            $description = get_option( 'aqualuxe_seo_home_description', '' );
            
            // If custom description exists, use it
            if ( ! empty( $description ) ) {
                return $description;
            }
            
            // Get site description
            return get_bloginfo( 'description' );
        }
        
        // Check if we're on a category or tag archive
        if ( is_category() || is_tag() || is_tax() ) {
            // Get term description
            $term = get_queried_object();
            if ( ! empty( $term->description ) ) {
                return $term->description;
            }
        }
        
        // Default to site description
        return get_bloginfo( 'description' );
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    private function get_meta_keywords() {
        // Get post ID
        $post_id = get_queried_object_id();
        
        // Check if we're on a single post or page
        if ( is_singular() ) {
            // Get custom keywords
            $keywords = get_post_meta( $post_id, '_aqualuxe_seo_keywords', true );
            
            // If custom keywords exist, use them
            if ( ! empty( $keywords ) ) {
                return $keywords;
            }
            
            // Get post tags
            $tags = get_the_tags( $post_id );
            if ( ! empty( $tags ) ) {
                $tag_names = array();
                foreach ( $tags as $tag ) {
                    $tag_names[] = $tag->name;
                }
                return implode( ', ', $tag_names );
            }
            
            // Get post categories
            $categories = get_the_category( $post_id );
            if ( ! empty( $categories ) ) {
                $category_names = array();
                foreach ( $categories as $category ) {
                    $category_names[] = $category->name;
                }
                return implode( ', ', $category_names );
            }
        }
        
        // Check if we're on the homepage
        if ( is_home() || is_front_page() ) {
            // Get custom keywords
            $keywords = get_option( 'aqualuxe_seo_home_keywords', '' );
            
            // If custom keywords exist, use them
            if ( ! empty( $keywords ) ) {
                return $keywords;
            }
        }
        
        // Check if we're on a category or tag archive
        if ( is_category() || is_tag() || is_tax() ) {
            // Get term name
            $term = get_queried_object();
            if ( ! empty( $term->name ) ) {
                return $term->name;
            }
        }
        
        // Default to empty
        return '';
    }

    /**
     * Get robots meta
     *
     * @return string
     */
    private function get_robots_meta() {
        // Get post ID
        $post_id = get_queried_object_id();
        
        // Check if we're on a single post or page
        if ( is_singular() ) {
            // Get custom robots
            $robots = get_post_meta( $post_id, '_aqualuxe_seo_robots', true );
            
            // If custom robots exist, use them
            if ( ! empty( $robots ) ) {
                return $robots;
            }
        }
        
        // Check if we're on a search page
        if ( is_search() ) {
            return 'noindex,follow';
        }
        
        // Check if we're on an archive page
        if ( is_archive() ) {
            // Check if pagination is greater than 1
            if ( get_query_var( 'paged' ) > 1 ) {
                return 'noindex,follow';
            }
        }
        
        // Default to index,follow
        return 'index,follow';
    }

    /**
     * Get canonical URL
     *
     * @return string
     */
    private function get_canonical_url() {
        // Get post ID
        $post_id = get_queried_object_id();
        
        // Check if we're on a single post or page
        if ( is_singular() ) {
            // Get custom canonical URL
            $canonical = get_post_meta( $post_id, '_aqualuxe_seo_canonical', true );
            
            // If custom canonical URL exists, use it
            if ( ! empty( $canonical ) ) {
                return $canonical;
            }
            
            // Get permalink
            return get_permalink( $post_id );
        }
        
        // Check if we're on the homepage
        if ( is_home() || is_front_page() ) {
            return home_url( '/' );
        }
        
        // Check if we're on a category or tag archive
        if ( is_category() || is_tag() || is_tax() ) {
            return get_term_link( get_queried_object() );
        }
        
        // Check if we're on a search page
        if ( is_search() ) {
            return get_search_link( get_search_query() );
        }
        
        // Check if we're on an author page
        if ( is_author() ) {
            return get_author_posts_url( get_queried_object_id() );
        }
        
        // Check if we're on a date archive
        if ( is_date() ) {
            if ( is_day() ) {
                return get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
            } elseif ( is_month() ) {
                return get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
            } elseif ( is_year() ) {
                return get_year_link( get_query_var( 'year' ) );
            }
        }
        
        // Default to current URL
        global $wp;
        return home_url( $wp->request );
    }

    /**
     * Get custom title
     *
     * @return string
     */
    private function get_custom_title() {
        // Get post ID
        $post_id = get_queried_object_id();
        
        // Check if we're on a single post or page
        if ( is_singular() ) {
            // Get custom title
            $title = get_post_meta( $post_id, '_aqualuxe_seo_title', true );
            
            // If custom title exists, use it
            if ( ! empty( $title ) ) {
                return $title;
            }
        }
        
        // Check if we're on the homepage
        if ( is_home() || is_front_page() ) {
            // Get custom title
            $title = get_option( 'aqualuxe_seo_home_title', '' );
            
            // If custom title exists, use it
            if ( ! empty( $title ) ) {
                return $title;
            }
        }
        
        // Return empty string to use default title
        return '';
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
            'enable_custom_title' => true,
            'title_separator' => '-',
            'enable_schema' => true,
            'enable_social' => true,
            'enable_robots' => true,
            'enable_canonical' => true,
            'enable_breadcrumbs' => true,
            'use_keywords' => false,
        );
    }
}