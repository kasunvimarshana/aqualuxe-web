<?php
/**
 * Template Name: FAQ Page
 *
 * The template for displaying the FAQ page.
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

        <div class="faq-container">
            <div class="container">
                <div class="faq-intro">
                    <div class="section-header">
                        <h2 class="section-title"><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h2>
                        <p class="section-description"><?php esc_html_e('Find answers to common questions about our products and services. If you can\'t find what you\'re looking for, please contact our customer support team.', 'aqualuxe'); ?></p>
                    </div>
                </div>

                <div class="faq-search">
                    <form role="search" method="get" class="faq-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="search" class="faq-search-field" placeholder="<?php esc_attr_e('Search FAQs...', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="page" />
                        <button type="submit" class="faq-search-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
                        </button>
                    </form>
                </div>

                <div class="faq-categories">
                    <div class="faq-category-nav">
                        <ul class="faq-category-list">
                            <li class="faq-category-item active"><a href="#general" data-category="general"><?php esc_html_e('General', 'aqualuxe'); ?></a></li>
                            <li class="faq-category-item"><a href="#products" data-category="products"><?php esc_html_e('Products', 'aqualuxe'); ?></a></li>
                            <li class="faq-category-item"><a href="#shipping" data-category="shipping"><?php esc_html_e('Shipping & Delivery', 'aqualuxe'); ?></a></li>
                            <li class="faq-category-item"><a href="#returns" data-category="returns"><?php esc_html_e('Returns & Refunds', 'aqualuxe'); ?></a></li>
                            <li class="faq-category-item"><a href="#account" data-category="account"><?php esc_html_e('Account & Orders', 'aqualuxe'); ?></a></li>
                            <li class="faq-category-item"><a href="#maintenance" data-category="maintenance"><?php esc_html_e('Maintenance & Care', 'aqualuxe'); ?></a></li>
                        </ul>
                    </div>

                    <div class="faq-category-content">
                        <div id="general" class="faq-category active">
                            <h3 class="faq-category-title"><?php esc_html_e('General Questions', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What is AquaLuxe?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('AquaLuxe is a premium aquatic retail brand specializing in high-quality aquariums, filtration systems, and accessories for both freshwater and saltwater environments. We focus on combining elegant design with cutting-edge technology to create beautiful and healthy aquatic ecosystems.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How can I contact customer support?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('You can contact our customer support team through several channels:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Phone: +1 (800) 555-1234 (Monday-Friday, 9am-6pm EST)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Email: support@aqualuxe.com', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Contact Form: Visit our Contact page', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Live Chat: Available on our website during business hours', 'aqualuxe'); ?></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Do you have a physical store?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Yes, our flagship store is located in San Francisco, California. We also have partner showrooms in New York, Miami, and Chicago where you can see our products in person. Visit our Contact page for specific addresses and opening hours.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Do you ship internationally?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Yes, we ship to most countries worldwide. International shipping rates and delivery times vary by location. Please note that customers are responsible for any import duties or taxes that may apply in their country.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="products" class="faq-category">
                            <h3 class="faq-category-title"><?php esc_html_e('Product Questions', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What types of aquariums do you offer?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('We offer a wide range of aquariums including:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Freshwater aquariums in various sizes and designs', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Saltwater and reef-ready systems', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Custom built-in aquariums for residential and commercial spaces', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Nano aquariums for small spaces', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Specialty aquariums for specific species', 'aqualuxe'); ?></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Can I customize my aquarium?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Absolutely! We specialize in custom aquarium solutions. Our design team can work with you to create a unique aquarium that fits your space and aesthetic preferences. We offer customization for dimensions, materials, filtration systems, lighting, and cabinetry. Contact us to schedule a consultation.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What warranty do your products have?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Our products come with the following warranties:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Aquariums: 5-year warranty against manufacturing defects', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Filtration systems: 2-year warranty', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Lighting: 1-year warranty', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Electronic components: 1-year warranty', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('Extended warranties are available for purchase. Please refer to the specific product page for detailed warranty information.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Do you sell live fish or plants?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('We offer a select range of live aquatic plants through our online store. For live fish, we work with trusted local partners to ensure healthy specimens and proper shipping. Live fish are only available in certain regions to ensure safe delivery. Please contact us for more information about live fish availability in your area.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="shipping" class="faq-category">
                            <h3 class="faq-category-title"><?php esc_html_e('Shipping & Delivery', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What are your shipping rates?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('We offer free shipping on all orders over $100 within the continental United States. For orders under $100, shipping rates start at $9.95 depending on weight and dimensions. International shipping rates vary by location and will be calculated at checkout.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How long will it take to receive my order?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Delivery times depend on your location and the products ordered:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Standard accessories and supplies: 3-5 business days (US)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Aquariums and large equipment: 7-14 business days (US)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Custom orders: 4-8 weeks depending on specifications', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('International orders: 10-21 business days', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('You will receive tracking information once your order ships.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How do you ship large aquariums?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Large aquariums are shipped via freight carriers with specialized equipment for handling fragile glass items. We use custom crating and packaging to ensure safe delivery. For aquariums over 100 gallons, white glove delivery service is included, which includes placement in your desired location and removal of packaging materials.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What if my order arrives damaged?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('If your order arrives damaged, please:', 'aqualuxe'); ?></p>
                                        <ol>
                                            <li><?php esc_html_e('Take photos of the damaged packaging and product', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Contact our customer service team within 48 hours', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Do not discard the original packaging', 'aqualuxe'); ?></li>
                                        </ol>
                                        <p><?php esc_html_e('We will arrange for a replacement to be sent or issue a refund as appropriate.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="returns" class="faq-category">
                            <h3 class="faq-category-title"><?php esc_html_e('Returns & Refunds', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What is your return policy?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('We offer a 30-day money-back guarantee on most products. If you\'re not satisfied with your purchase, you can return it within 30 days for a full refund of the product price. Please note that shipping costs are non-refundable, and return shipping is the responsibility of the customer unless the item is defective.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('Exceptions to our standard return policy:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Custom aquariums and made-to-order items cannot be returned unless defective', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Live plants and animals have a 7-day guarantee and specific return conditions', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Opened consumables (food, supplements, water treatments) cannot be returned', 'aqualuxe'); ?></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How do I initiate a return?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('To initiate a return:', 'aqualuxe'); ?></p>
                                        <ol>
                                            <li><?php esc_html_e('Log in to your account and go to "Order History"', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Select the order containing the item you wish to return', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Click "Return Item" and follow the instructions', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Print the return label (if applicable) and ship the item back to us', 'aqualuxe'); ?></li>
                                        </ol>
                                        <p><?php esc_html_e('Alternatively, you can contact our customer service team for assistance with your return.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('When will I receive my refund?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Once we receive and inspect your return, we will process your refund within 3-5 business days. The refund will be issued to the original payment method. Depending on your bank or credit card company, it may take an additional 5-10 business days for the refund to appear in your account.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Can I exchange an item instead of returning it?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Yes, we offer exchanges for items of equal or greater value (you will be charged the difference if selecting a more expensive item). To request an exchange, contact our customer service team with your order number and the item you would like to exchange for. Please note that custom items are not eligible for exchange.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="account" class="faq-category">
                            <h3 class="faq-category-title"><?php esc_html_e('Account & Orders', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How do I create an account?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('To create an account:', 'aqualuxe'); ?></p>
                                        <ol>
                                            <li><?php esc_html_e('Click on "Account" in the top navigation', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Select "Register" or "Create Account"', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Fill in your email address and create a password', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Complete the registration form with your details', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Click "Create Account" to finish', 'aqualuxe'); ?></li>
                                        </ol>
                                        <p><?php esc_html_e('You can also create an account during the checkout process.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How can I track my order?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('You can track your order in two ways:', 'aqualuxe'); ?></p>
                                        <ol>
                                            <li><?php esc_html_e('Log in to your account and go to "Order History" to see the status and tracking information', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Use the tracking link provided in your shipping confirmation email', 'aqualuxe'); ?></li>
                                        </ol>
                                        <p><?php esc_html_e('If you checked out as a guest, you can track your order using the order number and email address provided during checkout.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Can I modify or cancel my order?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('You can modify or cancel your order within 2 hours of placing it, provided it has not yet entered the processing stage. To do so, contact our customer service team immediately with your order number.', 'aqualuxe'); ?></p>
                                        <p><?php esc_html_e('For custom orders, modifications or cancellations must be made within 24 hours of placing the order, before production begins.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What payment methods do you accept?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('We accept the following payment methods:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Credit/Debit Cards (Visa, Mastercard, American Express, Discover)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('PayPal', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Apple Pay', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Google Pay', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Shop Pay', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Financing options through Affirm (for orders over $500)', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('For custom installations and large orders, we also accept wire transfers and checks. Please contact our sales team for details.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="maintenance" class="faq-category">
                            <h3 class="faq-category-title"><?php esc_html_e('Maintenance & Care', 'aqualuxe'); ?></h3>
                            
                            <div class="faq-items">
                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('Do you offer maintenance services?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Yes, we offer professional maintenance services for both residential and commercial aquariums in select areas. Our maintenance plans include:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Regular cleaning and water changes', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Water quality testing and adjustment', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Equipment checks and maintenance', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Fish and plant health assessment', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Algae control and removal', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('Contact us for details on our maintenance packages and service areas.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How often should I clean my aquarium?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('The cleaning frequency depends on your aquarium size, filtration system, and bioload (number of fish). As a general guideline:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Weekly: Test water parameters, clean glass/acrylic surfaces, remove visible debris', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Bi-weekly: Perform 15-25% water change, rinse filter media (if necessary)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Monthly: Clean decorations and substrate (partial), trim plants, check equipment', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Quarterly: Deep clean filters, check and replace worn parts', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('Our AquaLuxe filtration systems are designed to reduce maintenance frequency while maintaining optimal water quality.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('How do I maintain proper water quality?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('Maintaining proper water quality is essential for a healthy aquarium. Here are key practices:', 'aqualuxe'); ?></p>
                                        <ul>
                                            <li><?php esc_html_e('Regular water testing: Monitor pH, ammonia, nitrite, nitrate, and other parameters specific to your aquarium type', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Consistent water changes: Replace 15-25% of the water every 1-2 weeks', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Proper filtration: Ensure your filter is appropriately sized and maintained', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Avoid overfeeding: Feed only what your fish can consume in 2-3 minutes', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Maintain appropriate stocking levels: Don\'t overcrowd your aquarium', 'aqualuxe'); ?></li>
                                        </ul>
                                        <p><?php esc_html_e('We offer water quality test kits and water treatment products to help maintain optimal conditions.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>

                                <div class="faq-item">
                                    <div class="faq-question">
                                        <h4><?php esc_html_e('What should I do if my fish are sick?', 'aqualuxe'); ?></h4>
                                        <span class="faq-toggle"></span>
                                    </div>
                                    <div class="faq-answer">
                                        <p><?php esc_html_e('If you notice signs of illness in your fish (unusual behavior, spots, damaged fins, etc.):', 'aqualuxe'); ?></p>
                                        <ol>
                                            <li><?php esc_html_e('Test your water parameters immediately', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Perform a partial water change (25-30%)', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Isolate sick fish if possible using a quarantine tank', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Identify the specific disease or condition', 'aqualuxe'); ?></li>
                                            <li><?php esc_html_e('Apply appropriate treatment', 'aqualuxe'); ?></li>
                                        </ol>
                                        <p><?php esc_html_e('Our customer support team includes aquatic specialists who can help diagnose and treat common fish ailments. Contact us with details and photos of your fish for assistance.', 'aqualuxe'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="faq-contact">
                    <div class="faq-contact__inner">
                        <h3 class="faq-contact__title"><?php esc_html_e('Still Have Questions?', 'aqualuxe'); ?></h3>
                        <p class="faq-contact__text"><?php esc_html_e('If you couldn\'t find the answer you were looking for, please contact our customer support team.', 'aqualuxe'); ?></p>
                        <div class="faq-contact__buttons">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a>
                            <a href="#" class="btn btn-outline faq-chat-button"><?php esc_html_e('Live Chat', 'aqualuxe'); ?></a>
                        </div>
                    </div>
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