<?php
/**
 * Enqueue scripts and styles for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_scripts() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Main CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/main.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/main.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/assets/dist/css/main.css', array(), $theme_version );
    }
    
    // WooCommerce styles
    if ( class_exists( 'WooCommerce' ) ) {
        if ( $mix_manifest && isset( $mix_manifest['/css/woocommerce.css'] ) ) {
            wp_enqueue_style( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/woocommerce.css'], array(), null );
        } else {
            wp_enqueue_style( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), $theme_version );
        }
    }
    
    // Main JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/main.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/main.js'], array( 'jquery' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-script', get_template_directory_uri() . '/assets/dist/js/main.js', array( 'jquery' ), $theme_version, true );
    }
    
    // WooCommerce scripts
    if ( class_exists( 'WooCommerce' ) ) {
        if ( $mix_manifest && isset( $mix_manifest['/js/woocommerce.js'] ) ) {
            wp_enqueue_script( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/woocommerce.js'], array( 'jquery' ), null, true );
        } else {
            wp_enqueue_script( 'aqualuxe-woocommerce', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array( 'jquery' ), $theme_version, true );
        }
    }
    
    // Comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    // Localize script
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxeSettings',
        array(
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'themeUrl'     => get_template_directory_uri(),
            'siteUrl'      => site_url(),
            'nonce'        => wp_create_nonce( 'aqualuxe-nonce' ),
            'isWooCommerce' => class_exists( 'WooCommerce' ),
            'darkMode'     => array(
                'enabled'   => get_theme_mod( 'aqualuxe_dark_mode_enable', true ),
                'default'   => get_theme_mod( 'aqualuxe_dark_mode_default', false ),
                'auto'      => get_theme_mod( 'aqualuxe_dark_mode_auto', true ),
                'saveInCookies' => get_theme_mod( 'aqualuxe_dark_mode_cookies', true ),
                'cookieExpiration' => 30, // days
            ),
            'i18n'         => array(
                'searchPlaceholder' => esc_html__( 'Search...', 'aqualuxe' ),
                'menuToggle'        => esc_html__( 'Menu', 'aqualuxe' ),
                'searchToggle'      => esc_html__( 'Search', 'aqualuxe' ),
                'darkModeToggle'    => esc_html__( 'Toggle dark mode', 'aqualuxe' ),
                'addToCart'         => esc_html__( 'Add to cart', 'aqualuxe' ),
                'adding'            => esc_html__( 'Adding...', 'aqualuxe' ),
                'added'             => esc_html__( 'Added!', 'aqualuxe' ),
                'viewCart'          => esc_html__( 'View cart', 'aqualuxe' ),
                'errorMessage'      => esc_html__( 'Something went wrong. Please try again.', 'aqualuxe' ),
            ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_scripts() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Admin CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/admin.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-admin-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/admin.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-admin-style', get_template_directory_uri() . '/assets/dist/css/admin.css', array(), $theme_version );
    }
    
    // Admin JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/admin.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-admin-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/admin.js'], array( 'jquery' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-admin-script', get_template_directory_uri() . '/assets/dist/js/admin.js', array( 'jquery' ), $theme_version, true );
    }
    
    // Localize script
    wp_localize_script(
        'aqualuxe-admin-script',
        'aqualuxeAdminSettings',
        array(
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'themeUrl'     => get_template_directory_uri(),
            'nonce'        => wp_create_nonce( 'aqualuxe-admin-nonce' ),
            'i18n'         => array(
                'confirmDelete' => esc_html__( 'Are you sure you want to delete this item?', 'aqualuxe' ),
                'saving'        => esc_html__( 'Saving...', 'aqualuxe' ),
                'saved'         => esc_html__( 'Saved!', 'aqualuxe' ),
                'error'         => esc_html__( 'Error!', 'aqualuxe' ),
            ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_admin_scripts' );

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/editor.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-editor-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/editor.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-editor-style', get_template_directory_uri() . '/assets/dist/css/editor.css', array(), $theme_version );
    }
    
    // Editor JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/editor.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-editor-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/editor.js'], array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-editor-script', get_template_directory_uri() . '/assets/dist/js/editor.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), $theme_version, true );
    }
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add async/defer attributes to enqueued scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function aqualuxe_script_loader_tag( $tag, $handle ) {
    // Add async attribute to specific scripts
    $async_scripts = array(
        'aqualuxe-script',
    );
    
    if ( in_array( $handle, $async_scripts, true ) ) {
        return str_replace( ' src', ' async src', $tag );
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = array(
        'aqualuxe-woocommerce',
    );
    
    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2 );

/**
 * Add preload for critical assets.
 */
function aqualuxe_preload_assets() {
    // Preload main CSS file
    echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/main.css' ) . '" as="style">';
    
    // Preload main JavaScript file
    echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/js/main.js' ) . '" as="script">';
    
    // Preload logo if available
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_info = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo_info ) {
            echo '<link rel="preload" href="' . esc_url( $logo_info[0] ) . '" as="image">';
        }
    }
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );

/**
 * Add DNS prefetch for external resources.
 */
function aqualuxe_dns_prefetch() {
    echo '<meta http-equiv="x-dns-prefetch-control" content="on">';
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//s.w.org">';
}
add_action( 'wp_head', 'aqualuxe_dns_prefetch', 0 );

/**
 * Add custom body classes.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
    // Add a class if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }
    
    // Add a class for the dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark-mode';
    }
    
    // Add a class for the page layout
    $classes[] = 'layout-' . aqualuxe_get_page_layout();
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add custom admin body classes.
 *
 * @param string $classes Body classes.
 * @return string
 */
function aqualuxe_admin_body_classes( $classes ) {
    // Add a class for the theme
    $classes .= ' aqualuxe-theme';
    
    return $classes;
}
add_filter( 'admin_body_class', 'aqualuxe_admin_body_classes' );

/**
 * Register the required plugins for this theme.
 */
function aqualuxe_register_required_plugins() {
    $plugins = array(
        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => false,
        ),
        array(
            'name'      => 'Kirki Customizer Framework',
            'slug'      => 'kirki',
            'required'  => false,
        ),
        array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false,
        ),
        array(
            'name'      => 'Yoast SEO',
            'slug'      => 'wordpress-seo',
            'required'  => false,
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
 * Add custom image sizes.
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-card', 600, 400, true );
    add_image_size( 'aqualuxe-square', 600, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library.
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
        'aqualuxe-card'     => __( 'Card Image', 'aqualuxe' ),
        'aqualuxe-square'   => __( 'Square Image', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add SVG support.
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function aqualuxe_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_mime_types' );

/**
 * Fix SVG display in media library.
 *
 * @param array $response The response.
 * @param object $attachment The attachment.
 * @param array $meta The attachment meta.
 * @return array
 */
function aqualuxe_fix_svg_size_attributes( $response, $attachment, $meta ) {
    if ( $response['mime'] === 'image/svg+xml' && empty( $response['sizes'] ) ) {
        $svg_path = get_attached_file( $attachment->ID );
        
        if ( ! file_exists( $svg_path ) ) {
            return $response;
        }
        
        $svg = simplexml_load_file( $svg_path );
        $attr = $svg->attributes();
        $viewbox = explode( ' ', $attr->viewBox );
        $response['sizes'] = array(
            'full' => array(
                'url'         => $response['url'],
                'width'       => isset( $attr->width ) ? (int) $attr->width : (count( $viewbox ) === 4 ? (int) $viewbox[2] : null),
                'height'      => isset( $attr->height ) ? (int) $attr->height : (count( $viewbox ) === 4 ? (int) $viewbox[3] : null),
                'orientation' => 'portrait',
            ),
        );
    }
    
    return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'aqualuxe_fix_svg_size_attributes', 10, 3 );

/**
 * Add custom styles to the admin.
 */
function aqualuxe_admin_styles() {
    echo '<style>
        .aqualuxe-theme .wp-block {
            max-width: 1200px;
        }
        .aqualuxe-theme .editor-styles-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
    </style>';
}
add_action( 'admin_head', 'aqualuxe_admin_styles' );

/**
 * Add custom styles to the login page.
 */
function aqualuxe_login_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Login CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/login.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-login-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/login.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-login-style', get_template_directory_uri() . '/assets/dist/css/login.css', array(), $theme_version );
    }
    
    // Custom logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_info = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo_info ) {
            echo '<style>
                .login h1 a {
                    background-image: url(' . esc_url( $logo_info[0] ) . ') !important;
                    background-size: contain !important;
                    width: 320px !important;
                    height: ' . esc_attr( $logo_info[2] / $logo_info[1] * 320 ) . 'px !important;
                }
            </style>';
        }
    }
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_styles' );

/**
 * Change the login logo URL.
 *
 * @return string
 */
function aqualuxe_login_logo_url() {
    return home_url( '/' );
}
add_filter( 'login_headerurl', 'aqualuxe_login_logo_url' );

/**
 * Change the login logo title.
 *
 * @return string
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'aqualuxe_login_logo_title' );

/**
 * Add custom styles to the editor.
 */
function aqualuxe_add_editor_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/editor.css'] ) ) {
        add_editor_style( '/assets/dist' . $mix_manifest['/css/editor.css'] );
    } else {
        add_editor_style( '/assets/dist/css/editor.css' );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_add_editor_styles' );

/**
 * Add custom styles to the Gutenberg editor.
 */
function aqualuxe_gutenberg_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/gutenberg.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-gutenberg-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/gutenberg.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-gutenberg-style', get_template_directory_uri() . '/assets/dist/css/gutenberg.css', array(), $theme_version );
    }
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_gutenberg_styles' );

/**
 * Add custom styles to the Gutenberg editor.
 */
function aqualuxe_gutenberg_editor_styles() {
    echo '<style>
        .editor-styles-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
        }
        .editor-styles-wrapper .wp-block {
            max-width: 1200px;
        }
        .editor-styles-wrapper .wp-block[data-align="wide"] {
            max-width: 1400px;
        }
        .editor-styles-wrapper .wp-block[data-align="full"] {
            max-width: none;
        }
    </style>';
}
add_action( 'admin_head', 'aqualuxe_gutenberg_editor_styles' );

/**
 * Add custom styles to the Gutenberg editor.
 */
function aqualuxe_gutenberg_editor_assets() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/gutenberg.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-gutenberg-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/gutenberg.js'], array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-gutenberg-script', get_template_directory_uri() . '/assets/dist/js/gutenberg.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), $theme_version, true );
    }
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_gutenberg_editor_assets' );