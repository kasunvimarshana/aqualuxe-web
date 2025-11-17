<?php
/**
 * Markup helper functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Sanitize and escape HTML attributes
 *
 * @param array $attrs Attributes to sanitize and escape
 * @return string Escaped HTML attributes
 */
function aqualuxe_attr( $attrs = [] ) {
    if ( empty( $attrs ) ) {
        return '';
    }
    
    $html = '';
    
    foreach ( $attrs as $key => $value ) {
        if ( is_array( $value ) ) {
            $value = implode( ' ', $value );
        }
        
        if ( in_array( $key, [ 'aria', 'data' ], true ) && is_array( $value ) ) {
            foreach ( $value as $k => $v ) {
                $html .= ' ' . $key . '-' . $k . '="' . esc_attr( $v ) . '"';
            }
        } else {
            $html .= ' ' . $key . '="' . esc_attr( $value ) . '"';
        }
    }
    
    return $html;
}

/**
 * Generate HTML attributes string
 *
 * @param array $attrs Attributes to generate
 * @return string HTML attributes string
 */
function aqualuxe_get_attr( $attrs = [] ) {
    return aqualuxe_attr( $attrs );
}

/**
 * Output HTML attributes
 *
 * @param array $attrs Attributes to output
 * @return void
 */
function aqualuxe_the_attr( $attrs = [] ) {
    echo aqualuxe_attr( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate CSS classes string
 *
 * @param array|string $classes Classes to generate
 * @return string CSS classes string
 */
function aqualuxe_classes( $classes = [] ) {
    if ( empty( $classes ) ) {
        return '';
    }
    
    if ( is_string( $classes ) ) {
        $classes = explode( ' ', $classes );
    }
    
    $classes = array_map( 'sanitize_html_class', $classes );
    $classes = array_filter( $classes );
    
    return implode( ' ', $classes );
}

/**
 * Generate CSS classes array
 *
 * @param array|string $classes Classes to generate
 * @return array CSS classes array
 */
function aqualuxe_get_classes( $classes = [] ) {
    if ( empty( $classes ) ) {
        return [];
    }
    
    if ( is_string( $classes ) ) {
        $classes = explode( ' ', $classes );
    }
    
    $classes = array_map( 'sanitize_html_class', $classes );
    $classes = array_filter( $classes );
    
    return $classes;
}

/**
 * Output CSS classes
 *
 * @param array|string $classes Classes to output
 * @return void
 */
function aqualuxe_the_classes( $classes = [] ) {
    echo esc_attr( aqualuxe_classes( $classes ) );
}

/**
 * Generate HTML element
 *
 * @param string $tag HTML tag
 * @param array $attrs HTML attributes
 * @param string $content Element content
 * @return string HTML element
 */
function aqualuxe_element( $tag, $attrs = [], $content = '' ) {
    $html = '<' . $tag . aqualuxe_attr( $attrs ) . '>';
    
    if ( ! in_array( $tag, [ 'img', 'input', 'br', 'hr', 'meta', 'link' ], true ) ) {
        $html .= $content;
        $html .= '</' . $tag . '>';
    }
    
    return $html;
}

/**
 * Output HTML element
 *
 * @param string $tag HTML tag
 * @param array $attrs HTML attributes
 * @param string $content Element content
 * @return void
 */
function aqualuxe_the_element( $tag, $attrs = [], $content = '' ) {
    echo aqualuxe_element( $tag, $attrs, $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate SVG icon
 *
 * @param string $icon Icon name
 * @param array $attrs Icon attributes
 * @return string SVG icon
 */
function aqualuxe_icon( $icon, $attrs = [] ) {
    $default_attrs = [
        'class' => 'icon icon-' . $icon,
        'aria-hidden' => 'true',
        'focusable' => 'false',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    $icon_path = AQUALUXE_DIR . 'assets/dist/images/icons/' . $icon . '.svg';
    
    if ( ! file_exists( $icon_path ) ) {
        return '';
    }
    
    $icon_content = file_get_contents( $icon_path );
    
    if ( ! $icon_content ) {
        return '';
    }
    
    // Extract SVG attributes
    $svg_attrs = [];
    preg_match( '/<svg([^>]+)>/', $icon_content, $svg_tag );
    
    if ( ! empty( $svg_tag[1] ) ) {
        preg_match_all( '/(\w+)="([^"]+)"/', $svg_tag[1], $svg_attrs_matches, PREG_SET_ORDER );
        
        foreach ( $svg_attrs_matches as $svg_attr ) {
            $svg_attrs[ $svg_attr[1] ] = $svg_attr[2];
        }
    }
    
    // Merge SVG attributes with custom attributes
    $attrs = wp_parse_args( $attrs, $svg_attrs );
    
    // Remove SVG tag
    $icon_content = preg_replace( '/<svg[^>]+>/', '', $icon_content );
    $icon_content = str_replace( '</svg>', '', $icon_content );
    
    return aqualuxe_element( 'svg', $attrs, $icon_content );
}

/**
 * Output SVG icon
 *
 * @param string $icon Icon name
 * @param array $attrs Icon attributes
 * @return void
 */
function aqualuxe_the_icon( $icon, $attrs = [] ) {
    echo aqualuxe_icon( $icon, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate button
 *
 * @param string $text Button text
 * @param string $url Button URL
 * @param array $attrs Button attributes
 * @return string Button HTML
 */
function aqualuxe_button( $text, $url = '', $attrs = [] ) {
    $default_attrs = [
        'class' => 'button',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    if ( ! empty( $url ) ) {
        $attrs['href'] = esc_url( $url );
        return aqualuxe_element( 'a', $attrs, $text );
    }
    
    return aqualuxe_element( 'button', $attrs, $text );
}

/**
 * Output button
 *
 * @param string $text Button text
 * @param string $url Button URL
 * @param array $attrs Button attributes
 * @return void
 */
function aqualuxe_the_button( $text, $url = '', $attrs = [] ) {
    echo aqualuxe_button( $text, $url, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate image
 *
 * @param int|string $image Image ID or URL
 * @param string $size Image size
 * @param array $attrs Image attributes
 * @return string Image HTML
 */
function aqualuxe_image( $image, $size = 'full', $attrs = [] ) {
    if ( empty( $image ) ) {
        return '';
    }
    
    $default_attrs = [
        'class' => 'image',
        'loading' => 'lazy',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    if ( is_numeric( $image ) ) {
        return wp_get_attachment_image( $image, $size, false, $attrs );
    }
    
    if ( is_string( $image ) ) {
        $attrs['src'] = esc_url( $image );
        
        if ( ! isset( $attrs['alt'] ) ) {
            $attrs['alt'] = '';
        }
        
        return aqualuxe_element( 'img', $attrs );
    }
    
    return '';
}

/**
 * Output image
 *
 * @param int|string $image Image ID or URL
 * @param string $size Image size
 * @param array $attrs Image attributes
 * @return void
 */
function aqualuxe_the_image( $image, $size = 'full', $attrs = [] ) {
    echo aqualuxe_image( $image, $size, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate responsive image
 *
 * @param int $image_id Image ID
 * @param array $sizes Image sizes
 * @param array $attrs Image attributes
 * @return string Responsive image HTML
 */
function aqualuxe_responsive_image( $image_id, $sizes = [], $attrs = [] ) {
    if ( empty( $image_id ) || ! is_numeric( $image_id ) ) {
        return '';
    }
    
    $default_sizes = [
        'full' => '100vw',
        'large' => '(min-width: 1200px) 1200px, 100vw',
        'medium' => '(min-width: 768px) 768px, 100vw',
        'thumbnail' => '(min-width: 480px) 480px, 100vw',
    ];
    
    $sizes = wp_parse_args( $sizes, $default_sizes );
    
    $default_attrs = [
        'class' => 'image',
        'loading' => 'lazy',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    $srcset = [];
    $sizes_attr = [];
    
    foreach ( $sizes as $size => $media ) {
        $image = wp_get_attachment_image_src( $image_id, $size );
        
        if ( $image ) {
            $srcset[] = $image[0] . ' ' . $image[1] . 'w';
            $sizes_attr[] = $media;
        }
    }
    
    if ( empty( $srcset ) ) {
        return '';
    }
    
    $attrs['srcset'] = implode( ', ', $srcset );
    $attrs['sizes'] = implode( ', ', $sizes_attr );
    
    $image = wp_get_attachment_image_src( $image_id, 'full' );
    
    if ( ! $image ) {
        return '';
    }
    
    $attrs['src'] = $image[0];
    
    if ( ! isset( $attrs['alt'] ) ) {
        $attrs['alt'] = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
    }
    
    return aqualuxe_element( 'img', $attrs );
}

/**
 * Output responsive image
 *
 * @param int $image_id Image ID
 * @param array $sizes Image sizes
 * @param array $attrs Image attributes
 * @return void
 */
function aqualuxe_the_responsive_image( $image_id, $sizes = [], $attrs = [] ) {
    echo aqualuxe_responsive_image( $image_id, $sizes, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate section
 *
 * @param string $content Section content
 * @param array $attrs Section attributes
 * @return string Section HTML
 */
function aqualuxe_section( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'section',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'section', $attrs, $content );
}

/**
 * Output section
 *
 * @param string $content Section content
 * @param array $attrs Section attributes
 * @return void
 */
function aqualuxe_the_section( $content, $attrs = [] ) {
    echo aqualuxe_section( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate container
 *
 * @param string $content Container content
 * @param array $attrs Container attributes
 * @return string Container HTML
 */
function aqualuxe_container( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'container',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'div', $attrs, $content );
}

/**
 * Output container
 *
 * @param string $content Container content
 * @param array $attrs Container attributes
 * @return void
 */
function aqualuxe_the_container( $content, $attrs = [] ) {
    echo aqualuxe_container( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate row
 *
 * @param string $content Row content
 * @param array $attrs Row attributes
 * @return string Row HTML
 */
function aqualuxe_row( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'row',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'div', $attrs, $content );
}

/**
 * Output row
 *
 * @param string $content Row content
 * @param array $attrs Row attributes
 * @return void
 */
function aqualuxe_the_row( $content, $attrs = [] ) {
    echo aqualuxe_row( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate column
 *
 * @param string $content Column content
 * @param array $attrs Column attributes
 * @return string Column HTML
 */
function aqualuxe_column( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'column',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'div', $attrs, $content );
}

/**
 * Output column
 *
 * @param string $content Column content
 * @param array $attrs Column attributes
 * @return void
 */
function aqualuxe_the_column( $content, $attrs = [] ) {
    echo aqualuxe_column( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate card
 *
 * @param string $content Card content
 * @param array $attrs Card attributes
 * @return string Card HTML
 */
function aqualuxe_card( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'card',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'div', $attrs, $content );
}

/**
 * Output card
 *
 * @param string $content Card content
 * @param array $attrs Card attributes
 * @return void
 */
function aqualuxe_the_card( $content, $attrs = [] ) {
    echo aqualuxe_card( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate heading
 *
 * @param string $content Heading content
 * @param int $level Heading level
 * @param array $attrs Heading attributes
 * @return string Heading HTML
 */
function aqualuxe_heading( $content, $level = 2, $attrs = [] ) {
    $level = min( 6, max( 1, absint( $level ) ) );
    
    $default_attrs = [
        'class' => 'heading heading-' . $level,
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'h' . $level, $attrs, $content );
}

/**
 * Output heading
 *
 * @param string $content Heading content
 * @param int $level Heading level
 * @param array $attrs Heading attributes
 * @return void
 */
function aqualuxe_the_heading( $content, $level = 2, $attrs = [] ) {
    echo aqualuxe_heading( $content, $level, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate paragraph
 *
 * @param string $content Paragraph content
 * @param array $attrs Paragraph attributes
 * @return string Paragraph HTML
 */
function aqualuxe_paragraph( $content, $attrs = [] ) {
    $default_attrs = [
        'class' => 'paragraph',
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'p', $attrs, $content );
}

/**
 * Output paragraph
 *
 * @param string $content Paragraph content
 * @param array $attrs Paragraph attributes
 * @return void
 */
function aqualuxe_the_paragraph( $content, $attrs = [] ) {
    echo aqualuxe_paragraph( $content, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate link
 *
 * @param string $content Link content
 * @param string $url Link URL
 * @param array $attrs Link attributes
 * @return string Link HTML
 */
function aqualuxe_link( $content, $url, $attrs = [] ) {
    $default_attrs = [
        'class' => 'link',
        'href' => esc_url( $url ),
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return aqualuxe_element( 'a', $attrs, $content );
}

/**
 * Output link
 *
 * @param string $content Link content
 * @param string $url Link URL
 * @param array $attrs Link attributes
 * @return void
 */
function aqualuxe_the_link( $content, $url, $attrs = [] ) {
    echo aqualuxe_link( $content, $url, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Generate list
 *
 * @param array $items List items
 * @param string $type List type
 * @param array $attrs List attributes
 * @return string List HTML
 */
function aqualuxe_list( $items, $type = 'ul', $attrs = [] ) {
    if ( empty( $items ) ) {
        return '';
    }
    
    $type = in_array( $type, [ 'ul', 'ol' ], true ) ? $type : 'ul';
    
    $default_attrs = [
        'class' => 'list list-' . $type,
    ];
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    $items_html = '';
    
    foreach ( $items as $item ) {
        $items_html .= aqualuxe_element( 'li', [ 'class' => 'list-item' ], $item );
    }
    
    return aqualuxe_element( $type, $attrs, $items_html );
}

/**
 * Output list
 *
 * @param array $items List items
 * @param string $type List type
 * @param array $attrs List attributes
 * @return void
 */
function aqualuxe_the_list( $items, $type = 'ul', $attrs = [] ) {
    echo aqualuxe_list( $items, $type, $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}