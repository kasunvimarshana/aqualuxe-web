<?php
/**
 * Theme Options for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Add theme options page
 */
function aqualuxe_add_theme_options_page() {
    add_theme_page(
        __( 'AquaLuxe Options', 'aqualuxe' ),
        __( 'AquaLuxe Options', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-options',
        'aqualuxe_theme_options_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_add_theme_options_page' );

/**
 * Register theme options settings
 */
function aqualuxe_register_theme_options() {
    register_setting( 'aqualuxe_options', 'aqualuxe_theme_options', 'aqualuxe_validate_theme_options' );
    
    // General Settings
    add_settings_section(
        'aqualuxe_general_settings',
        __( 'General Settings', 'aqualuxe' ),
        'aqualuxe_general_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_logo',
        __( 'Logo', 'aqualuxe' ),
        'aqualuxe_logo_field',
        'aqualuxe-options',
        'aqualuxe_general_settings'
    );
    
    add_settings_field(
        'aqualuxe_favicon',
        __( 'Favicon', 'aqualuxe' ),
        'aqualuxe_favicon_field',
        'aqualuxe-options',
        'aqualuxe_general_settings'
    );
    
    add_settings_field(
        'aqualuxe_preloader',
        __( 'Preloader', 'aqualuxe' ),
        'aqualuxe_preloader_field',
        'aqualuxe-options',
        'aqualuxe_general_settings'
    );
    
    add_settings_field(
        'aqualuxe_back_to_top',
        __( 'Back to Top Button', 'aqualuxe' ),
        'aqualuxe_back_to_top_field',
        'aqualuxe-options',
        'aqualuxe_general_settings'
    );
    
    // Header Settings
    add_settings_section(
        'aqualuxe_header_settings',
        __( 'Header Settings', 'aqualuxe' ),
        'aqualuxe_header_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_header_style',
        __( 'Header Style', 'aqualuxe' ),
        'aqualuxe_header_style_field',
        'aqualuxe-options',
        'aqualuxe_header_settings'
    );
    
    add_settings_field(
        'aqualuxe_sticky_header',
        __( 'Sticky Header', 'aqualuxe' ),
        'aqualuxe_sticky_header_field',
        'aqualuxe-options',
        'aqualuxe_header_settings'
    );
    
    add_settings_field(
        'aqualuxe_header_contact_info',
        __( 'Contact Information', 'aqualuxe' ),
        'aqualuxe_header_contact_info_field',
        'aqualuxe-options',
        'aqualuxe_header_settings'
    );
    
    add_settings_field(
        'aqualuxe_header_button',
        __( 'Header Button', 'aqualuxe' ),
        'aqualuxe_header_button_field',
        'aqualuxe-options',
        'aqualuxe_header_settings'
    );
    
    // Footer Settings
    add_settings_section(
        'aqualuxe_footer_settings',
        __( 'Footer Settings', 'aqualuxe' ),
        'aqualuxe_footer_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_footer_style',
        __( 'Footer Style', 'aqualuxe' ),
        'aqualuxe_footer_style_field',
        'aqualuxe-options',
        'aqualuxe_footer_settings'
    );
    
    add_settings_field(
        'aqualuxe_footer_widgets',
        __( 'Footer Widgets', 'aqualuxe' ),
        'aqualuxe_footer_widgets_field',
        'aqualuxe-options',
        'aqualuxe_footer_settings'
    );
    
    add_settings_field(
        'aqualuxe_footer_copyright',
        __( 'Copyright Text', 'aqualuxe' ),
        'aqualuxe_footer_copyright_field',
        'aqualuxe-options',
        'aqualuxe_footer_settings'
    );
    
    add_settings_field(
        'aqualuxe_footer_payment_icons',
        __( 'Payment Icons', 'aqualuxe' ),
        'aqualuxe_footer_payment_icons_field',
        'aqualuxe-options',
        'aqualuxe_footer_settings'
    );
    
    // Blog Settings
    add_settings_section(
        'aqualuxe_blog_settings',
        __( 'Blog Settings', 'aqualuxe' ),
        'aqualuxe_blog_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_blog_layout',
        __( 'Blog Layout', 'aqualuxe' ),
        'aqualuxe_blog_layout_field',
        'aqualuxe-options',
        'aqualuxe_blog_settings'
    );
    
    add_settings_field(
        'aqualuxe_blog_sidebar',
        __( 'Blog Sidebar', 'aqualuxe' ),
        'aqualuxe_blog_sidebar_field',
        'aqualuxe-options',
        'aqualuxe_blog_settings'
    );
    
    add_settings_field(
        'aqualuxe_blog_meta',
        __( 'Post Meta', 'aqualuxe' ),
        'aqualuxe_blog_meta_field',
        'aqualuxe-options',
        'aqualuxe_blog_settings'
    );
    
    add_settings_field(
        'aqualuxe_blog_excerpt_length',
        __( 'Excerpt Length', 'aqualuxe' ),
        'aqualuxe_blog_excerpt_length_field',
        'aqualuxe-options',
        'aqualuxe_blog_settings'
    );
    
    // WooCommerce Settings
    if ( class_exists( 'WooCommerce' ) ) {
        add_settings_section(
            'aqualuxe_woocommerce_settings',
            __( 'WooCommerce Settings', 'aqualuxe' ),
            'aqualuxe_woocommerce_settings_section',
            'aqualuxe-options'
        );
        
        add_settings_field(
            'aqualuxe_shop_layout',
            __( 'Shop Layout', 'aqualuxe' ),
            'aqualuxe_shop_layout_field',
            'aqualuxe-options',
            'aqualuxe_woocommerce_settings'
        );
        
        add_settings_field(
            'aqualuxe_shop_sidebar',
            __( 'Shop Sidebar', 'aqualuxe' ),
            'aqualuxe_shop_sidebar_field',
            'aqualuxe-options',
            'aqualuxe_woocommerce_settings'
        );
        
        add_settings_field(
            'aqualuxe_products_per_page',
            __( 'Products Per Page', 'aqualuxe' ),
            'aqualuxe_products_per_page_field',
            'aqualuxe-options',
            'aqualuxe_woocommerce_settings'
        );
        
        add_settings_field(
            'aqualuxe_products_per_row',
            __( 'Products Per Row', 'aqualuxe' ),
            'aqualuxe_products_per_row_field',
            'aqualuxe-options',
            'aqualuxe_woocommerce_settings'
        );
        
        add_settings_field(
            'aqualuxe_product_features',
            __( 'Product Features', 'aqualuxe' ),
            'aqualuxe_product_features_field',
            'aqualuxe-options',
            'aqualuxe_woocommerce_settings'
        );
    }
    
    // Social Media Settings
    add_settings_section(
        'aqualuxe_social_settings',
        __( 'Social Media Settings', 'aqualuxe' ),
        'aqualuxe_social_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_social_links',
        __( 'Social Links', 'aqualuxe' ),
        'aqualuxe_social_links_field',
        'aqualuxe-options',
        'aqualuxe_social_settings'
    );
    
    add_settings_field(
        'aqualuxe_social_sharing',
        __( 'Social Sharing', 'aqualuxe' ),
        'aqualuxe_social_sharing_field',
        'aqualuxe-options',
        'aqualuxe_social_settings'
    );
    
    // Custom Code Settings
    add_settings_section(
        'aqualuxe_custom_code_settings',
        __( 'Custom Code Settings', 'aqualuxe' ),
        'aqualuxe_custom_code_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_custom_css',
        __( 'Custom CSS', 'aqualuxe' ),
        'aqualuxe_custom_css_field',
        'aqualuxe-options',
        'aqualuxe_custom_code_settings'
    );
    
    add_settings_field(
        'aqualuxe_custom_js',
        __( 'Custom JavaScript', 'aqualuxe' ),
        'aqualuxe_custom_js_field',
        'aqualuxe-options',
        'aqualuxe_custom_code_settings'
    );
    
    add_settings_field(
        'aqualuxe_header_code',
        __( 'Header Code', 'aqualuxe' ),
        'aqualuxe_header_code_field',
        'aqualuxe-options',
        'aqualuxe_custom_code_settings'
    );
    
    add_settings_field(
        'aqualuxe_footer_code',
        __( 'Footer Code', 'aqualuxe' ),
        'aqualuxe_footer_code_field',
        'aqualuxe-options',
        'aqualuxe_custom_code_settings'
    );
    
    // Performance Settings
    add_settings_section(
        'aqualuxe_performance_settings',
        __( 'Performance Settings', 'aqualuxe' ),
        'aqualuxe_performance_settings_section',
        'aqualuxe-options'
    );
    
    add_settings_field(
        'aqualuxe_minify_assets',
        __( 'Minify Assets', 'aqualuxe' ),
        'aqualuxe_minify_assets_field',
        'aqualuxe-options',
        'aqualuxe_performance_settings'
    );
    
    add_settings_field(
        'aqualuxe_lazy_loading',
        __( 'Lazy Loading', 'aqualuxe' ),
        'aqualuxe_lazy_loading_field',
        'aqualuxe-options',
        'aqualuxe_performance_settings'
    );
    
    add_settings_field(
        'aqualuxe_preload_assets',
        __( 'Preload Assets', 'aqualuxe' ),
        'aqualuxe_preload_assets_field',
        'aqualuxe-options',
        'aqualuxe_performance_settings'
    );
    
    add_settings_field(
        'aqualuxe_browser_caching',
        __( 'Browser Caching', 'aqualuxe' ),
        'aqualuxe_browser_caching_field',
        'aqualuxe-options',
        'aqualuxe_performance_settings'
    );
}
add_action( 'admin_init', 'aqualuxe_register_theme_options' );

/**
 * Theme options page content
 */
function aqualuxe_theme_options_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'AquaLuxe Theme Options', 'aqualuxe' ); ?></h1>
        
        <?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Theme options updated successfully!', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="post" action="options.php">
            <?php
            settings_fields( 'aqualuxe_options' );
            
            // Get the active tab from the $_GET param
            $default_tab = 'general';
            $tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab;
            
            // Define tabs
            $tabs = array(
                'general'     => __( 'General', 'aqualuxe' ),
                'header'      => __( 'Header', 'aqualuxe' ),
                'footer'      => __( 'Footer', 'aqualuxe' ),
                'blog'        => __( 'Blog', 'aqualuxe' ),
                'social'      => __( 'Social Media', 'aqualuxe' ),
                'custom_code' => __( 'Custom Code', 'aqualuxe' ),
                'performance' => __( 'Performance', 'aqualuxe' ),
            );
            
            // Add WooCommerce tab if active
            if ( class_exists( 'WooCommerce' ) ) {
                $tabs['woocommerce'] = __( 'WooCommerce', 'aqualuxe' );
            }
            ?>
            
            <div class="nav-tab-wrapper">
                <?php foreach ( $tabs as $tab_id => $tab_name ) : ?>
                    <a href="?page=aqualuxe-options&tab=<?php echo esc_attr( $tab_id ); ?>" class="nav-tab <?php echo $tab === $tab_id ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html( $tab_name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="tab-content">
                <?php
                switch ( $tab ) {
                    case 'general':
                        do_settings_sections( 'aqualuxe-options' );
                        break;
                    case 'header':
                        do_settings_sections( 'aqualuxe-header-options' );
                        break;
                    case 'footer':
                        do_settings_sections( 'aqualuxe-footer-options' );
                        break;
                    case 'blog':
                        do_settings_sections( 'aqualuxe-blog-options' );
                        break;
                    case 'woocommerce':
                        do_settings_sections( 'aqualuxe-woocommerce-options' );
                        break;
                    case 'social':
                        do_settings_sections( 'aqualuxe-social-options' );
                        break;
                    case 'custom_code':
                        do_settings_sections( 'aqualuxe-custom-code-options' );
                        break;
                    case 'performance':
                        do_settings_sections( 'aqualuxe-performance-options' );
                        break;
                    default:
                        do_settings_sections( 'aqualuxe-options' );
                        break;
                }
                ?>
            </div>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * General settings section callback
 */
function aqualuxe_general_settings_section() {
    echo '<p>' . esc_html__( 'Configure general theme settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Logo field callback
 */
function aqualuxe_logo_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $logo = isset( $options['logo'] ) ? $options['logo'] : '';
    ?>
    <div class="media-uploader">
        <input type="text" name="aqualuxe_theme_options[logo]" id="aqualuxe-logo" class="regular-text" value="<?php echo esc_url( $logo ); ?>" />
        <input type="button" class="button button-secondary" id="aqualuxe-logo-button" value="<?php esc_attr_e( 'Upload Logo', 'aqualuxe' ); ?>" />
        
        <?php if ( ! empty( $logo ) ) : ?>
            <div class="image-preview">
                <img src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr_e( 'Logo Preview', 'aqualuxe' ); ?>" />
            </div>
        <?php endif; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Upload your logo image. Recommended size: 200x60px.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Favicon field callback
 */
function aqualuxe_favicon_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $favicon = isset( $options['favicon'] ) ? $options['favicon'] : '';
    ?>
    <div class="media-uploader">
        <input type="text" name="aqualuxe_theme_options[favicon]" id="aqualuxe-favicon" class="regular-text" value="<?php echo esc_url( $favicon ); ?>" />
        <input type="button" class="button button-secondary" id="aqualuxe-favicon-button" value="<?php esc_attr_e( 'Upload Favicon', 'aqualuxe' ); ?>" />
        
        <?php if ( ! empty( $favicon ) ) : ?>
            <div class="image-preview">
                <img src="<?php echo esc_url( $favicon ); ?>" alt="<?php esc_attr_e( 'Favicon Preview', 'aqualuxe' ); ?>" />
            </div>
        <?php endif; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Upload your favicon image. Recommended size: 32x32px.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Preloader field callback
 */
function aqualuxe_preloader_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $preloader = isset( $options['preloader'] ) ? $options['preloader'] : false;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[preloader]" value="1" <?php checked( $preloader, true ); ?> />
        <?php esc_html_e( 'Enable preloader animation', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Show a loading animation while the page is loading.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Back to top button field callback
 */
function aqualuxe_back_to_top_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $back_to_top = isset( $options['back_to_top'] ) ? $options['back_to_top'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[back_to_top]" value="1" <?php checked( $back_to_top, true ); ?> />
        <?php esc_html_e( 'Enable back to top button', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Show a button that allows users to scroll back to the top of the page.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Header settings section callback
 */
function aqualuxe_header_settings_section() {
    echo '<p>' . esc_html__( 'Configure header settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Header style field callback
 */
function aqualuxe_header_style_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $header_style = isset( $options['header_style'] ) ? $options['header_style'] : 'default';
    ?>
    <select name="aqualuxe_theme_options[header_style]">
        <option value="default" <?php selected( $header_style, 'default' ); ?>><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
        <option value="centered" <?php selected( $header_style, 'centered' ); ?>><?php esc_html_e( 'Centered', 'aqualuxe' ); ?></option>
        <option value="transparent" <?php selected( $header_style, 'transparent' ); ?>><?php esc_html_e( 'Transparent', 'aqualuxe' ); ?></option>
        <option value="minimal" <?php selected( $header_style, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the header layout style.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Sticky header field callback
 */
function aqualuxe_sticky_header_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $sticky_header = isset( $options['sticky_header'] ) ? $options['sticky_header'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[sticky_header]" value="1" <?php checked( $sticky_header, true ); ?> />
        <?php esc_html_e( 'Enable sticky header', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Keep the header fixed at the top when scrolling.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Header contact info field callback
 */
function aqualuxe_header_contact_info_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $phone = isset( $options['header_phone'] ) ? $options['header_phone'] : '';
    $email = isset( $options['header_email'] ) ? $options['header_email'] : '';
    $address = isset( $options['header_address'] ) ? $options['header_address'] : '';
    ?>
    <div class="contact-info-fields">
        <p>
            <label for="aqualuxe-header-phone"><?php esc_html_e( 'Phone Number:', 'aqualuxe' ); ?></label>
            <input type="text" name="aqualuxe_theme_options[header_phone]" id="aqualuxe-header-phone" class="regular-text" value="<?php echo esc_attr( $phone ); ?>" />
        </p>
        <p>
            <label for="aqualuxe-header-email"><?php esc_html_e( 'Email Address:', 'aqualuxe' ); ?></label>
            <input type="email" name="aqualuxe_theme_options[header_email]" id="aqualuxe-header-email" class="regular-text" value="<?php echo esc_attr( $email ); ?>" />
        </p>
        <p>
            <label for="aqualuxe-header-address"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
            <input type="text" name="aqualuxe_theme_options[header_address]" id="aqualuxe-header-address" class="regular-text" value="<?php echo esc_attr( $address ); ?>" />
        </p>
    </div>
    <p class="description"><?php esc_html_e( 'Enter your contact information to display in the header.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Header button field callback
 */
function aqualuxe_header_button_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $button_text = isset( $options['header_button_text'] ) ? $options['header_button_text'] : '';
    $button_url = isset( $options['header_button_url'] ) ? $options['header_button_url'] : '';
    $button_style = isset( $options['header_button_style'] ) ? $options['header_button_style'] : 'primary';
    ?>
    <div class="header-button-fields">
        <p>
            <label for="aqualuxe-header-button-text"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input type="text" name="aqualuxe_theme_options[header_button_text]" id="aqualuxe-header-button-text" class="regular-text" value="<?php echo esc_attr( $button_text ); ?>" />
        </p>
        <p>
            <label for="aqualuxe-header-button-url"><?php esc_html_e( 'Button URL:', 'aqualuxe' ); ?></label>
            <input type="url" name="aqualuxe_theme_options[header_button_url]" id="aqualuxe-header-button-url" class="regular-text" value="<?php echo esc_url( $button_url ); ?>" />
        </p>
        <p>
            <label for="aqualuxe-header-button-style"><?php esc_html_e( 'Button Style:', 'aqualuxe' ); ?></label>
            <select name="aqualuxe_theme_options[header_button_style]" id="aqualuxe-header-button-style">
                <option value="primary" <?php selected( $button_style, 'primary' ); ?>><?php esc_html_e( 'Primary', 'aqualuxe' ); ?></option>
                <option value="secondary" <?php selected( $button_style, 'secondary' ); ?>><?php esc_html_e( 'Secondary', 'aqualuxe' ); ?></option>
                <option value="outline" <?php selected( $button_style, 'outline' ); ?>><?php esc_html_e( 'Outline', 'aqualuxe' ); ?></option>
            </select>
        </p>
    </div>
    <p class="description"><?php esc_html_e( 'Configure the header call-to-action button.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Footer settings section callback
 */
function aqualuxe_footer_settings_section() {
    echo '<p>' . esc_html__( 'Configure footer settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Footer style field callback
 */
function aqualuxe_footer_style_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $footer_style = isset( $options['footer_style'] ) ? $options['footer_style'] : 'default';
    ?>
    <select name="aqualuxe_theme_options[footer_style]">
        <option value="default" <?php selected( $footer_style, 'default' ); ?>><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
        <option value="centered" <?php selected( $footer_style, 'centered' ); ?>><?php esc_html_e( 'Centered', 'aqualuxe' ); ?></option>
        <option value="minimal" <?php selected( $footer_style, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'aqualuxe' ); ?></option>
        <option value="dark" <?php selected( $footer_style, 'dark' ); ?>><?php esc_html_e( 'Dark', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the footer layout style.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Footer widgets field callback
 */
function aqualuxe_footer_widgets_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $footer_widgets = isset( $options['footer_widgets'] ) ? $options['footer_widgets'] : '4';
    ?>
    <select name="aqualuxe_theme_options[footer_widgets]">
        <option value="0" <?php selected( $footer_widgets, '0' ); ?>><?php esc_html_e( 'No Widgets', 'aqualuxe' ); ?></option>
        <option value="1" <?php selected( $footer_widgets, '1' ); ?>><?php esc_html_e( '1 Column', 'aqualuxe' ); ?></option>
        <option value="2" <?php selected( $footer_widgets, '2' ); ?>><?php esc_html_e( '2 Columns', 'aqualuxe' ); ?></option>
        <option value="3" <?php selected( $footer_widgets, '3' ); ?>><?php esc_html_e( '3 Columns', 'aqualuxe' ); ?></option>
        <option value="4" <?php selected( $footer_widgets, '4' ); ?>><?php esc_html_e( '4 Columns', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the number of widget columns to display in the footer.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Footer copyright field callback
 */
function aqualuxe_footer_copyright_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $copyright = isset( $options['footer_copyright'] ) ? $options['footer_copyright'] : sprintf( __( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) );
    ?>
    <textarea name="aqualuxe_theme_options[footer_copyright]" id="aqualuxe-footer-copyright" class="large-text" rows="3"><?php echo esc_textarea( $copyright ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Enter your copyright text. Use {year} to display the current year.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Footer payment icons field callback
 */
function aqualuxe_footer_payment_icons_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $payment_icons = isset( $options['footer_payment_icons'] ) ? $options['footer_payment_icons'] : array();
    
    $available_icons = array(
        'visa'       => __( 'Visa', 'aqualuxe' ),
        'mastercard' => __( 'MasterCard', 'aqualuxe' ),
        'amex'       => __( 'American Express', 'aqualuxe' ),
        'discover'   => __( 'Discover', 'aqualuxe' ),
        'paypal'     => __( 'PayPal', 'aqualuxe' ),
        'apple-pay'  => __( 'Apple Pay', 'aqualuxe' ),
        'google-pay' => __( 'Google Pay', 'aqualuxe' ),
        'stripe'     => __( 'Stripe', 'aqualuxe' ),
    );
    ?>
    <div class="payment-icons-field">
        <?php foreach ( $available_icons as $icon => $label ) : ?>
            <label>
                <input type="checkbox" name="aqualuxe_theme_options[footer_payment_icons][]" value="<?php echo esc_attr( $icon ); ?>" <?php checked( in_array( $icon, $payment_icons, true ) ); ?> />
                <?php echo esc_html( $label ); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Select the payment icons to display in the footer.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Blog settings section callback
 */
function aqualuxe_blog_settings_section() {
    echo '<p>' . esc_html__( 'Configure blog settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Blog layout field callback
 */
function aqualuxe_blog_layout_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $blog_layout = isset( $options['blog_layout'] ) ? $options['blog_layout'] : 'grid';
    ?>
    <select name="aqualuxe_theme_options[blog_layout]">
        <option value="grid" <?php selected( $blog_layout, 'grid' ); ?>><?php esc_html_e( 'Grid', 'aqualuxe' ); ?></option>
        <option value="list" <?php selected( $blog_layout, 'list' ); ?>><?php esc_html_e( 'List', 'aqualuxe' ); ?></option>
        <option value="masonry" <?php selected( $blog_layout, 'masonry' ); ?>><?php esc_html_e( 'Masonry', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the blog archive layout.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Blog sidebar field callback
 */
function aqualuxe_blog_sidebar_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $blog_sidebar = isset( $options['blog_sidebar'] ) ? $options['blog_sidebar'] : 'right';
    ?>
    <select name="aqualuxe_theme_options[blog_sidebar]">
        <option value="right" <?php selected( $blog_sidebar, 'right' ); ?>><?php esc_html_e( 'Right Sidebar', 'aqualuxe' ); ?></option>
        <option value="left" <?php selected( $blog_sidebar, 'left' ); ?>><?php esc_html_e( 'Left Sidebar', 'aqualuxe' ); ?></option>
        <option value="none" <?php selected( $blog_sidebar, 'none' ); ?>><?php esc_html_e( 'No Sidebar', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the sidebar position for blog pages.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Blog meta field callback
 */
function aqualuxe_blog_meta_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $meta_options = isset( $options['blog_meta'] ) ? $options['blog_meta'] : array( 'date', 'author', 'categories', 'comments' );
    
    $available_meta = array(
        'date'       => __( 'Date', 'aqualuxe' ),
        'author'     => __( 'Author', 'aqualuxe' ),
        'categories' => __( 'Categories', 'aqualuxe' ),
        'tags'       => __( 'Tags', 'aqualuxe' ),
        'comments'   => __( 'Comments', 'aqualuxe' ),
    );
    ?>
    <div class="blog-meta-field">
        <?php foreach ( $available_meta as $meta => $label ) : ?>
            <label>
                <input type="checkbox" name="aqualuxe_theme_options[blog_meta][]" value="<?php echo esc_attr( $meta ); ?>" <?php checked( in_array( $meta, $meta_options, true ) ); ?> />
                <?php echo esc_html( $label ); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Select which meta information to display on blog posts.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Blog excerpt length field callback
 */
function aqualuxe_blog_excerpt_length_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $excerpt_length = isset( $options['blog_excerpt_length'] ) ? $options['blog_excerpt_length'] : 30;
    ?>
    <input type="number" name="aqualuxe_theme_options[blog_excerpt_length]" id="aqualuxe-blog-excerpt-length" class="small-text" value="<?php echo esc_attr( $excerpt_length ); ?>" min="10" max="100" />
    <p class="description"><?php esc_html_e( 'Set the number of words in post excerpts.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * WooCommerce settings section callback
 */
function aqualuxe_woocommerce_settings_section() {
    echo '<p>' . esc_html__( 'Configure WooCommerce settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Shop layout field callback
 */
function aqualuxe_shop_layout_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $shop_layout = isset( $options['shop_layout'] ) ? $options['shop_layout'] : 'grid';
    ?>
    <select name="aqualuxe_theme_options[shop_layout]">
        <option value="grid" <?php selected( $shop_layout, 'grid' ); ?>><?php esc_html_e( 'Grid', 'aqualuxe' ); ?></option>
        <option value="list" <?php selected( $shop_layout, 'list' ); ?>><?php esc_html_e( 'List', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the shop products layout.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Shop sidebar field callback
 */
function aqualuxe_shop_sidebar_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $shop_sidebar = isset( $options['shop_sidebar'] ) ? $options['shop_sidebar'] : 'right';
    ?>
    <select name="aqualuxe_theme_options[shop_sidebar]">
        <option value="right" <?php selected( $shop_sidebar, 'right' ); ?>><?php esc_html_e( 'Right Sidebar', 'aqualuxe' ); ?></option>
        <option value="left" <?php selected( $shop_sidebar, 'left' ); ?>><?php esc_html_e( 'Left Sidebar', 'aqualuxe' ); ?></option>
        <option value="none" <?php selected( $shop_sidebar, 'none' ); ?>><?php esc_html_e( 'No Sidebar', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the sidebar position for shop pages.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Products per page field callback
 */
function aqualuxe_products_per_page_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $products_per_page = isset( $options['products_per_page'] ) ? $options['products_per_page'] : 12;
    ?>
    <input type="number" name="aqualuxe_theme_options[products_per_page]" id="aqualuxe-products-per-page" class="small-text" value="<?php echo esc_attr( $products_per_page ); ?>" min="4" max="48" step="4" />
    <p class="description"><?php esc_html_e( 'Set the number of products to display per page.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Products per row field callback
 */
function aqualuxe_products_per_row_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $products_per_row = isset( $options['products_per_row'] ) ? $options['products_per_row'] : 3;
    ?>
    <select name="aqualuxe_theme_options[products_per_row]">
        <option value="2" <?php selected( $products_per_row, 2 ); ?>><?php esc_html_e( '2 Products', 'aqualuxe' ); ?></option>
        <option value="3" <?php selected( $products_per_row, 3 ); ?>><?php esc_html_e( '3 Products', 'aqualuxe' ); ?></option>
        <option value="4" <?php selected( $products_per_row, 4 ); ?>><?php esc_html_e( '4 Products', 'aqualuxe' ); ?></option>
        <option value="5" <?php selected( $products_per_row, 5 ); ?>><?php esc_html_e( '5 Products', 'aqualuxe' ); ?></option>
    </select>
    <p class="description"><?php esc_html_e( 'Select the number of products to display per row.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Product features field callback
 */
function aqualuxe_product_features_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $features = isset( $options['product_features'] ) ? $options['product_features'] : array( 'quick_view', 'wishlist', 'compare' );
    
    $available_features = array(
        'quick_view' => __( 'Quick View', 'aqualuxe' ),
        'wishlist'   => __( 'Wishlist', 'aqualuxe' ),
        'compare'    => __( 'Compare', 'aqualuxe' ),
        'zoom'       => __( 'Image Zoom', 'aqualuxe' ),
        'gallery'    => __( 'Image Gallery', 'aqualuxe' ),
    );
    ?>
    <div class="product-features-field">
        <?php foreach ( $available_features as $feature => $label ) : ?>
            <label>
                <input type="checkbox" name="aqualuxe_theme_options[product_features][]" value="<?php echo esc_attr( $feature ); ?>" <?php checked( in_array( $feature, $features, true ) ); ?> />
                <?php echo esc_html( $label ); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Select which features to enable for products.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Social media settings section callback
 */
function aqualuxe_social_settings_section() {
    echo '<p>' . esc_html__( 'Configure social media settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Social links field callback
 */
function aqualuxe_social_links_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $social_links = isset( $options['social_links'] ) ? $options['social_links'] : array();
    
    $networks = array(
        'facebook'  => __( 'Facebook', 'aqualuxe' ),
        'twitter'   => __( 'Twitter', 'aqualuxe' ),
        'instagram' => __( 'Instagram', 'aqualuxe' ),
        'linkedin'  => __( 'LinkedIn', 'aqualuxe' ),
        'youtube'   => __( 'YouTube', 'aqualuxe' ),
        'pinterest' => __( 'Pinterest', 'aqualuxe' ),
        'tiktok'    => __( 'TikTok', 'aqualuxe' ),
    );
    ?>
    <div class="social-links-field">
        <?php foreach ( $networks as $network => $label ) : ?>
            <p>
                <label for="aqualuxe-social-<?php echo esc_attr( $network ); ?>"><?php echo esc_html( $label ); ?>:</label>
                <input type="url" name="aqualuxe_theme_options[social_links][<?php echo esc_attr( $network ); ?>]" id="aqualuxe-social-<?php echo esc_attr( $network ); ?>" class="regular-text" value="<?php echo isset( $social_links[ $network ] ) ? esc_url( $social_links[ $network ] ) : ''; ?>" placeholder="https://" />
            </p>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Enter your social media profile URLs.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Social sharing field callback
 */
function aqualuxe_social_sharing_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $social_sharing = isset( $options['social_sharing'] ) ? $options['social_sharing'] : array( 'facebook', 'twitter', 'linkedin', 'pinterest' );
    
    $networks = array(
        'facebook'  => __( 'Facebook', 'aqualuxe' ),
        'twitter'   => __( 'Twitter', 'aqualuxe' ),
        'linkedin'  => __( 'LinkedIn', 'aqualuxe' ),
        'pinterest' => __( 'Pinterest', 'aqualuxe' ),
        'email'     => __( 'Email', 'aqualuxe' ),
        'whatsapp'  => __( 'WhatsApp', 'aqualuxe' ),
        'telegram'  => __( 'Telegram', 'aqualuxe' ),
    );
    ?>
    <div class="social-sharing-field">
        <?php foreach ( $networks as $network => $label ) : ?>
            <label>
                <input type="checkbox" name="aqualuxe_theme_options[social_sharing][]" value="<?php echo esc_attr( $network ); ?>" <?php checked( in_array( $network, $social_sharing, true ) ); ?> />
                <?php echo esc_html( $label ); ?>
            </label><br>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e( 'Select which social sharing buttons to display on posts and products.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Custom code settings section callback
 */
function aqualuxe_custom_code_settings_section() {
    echo '<p>' . esc_html__( 'Add custom code to your site.', 'aqualuxe' ) . '</p>';
}

/**
 * Custom CSS field callback
 */
function aqualuxe_custom_css_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $custom_css = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
    ?>
    <textarea name="aqualuxe_theme_options[custom_css]" id="aqualuxe-custom-css" class="large-text code" rows="10"><?php echo esc_textarea( $custom_css ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Add custom CSS to customize the appearance of your site.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Custom JavaScript field callback
 */
function aqualuxe_custom_js_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $custom_js = isset( $options['custom_js'] ) ? $options['custom_js'] : '';
    ?>
    <textarea name="aqualuxe_theme_options[custom_js]" id="aqualuxe-custom-js" class="large-text code" rows="10"><?php echo esc_textarea( $custom_js ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Add custom JavaScript to enhance your site functionality.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Header code field callback
 */
function aqualuxe_header_code_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $header_code = isset( $options['header_code'] ) ? $options['header_code'] : '';
    ?>
    <textarea name="aqualuxe_theme_options[header_code]" id="aqualuxe-header-code" class="large-text code" rows="5"><?php echo esc_textarea( $header_code ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Add custom code to the <head> section of your site.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Footer code field callback
 */
function aqualuxe_footer_code_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $footer_code = isset( $options['footer_code'] ) ? $options['footer_code'] : '';
    ?>
    <textarea name="aqualuxe_theme_options[footer_code]" id="aqualuxe-footer-code" class="large-text code" rows="5"><?php echo esc_textarea( $footer_code ); ?></textarea>
    <p class="description"><?php esc_html_e( 'Add custom code before the closing </body> tag of your site.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Performance settings section callback
 */
function aqualuxe_performance_settings_section() {
    echo '<p>' . esc_html__( 'Configure performance optimization settings.', 'aqualuxe' ) . '</p>';
}

/**
 * Minify assets field callback
 */
function aqualuxe_minify_assets_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $minify_assets = isset( $options['minify_assets'] ) ? $options['minify_assets'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[minify_assets]" value="1" <?php checked( $minify_assets, true ); ?> />
        <?php esc_html_e( 'Minify CSS and JavaScript files', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Reduce file sizes by removing unnecessary characters from CSS and JavaScript files.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Lazy loading field callback
 */
function aqualuxe_lazy_loading_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $lazy_loading = isset( $options['lazy_loading'] ) ? $options['lazy_loading'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[lazy_loading]" value="1" <?php checked( $lazy_loading, true ); ?> />
        <?php esc_html_e( 'Enable lazy loading for images', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Load images only when they enter the viewport to improve page load speed.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Preload assets field callback
 */
function aqualuxe_preload_assets_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $preload_assets = isset( $options['preload_assets'] ) ? $options['preload_assets'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[preload_assets]" value="1" <?php checked( $preload_assets, true ); ?> />
        <?php esc_html_e( 'Preload critical assets', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Preload critical CSS, fonts, and other assets to improve page rendering.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Browser caching field callback
 */
function aqualuxe_browser_caching_field() {
    $options = get_option( 'aqualuxe_theme_options' );
    $browser_caching = isset( $options['browser_caching'] ) ? $options['browser_caching'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_theme_options[browser_caching]" value="1" <?php checked( $browser_caching, true ); ?> />
        <?php esc_html_e( 'Enable browser caching', 'aqualuxe' ); ?>
    </label>
    <p class="description"><?php esc_html_e( 'Set appropriate cache headers to allow browsers to cache static assets.', 'aqualuxe' ); ?></p>
    <?php
}

/**
 * Validate theme options
 */
function aqualuxe_validate_theme_options( $input ) {
    $output = array();
    
    // General Settings
    if ( isset( $input['logo'] ) ) {
        $output['logo'] = esc_url_raw( $input['logo'] );
    }
    
    if ( isset( $input['favicon'] ) ) {
        $output['favicon'] = esc_url_raw( $input['favicon'] );
    }
    
    $output['preloader'] = isset( $input['preloader'] ) ? (bool) $input['preloader'] : false;
    $output['back_to_top'] = isset( $input['back_to_top'] ) ? (bool) $input['back_to_top'] : true;
    
    // Header Settings
    if ( isset( $input['header_style'] ) ) {
        $output['header_style'] = sanitize_text_field( $input['header_style'] );
    }
    
    $output['sticky_header'] = isset( $input['sticky_header'] ) ? (bool) $input['sticky_header'] : true;
    
    if ( isset( $input['header_phone'] ) ) {
        $output['header_phone'] = sanitize_text_field( $input['header_phone'] );
    }
    
    if ( isset( $input['header_email'] ) ) {
        $output['header_email'] = sanitize_email( $input['header_email'] );
    }
    
    if ( isset( $input['header_address'] ) ) {
        $output['header_address'] = sanitize_text_field( $input['header_address'] );
    }
    
    if ( isset( $input['header_button_text'] ) ) {
        $output['header_button_text'] = sanitize_text_field( $input['header_button_text'] );
    }
    
    if ( isset( $input['header_button_url'] ) ) {
        $output['header_button_url'] = esc_url_raw( $input['header_button_url'] );
    }
    
    if ( isset( $input['header_button_style'] ) ) {
        $output['header_button_style'] = sanitize_text_field( $input['header_button_style'] );
    }
    
    // Footer Settings
    if ( isset( $input['footer_style'] ) ) {
        $output['footer_style'] = sanitize_text_field( $input['footer_style'] );
    }
    
    if ( isset( $input['footer_widgets'] ) ) {
        $output['footer_widgets'] = sanitize_text_field( $input['footer_widgets'] );
    }
    
    if ( isset( $input['footer_copyright'] ) ) {
        $output['footer_copyright'] = wp_kses_post( $input['footer_copyright'] );
    }
    
    if ( isset( $input['footer_payment_icons'] ) && is_array( $input['footer_payment_icons'] ) ) {
        $output['footer_payment_icons'] = array_map( 'sanitize_text_field', $input['footer_payment_icons'] );
    }
    
    // Blog Settings
    if ( isset( $input['blog_layout'] ) ) {
        $output['blog_layout'] = sanitize_text_field( $input['blog_layout'] );
    }
    
    if ( isset( $input['blog_sidebar'] ) ) {
        $output['blog_sidebar'] = sanitize_text_field( $input['blog_sidebar'] );
    }
    
    if ( isset( $input['blog_meta'] ) && is_array( $input['blog_meta'] ) ) {
        $output['blog_meta'] = array_map( 'sanitize_text_field', $input['blog_meta'] );
    }
    
    if ( isset( $input['blog_excerpt_length'] ) ) {
        $output['blog_excerpt_length'] = absint( $input['blog_excerpt_length'] );
    }
    
    // WooCommerce Settings
    if ( isset( $input['shop_layout'] ) ) {
        $output['shop_layout'] = sanitize_text_field( $input['shop_layout'] );
    }
    
    if ( isset( $input['shop_sidebar'] ) ) {
        $output['shop_sidebar'] = sanitize_text_field( $input['shop_sidebar'] );
    }
    
    if ( isset( $input['products_per_page'] ) ) {
        $output['products_per_page'] = absint( $input['products_per_page'] );
    }
    
    if ( isset( $input['products_per_row'] ) ) {
        $output['products_per_row'] = absint( $input['products_per_row'] );
    }
    
    if ( isset( $input['product_features'] ) && is_array( $input['product_features'] ) ) {
        $output['product_features'] = array_map( 'sanitize_text_field', $input['product_features'] );
    }
    
    // Social Media Settings
    if ( isset( $input['social_links'] ) && is_array( $input['social_links'] ) ) {
        foreach ( $input['social_links'] as $network => $url ) {
            if ( ! empty( $url ) ) {
                $output['social_links'][ sanitize_text_field( $network ) ] = esc_url_raw( $url );
            }
        }
    }
    
    if ( isset( $input['social_sharing'] ) && is_array( $input['social_sharing'] ) ) {
        $output['social_sharing'] = array_map( 'sanitize_text_field', $input['social_sharing'] );
    }
    
    // Custom Code Settings
    if ( isset( $input['custom_css'] ) ) {
        $output['custom_css'] = wp_strip_all_tags( $input['custom_css'] );
    }
    
    if ( isset( $input['custom_js'] ) ) {
        $output['custom_js'] = wp_strip_all_tags( $input['custom_js'] );
    }
    
    if ( isset( $input['header_code'] ) ) {
        $output['header_code'] = wp_kses_post( $input['header_code'] );
    }
    
    if ( isset( $input['footer_code'] ) ) {
        $output['footer_code'] = wp_kses_post( $input['footer_code'] );
    }
    
    // Performance Settings
    $output['minify_assets'] = isset( $input['minify_assets'] ) ? (bool) $input['minify_assets'] : true;
    $output['lazy_loading'] = isset( $input['lazy_loading'] ) ? (bool) $input['lazy_loading'] : true;
    $output['preload_assets'] = isset( $input['preload_assets'] ) ? (bool) $input['preload_assets'] : true;
    $output['browser_caching'] = isset( $input['browser_caching'] ) ? (bool) $input['browser_caching'] : true;
    
    return $output;
}

/**
 * Enqueue admin scripts
 */
function aqualuxe_admin_scripts( $hook ) {
    if ( 'appearance_page_aqualuxe-options' !== $hook ) {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    
    wp_enqueue_script(
        'aqualuxe-admin-js',
        AQUALUXE_URI . 'assets/js/admin.js',
        array( 'jquery', 'wp-color-picker' ),
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Add theme options link to admin bar
 */
function aqualuxe_admin_bar_link( $wp_admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    $wp_admin_bar->add_node(
        array(
            'id'    => 'aqualuxe-options',
            'title' => __( 'AquaLuxe Options', 'aqualuxe' ),
            'href'  => admin_url( 'themes.php?page=aqualuxe-options' ),
        )
    );
}
add_action( 'admin_bar_menu', 'aqualuxe_admin_bar_link', 100 );

/**
 * Get theme option
 */
function aqualuxe_get_option( $option, $default = false ) {
    $options = get_option( 'aqualuxe_theme_options' );
    
    if ( isset( $options[ $option ] ) ) {
        return $options[ $option ];
    }
    
    return $default;
}

/**
 * Add custom code to head and footer
 */
function aqualuxe_add_header_code() {
    $header_code = aqualuxe_get_option( 'header_code' );
    
    if ( ! empty( $header_code ) ) {
        echo wp_kses_post( $header_code );
    }
}
add_action( 'wp_head', 'aqualuxe_add_header_code', 999 );

function aqualuxe_add_footer_code() {
    $footer_code = aqualuxe_get_option( 'footer_code' );
    
    if ( ! empty( $footer_code ) ) {
        echo wp_kses_post( $footer_code );
    }
}
add_action( 'wp_footer', 'aqualuxe_add_footer_code', 999 );

/**
 * Add custom CSS
 */
function aqualuxe_add_custom_css() {
    $custom_css = aqualuxe_get_option( 'custom_css' );
    
    if ( ! empty( $custom_css ) ) {
        echo '<style type="text/css">' . wp_strip_all_tags( $custom_css ) . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_add_custom_css', 999 );

/**
 * Add custom JavaScript
 */
function aqualuxe_add_custom_js() {
    $custom_js = aqualuxe_get_option( 'custom_js' );
    
    if ( ! empty( $custom_js ) ) {
        echo '<script>' . wp_strip_all_tags( $custom_js ) . '</script>';
    }
}
add_action( 'wp_footer', 'aqualuxe_add_custom_js', 999 );

/**
 * Set excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    $excerpt_length = aqualuxe_get_option( 'blog_excerpt_length', 30 );
    return $excerpt_length;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Set WooCommerce products per page
 */
function aqualuxe_products_per_page( $products ) {
    $products_per_page = aqualuxe_get_option( 'products_per_page', 12 );
    return $products_per_page;
}
add_filter( 'loop_shop_per_page', 'aqualuxe_products_per_page' );

/**
 * Set WooCommerce products per row
 */
function aqualuxe_products_per_row( $columns ) {
    $products_per_row = aqualuxe_get_option( 'products_per_row', 3 );
    return $products_per_row;
}
add_filter( 'loop_shop_columns', 'aqualuxe_products_per_row' );

/**
 * Add browser caching headers
 */
function aqualuxe_browser_caching() {
    $browser_caching = aqualuxe_get_option( 'browser_caching', true );
    
    if ( $browser_caching ) {
        $file_types = array(
            'text/css'                => 'access plus 1 year',
            'text/javascript'         => 'access plus 1 year',
            'application/javascript'  => 'access plus 1 year',
            'image/jpeg'              => 'access plus 1 year',
            'image/png'               => 'access plus 1 year',
            'image/gif'               => 'access plus 1 year',
            'image/svg+xml'           => 'access plus 1 year',
            'image/webp'              => 'access plus 1 year',
            'font/woff'               => 'access plus 1 year',
            'font/woff2'              => 'access plus 1 year',
            'application/font-woff'   => 'access plus 1 year',
            'application/font-woff2'  => 'access plus 1 year',
        );
        
        foreach ( $file_types as $file_type => $expiration ) {
            header( "Cache-Control: public, max-age=31536000" );
            header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + 31536000 ) . " GMT" );
        }
    }
}
add_action( 'send_headers', 'aqualuxe_browser_caching' );