<?php
/**
 * Social Sharing Module
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
 * Social Sharing Module Class
 */
class AquaLuxe_Social_Sharing {

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
        $this->settings = get_option( 'aqualuxe_social_sharing_settings', array(
            'enabled'           => true,
            'post_types'        => array( 'post' ),
            'position'          => 'after_content',
            'style'             => 'buttons',
            'networks'          => array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ),
            'show_share_count'  => true,
            'custom_label'      => __( 'Share this:', 'aqualuxe' ),
            'twitter_username'  => '',
            'facebook_app_id'   => '',
        ) );

        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Skip if social sharing is disabled
        if ( ! $this->settings['enabled'] ) {
            return;
        }

        // Add social sharing buttons to content
        if ( 'after_content' === $this->settings['position'] ) {
            add_filter( 'the_content', array( $this, 'add_sharing_buttons_to_content' ) );
        } elseif ( 'before_content' === $this->settings['position'] ) {
            add_filter( 'the_content', array( $this, 'add_sharing_buttons_before_content' ) );
        } elseif ( 'both' === $this->settings['position'] ) {
            add_filter( 'the_content', array( $this, 'add_sharing_buttons_to_both' ) );
        }

        // Add shortcode
        add_shortcode( 'aqualuxe_social_sharing', array( $this, 'social_sharing_shortcode' ) );

        // Add widget
        add_action( 'widgets_init', array( $this, 'register_widget' ) );

        // Add settings page
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-social-sharing',
            plugin_dir_url( __FILE__ ) . 'assets/css/social-sharing.css',
            array(),
            '1.0.0'
        );

        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-social-sharing',
            plugin_dir_url( __FILE__ ) . 'assets/js/social-sharing.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-social-sharing',
            'aqualuxeSocialSharing',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'shareCountNonce' => wp_create_nonce( 'aqualuxe-share-count-nonce' ),
            )
        );
    }

    /**
     * Add sharing buttons after content
     *
     * @param string $content The post content.
     * @return string Modified content.
     */
    public function add_sharing_buttons_to_content( $content ) {
        // Only add to enabled post types
        if ( ! is_singular( $this->settings['post_types'] ) ) {
            return $content;
        }

        return $content . $this->get_sharing_buttons_html();
    }

    /**
     * Add sharing buttons before content
     *
     * @param string $content The post content.
     * @return string Modified content.
     */
    public function add_sharing_buttons_before_content( $content ) {
        // Only add to enabled post types
        if ( ! is_singular( $this->settings['post_types'] ) ) {
            return $content;
        }

        return $this->get_sharing_buttons_html() . $content;
    }

    /**
     * Add sharing buttons before and after content
     *
     * @param string $content The post content.
     * @return string Modified content.
     */
    public function add_sharing_buttons_to_both( $content ) {
        // Only add to enabled post types
        if ( ! is_singular( $this->settings['post_types'] ) ) {
            return $content;
        }

        return $this->get_sharing_buttons_html() . $content . $this->get_sharing_buttons_html();
    }

    /**
     * Social sharing shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function social_sharing_shortcode( $atts ) {
        $atts = shortcode_atts(
            array(
                'style'           => $this->settings['style'],
                'networks'        => implode( ',', $this->settings['networks'] ),
                'show_count'      => $this->settings['show_share_count'] ? 'true' : 'false',
                'label'           => $this->settings['custom_label'],
                'post_id'         => get_the_ID(),
            ),
            $atts,
            'aqualuxe_social_sharing'
        );

        // Convert networks string to array
        if ( is_string( $atts['networks'] ) ) {
            $atts['networks'] = explode( ',', $atts['networks'] );
        }

        // Convert show_count to boolean
        $atts['show_count'] = filter_var( $atts['show_count'], FILTER_VALIDATE_BOOLEAN );

        return $this->get_sharing_buttons_html( $atts );
    }

    /**
     * Register widget
     */
    public function register_widget() {
        register_widget( 'AquaLuxe_Social_Sharing_Widget' );
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __( 'Social Sharing Settings', 'aqualuxe' ),
            __( 'Social Sharing', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-social-sharing',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'aqualuxe_social_sharing_settings',
            'aqualuxe_social_sharing_settings',
            array( $this, 'sanitize_settings' )
        );

        // General settings section
        add_settings_section(
            'aqualuxe_social_sharing_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'render_general_section' ),
            'aqualuxe-social-sharing'
        );

        // Add settings fields
        add_settings_field(
            'enabled',
            __( 'Enable Social Sharing', 'aqualuxe' ),
            array( $this, 'render_enabled_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'post_types',
            __( 'Post Types', 'aqualuxe' ),
            array( $this, 'render_post_types_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'position',
            __( 'Position', 'aqualuxe' ),
            array( $this, 'render_position_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'style',
            __( 'Style', 'aqualuxe' ),
            array( $this, 'render_style_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'networks',
            __( 'Networks', 'aqualuxe' ),
            array( $this, 'render_networks_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'show_share_count',
            __( 'Show Share Count', 'aqualuxe' ),
            array( $this, 'render_show_share_count_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        add_settings_field(
            'custom_label',
            __( 'Custom Label', 'aqualuxe' ),
            array( $this, 'render_custom_label_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_general'
        );

        // Advanced settings section
        add_settings_section(
            'aqualuxe_social_sharing_advanced',
            __( 'Advanced Settings', 'aqualuxe' ),
            array( $this, 'render_advanced_section' ),
            'aqualuxe-social-sharing'
        );

        add_settings_field(
            'twitter_username',
            __( 'Twitter Username', 'aqualuxe' ),
            array( $this, 'render_twitter_username_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_advanced'
        );

        add_settings_field(
            'facebook_app_id',
            __( 'Facebook App ID', 'aqualuxe' ),
            array( $this, 'render_facebook_app_id_field' ),
            'aqualuxe-social-sharing',
            'aqualuxe_social_sharing_advanced'
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

        // Sanitize enabled
        $sanitized['enabled'] = isset( $input['enabled'] ) ? (bool) $input['enabled'] : false;

        // Sanitize post types
        $sanitized['post_types'] = isset( $input['post_types'] ) && is_array( $input['post_types'] ) ? array_map( 'sanitize_text_field', $input['post_types'] ) : array( 'post' );

        // Sanitize position
        $sanitized['position'] = isset( $input['position'] ) ? sanitize_text_field( $input['position'] ) : 'after_content';

        // Sanitize style
        $sanitized['style'] = isset( $input['style'] ) ? sanitize_text_field( $input['style'] ) : 'buttons';

        // Sanitize networks
        $sanitized['networks'] = isset( $input['networks'] ) && is_array( $input['networks'] ) ? array_map( 'sanitize_text_field', $input['networks'] ) : array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' );

        // Sanitize show share count
        $sanitized['show_share_count'] = isset( $input['show_share_count'] ) ? (bool) $input['show_share_count'] : false;

        // Sanitize custom label
        $sanitized['custom_label'] = isset( $input['custom_label'] ) ? sanitize_text_field( $input['custom_label'] ) : __( 'Share this:', 'aqualuxe' );

        // Sanitize Twitter username
        $sanitized['twitter_username'] = isset( $input['twitter_username'] ) ? sanitize_text_field( $input['twitter_username'] ) : '';

        // Sanitize Facebook App ID
        $sanitized['facebook_app_id'] = isset( $input['facebook_app_id'] ) ? sanitize_text_field( $input['facebook_app_id'] ) : '';

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
                settings_fields( 'aqualuxe_social_sharing_settings' );
                do_settings_sections( 'aqualuxe-social-sharing' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general section
     */
    public function render_general_section() {
        echo '<p>' . esc_html__( 'Configure the general settings for social sharing buttons.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render advanced section
     */
    public function render_advanced_section() {
        echo '<p>' . esc_html__( 'Configure advanced settings for social sharing buttons.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render enabled field
     */
    public function render_enabled_field() {
        $enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_social_sharing_settings[enabled]" value="1" <?php checked( $enabled ); ?> />
            <?php esc_html_e( 'Enable social sharing buttons', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render post types field
     */
    public function render_post_types_field() {
        $post_types = isset( $this->settings['post_types'] ) ? $this->settings['post_types'] : array( 'post' );
        $available_post_types = get_post_types( array( 'public' => true ), 'objects' );

        foreach ( $available_post_types as $post_type ) {
            ?>
            <label>
                <input type="checkbox" name="aqualuxe_social_sharing_settings[post_types][]" value="<?php echo esc_attr( $post_type->name ); ?>" <?php checked( in_array( $post_type->name, $post_types, true ) ); ?> />
                <?php echo esc_html( $post_type->label ); ?>
            </label><br>
            <?php
        }
    }

    /**
     * Render position field
     */
    public function render_position_field() {
        $position = isset( $this->settings['position'] ) ? $this->settings['position'] : 'after_content';
        ?>
        <select name="aqualuxe_social_sharing_settings[position]">
            <option value="after_content" <?php selected( $position, 'after_content' ); ?>><?php esc_html_e( 'After content', 'aqualuxe' ); ?></option>
            <option value="before_content" <?php selected( $position, 'before_content' ); ?>><?php esc_html_e( 'Before content', 'aqualuxe' ); ?></option>
            <option value="both" <?php selected( $position, 'both' ); ?>><?php esc_html_e( 'Before and after content', 'aqualuxe' ); ?></option>
            <option value="manual" <?php selected( $position, 'manual' ); ?>><?php esc_html_e( 'Manual (use shortcode or widget)', 'aqualuxe' ); ?></option>
        </select>
        <?php
    }

    /**
     * Render style field
     */
    public function render_style_field() {
        $style = isset( $this->settings['style'] ) ? $this->settings['style'] : 'buttons';
        ?>
        <select name="aqualuxe_social_sharing_settings[style]">
            <option value="buttons" <?php selected( $style, 'buttons' ); ?>><?php esc_html_e( 'Buttons', 'aqualuxe' ); ?></option>
            <option value="icons" <?php selected( $style, 'icons' ); ?>><?php esc_html_e( 'Icons only', 'aqualuxe' ); ?></option>
            <option value="minimal" <?php selected( $style, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'aqualuxe' ); ?></option>
        </select>
        <?php
    }

    /**
     * Render networks field
     */
    public function render_networks_field() {
        $networks = isset( $this->settings['networks'] ) ? $this->settings['networks'] : array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' );
        $available_networks = $this->get_available_networks();

        foreach ( $available_networks as $network_id => $network_name ) {
            ?>
            <label>
                <input type="checkbox" name="aqualuxe_social_sharing_settings[networks][]" value="<?php echo esc_attr( $network_id ); ?>" <?php checked( in_array( $network_id, $networks, true ) ); ?> />
                <?php echo esc_html( $network_name ); ?>
            </label><br>
            <?php
        }
    }

    /**
     * Render show share count field
     */
    public function render_show_share_count_field() {
        $show_share_count = isset( $this->settings['show_share_count'] ) ? $this->settings['show_share_count'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_social_sharing_settings[show_share_count]" value="1" <?php checked( $show_share_count ); ?> />
            <?php esc_html_e( 'Show share count when available', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render custom label field
     */
    public function render_custom_label_field() {
        $custom_label = isset( $this->settings['custom_label'] ) ? $this->settings['custom_label'] : __( 'Share this:', 'aqualuxe' );
        ?>
        <input type="text" name="aqualuxe_social_sharing_settings[custom_label]" value="<?php echo esc_attr( $custom_label ); ?>" class="regular-text" />
        <?php
    }

    /**
     * Render Twitter username field
     */
    public function render_twitter_username_field() {
        $twitter_username = isset( $this->settings['twitter_username'] ) ? $this->settings['twitter_username'] : '';
        ?>
        <input type="text" name="aqualuxe_social_sharing_settings[twitter_username]" value="<?php echo esc_attr( $twitter_username ); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e( 'Your Twitter username without the @ symbol.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render Facebook App ID field
     */
    public function render_facebook_app_id_field() {
        $facebook_app_id = isset( $this->settings['facebook_app_id'] ) ? $this->settings['facebook_app_id'] : '';
        ?>
        <input type="text" name="aqualuxe_social_sharing_settings[facebook_app_id]" value="<?php echo esc_attr( $facebook_app_id ); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e( 'Your Facebook App ID for share count API.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Get available networks
     *
     * @return array Available networks.
     */
    private function get_available_networks() {
        return array(
            'facebook'  => __( 'Facebook', 'aqualuxe' ),
            'twitter'   => __( 'Twitter', 'aqualuxe' ),
            'linkedin'  => __( 'LinkedIn', 'aqualuxe' ),
            'pinterest' => __( 'Pinterest', 'aqualuxe' ),
            'reddit'    => __( 'Reddit', 'aqualuxe' ),
            'tumblr'    => __( 'Tumblr', 'aqualuxe' ),
            'whatsapp'  => __( 'WhatsApp', 'aqualuxe' ),
            'telegram'  => __( 'Telegram', 'aqualuxe' ),
            'email'     => __( 'Email', 'aqualuxe' ),
            'print'     => __( 'Print', 'aqualuxe' ),
        );
    }

    /**
     * Get sharing buttons HTML
     *
     * @param array $args Arguments.
     * @return string Sharing buttons HTML.
     */
    public function get_sharing_buttons_html( $args = array() ) {
        $defaults = array(
            'style'           => $this->settings['style'],
            'networks'        => $this->settings['networks'],
            'show_count'      => $this->settings['show_share_count'],
            'label'           => $this->settings['custom_label'],
            'post_id'         => get_the_ID(),
        );

        $args = wp_parse_args( $args, $defaults );

        // Get post data
        $post = get_post( $args['post_id'] );
        if ( ! $post ) {
            return '';
        }

        $permalink = get_permalink( $post->ID );
        $title = get_the_title( $post->ID );
        $excerpt = has_excerpt( $post->ID ) ? get_the_excerpt( $post->ID ) : wp_trim_words( $post->post_content, 55, '...' );
        
        // Get featured image
        $image = '';
        if ( has_post_thumbnail( $post->ID ) ) {
            $image_id = get_post_thumbnail_id( $post->ID );
            $image_data = wp_get_attachment_image_src( $image_id, 'large' );
            if ( $image_data ) {
                $image = $image_data[0];
            }
        }

        // Start output
        ob_start();
        ?>
        <div class="aqualuxe-social-sharing aqualuxe-social-sharing--<?php echo esc_attr( $args['style'] ); ?>">
            <?php if ( ! empty( $args['label'] ) ) : ?>
                <span class="aqualuxe-social-sharing__label"><?php echo esc_html( $args['label'] ); ?></span>
            <?php endif; ?>
            
            <div class="aqualuxe-social-sharing__buttons">
                <?php
                foreach ( $args['networks'] as $network ) {
                    $this->render_network_button( $network, $permalink, $title, $excerpt, $image, $args );
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render network button
     *
     * @param string $network  Network ID.
     * @param string $url      URL to share.
     * @param string $title    Title to share.
     * @param string $excerpt  Excerpt to share.
     * @param string $image    Image to share.
     * @param array  $args     Arguments.
     */
    private function render_network_button( $network, $url, $title, $excerpt, $image, $args ) {
        $networks = $this->get_available_networks();
        $network_name = isset( $networks[ $network ] ) ? $networks[ $network ] : '';
        
        if ( empty( $network_name ) ) {
            return;
        }

        $share_url = $this->get_share_url( $network, $url, $title, $excerpt, $image );
        $icon = $this->get_network_icon( $network );
        $count = $args['show_count'] ? $this->get_share_count( $network, $url ) : '';
        $count_html = '';
        
        if ( $count !== '' && $count !== false ) {
            $count_html = '<span class="aqualuxe-social-sharing__count">' . esc_html( $count ) . '</span>';
        }
        
        $target = $network === 'print' || $network === 'email' ? '_self' : '_blank';
        $rel = $target === '_blank' ? 'noopener noreferrer' : '';
        $onclick = $network === 'print' ? 'window.print();return false;' : '';
        
        ?>
        <a href="<?php echo esc_url( $share_url ); ?>" 
           class="aqualuxe-social-sharing__button aqualuxe-social-sharing__button--<?php echo esc_attr( $network ); ?>" 
           title="<?php echo esc_attr( sprintf( __( 'Share on %s', 'aqualuxe' ), $network_name ) ); ?>"
           target="<?php echo esc_attr( $target ); ?>"
           rel="<?php echo esc_attr( $rel ); ?>"
           <?php if ( $onclick ) : ?>onclick="<?php echo esc_attr( $onclick ); ?>"<?php endif; ?>
           data-network="<?php echo esc_attr( $network ); ?>">
            <span class="aqualuxe-social-sharing__icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            <?php if ( $args['style'] === 'buttons' ) : ?>
                <span class="aqualuxe-social-sharing__name"><?php echo esc_html( $network_name ); ?></span>
            <?php endif; ?>
            <?php echo $count_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
        <?php
    }

    /**
     * Get share URL for a network
     *
     * @param string $network Network ID.
     * @param string $url     URL to share.
     * @param string $title   Title to share.
     * @param string $excerpt Excerpt to share.
     * @param string $image   Image to share.
     * @return string Share URL.
     */
    private function get_share_url( $network, $url, $title, $excerpt, $image ) {
        $url = rawurlencode( $url );
        $title = rawurlencode( $title );
        $excerpt = rawurlencode( $excerpt );
        $image = rawurlencode( $image );
        
        switch ( $network ) {
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
                
            case 'twitter':
                $via = ! empty( $this->settings['twitter_username'] ) ? '&via=' . rawurlencode( $this->settings['twitter_username'] ) : '';
                return 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . $via;
                
            case 'linkedin':
                return 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&summary=' . $excerpt;
                
            case 'pinterest':
                return 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $image . '&description=' . $title;
                
            case 'reddit':
                return 'https://reddit.com/submit?url=' . $url . '&title=' . $title;
                
            case 'tumblr':
                return 'https://www.tumblr.com/share/link?url=' . $url . '&name=' . $title . '&description=' . $excerpt;
                
            case 'whatsapp':
                return 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url;
                
            case 'telegram':
                return 'https://telegram.me/share/url?url=' . $url . '&text=' . $title;
                
            case 'email':
                return 'mailto:?subject=' . $title . '&body=' . $excerpt . '%20' . $url;
                
            case 'print':
                return '#print';
                
            default:
                return '';
        }
    }

    /**
     * Get network icon
     *
     * @param string $network Network ID.
     * @return string Network icon HTML.
     */
    private function get_network_icon( $network ) {
        switch ( $network ) {
            case 'facebook':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg>';
                
            case 'twitter':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg>';
                
            case 'linkedin':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path></svg>';
                
            case 'pinterest':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"></path></svg>';
                
            case 'reddit':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M201.5 305.5c-13.8 0-24.9-11.1-24.9-24.6 0-13.8 11.1-24.9 24.9-24.9 13.6 0 24.6 11.1 24.6 24.9 0 13.6-11.1 24.6-24.6 24.6zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-132.3-41.2c-9.4 0-17.7 3.9-23.8 10-22.4-15.5-52.6-25.5-86.1-26.6l17.4-78.3 55.4 12.5c0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.3 24.9-24.9s-11.1-24.9-24.9-24.9c-9.7 0-18 5.8-22.1 13.8l-61.2-13.6c-3-.8-6.1 1.4-6.9 4.4l-19.1 86.4c-33.2 1.4-63.1 11.3-85.5 26.8-6.1-6.4-14.7-10.2-24.1-10.2-34.9 0-46.3 46.9-14.4 62.8-1.1 5-1.7 10.2-1.7 15.5 0 52.6 59.2 95.2 132 95.2 73.1 0 132.3-42.6 132.3-95.2 0-5.3-.6-10.8-1.9-15.8 31.3-16 19.8-62.5-14.9-62.5zM302.8 331c-18.2 18.2-76.1 17.9-93.6 0-2.2-2.2-6.1-2.2-8.3 0-2.5 2.5-2.5 6.4 0 8.6 22.8 22.8 87.3 22.8 110.2 0 2.5-2.2 2.5-6.1 0-8.6-2.2-2.2-6.1-2.2-8.3 0zm7.7-75c-13.6 0-24.6 11.1-24.6 24.9 0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.1 24.9-24.6 0-13.8-11-24.9-24.9-24.9z"></path></svg>';
                
            case 'tumblr':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M309.8 480.3c-13.6 14.5-50 31.7-97.4 31.7-120.8 0-147-88.8-147-140.6v-144H17.9c-5.5 0-10-4.5-10-10v-68c0-7.2 4.5-13.6 11.3-16 62-21.8 81.5-76 84.3-117.1.8-11 6.5-16.3 16.1-16.3h70.9c5.5 0 10 4.5 10 10v115.2h83c5.5 0 10 4.4 10 9.9v81.7c0 5.5-4.5 10-10 10h-83.4V360c0 34.2 23.7 53.6 68 35.8 4.8-1.9 9-3.2 12.7-2.2 3.5.9 5.8 3.4 7.4 7.9l22 64.3c1.8 5 3.3 10.6-.4 14.5z"></path></svg>';
                
            case 'whatsapp':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path></svg>';
                
            case 'telegram':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"></path></svg>';
                
            case 'email':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path></svg>';
                
            case 'print':
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M448 192V77.25c0-8.49-3.37-16.62-9.37-22.63L393.37 9.37c-6-6-14.14-9.37-22.63-9.37H96C78.33 0 64 14.33 64 32v160c-35.35 0-64 28.65-64 64v112c0 8.84 7.16 16 16 16h48v96c0 17.67 14.33 32 32 32h320c17.67 0 32-14.33 32-32v-96h48c8.84 0 16-7.16 16-16V256c0-35.35-28.65-64-64-64zm-64 256H128v-96h256v96zm0-224H128V64h192v48c0 8.84 7.16 16 16 16h48v96zm48 72c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path></svg>';
                
            default:
                return '';
        }
    }

    /**
     * Get share count
     *
     * @param string $network Network ID.
     * @param string $url     URL to get share count for.
     * @return string|false Share count or false if not available.
     */
    private function get_share_count( $network, $url ) {
        // For now, we'll return false as implementing share count APIs requires more complex code
        // and often requires API keys for each service
        return false;
    }
}

/**
 * Social Sharing Widget Class
 */
class AquaLuxe_Social_Sharing_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_sharing_widget',
            __( 'AquaLuxe Social Sharing', 'aqualuxe' ),
            array(
                'description' => __( 'Display social sharing buttons.', 'aqualuxe' ),
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args     Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        $social_sharing = new AquaLuxe_Social_Sharing();
        
        $widget_args = array(
            'style'      => ! empty( $instance['style'] ) ? $instance['style'] : 'buttons',
            'networks'   => ! empty( $instance['networks'] ) ? explode( ',', $instance['networks'] ) : array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ),
            'show_count' => ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : false,
            'label'      => ! empty( $instance['label'] ) ? $instance['label'] : '',
            'post_id'    => ! empty( $instance['post_id'] ) ? (int) $instance['post_id'] : get_the_ID(),
        );

        echo $social_sharing->get_sharing_buttons_html( $widget_args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'buttons';
        $networks = ! empty( $instance['networks'] ) ? $instance['networks'] : 'facebook,twitter,linkedin,pinterest,email';
        $show_count = ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : false;
        $label = ! empty( $instance['label'] ) ? $instance['label'] : __( 'Share this:', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="buttons" <?php selected( $style, 'buttons' ); ?>><?php esc_html_e( 'Buttons', 'aqualuxe' ); ?></option>
                <option value="icons" <?php selected( $style, 'icons' ); ?>><?php esc_html_e( 'Icons only', 'aqualuxe' ); ?></option>
                <option value="minimal" <?php selected( $style, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'networks' ) ); ?>"><?php esc_html_e( 'Networks (comma-separated):', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'networks' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'networks' ) ); ?>" type="text" value="<?php echo esc_attr( $networks ); ?>">
            <small><?php esc_html_e( 'Available: facebook, twitter, linkedin, pinterest, reddit, tumblr, whatsapp, telegram, email, print', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Label:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'label' ) ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Show share count', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array Updated widget instance.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['style'] = ! empty( $new_instance['style'] ) ? sanitize_text_field( $new_instance['style'] ) : 'buttons';
        $instance['networks'] = ! empty( $new_instance['networks'] ) ? sanitize_text_field( $new_instance['networks'] ) : 'facebook,twitter,linkedin,pinterest,email';
        $instance['show_count'] = ! empty( $new_instance['show_count'] ) ? (bool) $new_instance['show_count'] : false;
        $instance['label'] = ! empty( $new_instance['label'] ) ? sanitize_text_field( $new_instance['label'] ) : '';

        return $instance;
    }
}

// Initialize the module
new AquaLuxe_Social_Sharing();