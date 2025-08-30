<?php
/**
 * Homepage Newsletter Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$section_title = get_theme_mod('aqualuxe_homepage_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_homepage_newsletter_subtitle', __('Stay updated with our latest products, services, and aquatic care tips', 'aqualuxe'));
$show_section = get_theme_mod('aqualuxe_homepage_newsletter_show', true);
$background_color = get_theme_mod('aqualuxe_homepage_newsletter_bg_color', 'primary'); // primary, secondary, dark, light
$form_shortcode = get_theme_mod('aqualuxe_homepage_newsletter_form', ''); // For integration with form plugins like Contact Form 7, Mailchimp, etc.
$placeholder_text = get_theme_mod('aqualuxe_homepage_newsletter_placeholder', __('Enter your email address', 'aqualuxe'));
$button_text = get_theme_mod('aqualuxe_homepage_newsletter_button', __('Subscribe', 'aqualuxe'));
$privacy_text = get_theme_mod('aqualuxe_homepage_newsletter_privacy', __('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'));

// Exit if section is disabled
if (!$show_section) {
    return;
}

// Set background class
$bg_class = '';
switch ($background_color) {
    case 'primary':
        $bg_class = 'bg-primary-600 text-white';
        break;
    case 'secondary':
        $bg_class = 'bg-secondary-600 text-white';
        break;
    case 'dark':
        $bg_class = 'bg-gray-800 text-white';
        break;
    case 'light':
    default:
        $bg_class = 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-white';
        break;
}
?>

<section class="aqualuxe-newsletter py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <?php if ($section_title) : ?>
                <h2 class="text-3xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_subtitle) : ?>
                <p class="text-lg opacity-90 mb-8"><?php echo esc_html($section_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($form_shortcode) : ?>
                <?php echo do_shortcode($form_shortcode); ?>
            <?php else : ?>
                <form action="#" method="post" class="newsletter-form">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <div class="flex-grow">
                            <input type="email" name="email" placeholder="<?php echo esc_attr($placeholder_text); ?>" required class="w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 text-gray-800">
                        </div>
                        <div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-white text-primary-600 font-semibold rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white">
                                <?php echo esc_html($button_text); ?>
                            </button>
                        </div>
                    </div>
                    
                    <?php if ($privacy_text) : ?>
                        <p class="text-sm mt-4 opacity-80"><?php echo esc_html($privacy_text); ?></p>
                    <?php endif; ?>
                    
                    <div class="newsletter-response mt-4"></div>
                </form>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.querySelector('.newsletter-form');
                        const response = document.querySelector('.newsletter-response');
                        
                        if (form) {
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const email = this.querySelector('input[name="email"]').value;
                                
                                // This is a placeholder for actual newsletter subscription logic
                                // In a real implementation, you would send an AJAX request to your server
                                // or to a newsletter service API
                                
                                // Simulate API call with timeout
                                response.innerHTML = '<p class="text-sm font-medium">Processing your subscription...</p>';
                                
                                setTimeout(function() {
                                    response.innerHTML = '<p class="text-sm font-medium">Thank you for subscribing! Please check your email to confirm your subscription.</p>';
                                    form.reset();
                                }, 1500);
                                
                                // Example of how to implement with AJAX:
                                /*
                                fetch(ajaxurl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: new URLSearchParams({
                                        action: 'aqualuxe_newsletter_subscribe',
                                        email: email,
                                        nonce: aqualuxeNewsletter.nonce
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        response.innerHTML = '<p class="text-sm font-medium">' + data.message + '</p>';
                                        form.reset();
                                    } else {
                                        response.innerHTML = '<p class="text-sm font-medium text-red-300">' + data.message + '</p>';
                                    }
                                })
                                .catch(error => {
                                    response.innerHTML = '<p class="text-sm font-medium text-red-300">An error occurred. Please try again later.</p>';
                                    console.error('Error:', error);
                                });
                                */
                            });
                        }
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>
</section>