<?php
/**
 * Template part for displaying newsletter section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'newsletter_title', 'Subscribe to Our Newsletter' );
$section_subtitle = aqualuxe_get_option( 'newsletter_subtitle', 'Stay updated with our latest products, offers, and aquatic care tips' );
$section_content = aqualuxe_get_option( 'newsletter_content', '' );
$button_text = aqualuxe_get_option( 'newsletter_button_text', 'Subscribe' );
$newsletter_style = aqualuxe_get_option( 'newsletter_style', 'standard' );
$newsletter_bg_type = aqualuxe_get_option( 'newsletter_bg_type', 'color' );
$newsletter_bg_image = aqualuxe_get_option( 'newsletter_bg_image', '' );
$newsletter_form_action = aqualuxe_get_option( 'newsletter_form_action', '#' );
$newsletter_form_method = aqualuxe_get_option( 'newsletter_form_method', 'post' );
$newsletter_form_name = aqualuxe_get_option( 'newsletter_form_name', 'mc-embedded-subscribe-form' );

// If no content is set, use default
if ( empty( $section_content ) ) {
    $section_content = 'Join our community of aquatic enthusiasts and be the first to know about new arrivals, exclusive offers, and expert tips for maintaining your aquatic paradise.';
}

// Set up section classes based on style and background type
$section_class = '';
$container_class = '';
$content_class = '';
$overlay_class = '';
$text_class = '';
$form_class = '';

// Style-specific classes
switch ( $newsletter_style ) {
    case 'boxed':
        $container_class = 'max-w-4xl mx-auto bg-white dark:bg-dark-400 rounded-lg shadow-lg p-8 md:p-12';
        $content_class = 'text-center';
        $form_class = 'max-w-md mx-auto';
        break;
    case 'split':
        $container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8 items-center';
        $content_class = '';
        $form_class = '';
        break;
    case 'minimal':
        $container_class = 'max-w-3xl mx-auto';
        $content_class = 'text-center';
        $form_class = 'max-w-md mx-auto';
        break;
    case 'standard':
    default:
        $container_class = '';
        $content_class = 'text-center max-w-3xl mx-auto';
        $form_class = 'max-w-md mx-auto';
}

// Background-specific classes
switch ( $newsletter_bg_type ) {
    case 'image':
        if ( ! empty( $newsletter_bg_image ) ) {
            $section_class = 'bg-cover bg-center relative';
            $overlay_class = 'absolute inset-0 bg-dark-500 bg-opacity-70';
            $text_class = 'text-white relative z-10';
        } else {
            $section_class = 'bg-primary-500';
            $text_class = 'text-white';
        }
        break;
    case 'primary':
        $section_class = 'bg-primary-500';
        $text_class = 'text-white';
        break;
    case 'secondary':
        $section_class = 'bg-secondary-500';
        $text_class = 'text-white';
        break;
    case 'gradient':
        $section_class = 'bg-gradient-to-r from-primary-500 to-secondary-500';
        $text_class = 'text-white';
        break;
    case 'color':
    default:
        $section_class = 'bg-gray-50 dark:bg-dark-400';
        $text_class = '';
}

// Process the content
$section_content = wpautop( $section_content );
?>

<section id="newsletter" class="newsletter py-16 <?php echo esc_attr( $section_class ); ?>" <?php if ( $newsletter_bg_type === 'image' && ! empty( $newsletter_bg_image ) ) : ?>style="background-image: url('<?php echo esc_url( $newsletter_bg_image ); ?>');"<?php endif; ?>>
    <?php if ( $newsletter_bg_type === 'image' && ! empty( $newsletter_bg_image ) ) : ?>
        <div class="<?php echo esc_attr( $overlay_class ); ?>"></div>
    <?php endif; ?>
    
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="<?php echo esc_attr( $container_class ); ?>">
            <?php if ( $newsletter_style === 'split' ) : ?>
                <div class="newsletter-content <?php echo esc_attr( $text_class ); ?>">
                    <?php if ( $section_title ) : ?>
                        <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $section_subtitle ) : ?>
                        <p class="section-subtitle text-lg <?php echo esc_attr( $text_class ? '' : 'text-gray-600 dark:text-gray-300' ); ?> mb-4"><?php echo esc_html( $section_subtitle ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( $section_content ) : ?>
                        <div class="newsletter-description prose <?php echo esc_attr( $text_class ? 'prose-invert' : 'dark:prose-invert' ); ?> max-w-none mb-4">
                            <?php echo wp_kses_post( $section_content ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="newsletter-features mt-6">
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php esc_html_e( 'Exclusive offers and discounts', 'aqualuxe' ); ?></span>
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php esc_html_e( 'New product announcements', 'aqualuxe' ); ?></span>
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php esc_html_e( 'Expert aquatic care tips', 'aqualuxe' ); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="newsletter-form-container">
                    <div class="newsletter-form-wrapper bg-white dark:bg-dark-500 rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold mb-4 <?php echo esc_attr( $text_class ? '' : 'text-dark-500 dark:text-white' ); ?>"><?php esc_html_e( 'Join Our Newsletter', 'aqualuxe' ); ?></h3>
                        
                        <form action="<?php echo esc_url( $newsletter_form_action ); ?>" method="<?php echo esc_attr( $newsletter_form_method ); ?>" name="<?php echo esc_attr( $newsletter_form_name ); ?>" class="newsletter-form space-y-4">
                            <div class="form-group">
                                <label for="newsletter-name" class="form-label"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?></label>
                                <input type="text" id="newsletter-name" name="FNAME" class="form-input w-full" placeholder="<?php esc_attr_e( 'Enter your name', 'aqualuxe' ); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="newsletter-email" class="form-label"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?></label>
                                <input type="email" id="newsletter-email" name="EMAIL" class="form-input w-full" placeholder="<?php esc_attr_e( 'Enter your email address', 'aqualuxe' ); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="privacy_consent" class="form-checkbox" required>
                                    <span class="ml-2 text-sm">
                                        <?php
                                        printf(
                                            /* translators: %s: Privacy policy link */
                                            esc_html__( 'I agree to the %s', 'aqualuxe' ),
                                            '<a href="' . esc_url( get_privacy_policy_url() ) . '" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">' . esc_html__( 'Privacy Policy', 'aqualuxe' ) . '</a>'
                                        );
                                        ?>
                                    </span>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn-primary w-full">
                                    <?php echo esc_html( $button_text ); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            
            <?php else : ?>
                <div class="newsletter-content <?php echo esc_attr( $content_class . ' ' . $text_class ); ?>">
                    <?php if ( $section_title ) : ?>
                        <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $section_subtitle ) : ?>
                        <p class="section-subtitle text-lg <?php echo esc_attr( $text_class ? '' : 'text-gray-600 dark:text-gray-300' ); ?> mb-4"><?php echo esc_html( $section_subtitle ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( $section_content ) : ?>
                        <div class="newsletter-description prose <?php echo esc_attr( $text_class ? 'prose-invert' : 'dark:prose-invert' ); ?> max-w-none mb-6">
                            <?php echo wp_kses_post( $section_content ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo esc_url( $newsletter_form_action ); ?>" method="<?php echo esc_attr( $newsletter_form_method ); ?>" name="<?php echo esc_attr( $newsletter_form_name ); ?>" class="newsletter-form <?php echo esc_attr( $form_class ); ?>">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="email" name="EMAIL" class="form-input flex-grow" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                            <button type="submit" class="btn-primary whitespace-nowrap">
                                <?php echo esc_html( $button_text ); ?>
                            </button>
                        </div>
                        
                        <div class="newsletter-privacy-notice mt-3 text-sm <?php echo esc_attr( $text_class ? '' : 'text-gray-600 dark:text-gray-400' ); ?>">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="privacy_consent" class="form-checkbox" required>
                                <span class="ml-2">
                                    <?php
                                    printf(
                                        /* translators: %s: Privacy policy link */
                                        esc_html__( 'I agree to the %s', 'aqualuxe' ),
                                        '<a href="' . esc_url( get_privacy_policy_url() ) . '" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">' . esc_html__( 'Privacy Policy', 'aqualuxe' ) . '</a>'
                                    );
                                    ?>
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>