<?php
/**
 * Template part for displaying a hero section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display a hero section
 *
 * @param array $args {
 *     Optional. Arguments to customize the hero section.
 *
 *     @type string $title       The hero section title.
 *     @type string $subtitle    The hero section subtitle.
 *     @type string $description The hero section description.
 *     @type string $image       The hero section background image URL.
 *     @type string $overlay     The hero section overlay color (hex code).
 *     @type string $text_color  The hero section text color (light or dark).
 *     @type array  $buttons     Array of button arrays with 'text', 'url', and 'style' keys.
 *     @type string $height      The hero section height (small, medium, large, full).
 *     @type string $alignment   The hero section content alignment (left, center, right).
 * }
 */

$defaults = array(
    'title'       => '',
    'subtitle'    => '',
    'description' => '',
    'image'       => '',
    'overlay'     => 'rgba(0, 0, 0, 0.5)',
    'text_color'  => 'light',
    'buttons'     => array(),
    'height'      => 'medium',
    'alignment'   => 'center',
);

$args = wp_parse_args( $args, $defaults );

// Set height class
switch ( $args['height'] ) {
    case 'small':
        $height_class = 'min-h-[300px] md:min-h-[400px]';
        break;
    case 'medium':
        $height_class = 'min-h-[400px] md:min-h-[500px]';
        break;
    case 'large':
        $height_class = 'min-h-[500px] md:min-h-[600px]';
        break;
    case 'full':
        $height_class = 'min-h-screen';
        break;
    default:
        $height_class = 'min-h-[400px] md:min-h-[500px]';
}

// Set alignment class
switch ( $args['alignment'] ) {
    case 'left':
        $alignment_class = 'text-left items-start';
        break;
    case 'center':
        $alignment_class = 'text-center items-center';
        break;
    case 'right':
        $alignment_class = 'text-right items-end';
        break;
    default:
        $alignment_class = 'text-center items-center';
}

// Set text color class
$text_color_class = ( 'dark' === $args['text_color'] ) ? 'text-gray-800' : 'text-white';

// Set background style
$bg_style = '';
if ( $args['image'] ) {
    $bg_style = 'background-image: url(' . esc_url( $args['image'] ) . '); background-size: cover; background-position: center;';
}

// Set overlay style
$overlay_style = 'background-color: ' . esc_attr( $args['overlay'] ) . ';';
?>

<section class="hero-section relative <?php echo esc_attr( $height_class ); ?> flex items-center justify-center overflow-hidden" style="<?php echo esc_attr( $bg_style ); ?>">
    <?php if ( $args['image'] ) : ?>
        <div class="absolute inset-0" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="hero-content flex flex-col <?php echo esc_attr( $alignment_class ); ?> max-w-3xl mx-auto">
            <?php if ( $args['subtitle'] ) : ?>
                <div class="hero-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        <?php echo esc_html( $args['subtitle'] ); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <?php if ( $args['title'] ) : ?>
                <h1 class="hero-title text-3xl md:text-5xl lg:text-6xl font-serif font-bold mb-4 <?php echo esc_attr( $text_color_class ); ?>">
                    <?php echo wp_kses_post( $args['title'] ); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ( $args['description'] ) : ?>
                <div class="hero-description text-lg md:text-xl mb-8 <?php echo esc_attr( $text_color_class ); ?>">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>
            
            <?php if ( ! empty( $args['buttons'] ) ) : ?>
                <div class="hero-buttons flex flex-wrap gap-4 justify-center">
                    <?php foreach ( $args['buttons'] as $button ) : ?>
                        <?php
                        $button_defaults = array(
                            'text'  => '',
                            'url'   => '',
                            'style' => 'primary',
                        );
                        
                        $button = wp_parse_args( $button, $button_defaults );
                        
                        // Set button class
                        switch ( $button['style'] ) {
                            case 'primary':
                                $button_class = 'bg-primary hover:bg-primary-dark text-white';
                                break;
                            case 'secondary':
                                $button_class = 'bg-secondary hover:bg-secondary-dark text-primary';
                                break;
                            case 'outline':
                                $button_class = 'bg-transparent border-2 border-white hover:bg-white hover:text-primary text-white';
                                break;
                            case 'outline-dark':
                                $button_class = 'bg-transparent border-2 border-primary hover:bg-primary hover:text-white text-primary';
                                break;
                            default:
                                $button_class = 'bg-primary hover:bg-primary-dark text-white';
                        }
                        ?>
                        
                        <?php if ( $button['text'] && $button['url'] ) : ?>
                            <a href="<?php echo esc_url( $button['url'] ); ?>" class="inline-block px-6 py-3 rounded-lg font-medium transition-colors duration-300 <?php echo esc_attr( $button_class ); ?>">
                                <?php echo esc_html( $button['text'] ); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>