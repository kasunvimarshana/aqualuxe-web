<?php
/**
 * AquaLuxe Block Styles
 *
 * @package AquaLuxe
 */

/**
 * Register custom block styles.
 */
function aqualuxe_register_block_styles() {
    // Card style for Group block
    register_block_style(
        'core/group',
        array(
            'name'         => 'aqualuxe-card',
            'label'        => __( 'Card', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-card {
                    border-radius: 0.5rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    overflow: hidden;
                }
                .dark .is-style-aqualuxe-card {
                    background-color: #1f2937;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
                }
            ',
        )
    );

    // Notice style for Group block
    register_block_style(
        'core/group',
        array(
            'name'         => 'aqualuxe-notice',
            'label'        => __( 'Notice', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-notice {
                    background-color: #e0f2fe;
                    border-left: 4px solid #0284c7;
                    padding: 1rem 1.5rem;
                    border-radius: 0 0.375rem 0.375rem 0;
                }
                .is-style-aqualuxe-notice.has-background {
                    border-left-width: 4px !important;
                }
                .dark .is-style-aqualuxe-notice {
                    background-color: rgba(2, 132, 199, 0.1);
                    border-left-color: #0284c7;
                }
            ',
        )
    );

    // Alert style for Group block
    register_block_style(
        'core/group',
        array(
            'name'         => 'aqualuxe-alert',
            'label'        => __( 'Alert', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-alert {
                    background-color: #fee2e2;
                    border-left: 4px solid #ef4444;
                    padding: 1rem 1.5rem;
                    border-radius: 0 0.375rem 0.375rem 0;
                }
                .is-style-aqualuxe-alert.has-background {
                    border-left-width: 4px !important;
                }
                .dark .is-style-aqualuxe-alert {
                    background-color: rgba(239, 68, 68, 0.1);
                    border-left-color: #ef4444;
                }
            ',
        )
    );

    // Highlight style for Paragraph block
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'aqualuxe-highlight',
            'label'        => __( 'Highlight', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-highlight {
                    background-color: #fef3c7;
                    padding: 0.2em 0.4em;
                    border-radius: 0.25em;
                }
                .dark .is-style-aqualuxe-highlight {
                    background-color: rgba(254, 243, 199, 0.2);
                    color: #fef3c7;
                }
            ',
        )
    );

    // Lead style for Paragraph block
    register_block_style(
        'core/paragraph',
        array(
            'name'         => 'aqualuxe-lead',
            'label'        => __( 'Lead', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-lead {
                    font-size: 1.25rem;
                    line-height: 1.6;
                    font-weight: 300;
                }
                @media (min-width: 768px) {
                    .is-style-aqualuxe-lead {
                        font-size: 1.5rem;
                    }
                }
            ',
        )
    );

    // Rounded style for Image block
    register_block_style(
        'core/image',
        array(
            'name'         => 'aqualuxe-rounded',
            'label'        => __( 'Rounded', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-rounded img {
                    border-radius: 0.5rem;
                    overflow: hidden;
                }
            ',
        )
    );

    // Shadow style for Image block
    register_block_style(
        'core/image',
        array(
            'name'         => 'aqualuxe-shadow',
            'label'        => __( 'Shadow', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-shadow img {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
                .dark .is-style-aqualuxe-shadow img {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
                }
            ',
        )
    );

    // Frame style for Image block
    register_block_style(
        'core/image',
        array(
            'name'         => 'aqualuxe-frame',
            'label'        => __( 'Frame', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-frame img {
                    border: 8px solid white;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }
                .dark .is-style-aqualuxe-frame img {
                    border-color: #1f2937;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
                }
            ',
        )
    );

    // Gradient style for Button block
    register_block_style(
        'core/button',
        array(
            'name'         => 'aqualuxe-gradient',
            'label'        => __( 'Gradient', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-gradient .wp-block-button__link {
                    background: linear-gradient(135deg, #0284c7 0%, #7dd3fc 100%);
                    transition: all 0.3s ease;
                }
                .is-style-aqualuxe-gradient .wp-block-button__link:hover {
                    background: linear-gradient(135deg, #0369a1 0%, #38bdf8 100%);
                    transform: translateY(-2px);
                }
            ',
        )
    );

    // Soft Shadow style for Button block
    register_block_style(
        'core/button',
        array(
            'name'         => 'aqualuxe-soft-shadow',
            'label'        => __( 'Soft Shadow', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-soft-shadow .wp-block-button__link {
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    transition: all 0.3s ease;
                }
                .is-style-aqualuxe-soft-shadow .wp-block-button__link:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
            ',
        )
    );

    // Modern style for Quote block
    register_block_style(
        'core/quote',
        array(
            'name'         => 'aqualuxe-modern',
            'label'        => __( 'Modern', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-modern {
                    background-color: #f9fafb;
                    border-left: 4px solid #0284c7;
                    padding: 1.5rem;
                    border-radius: 0 0.5rem 0.5rem 0;
                    font-style: italic;
                }
                .is-style-aqualuxe-modern p {
                    position: relative;
                    padding-left: 2rem;
                }
                .is-style-aqualuxe-modern p::before {
                    content: """;
                    position: absolute;
                    left: 0;
                    top: -0.5rem;
                    font-size: 3rem;
                    color: #0284c7;
                    opacity: 0.3;
                    font-family: Georgia, serif;
                }
                .dark .is-style-aqualuxe-modern {
                    background-color: #1f2937;
                    border-left-color: #0284c7;
                }
            ',
        )
    );

    // Testimonial style for Quote block
    register_block_style(
        'core/quote',
        array(
            'name'         => 'aqualuxe-testimonial',
            'label'        => __( 'Testimonial', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-testimonial {
                    background-color: #ffffff;
                    padding: 2rem;
                    border-radius: 0.5rem;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                    text-align: center;
                    font-style: italic;
                    border: none;
                }
                .is-style-aqualuxe-testimonial p::before {
                    content: """;
                    display: block;
                    font-size: 3rem;
                    line-height: 1;
                    color: #0284c7;
                    margin-bottom: 1rem;
                    font-family: Georgia, serif;
                }
                .is-style-aqualuxe-testimonial cite {
                    font-style: normal;
                    font-weight: 600;
                }
                .dark .is-style-aqualuxe-testimonial {
                    background-color: #1f2937;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
                }
            ',
        )
    );

    // Gradient style for Separator block
    register_block_style(
        'core/separator',
        array(
            'name'         => 'aqualuxe-gradient',
            'label'        => __( 'Gradient', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-gradient {
                    height: 3px;
                    background: linear-gradient(90deg, #0284c7 0%, #7dd3fc 100%);
                    border: none;
                }
            ',
        )
    );

    // Checked style for List block
    register_block_style(
        'core/list',
        array(
            'name'         => 'aqualuxe-checked',
            'label'        => __( 'Checked', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-checked {
                    list-style: none;
                    padding-left: 0;
                }
                .is-style-aqualuxe-checked li {
                    position: relative;
                    padding-left: 2rem;
                    margin-bottom: 0.5rem;
                }
                .is-style-aqualuxe-checked li::before {
                    content: "✓";
                    position: absolute;
                    left: 0;
                    top: 0;
                    color: #0284c7;
                    font-weight: bold;
                }
                .dark .is-style-aqualuxe-checked li::before {
                    color: #38bdf8;
                }
            ',
        )
    );

    // Arrow style for List block
    register_block_style(
        'core/list',
        array(
            'name'         => 'aqualuxe-arrow',
            'label'        => __( 'Arrow', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-arrow {
                    list-style: none;
                    padding-left: 0;
                }
                .is-style-aqualuxe-arrow li {
                    position: relative;
                    padding-left: 1.5rem;
                    margin-bottom: 0.5rem;
                }
                .is-style-aqualuxe-arrow li::before {
                    content: "→";
                    position: absolute;
                    left: 0;
                    top: 0;
                    color: #0284c7;
                }
                .dark .is-style-aqualuxe-arrow li::before {
                    color: #38bdf8;
                }
            ',
        )
    );

    // Boxed style for Table block
    register_block_style(
        'core/table',
        array(
            'name'         => 'aqualuxe-boxed',
            'label'        => __( 'Boxed', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-boxed {
                    border-collapse: separate;
                    border-spacing: 0;
                    border-radius: 0.5rem;
                    overflow: hidden;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }
                .is-style-aqualuxe-boxed th {
                    background-color: #0284c7;
                    color: white;
                    border: none;
                    text-align: left;
                    padding: 0.75rem 1rem;
                }
                .is-style-aqualuxe-boxed td {
                    border: 1px solid #e5e7eb;
                    padding: 0.75rem 1rem;
                }
                .is-style-aqualuxe-boxed tr:nth-child(even) td {
                    background-color: #f9fafb;
                }
                .dark .is-style-aqualuxe-boxed {
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
                }
                .dark .is-style-aqualuxe-boxed th {
                    background-color: #0369a1;
                }
                .dark .is-style-aqualuxe-boxed td {
                    border-color: #374151;
                }
                .dark .is-style-aqualuxe-boxed tr:nth-child(even) td {
                    background-color: #1f2937;
                }
            ',
        )
    );

    // Card style for Cover block
    register_block_style(
        'core/cover',
        array(
            'name'         => 'aqualuxe-card',
            'label'        => __( 'Card', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-card {
                    border-radius: 0.5rem;
                    overflow: hidden;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }
                .dark .is-style-aqualuxe-card {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
                }
            ',
        )
    );

    // Fancy style for Heading block
    register_block_style(
        'core/heading',
        array(
            'name'         => 'aqualuxe-fancy',
            'label'        => __( 'Fancy', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-fancy {
                    position: relative;
                    display: inline-block;
                    padding-bottom: 0.5rem;
                }
                .is-style-aqualuxe-fancy::after {
                    content: "";
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    width: 3rem;
                    height: 3px;
                    background: linear-gradient(90deg, #0284c7 0%, #7dd3fc 100%);
                }
            ',
        )
    );

    // Underline style for Heading block
    register_block_style(
        'core/heading',
        array(
            'name'         => 'aqualuxe-underline',
            'label'        => __( 'Underline', 'aqualuxe' ),
            'inline_style' => '
                .is-style-aqualuxe-underline {
                    position: relative;
                    display: inline-block;
                    border-bottom: 2px solid #0284c7;
                    padding-bottom: 0.25rem;
                }
                .dark .is-style-aqualuxe-underline {
                    border-bottom-color: #38bdf8;
                }
            ',
        )
    );
}
add_action( 'init', 'aqualuxe_register_block_styles' );

/**
 * Add editor styles for custom block styles.
 */
function aqualuxe_add_editor_styles() {
    // Add editor styles for custom block styles
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'admin_init', 'aqualuxe_add_editor_styles' );

/**
 * Add theme support for block styles.
 */
function aqualuxe_setup_block_styles() {
    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );
    
    // Add support for wide and full alignments
    add_theme_support( 'align-wide' );
    
    // Add support for custom line heights
    add_theme_support( 'custom-line-height' );
    
    // Add support for custom units
    add_theme_support( 'custom-units' );
    
    // Add support for custom spacing
    add_theme_support( 'custom-spacing' );
    
    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    
    // Add support for experimental link color control
    add_theme_support( 'experimental-link-color' );
    
    // Add support for custom units in dimensions controls
    add_theme_support( 'custom-units', 'px', 'em', 'rem', 'vh', 'vw', '%' );
}
add_action( 'after_setup_theme', 'aqualuxe_setup_block_styles' );