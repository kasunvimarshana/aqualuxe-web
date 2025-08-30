<?php
/**
 * Testimonials Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Testimonials Module Class
 */
class AquaLuxe_Testimonials {

    /**
     * Module settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct() {
        $this->settings = get_option( 'aqualuxe_testimonials_settings', array(
            'autoplay'          => true,
            'autoplay_speed'    => 5000,
            'show_navigation'   => true,
            'show_pagination'   => true,
            'animation_speed'   => 500,
            'pause_on_hover'    => true,
            'enable_schema'     => true,
            'custom_css_class'  => '',
        ) );

        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register post type
        add_action( 'init', array( $this, 'register_testimonial_post_type' ) );
        
        // Register taxonomy
        add_action( 'init', array( $this, 'register_testimonial_taxonomy' ) );
        
        // Register shortcode
        add_shortcode( 'aqualuxe_testimonials', array( $this, 'testimonials_shortcode' ) );
        
        // Register Gutenberg block
        add_action( 'init', array( $this, 'register_block' ) );
        
        // Register settings page
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        
        // Add meta boxes
        add_action( 'add_meta_boxes', array( $this, 'add_testimonial_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_testimonial_meta' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Register testimonial post type
     */
    public function register_testimonial_post_type() {
        $labels = array(
            'name'               => _x( 'Testimonials', 'post type general name', 'aqualuxe' ),
            'singular_name'      => _x( 'Testimonial', 'post type singular name', 'aqualuxe' ),
            'menu_name'          => _x( 'Testimonials', 'admin menu', 'aqualuxe' ),
            'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'aqualuxe' ),
            'add_new'            => _x( 'Add New', 'testimonial', 'aqualuxe' ),
            'add_new_item'       => __( 'Add New Testimonial', 'aqualuxe' ),
            'new_item'           => __( 'New Testimonial', 'aqualuxe' ),
            'edit_item'          => __( 'Edit Testimonial', 'aqualuxe' ),
            'view_item'          => __( 'View Testimonial', 'aqualuxe' ),
            'all_items'          => __( 'All Testimonials', 'aqualuxe' ),
            'search_items'       => __( 'Search Testimonials', 'aqualuxe' ),
            'parent_item_colon'  => __( 'Parent Testimonials:', 'aqualuxe' ),
            'not_found'          => __( 'No testimonials found.', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No testimonials found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'testimonial' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-format-quote',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'aqualuxe_testimonial', $args );
    }

    /**
     * Register testimonial taxonomy
     */
    public function register_testimonial_taxonomy() {
        $labels = array(
            'name'              => _x( 'Testimonial Categories', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search Testimonial Categories', 'aqualuxe' ),
            'all_items'         => __( 'All Testimonial Categories', 'aqualuxe' ),
            'parent_item'       => __( 'Parent Testimonial Category', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent Testimonial Category:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit Testimonial Category', 'aqualuxe' ),
            'update_item'       => __( 'Update Testimonial Category', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New Testimonial Category', 'aqualuxe' ),
            'new_item_name'     => __( 'New Testimonial Category Name', 'aqualuxe' ),
            'menu_name'         => __( 'Categories', 'aqualuxe' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'testimonial-category' ),
            'show_in_rest'      => true,
        );

        register_taxonomy( 'aqualuxe_testimonial_category', array( 'aqualuxe_testimonial' ), $args );
    }

    /**
     * Add testimonial meta boxes
     */
    public function add_testimonial_meta_boxes() {
        add_meta_box(
            'aqualuxe_testimonial_details',
            __( 'Testimonial Details', 'aqualuxe' ),
            array( $this, 'render_testimonial_meta_box' ),
            'aqualuxe_testimonial',
            'normal',
            'high'
        );
    }

    /**
     * Render testimonial meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_testimonial_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'aqualuxe_testimonial_meta_box', 'aqualuxe_testimonial_meta_box_nonce' );

        // Get saved values
        $author_name = get_post_meta( $post->ID, '_testimonial_author_name', true );
        $author_title = get_post_meta( $post->ID, '_testimonial_author_title', true );
        $author_company = get_post_meta( $post->ID, '_testimonial_author_company', true );
        $author_location = get_post_meta( $post->ID, '_testimonial_author_location', true );
        $rating = get_post_meta( $post->ID, '_testimonial_rating', true );
        $date = get_post_meta( $post->ID, '_testimonial_date', true );
        $url = get_post_meta( $post->ID, '_testimonial_url', true );
        ?>
        <table class="form-table">
            <tr>
                <th><label for="testimonial_author_name"><?php esc_html_e( 'Author Name', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="text" id="testimonial_author_name" name="testimonial_author_name" value="<?php echo esc_attr( $author_name ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the name of the person giving the testimonial.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_author_title"><?php esc_html_e( 'Author Title/Position', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="text" id="testimonial_author_title" name="testimonial_author_title" value="<?php echo esc_attr( $author_title ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the title or position of the author.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_author_company"><?php esc_html_e( 'Author Company', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="text" id="testimonial_author_company" name="testimonial_author_company" value="<?php echo esc_attr( $author_company ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the company name of the author.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_author_location"><?php esc_html_e( 'Author Location', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="text" id="testimonial_author_location" name="testimonial_author_location" value="<?php echo esc_attr( $author_location ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the location of the author.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_rating"><?php esc_html_e( 'Rating', 'aqualuxe' ); ?></label></th>
                <td>
                    <select id="testimonial_rating" name="testimonial_rating">
                        <option value=""><?php esc_html_e( 'No Rating', 'aqualuxe' ); ?></option>
                        <option value="5" <?php selected( $rating, '5' ); ?>>5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="4.5" <?php selected( $rating, '4.5' ); ?>>4.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="4" <?php selected( $rating, '4' ); ?>>4 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="3.5" <?php selected( $rating, '3.5' ); ?>>3.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="3" <?php selected( $rating, '3' ); ?>>3 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="2.5" <?php selected( $rating, '2.5' ); ?>>2.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="2" <?php selected( $rating, '2' ); ?>>2 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="1.5" <?php selected( $rating, '1.5' ); ?>>1.5 <?php esc_html_e( 'Stars', 'aqualuxe' ); ?></option>
                        <option value="1" <?php selected( $rating, '1' ); ?>>1 <?php esc_html_e( 'Star', 'aqualuxe' ); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e( 'Select the rating for this testimonial.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="date" id="testimonial_date" name="testimonial_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the date when this testimonial was given.', 'aqualuxe' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="testimonial_url"><?php esc_html_e( 'URL', 'aqualuxe' ); ?></label></th>
                <td>
                    <input type="url" id="testimonial_url" name="testimonial_url" value="<?php echo esc_url( $url ); ?>" class="regular-text">
                    <p class="description"><?php esc_html_e( 'Enter the URL where this testimonial can be verified (optional).', 'aqualuxe' ); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save testimonial meta
     *
     * @param int $post_id Post ID.
     */
    public function save_testimonial_meta( $post_id ) {
        // Check if our nonce is set
        if ( ! isset( $_POST['aqualuxe_testimonial_meta_box_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid
        if ( ! wp_verify_nonce( $_POST['aqualuxe_testimonial_meta_box_nonce'], 'aqualuxe_testimonial_meta_box' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save the meta field values
        if ( isset( $_POST['testimonial_author_name'] ) ) {
            update_post_meta( $post_id, '_testimonial_author_name', sanitize_text_field( $_POST['testimonial_author_name'] ) );
        }

        if ( isset( $_POST['testimonial_author_title'] ) ) {
            update_post_meta( $post_id, '_testimonial_author_title', sanitize_text_field( $_POST['testimonial_author_title'] ) );
        }

        if ( isset( $_POST['testimonial_author_company'] ) ) {
            update_post_meta( $post_id, '_testimonial_author_company', sanitize_text_field( $_POST['testimonial_author_company'] ) );
        }

        if ( isset( $_POST['testimonial_author_location'] ) ) {
            update_post_meta( $post_id, '_testimonial_author_location', sanitize_text_field( $_POST['testimonial_author_location'] ) );
        }

        if ( isset( $_POST['testimonial_rating'] ) ) {
            update_post_meta( $post_id, '_testimonial_rating', sanitize_text_field( $_POST['testimonial_rating'] ) );
        }

        if ( isset( $_POST['testimonial_date'] ) ) {
            update_post_meta( $post_id, '_testimonial_date', sanitize_text_field( $_POST['testimonial_date'] ) );
        }

        if ( isset( $_POST['testimonial_url'] ) ) {
            update_post_meta( $post_id, '_testimonial_url', esc_url_raw( $_POST['testimonial_url'] ) );
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-testimonials',
            plugin_dir_url( __FILE__ ) . 'assets/css/testimonials.css',
            array(),
            '1.0.0'
        );

        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-testimonials',
            plugin_dir_url( __FILE__ ) . 'assets/js/testimonials.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-testimonials',
            'aqualuxeTestimonials',
            array(
                'autoplay'       => (bool) $this->settings['autoplay'],
                'autoplaySpeed'  => absint( $this->settings['autoplay_speed'] ),
                'animationSpeed' => absint( $this->settings['animation_speed'] ),
                'pauseOnHover'   => (bool) $this->settings['pause_on_hover'],
            )
        );
    }

    /**
     * Register Gutenberg block
     */
    public function register_block() {
        // Check if Gutenberg is available
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        // Register block script
        wp_register_script(
            'aqualuxe-testimonials-block',
            plugin_dir_url( __FILE__ ) . 'assets/js/testimonials-block.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
            '1.0.0',
            true
        );

        // Register block
        register_block_type( 'aqualuxe/testimonials', array(
            'editor_script' => 'aqualuxe-testimonials-block',
            'render_callback' => array( $this, 'render_testimonials_block' ),
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'category' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'limit' => array(
                    'type' => 'number',
                    'default' => 5,
                ),
                'orderby' => array(
                    'type' => 'string',
                    'default' => 'date',
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'DESC',
                ),
                'style' => array(
                    'type' => 'string',
                    'default' => 'slider',
                ),
                'columns' => array(
                    'type' => 'number',
                    'default' => 1,
                ),
                'show_image' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'show_rating' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'show_author_info' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'className' => array(
                    'type' => 'string',
                    'default' => '',
                ),
            ),
        ) );
    }

    /**
     * Render testimonials block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_testimonials_block( $attributes ) {
        $shortcode_atts = array(
            'title'            => $attributes['title'],
            'category'         => $attributes['category'],
            'limit'            => $attributes['limit'],
            'orderby'          => $attributes['orderby'],
            'order'            => $attributes['order'],
            'style'            => $attributes['style'],
            'columns'          => $attributes['columns'],
            'show_image'       => $attributes['show_image'] ? 'true' : 'false',
            'show_rating'      => $attributes['show_rating'] ? 'true' : 'false',
            'show_author_info' => $attributes['show_author_info'] ? 'true' : 'false',
            'class'            => $attributes['className'],
        );

        $shortcode = '[aqualuxe_testimonials';
        
        foreach ( $shortcode_atts as $key => $value ) {
            if ( ! empty( $value ) || $value === 'false' ) {
                $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
            }
        }
        
        $shortcode .= ']';
        
        return do_shortcode( $shortcode );
    }

    /**
     * Testimonials shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function testimonials_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'title'            => '',
                'category'         => '',
                'limit'            => 5,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'style'            => 'slider',
                'columns'          => 1,
                'show_image'       => 'true',
                'show_rating'      => 'true',
                'show_author_info' => 'true',
                'class'            => '',
            ),
            $atts,
            'aqualuxe_testimonials'
        );

        // Query testimonials
        $args = array(
            'post_type'      => 'aqualuxe_testimonial',
            'posts_per_page' => intval( $atts['limit'] ),
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );

        // Add category if specified
        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'aqualuxe_testimonial_category',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $atts['category'] ),
                ),
            );
        }

        $testimonials = get_posts( $args );
        
        if ( empty( $testimonials ) ) {
            return '<div class="aqualuxe-testimonials-empty">' . __( 'No testimonials found.', 'aqualuxe' ) . '</div>';
        }
        
        // Generate a unique ID for this testimonial group
        $testimonials_id = 'aqualuxe-testimonials-' . uniqid();
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-testimonials',
            'aqualuxe-testimonials-' . sanitize_html_class( $atts['style'] ),
        );
        
        if ( ! empty( $atts['class'] ) ) {
            $classes[] = sanitize_html_class( $atts['class'] );
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Add columns class for grid style
        if ( $atts['style'] === 'grid' ) {
            $columns = intval( $atts['columns'] );
            if ( $columns < 1 ) {
                $columns = 1;
            } elseif ( $columns > 4 ) {
                $columns = 4;
            }
            $classes[] = 'aqualuxe-testimonials-columns-' . $columns;
        }
        
        // Start output
        ob_start();
        ?>
        <div id="<?php echo esc_attr( $testimonials_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-style="<?php echo esc_attr( $atts['style'] ); ?>">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h3 class="aqualuxe-testimonials-title"><?php echo esc_html( $atts['title'] ); ?></h3>
            <?php endif; ?>
            
            <?php if ( $this->settings['enable_schema'] && $atts['style'] !== 'slider' ) : ?>
                <div itemscope itemtype="https://schema.org/Organization">
                    <div itemprop="review" itemscope itemtype="https://schema.org/Review">
            <?php endif; ?>
            
            <div class="aqualuxe-testimonials-container">
                <?php foreach ( $testimonials as $testimonial ) : ?>
                    <?php
                    // Get testimonial meta
                    $author_name = get_post_meta( $testimonial->ID, '_testimonial_author_name', true );
                    $author_title = get_post_meta( $testimonial->ID, '_testimonial_author_title', true );
                    $author_company = get_post_meta( $testimonial->ID, '_testimonial_author_company', true );
                    $author_location = get_post_meta( $testimonial->ID, '_testimonial_author_location', true );
                    $rating = get_post_meta( $testimonial->ID, '_testimonial_rating', true );
                    $date = get_post_meta( $testimonial->ID, '_testimonial_date', true );
                    $url = get_post_meta( $testimonial->ID, '_testimonial_url', true );
                    
                    // Use post title as author name if not specified
                    if ( empty( $author_name ) ) {
                        $author_name = $testimonial->post_title;
                    }
                    ?>
                    <div class="aqualuxe-testimonial">
                        <?php if ( $this->settings['enable_schema'] && $atts['style'] === 'slider' ) : ?>
                            <div itemscope itemtype="https://schema.org/Review">
                        <?php endif; ?>
                        
                        <div class="aqualuxe-testimonial-content">
                            <?php if ( filter_var( $atts['show_rating'], FILTER_VALIDATE_BOOLEAN ) && ! empty( $rating ) ) : ?>
                                <div class="aqualuxe-testimonial-rating" <?php if ( $this->settings['enable_schema'] ) : ?>itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating"<?php endif; ?>>
                                    <?php
                                    $rating_value = floatval( $rating );
                                    $full_stars = floor( $rating_value );
                                    $half_star = ( $rating_value - $full_stars ) >= 0.5;
                                    $empty_stars = 5 - $full_stars - ( $half_star ? 1 : 0 );
                                    
                                    // Output full stars
                                    for ( $i = 0; $i < $full_stars; $i++ ) {
                                        echo '<span class="star star-full">★</span>';
                                    }
                                    
                                    // Output half star if needed
                                    if ( $half_star ) {
                                        echo '<span class="star star-half">★</span>';
                                    }
                                    
                                    // Output empty stars
                                    for ( $i = 0; $i < $empty_stars; $i++ ) {
                                        echo '<span class="star star-empty">☆</span>';
                                    }
                                    
                                    if ( $this->settings['enable_schema'] ) :
                                    ?>
                                        <meta itemprop="ratingValue" content="<?php echo esc_attr( $rating_value ); ?>">
                                        <meta itemprop="bestRating" content="5">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="aqualuxe-testimonial-text" <?php if ( $this->settings['enable_schema'] ) : ?>itemprop="reviewBody"<?php endif; ?>>
                                <?php echo wpautop( $testimonial->post_content ); ?>
                            </div>
                        </div>
                        
                        <div class="aqualuxe-testimonial-author">
                            <?php if ( filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN ) && has_post_thumbnail( $testimonial->ID ) ) : ?>
                                <div class="aqualuxe-testimonial-author-image">
                                    <?php echo get_the_post_thumbnail( $testimonial->ID, 'thumbnail', array( 'class' => 'aqualuxe-testimonial-avatar' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( filter_var( $atts['show_author_info'], FILTER_VALIDATE_BOOLEAN ) ) : ?>
                                <div class="aqualuxe-testimonial-author-info">
                                    <div class="aqualuxe-testimonial-author-name" <?php if ( $this->settings['enable_schema'] ) : ?>itemprop="author" itemscope itemtype="https://schema.org/Person"<?php endif; ?>>
                                        <?php if ( ! empty( $url ) ) : ?>
                                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" <?php if ( $this->settings['enable_schema'] ) : ?>itemprop="name"<?php endif; ?>><?php echo esc_html( $author_name ); ?></a>
                                        <?php else : ?>
                                            <span <?php if ( $this->settings['enable_schema'] ) : ?>itemprop="name"<?php endif; ?>><?php echo esc_html( $author_name ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ( ! empty( $author_title ) || ! empty( $author_company ) ) : ?>
                                        <div class="aqualuxe-testimonial-author-title">
                                            <?php
                                            $title_parts = array();
                                            
                                            if ( ! empty( $author_title ) ) {
                                                $title_parts[] = $author_title;
                                            }
                                            
                                            if ( ! empty( $author_company ) ) {
                                                $title_parts[] = $author_company;
                                            }
                                            
                                            echo esc_html( implode( ', ', $title_parts ) );
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $author_location ) ) : ?>
                                        <div class="aqualuxe-testimonial-author-location">
                                            <?php echo esc_html( $author_location ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $date ) && $this->settings['enable_schema'] ) : ?>
                                        <meta itemprop="datePublished" content="<?php echo esc_attr( $date ); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ( $this->settings['enable_schema'] && $atts['style'] === 'slider' ) : ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ( $this->settings['enable_schema'] && $atts['style'] !== 'slider' ) : ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ( $atts['style'] === 'slider' && $this->settings['show_navigation'] ) : ?>
                <div class="aqualuxe-testimonials-nav">
                    <button class="aqualuxe-testimonials-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'aqualuxe' ); ?>">
                        <span class="aqualuxe-testimonials-nav-icon prev-icon"></span>
                    </button>
                    <button class="aqualuxe-testimonials-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'aqualuxe' ); ?>">
                        <span class="aqualuxe-testimonials-nav-icon next-icon"></span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ( $atts['style'] === 'slider' && $this->settings['show_pagination'] ) : ?>
                <div class="aqualuxe-testimonials-dots">
                    <?php for ( $i = 0; $i < count( $testimonials ); $i++ ) : ?>
                        <button class="aqualuxe-testimonials-dot<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo esc_attr( $i ); ?>" aria-label="<?php printf( esc_attr__( 'Go to testimonial %d', 'aqualuxe' ), $i + 1 ); ?>"></button>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __( 'Testimonials Settings', 'aqualuxe' ),
            __( 'Testimonials', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-testimonials',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'aqualuxe_testimonials_settings',
            'aqualuxe_testimonials_settings',
            array( $this, 'sanitize_settings' )
        );

        // General settings section
        add_settings_section(
            'aqualuxe_testimonials_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'render_general_section' ),
            'aqualuxe-testimonials'
        );

        // Add settings fields
        add_settings_field(
            'autoplay',
            __( 'Autoplay Slider', 'aqualuxe' ),
            array( $this, 'render_autoplay_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'autoplay_speed',
            __( 'Autoplay Speed (ms)', 'aqualuxe' ),
            array( $this, 'render_autoplay_speed_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'show_navigation',
            __( 'Show Navigation Arrows', 'aqualuxe' ),
            array( $this, 'render_show_navigation_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'show_pagination',
            __( 'Show Pagination Dots', 'aqualuxe' ),
            array( $this, 'render_show_pagination_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'animation_speed',
            __( 'Animation Speed (ms)', 'aqualuxe' ),
            array( $this, 'render_animation_speed_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'pause_on_hover',
            __( 'Pause on Hover', 'aqualuxe' ),
            array( $this, 'render_pause_on_hover_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'enable_schema',
            __( 'Enable Schema Markup', 'aqualuxe' ),
            array( $this, 'render_enable_schema_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );

        add_settings_field(
            'custom_css_class',
            __( 'Custom CSS Class', 'aqualuxe' ),
            array( $this, 'render_custom_css_class_field' ),
            'aqualuxe-testimonials',
            'aqualuxe_testimonials_general'
        );
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input.
     * @return array Sanitized settings.
     */
    public function sanitize_settings( $input ) {
        $sanitized = array();

        // Sanitize autoplay
        $sanitized['autoplay'] = isset( $input['autoplay'] ) ? (bool) $input['autoplay'] : false;

        // Sanitize autoplay speed
        $sanitized['autoplay_speed'] = isset( $input['autoplay_speed'] ) ? absint( $input['autoplay_speed'] ) : 5000;

        // Sanitize show navigation
        $sanitized['show_navigation'] = isset( $input['show_navigation'] ) ? (bool) $input['show_navigation'] : true;

        // Sanitize show pagination
        $sanitized['show_pagination'] = isset( $input['show_pagination'] ) ? (bool) $input['show_pagination'] : true;

        // Sanitize animation speed
        $sanitized['animation_speed'] = isset( $input['animation_speed'] ) ? absint( $input['animation_speed'] ) : 500;

        // Sanitize pause on hover
        $sanitized['pause_on_hover'] = isset( $input['pause_on_hover'] ) ? (bool) $input['pause_on_hover'] : true;

        // Sanitize enable schema
        $sanitized['enable_schema'] = isset( $input['enable_schema'] ) ? (bool) $input['enable_schema'] : true;

        // Sanitize custom CSS class
        $sanitized['custom_css_class'] = isset( $input['custom_css_class'] ) ? sanitize_html_class( $input['custom_css_class'] ) : '';

        return $sanitized;
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_testimonials_settings' );
                do_settings_sections( 'aqualuxe-testimonials' );
                submit_button();
                ?>
            </form>
            
            <div class="aqualuxe-testimonials-usage">
                <h2><?php esc_html_e( 'Usage', 'aqualuxe' ); ?></h2>
                <h3><?php esc_html_e( 'Shortcode', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You can use the following shortcode to display testimonials:', 'aqualuxe' ); ?></p>
                <pre>[aqualuxe_testimonials title="What Our Clients Say" category="featured" limit="5" style="slider" columns="1" show_image="true" show_rating="true" show_author_info="true"]</pre>
                
                <h3><?php esc_html_e( 'Available Styles', 'aqualuxe' ); ?></h3>
                <ul>
                    <li><code>slider</code> - <?php esc_html_e( 'Carousel slider style', 'aqualuxe' ); ?></li>
                    <li><code>grid</code> - <?php esc_html_e( 'Grid layout with multiple columns', 'aqualuxe' ); ?></li>
                    <li><code>list</code> - <?php esc_html_e( 'Simple list style', 'aqualuxe' ); ?></li>
                    <li><code>masonry</code> - <?php esc_html_e( 'Masonry grid layout', 'aqualuxe' ); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render general section
     */
    public function render_general_section() {
        echo '<p>' . esc_html__( 'Configure the general settings for testimonials.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render autoplay field
     */
    public function render_autoplay_field() {
        $autoplay = isset( $this->settings['autoplay'] ) ? $this->settings['autoplay'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_testimonials_settings[autoplay]" value="1" <?php checked( $autoplay ); ?> />
            <?php esc_html_e( 'Automatically slide through testimonials', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render autoplay speed field
     */
    public function render_autoplay_speed_field() {
        $autoplay_speed = isset( $this->settings['autoplay_speed'] ) ? absint( $this->settings['autoplay_speed'] ) : 5000;
        ?>
        <input type="number" name="aqualuxe_testimonials_settings[autoplay_speed]" value="<?php echo esc_attr( $autoplay_speed ); ?>" min="1000" max="10000" step="500" />
        <p class="description"><?php esc_html_e( 'Time between slides in milliseconds.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render show navigation field
     */
    public function render_show_navigation_field() {
        $show_navigation = isset( $this->settings['show_navigation'] ) ? $this->settings['show_navigation'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_testimonials_settings[show_navigation]" value="1" <?php checked( $show_navigation ); ?> />
            <?php esc_html_e( 'Show previous/next navigation arrows', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render show pagination field
     */
    public function render_show_pagination_field() {
        $show_pagination = isset( $this->settings['show_pagination'] ) ? $this->settings['show_pagination'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_testimonials_settings[show_pagination]" value="1" <?php checked( $show_pagination ); ?> />
            <?php esc_html_e( 'Show pagination dots', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render animation speed field
     */
    public function render_animation_speed_field() {
        $animation_speed = isset( $this->settings['animation_speed'] ) ? absint( $this->settings['animation_speed'] ) : 500;
        ?>
        <input type="number" name="aqualuxe_testimonials_settings[animation_speed]" value="<?php echo esc_attr( $animation_speed ); ?>" min="100" max="2000" step="100" />
        <p class="description"><?php esc_html_e( 'Speed of the slide animation in milliseconds.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render pause on hover field
     */
    public function render_pause_on_hover_field() {
        $pause_on_hover = isset( $this->settings['pause_on_hover'] ) ? $this->settings['pause_on_hover'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_testimonials_settings[pause_on_hover]" value="1" <?php checked( $pause_on_hover ); ?> />
            <?php esc_html_e( 'Pause autoplay when hovering over the slider', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render enable schema field
     */
    public function render_enable_schema_field() {
        $enable_schema = isset( $this->settings['enable_schema'] ) ? $this->settings['enable_schema'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_testimonials_settings[enable_schema]" value="1" <?php checked( $enable_schema ); ?> />
            <?php esc_html_e( 'Add schema.org review markup for better SEO', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'This adds structured data that may help with rich results in search engines.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render custom CSS class field
     */
    public function render_custom_css_class_field() {
        $custom_css_class = isset( $this->settings['custom_css_class'] ) ? $this->settings['custom_css_class'] : '';
        ?>
        <input type="text" name="aqualuxe_testimonials_settings[custom_css_class]" value="<?php echo esc_attr( $custom_css_class ); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e( 'Add a custom CSS class to all testimonial displays.', 'aqualuxe' ); ?></p>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Testimonials();