<?php
/**
 * Template Name: Contact Page
 *
 * The template for displaying the contact page.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if (has_post_thumbnail()) : ?>
            <div class="page-header page-header--with-image" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>');">
                <div class="container">
                    <div class="page-header__content">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                        <?php if (function_exists('yoast_breadcrumb')) : ?>
                            <?php yoast_breadcrumb('<div class="breadcrumbs">', '</div>'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php if (function_exists('yoast_breadcrumb')) : ?>
                        <?php yoast_breadcrumb('<div class="breadcrumbs">', '</div>'); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="contact-container">
            <div class="container">
                <div class="contact-intro">
                    <div class="section-header">
                        <h2 class="section-title"><?php esc_html_e('Get in Touch', 'aqualuxe'); ?></h2>
                        <p class="section-description"><?php esc_html_e('We\'d love to hear from you. Contact us using the information below or fill out the form and we\'ll get back to you as soon as possible.', 'aqualuxe'); ?></p>
                    </div>
                </div>

                <div class="contact-content">
                    <div class="contact-info">
                        <div class="contact-info__item">
                            <div class="contact-info__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9.366 10.682a10.556 10.556 0 0 0 3.952 3.952l.884-1.238a1 1 0 0 1 1.294-.296 11.422 11.422 0 0 0 4.583 1.364 1 1 0 0 1 .921.997v4.462a1 1 0 0 1-.898.995c-.53.055-1.064.082-1.602.082C9.94 21 3 14.06 3 5.5c0-.538.027-1.072.082-1.602A1 1 0 0 1 4.077 3h4.462a1 1 0 0 1 .997.921A11.422 11.422 0 0 0 10.9 8.504a1 1 0 0 1-.296 1.294l-1.238.884zm-2.522-.657l1.9-1.357A13.41 13.41 0 0 1 7.647 5H5.01c-.006.166-.009.333-.009.5C5 12.956 11.044 19 18.5 19c.167 0 .334-.003.5-.01v-2.637a13.41 13.41 0 0 1-3.668-1.097l-1.357 1.9a12.442 12.442 0 0 1-1.588-.75l-.058-.033a12.556 12.556 0 0 1-4.702-4.702l-.033-.058a12.442 12.442 0 0 1-.75-1.588z"/></svg>
                            </div>
                            <div class="contact-info__content">
                                <h3 class="contact-info__title"><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                                <p class="contact-info__text"><?php esc_html_e('Customer Support:', 'aqualuxe'); ?> <a href="tel:+18005551234">+1 (800) 555-1234</a></p>
                                <p class="contact-info__text"><?php esc_html_e('Sales Inquiries:', 'aqualuxe'); ?> <a href="tel:+18005555678">+1 (800) 555-5678</a></p>
                            </div>
                        </div>

                        <div class="contact-info__item">
                            <div class="contact-info__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z"/></svg>
                            </div>
                            <div class="contact-info__content">
                                <h3 class="contact-info__title"><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                                <p class="contact-info__text"><?php esc_html_e('General Inquiries:', 'aqualuxe'); ?> <a href="mailto:info@aqualuxe.com">info@aqualuxe.com</a></p>
                                <p class="contact-info__text"><?php esc_html_e('Customer Support:', 'aqualuxe'); ?> <a href="mailto:support@aqualuxe.com">support@aqualuxe.com</a></p>
                            </div>
                        </div>

                        <div class="contact-info__item">
                            <div class="contact-info__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>
                            </div>
                            <div class="contact-info__content">
                                <h3 class="contact-info__title"><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                                <p class="contact-info__text"><?php esc_html_e('123 Aquatic Avenue', 'aqualuxe'); ?></p>
                                <p class="contact-info__text"><?php esc_html_e('Suite 456', 'aqualuxe'); ?></p>
                                <p class="contact-info__text"><?php esc_html_e('San Francisco, CA 94107', 'aqualuxe'); ?></p>
                            </div>
                        </div>

                        <div class="contact-info__item">
                            <div class="contact-info__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg>
                            </div>
                            <div class="contact-info__content">
                                <h3 class="contact-info__title"><?php esc_html_e('Hours', 'aqualuxe'); ?></h3>
                                <p class="contact-info__text"><?php esc_html_e('Monday - Friday: 9:00 AM - 6:00 PM', 'aqualuxe'); ?></p>
                                <p class="contact-info__text"><?php esc_html_e('Saturday: 10:00 AM - 4:00 PM', 'aqualuxe'); ?></p>
                                <p class="contact-info__text"><?php esc_html_e('Sunday: Closed', 'aqualuxe'); ?></p>
                            </div>
                        </div>

                        <div class="contact-social">
                            <h3 class="contact-social__title"><?php esc_html_e('Connect With Us', 'aqualuxe'); ?></h3>
                            <div class="contact-social__links">
                                <a href="#" class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                                </a>
                                <a href="#" class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>
                                </a>
                                <a href="#" class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.041 0 2.67.01 2.986.058 4.04.045.976.207 1.505.344 1.858.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058 2.67 0 2.987-.01 4.04-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041 0-2.67-.01-2.986-.058-4.04-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.041-.058zm0 3.063a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm6.538-3.11a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4z"/></svg>
                                </a>
                                <a href="#" class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-3.5 15.5a.5.5 0 0 1-.5-.5V7a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-.5.5h-.5zm2 0a.5.5 0 0 1-.5-.5V7a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-4v2h2a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-2v2h4a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-5z"/></svg>
                                </a>
                                <a href="#" class="social-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M19.606 6.995c-.076-.298-.292-.523-.539-.592C18.63 6.28 16.5 6 12 6s-6.628.28-7.069.403c-.244.068-.46.293-.537.592C4.285 7.419 4 9.196 4 12s.285 4.58.394 5.006c.076.297.292.522.538.59C5.372 17.72 7.5 18 12 18s6.629-.28 7.069-.403c.244-.068.46-.293.537-.592C19.715 16.581 20 14.8 20 12s-.285-4.58-.394-5.005zm1.937-.497C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5v-7l6 3.5-6 3.5z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form">
                        <h3 class="contact-form__title"><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h3>
                        
                        <?php
                        // Check if Contact Form 7 is active
                        if (function_exists('wpcf7_contact_form')) {
                            // Get the contact form by ID or slug
                            $contact_form = wpcf7_contact_form(123); // Replace 123 with your form ID
                            
                            if ($contact_form) {
                                echo do_shortcode('[contact-form-7 id="' . $contact_form->id() . '" title="' . $contact_form->title() . '"]');
                            } else {
                                // Fallback form if Contact Form 7 form is not found
                                ?>
                                <form action="#" method="post" class="contact-form__inner">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="name"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                                            <input type="text" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                        <input type="text" id="subject" name="subject">
                                    </div>
                                    <div class="form-group">
                                        <label for="message"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <textarea id="message" name="message" rows="5" required></textarea>
                                    </div>
                                    <div class="form-group form-consent">
                                        <input type="checkbox" id="consent" name="consent" required>
                                        <label for="consent"><?php esc_html_e('I agree to the privacy policy and consent to my data being processed.', 'aqualuxe'); ?></label>
                                    </div>
                                    <div class="form-submit">
                                        <button type="submit" class="btn btn-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                    </div>
                                </form>
                                <?php
                            }
                        } else {
                            // Fallback form if Contact Form 7 is not active
                            ?>
                            <form action="#" method="post" class="contact-form__inner">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <input type="text" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                    <input type="text" id="subject" name="subject">
                                </div>
                                <div class="form-group">
                                    <label for="message"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <textarea id="message" name="message" rows="5" required></textarea>
                                </div>
                                <div class="form-group form-consent">
                                    <input type="checkbox" id="consent" name="consent" required>
                                    <label for="consent"><?php esc_html_e('I agree to the privacy policy and consent to my data being processed.', 'aqualuxe'); ?></label>
                                </div>
                                <div class="form-submit">
                                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-map">
            <div class="map-container">
                <?php
                // Check if ACF is active and if a Google Maps API key is set
                if (function_exists('get_field') && get_field('google_maps_api_key', 'option')) {
                    // Get the map field
                    $location = get_field('location_map');
                    
                    if ($location) {
                        echo '<div class="acf-map">';
                        echo '<div class="marker" data-lat="' . esc_attr($location['lat']) . '" data-lng="' . esc_attr($location['lng']) . '">';
                        echo '<h4>' . esc_html__('AquaLuxe Headquarters', 'aqualuxe') . '</h4>';
                        echo '<p>' . esc_html__('123 Aquatic Avenue, Suite 456', 'aqualuxe') . '<br>' . esc_html__('San Francisco, CA 94107', 'aqualuxe') . '</p>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        // Fallback iframe map
                        echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.7462606519114!2d-122.39953368468215!3d37.77059997975922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085807c1f0da6a5%3A0xb76310e9a92c545!2sSan%20Francisco%2C%20CA%2094107!5e0!3m2!1sen!2sus!4v1629902473226!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                    }
                } else {
                    // Fallback iframe map
                    echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.7462606519114!2d-122.39953368468215!3d37.77059997975922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085807c1f0da6a5%3A0xb76310e9a92c545!2sSan%20Francisco%2C%20CA%2094107!5e0!3m2!1sen!2sus!4v1629902473226!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';
                }
                ?>
            </div>
        </div>

        <div class="contact-faq">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h2>
                    <p class="section-description"><?php esc_html_e('Find quick answers to common questions', 'aqualuxe'); ?></p>
                </div>

                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php esc_html_e('What are your shipping rates?', 'aqualuxe'); ?></h3>
                            <span class="faq-toggle"></span>
                        </div>
                        <div class="faq-answer">
                            <p><?php esc_html_e('We offer free shipping on all orders over $100 within the continental United States. For orders under $100, shipping rates start at $9.95. International shipping rates vary by location.', 'aqualuxe'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php esc_html_e('What is your return policy?', 'aqualuxe'); ?></h3>
                            <span class="faq-toggle"></span>
                        </div>
                        <div class="faq-answer">
                            <p><?php esc_html_e('We offer a 30-day money-back guarantee on most products. If you\'re not satisfied with your purchase, you can return it within 30 days for a full refund. Please note that custom aquariums and live animals have special return policies.', 'aqualuxe'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php esc_html_e('Do you offer installation services?', 'aqualuxe'); ?></h3>
                            <span class="faq-toggle"></span>
                        </div>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Yes, we offer professional installation services for our premium aquariums and filtration systems. Installation services are available in select areas. Please contact our customer service team for more information and pricing.', 'aqualuxe'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php esc_html_e('Can I customize my aquarium?', 'aqualuxe'); ?></h3>
                            <span class="faq-toggle"></span>
                        </div>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Absolutely! We specialize in custom aquarium solutions. Our design team can work with you to create a unique aquarium that fits your space and aesthetic preferences. Contact us to schedule a consultation.', 'aqualuxe'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php esc_html_e('Do you offer maintenance services?', 'aqualuxe'); ?></h3>
                            <span class="faq-toggle"></span>
                        </div>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Yes, we offer comprehensive maintenance services for both residential and commercial aquariums. Our maintenance plans include regular cleaning, water testing, equipment checks, and more. Contact us for details on our maintenance packages.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="faq-footer">
                    <p><?php esc_html_e('Still have questions?', 'aqualuxe'); ?> <a href="#contact-form"><?php esc_html_e('Contact our team', 'aqualuxe'); ?></a></p>
                </div>
            </div>
        </div>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    </article><!-- #post-<?php the_ID(); ?> -->
</main><!-- #main -->

<?php
get_footer();