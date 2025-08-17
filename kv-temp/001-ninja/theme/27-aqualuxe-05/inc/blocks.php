<?php
/**
 * Custom Blocks for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom block categories
 */
function aqualuxe_register_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'aqualuxe',
                'title' => __( 'AquaLuxe', 'aqualuxe' ),
                'icon'  => 'water',
            ),
        )
    );
}
add_filter( 'block_categories_all', 'aqualuxe_register_block_categories', 10, 2 );

/**
 * Register custom blocks
 */
function aqualuxe_register_blocks() {
    // Check if Gutenberg is active
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }
    
    // Register block scripts and styles
    wp_register_script(
        'aqualuxe-blocks',
        AQUALUXE_URI . 'assets/js/blocks.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
        AQUALUXE_VERSION,
        true
    );
    
    wp_register_style(
        'aqualuxe-blocks-editor',
        AQUALUXE_URI . 'assets/css/blocks-editor.css',
        array( 'wp-edit-blocks' ),
        AQUALUXE_VERSION
    );
    
    wp_register_style(
        'aqualuxe-blocks-style',
        AQUALUXE_URI . 'assets/css/blocks.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Localize script with data
    wp_localize_script(
        'aqualuxe-blocks',
        'aqualuxeBlocksData',
        array(
            'themeUri' => AQUALUXE_URI,
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_blocks_nonce' ),
        )
    );
    
    // Register blocks
    register_block_type( 'aqualuxe/services', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_services_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'category'   => array(
                'type'    => 'string',
                'default' => '',
            ),
            'columns'    => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'date',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'DESC',
            ),
            'layout'     => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    register_block_type( 'aqualuxe/events', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_events_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'category'   => array(
                'type'    => 'string',
                'default' => '',
            ),
            'columns'    => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'meta_value',
            ),
            'meta_key'   => array(
                'type'    => 'string',
                'default' => '_event_date',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'ASC',
            ),
            'layout'     => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'show_past'  => array(
                'type'    => 'boolean',
                'default' => false,
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    register_block_type( 'aqualuxe/testimonials', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_testimonials_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'date',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'DESC',
            ),
            'layout'     => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'columns'    => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    register_block_type( 'aqualuxe/team', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_team_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => -1,
            ),
            'category'   => array(
                'type'    => 'string',
                'default' => '',
            ),
            'columns'    => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'menu_order',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'ASC',
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    register_block_type( 'aqualuxe/projects', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_projects_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => 6,
            ),
            'category'   => array(
                'type'    => 'string',
                'default' => '',
            ),
            'columns'    => array(
                'type'    => 'number',
                'default' => 3,
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'date',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'DESC',
            ),
            'layout'     => array(
                'type'    => 'string',
                'default' => 'grid',
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    register_block_type( 'aqualuxe/faqs', array(
        'editor_script'   => 'aqualuxe-blocks',
        'editor_style'    => 'aqualuxe-blocks-editor',
        'style'           => 'aqualuxe-blocks-style',
        'render_callback' => 'aqualuxe_faqs_block_render',
        'attributes'      => array(
            'count'      => array(
                'type'    => 'number',
                'default' => -1,
            ),
            'category'   => array(
                'type'    => 'string',
                'default' => '',
            ),
            'orderby'    => array(
                'type'    => 'string',
                'default' => 'menu_order',
            ),
            'order'      => array(
                'type'    => 'string',
                'default' => 'ASC',
            ),
            'className'  => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ) );
    
    // WooCommerce blocks
    if ( class_exists( 'WooCommerce' ) ) {
        register_block_type( 'aqualuxe/featured-products', array(
            'editor_script'   => 'aqualuxe-blocks',
            'editor_style'    => 'aqualuxe-blocks-editor',
            'style'           => 'aqualuxe-blocks-style',
            'render_callback' => 'aqualuxe_featured_products_block_render',
            'attributes'      => array(
                'count'      => array(
                    'type'    => 'number',
                    'default' => 4,
                ),
                'columns'    => array(
                    'type'    => 'number',
                    'default' => 4,
                ),
                'category'   => array(
                    'type'    => 'string',
                    'default' => '',
                ),
                'orderby'    => array(
                    'type'    => 'string',
                    'default' => 'date',
                ),
                'order'      => array(
                    'type'    => 'string',
                    'default' => 'DESC',
                ),
                'className'  => array(
                    'type'    => 'string',
                    'default' => '',
                ),
            ),
        ) );
        
        register_block_type( 'aqualuxe/product-categories', array(
            'editor_script'   => 'aqualuxe-blocks',
            'editor_style'    => 'aqualuxe-blocks-editor',
            'style'           => 'aqualuxe-blocks-style',
            'render_callback' => 'aqualuxe_product_categories_block_render',
            'attributes'      => array(
                'count'      => array(
                    'type'    => 'number',
                    'default' => -1,
                ),
                'columns'    => array(
                    'type'    => 'number',
                    'default' => 4,
                ),
                'hide_empty' => array(
                    'type'    => 'boolean',
                    'default' => true,
                ),
                'orderby'    => array(
                    'type'    => 'string',
                    'default' => 'name',
                ),
                'order'      => array(
                    'type'    => 'string',
                    'default' => 'ASC',
                ),
                'parent'     => array(
                    'type'    => 'string',
                    'default' => '',
                ),
                'className'  => array(
                    'type'    => 'string',
                    'default' => '',
                ),
            ),
        ) );
    }
}
add_action( 'init', 'aqualuxe_register_blocks' );

/**
 * Render Services block
 */
function aqualuxe_services_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => 3,
        'category'   => '',
        'columns'    => 3,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'layout'     => 'grid',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'category'   => $attributes['category'],
        'columns'    => $attributes['columns'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
        'layout'     => $attributes['layout'],
    );
    
    $shortcode = '[aqualuxe_services';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Events block
 */
function aqualuxe_events_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => 3,
        'category'   => '',
        'columns'    => 3,
        'orderby'    => 'meta_value',
        'meta_key'   => '_event_date',
        'order'      => 'ASC',
        'layout'     => 'grid',
        'show_past'  => false,
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'category'   => $attributes['category'],
        'columns'    => $attributes['columns'],
        'orderby'    => $attributes['orderby'],
        'meta_key'   => $attributes['meta_key'],
        'order'      => $attributes['order'],
        'layout'     => $attributes['layout'],
        'show_past'  => $attributes['show_past'] ? 'yes' : 'no',
    );
    
    $shortcode = '[aqualuxe_events';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Testimonials block
 */
function aqualuxe_testimonials_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => 3,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'layout'     => 'grid',
        'columns'    => 3,
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
        'layout'     => $attributes['layout'],
        'columns'    => $attributes['columns'],
    );
    
    $shortcode = '[aqualuxe_testimonials';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Team block
 */
function aqualuxe_team_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => -1,
        'category'   => '',
        'columns'    => 3,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'category'   => $attributes['category'],
        'columns'    => $attributes['columns'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
    );
    
    $shortcode = '[aqualuxe_team';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Projects block
 */
function aqualuxe_projects_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => 6,
        'category'   => '',
        'columns'    => 3,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'layout'     => 'grid',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'category'   => $attributes['category'],
        'columns'    => $attributes['columns'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
        'layout'     => $attributes['layout'],
    );
    
    $shortcode = '[aqualuxe_projects';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render FAQs block
 */
function aqualuxe_faqs_block_render( $attributes ) {
    $attributes = wp_parse_args( $attributes, array(
        'count'      => -1,
        'category'   => '',
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'count'      => $attributes['count'],
        'category'   => $attributes['category'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
    );
    
    $shortcode = '[aqualuxe_faqs';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Featured Products block
 */
function aqualuxe_featured_products_block_render( $attributes ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }
    
    $attributes = wp_parse_args( $attributes, array(
        'count'      => 4,
        'columns'    => 4,
        'category'   => '',
        'orderby'    => 'date',
        'order'      => 'DESC',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'limit'      => $attributes['count'],
        'columns'    => $attributes['columns'],
        'category'   => $attributes['category'],
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
    );
    
    $shortcode = '[featured_products';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Render Product Categories block
 */
function aqualuxe_product_categories_block_render( $attributes ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '';
    }
    
    $attributes = wp_parse_args( $attributes, array(
        'count'      => -1,
        'columns'    => 4,
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
        'parent'     => '',
        'className'  => '',
    ) );
    
    $shortcode_atts = array(
        'number'     => $attributes['count'],
        'columns'    => $attributes['columns'],
        'hide_empty' => $attributes['hide_empty'] ? 1 : 0,
        'orderby'    => $attributes['orderby'],
        'order'      => $attributes['order'],
        'parent'     => $attributes['parent'],
    );
    
    $shortcode = '[product_categories';
    
    foreach ( $shortcode_atts as $key => $value ) {
        if ( ! empty( $value ) || $value === 0 ) {
            $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    $shortcode .= ']';
    
    $output = do_shortcode( $shortcode );
    
    if ( ! empty( $attributes['className'] ) ) {
        $output = '<div class="' . esc_attr( $attributes['className'] ) . '">' . $output . '</div>';
    }
    
    return $output;
}

/**
 * Enqueue block assets for editor
 */
function aqualuxe_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'aqualuxe-blocks-editor',
        AQUALUXE_URI . 'assets/js/blocks-editor.js',
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-data' ),
        AQUALUXE_VERSION,
        true
    );
    
    wp_localize_script(
        'aqualuxe-blocks-editor',
        'aqualuxeBlocksEditorData',
        array(
            'themeUri'         => AQUALUXE_URI,
            'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
            'nonce'            => wp_create_nonce( 'aqualuxe_blocks_editor_nonce' ),
            'serviceCategories' => aqualuxe_get_taxonomy_terms( 'service_category' ),
            'eventCategories'   => aqualuxe_get_taxonomy_terms( 'event_category' ),
            'projectCategories' => aqualuxe_get_taxonomy_terms( 'project_category' ),
            'teamCategories'    => aqualuxe_get_taxonomy_terms( 'team_category' ),
            'faqCategories'     => aqualuxe_get_taxonomy_terms( 'faq_category' ),
            'productCategories' => class_exists( 'WooCommerce' ) ? aqualuxe_get_taxonomy_terms( 'product_cat' ) : array(),
        )
    );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_enqueue_block_editor_assets' );

/**
 * Get taxonomy terms for block editor
 */
function aqualuxe_get_taxonomy_terms( $taxonomy ) {
    $terms = get_terms( array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
    ) );
    
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return array();
    }
    
    $term_options = array(
        array(
            'value' => '',
            'label' => __( 'All Categories', 'aqualuxe' ),
        ),
    );
    
    foreach ( $terms as $term ) {
        $term_options[] = array(
            'value' => $term->slug,
            'label' => $term->name,
        );
    }
    
    return $term_options;
}

/**
 * Register block patterns
 */
function aqualuxe_register_block_patterns() {
    if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
        return;
    }
    
    // Register block pattern category
    register_block_pattern_category(
        'aqualuxe',
        array( 'label' => __( 'AquaLuxe', 'aqualuxe' ) )
    );
    
    // Register block patterns
    register_block_pattern(
        'aqualuxe/hero-section',
        array(
            'title'       => __( 'Hero Section', 'aqualuxe' ),
            'description' => __( 'A hero section with heading, text, and button.', 'aqualuxe' ),
            'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#1e40af"}},"textColor":"white","className":"aqualuxe-hero-section"} -->
<div class="wp-block-group alignfull aqualuxe-hero-section has-white-color has-text-color has-background" style="background-color:#1e40af;padding-top:80px;padding-bottom:80px"><!-- wp:group {"layout":{"inherit":true}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"48px","fontWeight":"700"}}} -->
<h1 class="has-text-align-center" style="font-size:48px;font-weight:700">Premium Ornamental Fish &amp; Aquatic Solutions</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px">Bringing elegance to aquatic life – globally.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"blue-900","style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-blue-900-color has-white-background-color has-text-color has-background" style="border-radius:4px">Shop Now</a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"white","style":{"border":{"radius":"4px"},"color":{"background":"transparent"}},"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-white-color has-text-color has-background" style="border-radius:4px;background-color:transparent">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories'  => array( 'aqualuxe' ),
        )
    );
    
    register_block_pattern(
        'aqualuxe/features-section',
        array(
            'title'       => __( 'Features Section', 'aqualuxe' ),
            'description' => __( 'A section with three feature columns.', 'aqualuxe' ),
            'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"className":"aqualuxe-features-section"} -->
<div class="wp-block-group alignfull aqualuxe-features-section" style="padding-top:80px;padding-bottom:80px"><!-- wp:group {"layout":{"inherit":true}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"36px","fontWeight":"700"}}} -->
<h2 class="has-text-align-center" style="font-size:36px;font-weight:700">Our Services</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Discover our premium aquatic solutions for your home or business.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"margin":{"top":"40px"}}}} -->
<div class="wp-block-columns" style="margin-top:40px"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"white","className":"has-shadow"} -->
<div class="wp-block-group has-shadow has-white-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"24px"}}} -->
<h3 class="has-text-align-center" style="font-size:24px">Custom Aquariums</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We design and install custom aquariums for homes, offices, and commercial spaces.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link" style="border-radius:4px">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"white","className":"has-shadow"} -->
<div class="wp-block-group has-shadow has-white-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"24px"}}} -->
<h3 class="has-text-align-center" style="font-size:24px">Maintenance Services</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Regular maintenance services to keep your aquarium clean and your fish healthy.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link" style="border-radius:4px">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"white","className":"has-shadow"} -->
<div class="wp-block-group has-shadow has-white-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3,"style":{"typography":{"fontSize":"24px"}}} -->
<h3 class="has-text-align-center" style="font-size:24px">Global Export</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We export premium ornamental fish and aquatic plants to customers worldwide.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link" style="border-radius:4px">Learn More</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories'  => array( 'aqualuxe' ),
        )
    );
    
    register_block_pattern(
        'aqualuxe/cta-section',
        array(
            'title'       => __( 'Call to Action Section', 'aqualuxe' ),
            'description' => __( 'A call to action section with heading, text, and button.', 'aqualuxe' ),
            'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}},"color":{"background":"#0ea5e9"}},"textColor":"white","className":"aqualuxe-cta-section"} -->
<div class="wp-block-group alignfull aqualuxe-cta-section has-white-color has-text-color has-background" style="background-color:#0ea5e9;padding-top:60px;padding-bottom:60px"><!-- wp:group {"layout":{"inherit":true}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"36px","fontWeight":"700"}}} -->
<h2 class="has-text-align-center" style="font-size:36px;font-weight:700">Ready to Transform Your Space?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px">Contact us today for a free consultation and quote for your custom aquarium project.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"white","textColor":"blue-600","style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-blue-600-color has-white-background-color has-text-color has-background" style="border-radius:4px">Get a Quote</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
            'categories'  => array( 'aqualuxe' ),
        )
    );
}
add_action( 'init', 'aqualuxe_register_block_patterns' );