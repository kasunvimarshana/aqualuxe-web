<?php
/**
 * Template part for displaying a hero slider section
 *
 * @package AquaLuxe
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional. Arguments to customize the hero slider section.
 *
 *     @type array  $slides          Array of slide data.
 *     @type string $height          The hero height. Default 'medium'.
 *     @type string $animation       The slider animation. Default 'fade'.
 *     @type bool   $arrows          Whether to show navigation arrows. Default true.
 *     @type bool   $dots            Whether to show navigation dots. Default true.
 *     @type bool   $autoplay        Whether to autoplay the slider. Default true.
 *     @type int    $autoplay_speed  Autoplay speed in milliseconds. Default 5000.
 *     @type bool   $pause_on_hover  Whether to pause on hover. Default true.
 * }
 */

// Default arguments
$defaults = array(
    'slides'         => array(),
    'height'         => 'medium',
    'animation'      => 'fade',
    'arrows'         => true,
    'dots'           => true,
    'autoplay'       => true,
    'autoplay_speed' => 5000,
    'pause_on_hover' => true,
);

// Parse arguments
$args = wp_parse_args( $args, $defaults );

// Set CSS classes
$slider_classes = array(
    'hero-slider',
    'hero-height-' . esc_attr( $args['height'] ),
);

// Set data attributes for slider
$slider_data = array(
    'animation'      => esc_attr( $args['animation'] ),
    'arrows'         => $args['arrows'] ? 'true' : 'false',
    'dots'           => $args['dots'] ? 'true' : 'false',
    'autoplay'       => $args['autoplay'] ? 'true' : 'false',
    'autoplay-speed' => esc_attr( $args['autoplay_speed'] ),
    'pause-on-hover' => $args['pause_on_hover'] ? 'true' : 'false',
);

// Generate data attributes string
$data_attributes = '';
foreach ( $slider_data as $key => $value ) {
    $data_attributes .= ' data-' . $key . '="' . $value . '"';
}

// Check if we have slides
if ( empty( $args['slides'] ) ) {
    return;
}
?>

<section class="<?php echo esc_attr( implode( ' ', $slider_classes ) ); ?>"<?php echo $data_attributes; ?>>
    <div class="hero-slider-container">
        <?php foreach ( $args['slides'] as $index => $slide ) : 
            // Default slide values
            $slide_defaults = array(
                'title'           => '',
                'subtitle'        => '',
                'content'         => '',
                'image'           => '',
                'overlay'         => 'true',
                'overlay_opacity' => '0.5',
                'text_align'      => 'center',
                'text_color'      => 'light',
                'buttons'         => array(),
            );
            
            // Parse slide arguments
            $slide = wp_parse_args( $slide, $slide_defaults );
            
            // Set slide CSS classes
            $slide_classes = array(
                'hero-slide',
                'hero-align-' . esc_attr( $slide['text_align'] ),
                'hero-text-' . esc_attr( $slide['text_color'] ),
            );
            
            // Set slide inline styles
            $slide_style = '';
            if ( ! empty( $slide['image'] ) ) {
                $slide_style = 'background-image: url(' . esc_url( $slide['image'] ) . ');';
                $slide_classes[] = 'hero-has-background';
            }
            
            // Set overlay style
            $overlay_style = '';
            if ( 'true' === $slide['overlay'] && ! empty( $slide['overlay_opacity'] ) ) {
                $overlay_style = 'opacity: ' . esc_attr( $slide['overlay_opacity'] ) . ';';
            }
        ?>
            <div class="<?php echo esc_attr( implode( ' ', $slide_classes ) ); ?>" <?php if ( $slide_style ) echo 'style="' . esc_attr( $slide_style ) . '"'; ?>>
                <?php if ( 'true' === $slide['overlay'] ) : ?>
                    <div class="hero-overlay" <?php if ( $overlay_style ) echo 'style="' . esc_attr( $overlay_style ) . '"'; ?>></div>
                <?php endif; ?>
                
                <div class="container">
                    <div class="hero-content">
                        <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                            <div class="hero-subtitle"><?php echo wp_kses_post( $slide['subtitle'] ); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $slide['title'] ) ) : ?>
                            <h2 class="hero-title"><?php echo wp_kses_post( $slide['title'] ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $slide['content'] ) ) : ?>
                            <div class="hero-description"><?php echo wp_kses_post( $slide['content'] ); ?></div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $slide['buttons'] ) ) : ?>
                            <div class="hero-buttons">
                                <?php foreach ( $slide['buttons'] as $button ) : 
                                    $button_defaults = array(
                                        'text'   => '',
                                        'url'    => '',
                                        'class'  => 'btn-primary',
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
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ( $args['arrows'] ) : ?>
        <div class="hero-slider-nav">
            <button class="hero-slider-prev" aria-label="<?php esc_attr_e( 'Previous slide', 'aqualuxe' ); ?>">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-slider-next" aria-label="<?php esc_attr_e( 'Next slide', 'aqualuxe' ); ?>">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if ( $args['dots'] && count( $args['slides'] ) > 1 ) : ?>
        <div class="hero-slider-dots">
            <?php for ( $i = 0; $i < count( $args['slides'] ); $i++ ) : ?>
                <button class="hero-slider-dot<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo esc_attr( $i ); ?>" aria-label="<?php printf( esc_attr__( 'Go to slide %d', 'aqualuxe' ), $i + 1 ); ?>"></button>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>

<?php
// Add initialization script
add_action( 'wp_footer', function() {
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize hero sliders
        const heroSliders = document.querySelectorAll('.hero-slider');
        
        heroSliders.forEach(function(slider) {
            let currentSlide = 0;
            const slides = slider.querySelectorAll('.hero-slide');
            const dots = slider.querySelectorAll('.hero-slider-dot');
            const prevBtn = slider.querySelector('.hero-slider-prev');
            const nextBtn = slider.querySelector('.hero-slider-next');
            const slideCount = slides.length;
            
            // Skip initialization if only one slide
            if (slideCount <= 1) return;
            
            // Animation type
            const animation = slider.dataset.animation || 'fade';
            
            // Autoplay settings
            const autoplay = slider.dataset.autoplay === 'true';
            const autoplaySpeed = parseInt(slider.dataset.autoplaySpeed) || 5000;
            const pauseOnHover = slider.dataset.pauseOnHover === 'true';
            
            let autoplayTimer = null;
            let isHovered = false;
            
            // Show slide function
            function showSlide(index) {
                // Normalize index
                if (index < 0) index = slideCount - 1;
                if (index >= slideCount) index = 0;
                
                // Update current slide
                currentSlide = index;
                
                // Update slides
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    if (i === currentSlide) {
                        slide.classList.add('active');
                    }
                });
                
                // Update dots
                dots.forEach((dot, i) => {
                    dot.classList.remove('active');
                    if (i === currentSlide) {
                        dot.classList.add('active');
                    }
                });
                
                // Reset autoplay timer
                if (autoplay && !isHovered) {
                    resetAutoplay();
                }
            }
            
            // Next slide function
            function nextSlide() {
                showSlide(currentSlide + 1);
            }
            
            // Previous slide function
            function prevSlide() {
                showSlide(currentSlide - 1);
            }
            
            // Reset autoplay timer
            function resetAutoplay() {
                if (autoplayTimer) {
                    clearTimeout(autoplayTimer);
                }
                
                if (autoplay && !isHovered) {
                    autoplayTimer = setTimeout(nextSlide, autoplaySpeed);
                }
            }
            
            // Initialize first slide
            showSlide(0);
            
            // Add event listeners
            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                });
            }
            
            // Dot navigation
            dots.forEach(function(dot) {
                dot.addEventListener('click', function(e) {
                    e.preventDefault();
                    const slideIndex = parseInt(this.dataset.slide);
                    showSlide(slideIndex);
                });
            });
            
            // Pause on hover
            if (pauseOnHover) {
                slider.addEventListener('mouseenter', function() {
                    isHovered = true;
                    if (autoplayTimer) {
                        clearTimeout(autoplayTimer);
                    }
                });
                
                slider.addEventListener('mouseleave', function() {
                    isHovered = false;
                    resetAutoplay();
                });
            }
            
            // Start autoplay
            if (autoplay) {
                resetAutoplay();
            }
            
            // Add swipe support
            let touchStartX = 0;
            let touchEndX = 0;
            
            slider.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            slider.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                if (touchEndX < touchStartX - swipeThreshold) {
                    // Swipe left
                    nextSlide();
                }
                
                if (touchEndX > touchStartX + swipeThreshold) {
                    // Swipe right
                    prevSlide();
                }
            }
        });
    });
</script>
<?php
}, 99 );