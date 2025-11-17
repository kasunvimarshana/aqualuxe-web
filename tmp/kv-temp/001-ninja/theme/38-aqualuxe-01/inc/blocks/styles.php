<?php
/**
 * Block Styles
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Custom Block Styles
 */
function aqualuxe_register_block_styles() {
    // Button styles
    register_block_style(
        'core/button',
        array(
            'name'         => 'fill-primary',
            'label'        => __( 'Fill Primary', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'fill-secondary',
            'label'        => __( 'Fill Secondary', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'outline-primary',
            'label'        => __( 'Outline Primary', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'outline-secondary',
            'label'        => __( 'Outline Secondary', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'pill',
            'label'        => __( 'Pill', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/button',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
        )
    );
    
    // Heading styles
    register_block_style(
        'core/heading',
        array(
            'name'         => 'underline',
            'label'        => __( 'Underline', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/heading',
        array(
            'name'         => 'highlight',
            'label'        => __( 'Highlight', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/heading',
        array(
            'name'         => 'accent-line',
            'label'        => __( 'Accent Line', 'aqualuxe' ),
        )
    );
    
    // Paragraph styles
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'lead',
            'label'        => __( 'Lead', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'small',
            'label'        => __( 'Small', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'fancy',
            'label'        => __( 'Fancy', 'aqualuxe' ),
        )
    );
    
    // List styles
    register_block_style(
        'core/list',
        array(
            'name'         => 'check-list',
            'label'        => __( 'Check List', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/list',
        array(
            'name'         => 'arrow-list',
            'label'        => __( 'Arrow List', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/list',
        array(
            'name'         => 'star-list',
            'label'        => __( 'Star List', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/list',
        array(
            'name'         => 'no-bullet',
            'label'        => __( 'No Bullet', 'aqualuxe' ),
        )
    );
    
    // Image styles
    register_block_style(
        'core/image',
        array(
            'name'         => 'rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/image',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/image',
        array(
            'name'         => 'frame',
            'label'        => __( 'Frame', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/image',
        array(
            'name'         => 'zoom-on-hover',
            'label'        => __( 'Zoom on Hover', 'aqualuxe' ),
        )
    );
    
    // Group styles
    register_block_style(
        'core/group',
        array(
            'name'         => 'card',
            'label'        => __( 'Card', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'border',
            'label'        => __( 'Border', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/group',
        array(
            'name'         => 'overlay',
            'label'        => __( 'Overlay', 'aqualuxe' ),
        )
    );
    
    // Columns styles
    register_block_style(
        'core/columns',
        array(
            'name'         => 'no-gap',
            'label'        => __( 'No Gap', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/columns',
        array(
            'name'         => 'large-gap',
            'label'        => __( 'Large Gap', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/columns',
        array(
            'name'         => 'card-grid',
            'label'        => __( 'Card Grid', 'aqualuxe' ),
        )
    );
    
    // Quote styles
    register_block_style(
        'core/quote',
        array(
            'name'         => 'fancy',
            'label'        => __( 'Fancy', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/quote',
        array(
            'name'         => 'testimonial',
            'label'        => __( 'Testimonial', 'aqualuxe' ),
        )
    );
    
    // Cover styles
    register_block_style(
        'core/cover',
        array(
            'name'         => 'rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/cover',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/cover',
        array(
            'name'         => 'gradient-overlay',
            'label'        => __( 'Gradient Overlay', 'aqualuxe' ),
        )
    );
    
    // Table styles
    register_block_style(
        'core/table',
        array(
            'name'         => 'clean',
            'label'        => __( 'Clean', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/table',
        array(
            'name'         => 'minimal',
            'label'        => __( 'Minimal', 'aqualuxe' ),
        )
    );
    
    // Separator styles
    register_block_style(
        'core/separator',
        array(
            'name'         => 'fancy',
            'label'        => __( 'Fancy', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/separator',
        array(
            'name'         => 'gradient',
            'label'        => __( 'Gradient', 'aqualuxe' ),
        )
    );
    
    // Media & Text styles
    register_block_style(
        'core/media-text',
        array(
            'name'         => 'rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/media-text',
        array(
            'name'         => 'shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
        )
    );
    
    register_block_style(
        'core/media-text',
        array(
            'name'         => 'overlap',
            'label'        => __( 'Overlap', 'aqualuxe' ),
        )
    );
}
add_action( 'init', 'aqualuxe_register_block_styles' );

/**
 * Enqueue Block Styles
 */
function aqualuxe_enqueue_block_styles() {
    wp_enqueue_style(
        'aqualuxe-block-styles',
        get_template_directory_uri() . '/assets/css/block-styles.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action( 'enqueue_block_assets', 'aqualuxe_enqueue_block_styles' );