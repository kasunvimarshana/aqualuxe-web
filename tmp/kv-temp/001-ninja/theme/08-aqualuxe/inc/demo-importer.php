<?php
/**
 * Demo Importer for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register demo import functionality
 */
function aqualuxe_demo_importer() {
    // Check if One Click Demo Import plugin is active
    if ( ! class_exists( 'OCDI_Plugin' ) ) {
        return;
    }
    
    // Define demo import files
    add_filter( 'ocdi/import_files', 'aqualuxe_demo_import_files' );
    
    // Define actions to perform after import
    add_action( 'ocdi/after_import', 'aqualuxe_after_import_setup' );
    
    // Change plugin page title
    add_filter( 'ocdi/plugin_page_title', 'aqualuxe_plugin_page_title' );
    
    // Change plugin intro text
    add_filter( 'ocdi/plugin_intro_text', 'aqualuxe_plugin_intro_text' );
    
    // Disable the branding notice
    add_filter( 'ocdi/disable_pt_branding', '__return_true' );
}
add_action( 'after_setup_theme', 'aqualuxe_demo_importer' );

/**
 * Define demo import files
 */
function aqualuxe_demo_import_files() {
    return array(
        array(
            'import_file_name'           => 'AquaLuxe Main Demo',
            'categories'                 => array( 'E-commerce', 'Business' ),
            'import_file_url'            => AQUALUXE_URI . 'assets/demo/content.xml',
            'import_widget_file_url'     => AQUALUXE_URI . 'assets/demo/widgets.wie',
            'import_customizer_file_url' => AQUALUXE_URI . 'assets/demo/customizer.dat',
            'import_preview_image_url'   => AQUALUXE_URI . 'assets/demo/preview.jpg',
            'preview_url'                => 'https://aqualuxe.com/demo/',
            'import_notice'              => __( 'After importing this demo, you will need to setup the slider separately.', 'aqualuxe' ),
        ),
        array(
            'import_file_name'           => 'AquaLuxe Minimal Demo',
            'categories'                 => array( 'E-commerce', 'Minimal' ),
            'import_file_url'            => AQUALUXE_URI . 'assets/demo/minimal/content.xml',
            'import_widget_file_url'     => AQUALUXE_URI . 'assets/demo/minimal/widgets.wie',
            'import_customizer_file_url' => AQUALUXE_URI . 'assets/demo/minimal/customizer.dat',
            'import_preview_image_url'   => AQUALUXE_URI . 'assets/demo/minimal/preview.jpg',
            'preview_url'                => 'https://aqualuxe.com/demo-minimal/',
        ),
        array(
            'import_file_name'           => 'AquaLuxe Blog Demo',
            'categories'                 => array( 'Blog' ),
            'import_file_url'            => AQUALUXE_URI . 'assets/demo/blog/content.xml',
            'import_widget_file_url'     => AQUALUXE_URI . 'assets/demo/blog/widgets.wie',
            'import_customizer_file_url' => AQUALUXE_URI . 'assets/demo/blog/customizer.dat',
            'import_preview_image_url'   => AQUALUXE_URI . 'assets/demo/blog/preview.jpg',
            'preview_url'                => 'https://aqualuxe.com/demo-blog/',
        ),
    );
}

/**
 * Setup after demo import
 */
function aqualuxe_after_import_setup( $selected_import ) {
    // Assign menus to their locations
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
    
    set_theme_mod(
        'nav_menu_locations',
        array(
            'primary-menu' => $main_menu ? $main_menu->term_id : '',
            'footer-menu'  => $footer_menu ? $footer_menu->term_id : '',
        )
    );
    
    // Assign front page and posts page (blog page)
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );
    
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );
    
    // Set up WooCommerce pages
    if ( class_exists( 'WooCommerce' ) ) {
        // Check if the demo has WooCommerce pages
        $shop_page = get_page_by_title( 'Shop' );
        $cart_page = get_page_by_title( 'Cart' );
        $checkout_page = get_page_by_title( 'Checkout' );
        $account_page = get_page_by_title( 'My Account' );
        
        if ( $shop_page ) {
            update_option( 'woocommerce_shop_page_id', $shop_page->ID );
        }
        
        if ( $cart_page ) {
            update_option( 'woocommerce_cart_page_id', $cart_page->ID );
        }
        
        if ( $checkout_page ) {
            update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
        }
        
        if ( $account_page ) {
            update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
        }
        
        // Update product attributes
        aqualuxe_update_product_attributes();
    }
    
    // Import sliders based on demo
    if ( 'AquaLuxe Main Demo' === $selected_import['import_file_name'] ) {
        aqualuxe_import_slider( 'main' );
    } elseif ( 'AquaLuxe Minimal Demo' === $selected_import['import_file_name'] ) {
        aqualuxe_import_slider( 'minimal' );
    }
    
    // Update Elementor settings if Elementor is active
    if ( class_exists( '\Elementor\Plugin' ) ) {
        // Update Elementor global settings
        update_option( 'elementor_disable_color_schemes', 'yes' );
        update_option( 'elementor_disable_typography_schemes', 'yes' );
        update_option( 'elementor_container_width', 1200 );
        
        // Update Elementor page settings
        if ( 'AquaLuxe Main Demo' === $selected_import['import_file_name'] ) {
            aqualuxe_update_elementor_settings( 'main' );
        } elseif ( 'AquaLuxe Minimal Demo' === $selected_import['import_file_name'] ) {
            aqualuxe_update_elementor_settings( 'minimal' );
        }
    }
    
    // Regenerate CSS files
    if ( class_exists( 'Elementor\Plugin' ) ) {
        \Elementor\Plugin::$instance->files_manager->clear_cache();
    }
    
    // Clear any unwanted transients
    delete_transient( 'aqualuxe_categories' );
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Import slider based on demo type
 */
function aqualuxe_import_slider( $demo_type ) {
    // Check if Revolution Slider is active
    if ( class_exists( 'RevSlider' ) ) {
        $slider_array = array(
            'main'    => AQUALUXE_DIR . 'assets/demo/revslider/main-slider.zip',
            'minimal' => AQUALUXE_DIR . 'assets/demo/revslider/minimal-slider.zip',
        );
        
        if ( isset( $slider_array[ $demo_type ] ) ) {
            $slider = new RevSlider();
            $slider->importSliderFromPost( true, true, $slider_array[ $demo_type ] );
        }
    }
}

/**
 * Update product attributes
 */
function aqualuxe_update_product_attributes() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Create product attributes
    $attributes = array(
        'color' => array(
            'name'         => 'Color',
            'slug'         => 'color',
            'type'         => 'color',
            'order_by'     => 'menu_order',
            'has_archives' => false,
            'terms'        => array(
                'Red'    => '#ff0000',
                'Blue'   => '#0000ff',
                'Green'  => '#00ff00',
                'Yellow' => '#ffff00',
                'Black'  => '#000000',
                'White'  => '#ffffff',
            ),
        ),
        'size' => array(
            'name'         => 'Size',
            'slug'         => 'size',
            'type'         => 'select',
            'order_by'     => 'menu_order',
            'has_archives' => false,
            'terms'        => array(
                'Small'  => '',
                'Medium' => '',
                'Large'  => '',
                'XL'     => '',
                'XXL'    => '',
            ),
        ),
        'material' => array(
            'name'         => 'Material',
            'slug'         => 'material',
            'type'         => 'select',
            'order_by'     => 'menu_order',
            'has_archives' => false,
            'terms'        => array(
                'Glass'    => '',
                'Plastic'  => '',
                'Ceramic'  => '',
                'Metal'    => '',
                'Wood'     => '',
                'Acrylic'  => '',
            ),
        ),
    );
    
    // Register attributes
    foreach ( $attributes as $attribute_slug => $attribute ) {
        $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_slug );
        
        if ( ! $attribute_id ) {
            $attribute_id = wc_create_attribute(
                array(
                    'name'         => $attribute['name'],
                    'slug'         => $attribute['slug'],
                    'type'         => $attribute['type'],
                    'order_by'     => $attribute['order_by'],
                    'has_archives' => $attribute['has_archives'],
                )
            );
            
            // Register the taxonomy
            register_taxonomy(
                'pa_' . $attribute['slug'],
                array( 'product' ),
                array(
                    'hierarchical' => false,
                    'show_ui'      => false,
                    'query_var'    => true,
                    'rewrite'      => false,
                )
            );
        }
        
        // Add terms
        foreach ( $attribute['terms'] as $term_name => $term_value ) {
            $term = get_term_by( 'name', $term_name, 'pa_' . $attribute['slug'] );
            
            if ( ! $term ) {
                $term = wp_insert_term( $term_name, 'pa_' . $attribute['slug'] );
                
                if ( ! is_wp_error( $term ) && ! empty( $term_value ) ) {
                    update_term_meta( $term['term_id'], 'color', $term_value );
                }
            }
        }
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Update Elementor settings
 */
function aqualuxe_update_elementor_settings( $demo_type ) {
    // Check if Elementor is active
    if ( ! class_exists( '\Elementor\Plugin' ) ) {
        return;
    }
    
    // Update Elementor global settings
    $elementor_settings = array(
        'main' => array(
            'container_width' => 1200,
            'space_between_widgets' => 30,
            'page_title_selector' => 'h1.entry-title',
            'global_image_lightbox' => 'yes',
            'stretched_section_container' => 'body',
        ),
        'minimal' => array(
            'container_width' => 1140,
            'space_between_widgets' => 20,
            'page_title_selector' => 'h1.entry-title',
            'global_image_lightbox' => 'yes',
            'stretched_section_container' => 'body',
        ),
    );
    
    if ( isset( $elementor_settings[ $demo_type ] ) ) {
        foreach ( $elementor_settings[ $demo_type ] as $key => $value ) {
            update_option( 'elementor_' . $key, $value );
        }
    }
    
    // Update Elementor color schemes
    $elementor_colors = array(
        'main' => array(
            '1' => '#0891B2', // Primary
            '2' => '#0E7490', // Secondary
            '3' => '#14B8A6', // Accent
            '4' => '#333333', // Text
        ),
        'minimal' => array(
            '1' => '#0891B2', // Primary
            '2' => '#0E7490', // Secondary
            '3' => '#14B8A6', // Accent
            '4' => '#333333', // Text
        ),
    );
    
    if ( isset( $elementor_colors[ $demo_type ] ) ) {
        update_option( 'elementor_scheme_color', $elementor_colors[ $demo_type ] );
    }
}

/**
 * Change plugin page title
 */
function aqualuxe_plugin_page_title() {
    return __( 'AquaLuxe Demo Import', 'aqualuxe' );
}

/**
 * Change plugin intro text
 */
function aqualuxe_plugin_intro_text( $default_text ) {
    $intro_text = '<div class="ocdi__intro-text">';
    $intro_text .= '<h2>' . __( 'AquaLuxe Demo Import', 'aqualuxe' ) . '</h2>';
    $intro_text .= '<p>' . __( 'Import the AquaLuxe demo content to get started with a pre-made website. Choose from the available demo options below.', 'aqualuxe' ) . '</p>';
    $intro_text .= '<p>' . __( 'The import process may take a few minutes, so please be patient.', 'aqualuxe' ) . '</p>';
    $intro_text .= '<p><strong>' . __( 'Important:', 'aqualuxe' ) . '</strong> ' . __( 'Before importing, make sure you have installed all required plugins.', 'aqualuxe' ) . '</p>';
    $intro_text .= '</div>';
    
    return $intro_text;
}

/**
 * Add admin notice if One Click Demo Import plugin is not active
 */
function aqualuxe_demo_import_plugin_notice() {
    if ( class_exists( 'OCDI_Plugin' ) ) {
        return;
    }
    
    $screen = get_current_screen();
    
    if ( $screen->id !== 'appearance_page_aqualuxe-demo-import' ) {
        return;
    }
    
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php esc_html_e( 'Please install and activate the One Click Demo Import plugin to import the AquaLuxe demo content.', 'aqualuxe' ); ?></p>
        <p>
            <a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary">
                <?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'aqualuxe_demo_import_plugin_notice' );

/**
 * Add demo import page to admin menu
 */
function aqualuxe_add_demo_import_page() {
    add_theme_page(
        __( 'Import Demo Data', 'aqualuxe' ),
        __( 'Import Demo Data', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-demo-import',
        'aqualuxe_demo_import_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_add_demo_import_page' );

/**
 * Demo import page content
 */
function aqualuxe_demo_import_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'AquaLuxe Demo Import', 'aqualuxe' ); ?></h1>
        
        <?php if ( ! class_exists( 'OCDI_Plugin' ) ) : ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e( 'Please install and activate the One Click Demo Import plugin to import the AquaLuxe demo content.', 'aqualuxe' ); ?></p>
                <p>
                    <a href="<?php echo esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ); ?>" class="button button-primary">
                        <?php esc_html_e( 'Install Plugins', 'aqualuxe' ); ?>
                    </a>
                </p>
            </div>
        <?php else : ?>
            <p><?php esc_html_e( 'Please go to the One Click Demo Import page to import the AquaLuxe demo content.', 'aqualuxe' ); ?></p>
            <p>
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=pt-one-click-demo-import' ) ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Go to Demo Import', 'aqualuxe' ); ?>
                </a>
            </p>
        <?php endif; ?>
        
        <div class="aqualuxe-demo-previews">
            <h2><?php esc_html_e( 'Available Demos', 'aqualuxe' ); ?></h2>
            
            <div class="aqualuxe-demo-preview">
                <div class="aqualuxe-demo-preview-image">
                    <img src="<?php echo esc_url( AQUALUXE_URI . 'assets/demo/preview.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Main Demo', 'aqualuxe' ); ?>">
                </div>
                <div class="aqualuxe-demo-preview-content">
                    <h3><?php esc_html_e( 'AquaLuxe Main Demo', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'A complete e-commerce setup with homepage slider, featured products, services, and more.', 'aqualuxe' ); ?></p>
                    <a href="https://aqualuxe.com/demo/" target="_blank" class="button button-secondary"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></a>
                </div>
            </div>
            
            <div class="aqualuxe-demo-preview">
                <div class="aqualuxe-demo-preview-image">
                    <img src="<?php echo esc_url( AQUALUXE_URI . 'assets/demo/minimal/preview.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Minimal Demo', 'aqualuxe' ); ?>">
                </div>
                <div class="aqualuxe-demo-preview-content">
                    <h3><?php esc_html_e( 'AquaLuxe Minimal Demo', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'A clean and minimal design focused on product presentation with a simplified layout.', 'aqualuxe' ); ?></p>
                    <a href="https://aqualuxe.com/demo-minimal/" target="_blank" class="button button-secondary"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></a>
                </div>
            </div>
            
            <div class="aqualuxe-demo-preview">
                <div class="aqualuxe-demo-preview-image">
                    <img src="<?php echo esc_url( AQUALUXE_URI . 'assets/demo/blog/preview.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Blog Demo', 'aqualuxe' ); ?>">
                </div>
                <div class="aqualuxe-demo-preview-content">
                    <h3><?php esc_html_e( 'AquaLuxe Blog Demo', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'A blog-focused layout with featured posts, categories, and sidebar widgets.', 'aqualuxe' ); ?></p>
                    <a href="https://aqualuxe.com/demo-blog/" target="_blank" class="button button-secondary"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></a>
                </div>
            </div>
        </div>
        
        <style>
            .aqualuxe-demo-previews {
                margin-top: 30px;
            }
            
            .aqualuxe-demo-preview {
                display: flex;
                margin-bottom: 30px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 3px;
                overflow: hidden;
            }
            
            .aqualuxe-demo-preview-image {
                flex: 0 0 300px;
            }
            
            .aqualuxe-demo-preview-image img {
                width: 100%;
                height: auto;
                display: block;
            }
            
            .aqualuxe-demo-preview-content {
                flex: 1;
                padding: 20px;
            }
            
            .aqualuxe-demo-preview-content h3 {
                margin-top: 0;
            }
        </style>
    </div>
    <?php
}

/**
 * Add demo import admin styles
 */
function aqualuxe_demo_import_admin_styles( $hook ) {
    if ( 'appearance_page_pt-one-click-demo-import' !== $hook ) {
        return;
    }
    
    wp_enqueue_style(
        'aqualuxe-demo-import',
        AQUALUXE_URI . 'assets/css/admin/demo-import.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_demo_import_admin_styles' );

/**
 * Register required plugins for the theme
 */
function aqualuxe_register_required_plugins() {
    $plugins = array(
        array(
            'name'     => 'One Click Demo Import',
            'slug'     => 'one-click-demo-import',
            'required' => false,
        ),
        array(
            'name'     => 'WooCommerce',
            'slug'     => 'woocommerce',
            'required' => false,
        ),
        array(
            'name'     => 'Elementor',
            'slug'     => 'elementor',
            'required' => false,
        ),
        array(
            'name'     => 'Contact Form 7',
            'slug'     => 'contact-form-7',
            'required' => false,
        ),
        array(
            'name'     => 'Slider Revolution',
            'slug'     => 'revslider',
            'source'   => AQUALUXE_DIR . 'assets/plugins/revslider.zip',
            'required' => false,
        ),
    );
    
    $config = array(
        'id'           => 'aqualuxe',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'parent_slug'  => 'themes.php',
        'capability'   => 'edit_theme_options',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );
    
    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'aqualuxe_register_required_plugins' );

/**
 * Include TGM Plugin Activation
 */
require_once AQUALUXE_DIR . 'inc/class-tgm-plugin-activation.php';