<?php
/**
 * Template part for displaying a hero section
 *
 * @package AquaLuxe
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Arguments to customize the hero section.
 *
 *     @type string $title           The hero title.
 *     @type string $subtitle        The hero subtitle.
 *     @type string $content         The hero content.
 *     @type string $image           The hero background image URL.
 *     @type string $overlay         Whether to add an overlay. Default 'true'.
 *     @type string $overlay_opacity The overlay opacity. Default '0.5'.
 *     @type string $height          The hero height. Default 'medium'.
 *     @type string $text_align      The text alignment. Default 'center'.
 *     @type string $text_color      The text color. Default 'light'.
 *     @type array  $buttons         Array of button data.
 * }
 */

// Default arguments
$defaults = array(
    'title'           => '',
    'subtitle'        => '',
    'content'         => '',
    'image'           => '',
    'overlay'         => 'true',
    'overlay_opacity' => '0.5',
    'height'          => 'medium',
    'text_align'      => 'center',
    'text_color'      => 'light',
    'buttons'         => array(),
);

// Parse arguments
$args = wp_parse_args( $args, $defaults );

// Set CSS classes
$hero_classes = array(
    'hero-section',
    'hero-height-' . esc_attr( $args['height'] ),
    'hero-align-' . esc_attr( $args['text_align'] ),
    'hero-text-' . esc_attr( $args['text_color'] ),
);

// Set inline styles
$hero_style = '';
if ( ! empty( $args['image'] ) ) {
    $hero_style = 'background-image: url(' . esc_url( $args['image'] ) . ');';
    $hero_classes[] = 'hero-has-background';
}

// Set overlay style
$overlay_style = '';
if ( 'true' === $args['overlay'] && ! empty( $args['overlay_opacity'] ) ) {
    $overlay_style = 'opacity: ' . esc_attr( $args['overlay_opacity'] ) . ';';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" <?php if ( $hero_style ) echo 'style="' . esc_attr( $hero_style ) . '"'; ?>>
    <?php if ( 'true' === $args['overlay'] ) : ?>
        <div class="hero-overlay" <?php if ( $overlay_style ) echo 'style="' . esc_attr( $overlay_style ) . '"'; ?>></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="hero-content">
            <?php if ( ! empty( $args['subtitle'] ) ) : ?>
                <div class="hero-subtitle"><?php echo wp_kses_post( $args['subtitle'] ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $args['title'] ) ) : ?>
                <h1 class="hero-title"><?php echo wp_kses_post( $args['title'] ); ?></h1>
            <?php endif; ?>
            
            <?php if ( ! empty( $args['content'] ) ) : ?>
                <div class="hero-description"><?php echo wp_kses_post( $args['content'] ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $args['buttons'] ) ) : ?>
                <div class="hero-buttons">
                    <?php foreach ( $args['buttons'] as $button ) : 
                        $button_defaults = array(
                            'text'  => '',
                            'url'   => '',
                            'class' => 'btn-primary',
                            'target' => '_self',
                        );
                        $button = wp_parse_args( $button, $button_defaults );
                        
                        if ( ! empty( $button['text'] ) && ! empty( $button['url'] ) ) :
                    ?>
                        <a href="<?php echo esc_url( $button['url'] ); ?>" class="btn <?php echo esc_attr( $button['class'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>">
                            <?php echo esc_html( $button['text'] ); ?>
                        </a>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>