<?php
/**
 * SEO Module
 *
 * @package AquaLuxe
 * @subpackage Modules/SEO
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * SEO Module Class
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
    protected $module_description = 'Enhances search engine optimization with meta tags, schema markup, and sitemap';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array(
        'enable_meta_tags' => true,
        'enable_schema_markup' => true,
        'enable_sitemap' => true,
        'enable_breadcrumbs' => true,
        'enable_social_meta' => true,
        'twitter_site' => '',
        'facebook_app_id' => '',
        'default_facebook_image' => '',
        'default_twitter_image' => '',
        'enable_robots_txt' => true,
        'noindex_archives' => false,
        'noindex_search' => true,
        'enable_canonical_urls' => true,
        'enable_auto_descriptions' => true,
    );

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Load module settings
        $this->load_settings();

        // Include module files
        $this->include_files();
    }

    /**
     * Include module files
     *
     * @return void
     */
    private function include_files() {
        // Include helper functions
        require_once dirname( __FILE__ ) . '/includes/helpers.php';

        // Include meta tags functions
        if ( $this->settings['enable_meta_tags'] ) {
            require_once dirname( __FILE__ ) . '/includes/meta-tags.php';
        }

        // Include schema markup functions
        if ( $this->settings['enable_schema_markup'] ) {
            require_once dirname( __FILE__ ) . '/includes/schema-markup.php';
        }

        // Include sitemap functions
        if ( $this->settings['enable_sitemap'] ) {
            require_once dirname( __FILE__ ) . '/includes/sitemap.php';
        }

        // Include breadcrumbs functions
        if ( $this->settings['enable_breadcrumbs'] ) {
            require_once dirname( __FILE__ ) . '/includes/breadcrumbs.php';
        }

        // Include social meta functions
        if ( $this->settings['enable_social_meta'] ) {
            require_once dirname( __FILE__ ) . '/includes/social-meta.php';
        }

        // Include robots.txt functions
        if ( $this->settings['enable_robots_txt'] ) {
            require_once dirname( __FILE__ ) . '/includes/robots-txt.php';
        }
    }

    /**
     * Setup module hooks
     *
     * @return void
     */
    public function setup_hooks() {
        // Add meta tags
        if ( $this->settings['enable_meta_tags'] ) {
            add_action( 'wp_head', array( $this, 'add_meta_tags' ), 1 );
        }

        // Add schema markup
        if ( $this->settings['enable_schema_markup'] ) {
            add_action( 'wp_footer', array( $this, 'add_schema_markup' ) );
        }

        // Add sitemap
        if ( $this->settings['enable_sitemap'] ) {
            add_action( 'init', array( $this, 'register_sitemap' ) );
        }

        // Add breadcrumbs
        if ( $this->settings['enable_breadcrumbs'] ) {
            add_action( 'aqualuxe_before_main_content', array( $this, 'add_breadcrumbs' ) );
        }

        // Add social meta
        if ( $this->settings['enable_social_meta'] ) {
            add_action( 'wp_head', array( $this, 'add_social_meta' ), 2 );
        }

        // Add robots.txt
        if ( $this->settings['enable_robots_txt'] ) {
            add_filter( 'robots_txt', array( $this, 'custom_robots_txt' ), 10, 2 );
        }

        // Add canonical URLs
        if ( $this->settings['enable_canonical_urls'] ) {
            add_action( 'wp_head', array( $this, 'add_canonical_url' ), 1 );
            remove_action( 'wp_head', 'rel_canonical' );
        }

        // Add noindex tags
        if ( $this->settings['noindex_archives'] || $this->settings['noindex_search'] ) {
            add_action( 'wp_head', array( $this, 'add_noindex_tags' ), 1 );
        }

        // Add auto descriptions
        if ( $this->settings['enable_auto_descriptions'] ) {
            add_filter( 'aqualuxe_meta_description', array( $this, 'generate_auto_description' ), 10, 2 );
        }

        // Add SEO columns to admin
        add_filter( 'manage_posts_columns', array( $this, 'add_seo_columns' ) );
        add_filter( 'manage_pages_columns', array( $this, 'add_seo_columns' ) );
        add_action( 'manage_posts_custom_column', array( $this, 'display_seo_columns' ), 10, 2 );
        add_action( 'manage_pages_custom_column', array( $this, 'display_seo_columns' ), 10, 2 );

        // Add meta boxes
        add_action( 'add_meta_boxes', array( $this, 'add_seo_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_seo_meta_boxes' ) );

        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Add meta tags
     *
     * @return void
     */
    public function add_meta_tags() {
        // Get current post/page
        $post_id = get_queried_object_id();

        // Get meta title
        $meta_title = aqualuxe_get_meta_title( $post_id );

        // Get meta description
        $meta_description = aqualuxe_get_meta_description( $post_id );

        // Get meta keywords
        $meta_keywords = aqualuxe_get_meta_keywords( $post_id );

        // Output meta tags
        if ( $meta_title ) {
            echo '<meta name="title" content="' . esc_attr( $meta_title ) . '">' . "\n";
        }

        if ( $meta_description ) {
            echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
        }

        if ( $meta_keywords ) {
            echo '<meta name="keywords" content="' . esc_attr( $meta_keywords ) . '">' . "\n";
        }

        // Add viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    }

    /**
     * Add schema markup
     *
     * @return void
     */
    public function add_schema_markup() {
        // Get current post/page
        $post_id = get_queried_object_id();

        // Get schema markup
        $schema_markup = aqualuxe_get_schema_markup( $post_id );

        // Output schema markup
        if ( $schema_markup ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $schema_markup ) . '</script>' . "\n";
        }
    }

    /**
     * Register sitemap
     *
     * @return void
     */
    public function register_sitemap() {
        // Add sitemap rewrite rule
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?aqualuxe_sitemap=1', 'top' );
        add_rewrite_rule( '^sitemap-([^/]+)\.xml$', 'index.php?aqualuxe_sitemap=$matches[1]', 'top' );

        // Add query var
        add_filter( 'query_vars', function( $vars ) {
            $vars[] = 'aqualuxe_sitemap';
            return $vars;
        } );

        // Add template redirect
        add_action( 'template_redirect', function() {
            $sitemap = get_query_var( 'aqualuxe_sitemap' );

            if ( $sitemap ) {
                // Set content type
                header( 'Content-Type: application/xml; charset=UTF-8' );

                // Generate sitemap
                if ( $sitemap === '1' ) {
                    // Main sitemap index
                    echo aqualuxe_get_sitemap_index();
                } else {
                    // Specific sitemap
                    echo aqualuxe_get_sitemap( $sitemap );
                }

                exit;
            }
        } );
    }

    /**
     * Add breadcrumbs
     *
     * @return void
     */
    public function add_breadcrumbs() {
        // Check if we're on a WooCommerce page
        if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
            // Let WooCommerce handle its own breadcrumbs
            return;
        }

        // Get breadcrumbs
        $breadcrumbs = aqualuxe_get_breadcrumbs();

        // Output breadcrumbs
        if ( $breadcrumbs ) {
            echo '<div class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">' . $breadcrumbs . '</div>';
        }
    }

    /**
     * Add social meta
     *
     * @return void
     */
    public function add_social_meta() {
        // Get current post/page
        $post_id = get_queried_object_id();

        // Get Open Graph meta
        $og_meta = aqualuxe_get_open_graph_meta( $post_id );

        // Get Twitter Card meta
        $twitter_meta = aqualuxe_get_twitter_card_meta( $post_id );

        // Output Open Graph meta
        if ( $og_meta && is_array( $og_meta ) ) {
            foreach ( $og_meta as $property => $content ) {
                echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
            }
        }

        // Output Twitter Card meta
        if ( $twitter_meta && is_array( $twitter_meta ) ) {
            foreach ( $twitter_meta as $name => $content ) {
                echo '<meta name="' . esc_attr( $name ) . '" content="' . esc_attr( $content ) . '">' . "\n";
            }
        }
    }

    /**
     * Custom robots.txt
     *
     * @param string $output Robots.txt output.
     * @param bool   $public Whether the site is public or not.
     * @return string
     */
    public function custom_robots_txt( $output, $public ) {
        if ( ! $public ) {
            return $output;
        }

        $output = "User-agent: *\n";
        
        // Add noindex rules
        if ( $this->settings['noindex_archives'] ) {
            $output .= "Disallow: /category/\n";
            $output .= "Disallow: /tag/\n";
            $output .= "Disallow: /author/\n";
        }
        
        if ( $this->settings['noindex_search'] ) {
            $output .= "Disallow: /?s=\n";
            $output .= "Disallow: /search/\n";
        }
        
        // Add sitemap
        if ( $this->settings['enable_sitemap'] ) {
            $output .= "\nSitemap: " . home_url( '/sitemap.xml' ) . "\n";
        }
        
        return $output;
    }

    /**
     * Add canonical URL
     *
     * @return void
     */
    public function add_canonical_url() {
        // Get canonical URL
        $canonical_url = aqualuxe_get_canonical_url();

        // Output canonical URL
        if ( $canonical_url ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
        }
    }

    /**
     * Add noindex tags
     *
     * @return void
     */
    public function add_noindex_tags() {
        // Check if we should add noindex tag
        $add_noindex = false;

        // Check archives
        if ( $this->settings['noindex_archives'] && ( is_category() || is_tag() || is_author() || is_date() ) ) {
            $add_noindex = true;
        }

        // Check search
        if ( $this->settings['noindex_search'] && is_search() ) {
            $add_noindex = true;
        }

        // Output noindex tag
        if ( $add_noindex ) {
            echo '<meta name="robots" content="noindex,follow">' . "\n";
        }
    }

    /**
     * Generate auto description
     *
     * @param string $description Description.
     * @param int    $post_id Post ID.
     * @return string
     */
    public function generate_auto_description( $description, $post_id ) {
        // If description is already set, return it
        if ( ! empty( $description ) ) {
            return $description;
        }

        // Get post
        $post = get_post( $post_id );

        // If no post, return empty
        if ( ! $post ) {
            return '';
        }

        // Get post content
        $content = $post->post_content;

        // Strip shortcodes
        $content = strip_shortcodes( $content );

        // Strip HTML
        $content = wp_strip_all_tags( $content );

        // Trim to 160 characters
        $description = wp_trim_words( $content, 25, '...' );

        return $description;
    }

    /**
     * Add SEO columns to admin
     *
     * @param array $columns Columns.
     * @return array
     */
    public function add_seo_columns( $columns ) {
        $columns['aqualuxe_seo_title'] = __( 'SEO Title', 'aqualuxe' );
        $columns['aqualuxe_seo_description'] = __( 'SEO Description', 'aqualuxe' );
        
        return $columns;
    }

    /**
     * Display SEO columns
     *
     * @param string $column Column name.
     * @param int    $post_id Post ID.
     * @return void
     */
    public function display_seo_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'aqualuxe_seo_title':
                $title = get_post_meta( $post_id, '_aqualuxe_seo_title', true );
                echo esc_html( $title ? $title : '-' );
                break;
                
            case 'aqualuxe_seo_description':
                $description = get_post_meta( $post_id, '_aqualuxe_seo_description', true );
                echo esc_html( $description ? $description : '-' );
                break;
        }
    }

    /**
     * Add SEO meta boxes
     *
     * @return void
     */
    public function add_seo_meta_boxes() {
        // Get post types
        $post_types = get_post_types( array( 'public' => true ) );
        
        // Add meta box to each post type
        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'aqualuxe_seo_meta_box',
                __( 'SEO Settings', 'aqualuxe' ),
                array( $this, 'render_seo_meta_box' ),
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render SEO meta box
     *
     * @param WP_Post $post Post object.
     * @return void
     */
    public function render_seo_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'aqualuxe_seo_meta_box', 'aqualuxe_seo_meta_box_nonce' );

        // Get meta values
        $title = get_post_meta( $post->ID, '_aqualuxe_seo_title', true );
        $description = get_post_meta( $post->ID, '_aqualuxe_seo_description', true );
        $keywords = get_post_meta( $post->ID, '_aqualuxe_seo_keywords', true );
        $noindex = get_post_meta( $post->ID, '_aqualuxe_seo_noindex', true );
        $canonical = get_post_meta( $post->ID, '_aqualuxe_seo_canonical', true );
        $og_title = get_post_meta( $post->ID, '_aqualuxe_og_title', true );
        $og_description = get_post_meta( $post->ID, '_aqualuxe_og_description', true );
        $og_image = get_post_meta( $post->ID, '_aqualuxe_og_image', true );
        $twitter_title = get_post_meta( $post->ID, '_aqualuxe_twitter_title', true );
        $twitter_description = get_post_meta( $post->ID, '_aqualuxe_twitter_description', true );
        $twitter_image = get_post_meta( $post->ID, '_aqualuxe_twitter_image', true );
        
        // Output fields
        ?>
        <div class="aqualuxe-seo-meta-box">
            <div class="aqualuxe-seo-section">
                <h3><?php esc_html_e( 'Meta Tags', 'aqualuxe' ); ?></h3>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_seo_title"><?php esc_html_e( 'Meta Title', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe_seo_title" name="aqualuxe_seo_title" value="<?php echo esc_attr( $title ); ?>" class="widefat">
                    <p class="description"><?php esc_html_e( 'Enter the meta title for this page. Leave blank to use the default title.', 'aqualuxe' ); ?></p>
                    <div class="aqualuxe-seo-counter"><span>0</span> / 60 <?php esc_html_e( 'characters', 'aqualuxe' ); ?></div>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_seo_description"><?php esc_html_e( 'Meta Description', 'aqualuxe' ); ?></label>
                    <textarea id="aqualuxe_seo_description" name="aqualuxe_seo_description" class="widefat" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter the meta description for this page. Leave blank to use the default description.', 'aqualuxe' ); ?></p>
                    <div class="aqualuxe-seo-counter"><span>0</span> / 160 <?php esc_html_e( 'characters', 'aqualuxe' ); ?></div>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_seo_keywords"><?php esc_html_e( 'Meta Keywords', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe_seo_keywords" name="aqualuxe_seo_keywords" value="<?php echo esc_attr( $keywords ); ?>" class="widefat">
                    <p class="description"><?php esc_html_e( 'Enter the meta keywords for this page, separated by commas.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_seo_noindex">
                        <input type="checkbox" id="aqualuxe_seo_noindex" name="aqualuxe_seo_noindex" value="1" <?php checked( $noindex, '1' ); ?>>
                        <?php esc_html_e( 'Noindex this page', 'aqualuxe' ); ?>
                    </label>
                    <p class="description"><?php esc_html_e( 'Check this box to prevent search engines from indexing this page.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_seo_canonical"><?php esc_html_e( 'Canonical URL', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe_seo_canonical" name="aqualuxe_seo_canonical" value="<?php echo esc_attr( $canonical ); ?>" class="widefat">
                    <p class="description"><?php esc_html_e( 'Enter the canonical URL for this page. Leave blank to use the default URL.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="aqualuxe-seo-section">
                <h3><?php esc_html_e( 'Social Media', 'aqualuxe' ); ?></h3>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_og_title"><?php esc_html_e( 'Facebook Title', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe_og_title" name="aqualuxe_og_title" value="<?php echo esc_attr( $og_title ); ?>" class="widefat">
                    <p class="description"><?php esc_html_e( 'Enter the title for Facebook sharing. Leave blank to use the meta title.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_og_description"><?php esc_html_e( 'Facebook Description', 'aqualuxe' ); ?></label>
                    <textarea id="aqualuxe_og_description" name="aqualuxe_og_description" class="widefat" rows="3"><?php echo esc_textarea( $og_description ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter the description for Facebook sharing. Leave blank to use the meta description.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_og_image"><?php esc_html_e( 'Facebook Image', 'aqualuxe' ); ?></label>
                    <div class="aqualuxe-seo-image-field">
                        <input type="text" id="aqualuxe_og_image" name="aqualuxe_og_image" value="<?php echo esc_attr( $og_image ); ?>" class="widefat">
                        <button type="button" class="button aqualuxe-seo-upload-button"><?php esc_html_e( 'Upload', 'aqualuxe' ); ?></button>
                    </div>
                    <p class="description"><?php esc_html_e( 'Enter the URL of the image for Facebook sharing. Leave blank to use the featured image.', 'aqualuxe' ); ?></p>
                    <div class="aqualuxe-seo-image-preview">
                        <?php if ( $og_image ) : ?>
                            <img src="<?php echo esc_url( $og_image ); ?>" alt="">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_twitter_title"><?php esc_html_e( 'Twitter Title', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe_twitter_title" name="aqualuxe_twitter_title" value="<?php echo esc_attr( $twitter_title ); ?>" class="widefat">
                    <p class="description"><?php esc_html_e( 'Enter the title for Twitter sharing. Leave blank to use the meta title.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_twitter_description"><?php esc_html_e( 'Twitter Description', 'aqualuxe' ); ?></label>
                    <textarea id="aqualuxe_twitter_description" name="aqualuxe_twitter_description" class="widefat" rows="3"><?php echo esc_textarea( $twitter_description ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter the description for Twitter sharing. Leave blank to use the meta description.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="aqualuxe-seo-field">
                    <label for="aqualuxe_twitter_image"><?php esc_html_e( 'Twitter Image', 'aqualuxe' ); ?></label>
                    <div class="aqualuxe-seo-image-field">
                        <input type="text" id="aqualuxe_twitter_image" name="aqualuxe_twitter_image" value="<?php echo esc_attr( $twitter_image ); ?>" class="widefat">
                        <button type="button" class="button aqualuxe-seo-upload-button"><?php esc_html_e( 'Upload', 'aqualuxe' ); ?></button>
                    </div>
                    <p class="description"><?php esc_html_e( 'Enter the URL of the image for Twitter sharing. Leave blank to use the featured image.', 'aqualuxe' ); ?></p>
                    <div class="aqualuxe-seo-image-preview">
                        <?php if ( $twitter_image ) : ?>
                            <img src="<?php echo esc_url( $twitter_image ); ?>" alt="">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save SEO meta boxes
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_seo_meta_boxes( $post_id ) {
        // Check if nonce is set
        if ( ! isset( $_POST['aqualuxe_seo_meta_box_nonce'] ) ) {
            return;
        }

        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['aqualuxe_seo_meta_box_nonce'], 'aqualuxe_seo_meta_box' ) ) {
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
        $fields = array(
            'aqualuxe_seo_title',
            'aqualuxe_seo_description',
            'aqualuxe_seo_keywords',
            'aqualuxe_seo_canonical',
            'aqualuxe_og_title',
            'aqualuxe_og_description',
            'aqualuxe_og_image',
            'aqualuxe_twitter_title',
            'aqualuxe_twitter_description',
            'aqualuxe_twitter_image',
        );

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
            }
        }

        // Save checkbox fields
        $checkbox_fields = array(
            'aqualuxe_seo_noindex',
        );

        foreach ( $checkbox_fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_post_meta( $post_id, '_' . $field, '1' );
            } else {
                update_post_meta( $post_id, '_' . $field, '' );
            }
        }
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook Hook suffix.
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        // Only enqueue on post edit screens
        if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
            return;
        }

        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-seo-admin',
            AQUALUXE_THEME_URI . 'modules/seo/assets/css/admin.css',
            array(),
            $this->version
        );

        // Enqueue scripts
        wp_enqueue_media();
        wp_enqueue_script(
            'aqualuxe-seo-admin',
            AQUALUXE_THEME_URI . 'modules/seo/assets/js/admin.js',
            array( 'jquery', 'wp-util' ),
            $this->version,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-seo-admin',
            'aqualuxeSEO',
            array(
                'mediaTitle' => __( 'Select or Upload Image', 'aqualuxe' ),
                'mediaButton' => __( 'Use this image', 'aqualuxe' ),
            )
        );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add SEO section
        $wp_customize->add_section(
            'aqualuxe_seo',
            array(
                'title'    => __( 'SEO', 'aqualuxe' ),
                'priority' => 45,
            )
        );

        // Meta tags setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_meta_tags',
            array(
                'default'           => $this->settings['enable_meta_tags'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_meta_tags',
            array(
                'label'    => __( 'Enable Meta Tags', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 10,
            )
        );

        // Schema markup setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_schema_markup',
            array(
                'default'           => $this->settings['enable_schema_markup'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_schema_markup',
            array(
                'label'    => __( 'Enable Schema Markup', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 20,
            )
        );

        // Sitemap setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_sitemap',
            array(
                'default'           => $this->settings['enable_sitemap'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_sitemap',
            array(
                'label'    => __( 'Enable Sitemap', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 30,
            )
        );

        // Breadcrumbs setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_breadcrumbs',
            array(
                'default'           => $this->settings['enable_breadcrumbs'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_breadcrumbs',
            array(
                'label'    => __( 'Enable Breadcrumbs', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 40,
            )
        );

        // Social meta setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_social_meta',
            array(
                'default'           => $this->settings['enable_social_meta'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_social_meta',
            array(
                'label'    => __( 'Enable Social Meta', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 50,
            )
        );

        // Twitter site setting
        $wp_customize->add_setting(
            'aqualuxe_seo_twitter_site',
            array(
                'default'           => $this->settings['twitter_site'],
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_twitter_site',
            array(
                'label'    => __( 'Twitter Username', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'text',
                'priority' => 60,
            )
        );

        // Facebook app ID setting
        $wp_customize->add_setting(
            'aqualuxe_seo_facebook_app_id',
            array(
                'default'           => $this->settings['facebook_app_id'],
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_facebook_app_id',
            array(
                'label'    => __( 'Facebook App ID', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'text',
                'priority' => 70,
            )
        );

        // Default Facebook image setting
        $wp_customize->add_setting(
            'aqualuxe_seo_default_facebook_image',
            array(
                'default'           => $this->settings['default_facebook_image'],
                'type'              => 'option',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_seo_default_facebook_image',
                array(
                    'label'    => __( 'Default Facebook Image', 'aqualuxe' ),
                    'section'  => 'aqualuxe_seo',
                    'priority' => 80,
                )
            )
        );

        // Default Twitter image setting
        $wp_customize->add_setting(
            'aqualuxe_seo_default_twitter_image',
            array(
                'default'           => $this->settings['default_twitter_image'],
                'type'              => 'option',
                'sanitize_callback' => 'esc_url_raw',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'aqualuxe_seo_default_twitter_image',
                array(
                    'label'    => __( 'Default Twitter Image', 'aqualuxe' ),
                    'section'  => 'aqualuxe_seo',
                    'priority' => 90,
                )
            )
        );

        // Robots.txt setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_robots_txt',
            array(
                'default'           => $this->settings['enable_robots_txt'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_robots_txt',
            array(
                'label'    => __( 'Enable Custom Robots.txt', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 100,
            )
        );

        // Noindex archives setting
        $wp_customize->add_setting(
            'aqualuxe_seo_noindex_archives',
            array(
                'default'           => $this->settings['noindex_archives'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_noindex_archives',
            array(
                'label'    => __( 'Noindex Archives', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 110,
            )
        );

        // Noindex search setting
        $wp_customize->add_setting(
            'aqualuxe_seo_noindex_search',
            array(
                'default'           => $this->settings['noindex_search'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_noindex_search',
            array(
                'label'    => __( 'Noindex Search', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 120,
            )
        );

        // Canonical URLs setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_canonical_urls',
            array(
                'default'           => $this->settings['enable_canonical_urls'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_canonical_urls',
            array(
                'label'    => __( 'Enable Canonical URLs', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 130,
            )
        );

        // Auto descriptions setting
        $wp_customize->add_setting(
            'aqualuxe_seo_enable_auto_descriptions',
            array(
                'default'           => $this->settings['enable_auto_descriptions'],
                'type'              => 'option',
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            )
        );

        $wp_customize->add_control(
            'aqualuxe_seo_enable_auto_descriptions',
            array(
                'label'    => __( 'Enable Auto Descriptions', 'aqualuxe' ),
                'section'  => 'aqualuxe_seo',
                'type'     => 'checkbox',
                'priority' => 140,
            )
        );
    }
}