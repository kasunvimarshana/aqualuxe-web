<?php
/**
 * FAQ Page List Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get FAQ settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_faq_list_title', __( 'Frequently Asked Questions', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_faq_list_subtitle', __( 'Find answers to common questions about our products and services', 'aqualuxe' ) );
$layout_style = get_theme_mod( 'aqualuxe_faq_list_layout', 'accordion' );
$group_by_category = get_theme_mod( 'aqualuxe_faq_group_by_category', true );
$show_category_tabs = get_theme_mod( 'aqualuxe_faq_show_category_tabs', true );

// Default FAQs if not set in customizer or if custom post type is not available
$default_faqs = array(
    // General Questions
    array(
        'category' => 'general',
        'question' => __( 'What is AquaLuxe?', 'aqualuxe' ),
        'answer'   => __( 'AquaLuxe is a premium WordPress theme designed for modern e-commerce businesses. It offers a wide range of features including performance optimization, SEO tools, and a modular architecture that makes it easy to customize for your specific needs.', 'aqualuxe' ),
    ),
    array(
        'category' => 'general',
        'question' => __( 'Do I need coding knowledge to use AquaLuxe?', 'aqualuxe' ),
        'answer'   => __( 'No, AquaLuxe is designed to be user-friendly and does not require coding knowledge for basic setup and customization. The theme includes a comprehensive admin panel that allows you to change colors, layouts, and other settings without touching code. However, for advanced customizations, some knowledge of HTML, CSS, or PHP may be helpful.', 'aqualuxe' ),
    ),
    array(
        'category' => 'general',
        'question' => __( 'Is AquaLuxe compatible with the latest version of WordPress?', 'aqualuxe' ),
        'answer'   => __( 'Yes, AquaLuxe is regularly updated to ensure compatibility with the latest version of WordPress. We follow WordPress best practices and standards to ensure smooth operation with each new release.', 'aqualuxe' ),
    ),
    
    // Products
    array(
        'category' => 'products',
        'question' => __( 'What features are included in AquaLuxe?', 'aqualuxe' ),
        'answer'   => __( 'AquaLuxe includes a wide range of features such as performance optimization, SEO tools, WooCommerce integration, multilingual support, dark mode, demo content importer, and much more. The theme is built with a modular architecture that allows you to enable or disable features as needed.', 'aqualuxe' ),
    ),
    array(
        'category' => 'products',
        'question' => __( 'Can I use AquaLuxe for non-ecommerce websites?', 'aqualuxe' ),
        'answer'   => __( 'Absolutely! While AquaLuxe is optimized for e-commerce with WooCommerce, it works perfectly for blogs, corporate sites, portfolios, and other types of websites. The theme has a dual-state architecture that adapts based on whether WooCommerce is active or not.', 'aqualuxe' ),
    ),
    array(
        'category' => 'products',
        'question' => __( 'Does AquaLuxe support page builders?', 'aqualuxe' ),
        'answer'   => __( 'Yes, AquaLuxe is compatible with popular page builders like Elementor, Beaver Builder, and the WordPress Block Editor (Gutenberg). This gives you flexibility in how you build and customize your pages.', 'aqualuxe' ),
    ),
    
    // Shipping & Delivery
    array(
        'category' => 'shipping',
        'question' => __( 'How can I set up shipping methods in AquaLuxe?', 'aqualuxe' ),
        'answer'   => __( 'AquaLuxe uses WooCommerce\'s shipping system, which you can configure in the WooCommerce settings. Go to WooCommerce > Settings > Shipping to set up shipping zones, methods, and rates. The theme includes optimized templates for displaying shipping information during checkout.', 'aqualuxe' ),
    ),
    array(
        'category' => 'shipping',
        'question' => __( 'Does AquaLuxe support international shipping?', 'aqualuxe' ),
        'answer'   => __( 'Yes, AquaLuxe fully supports international shipping through WooCommerce. You can set up different shipping zones for different countries and regions, and apply specific shipping methods and rates to each zone.', 'aqualuxe' ),
    ),
    
    // Returns & Refunds
    array(
        'category' => 'returns',
        'question' => __( 'What is your refund policy?', 'aqualuxe' ),
        'answer'   => __( 'We offer a 30-day money-back guarantee on AquaLuxe. If you\'re not satisfied with the theme for any reason, you can request a full refund within 30 days of purchase. Please contact our support team to initiate the refund process.', 'aqualuxe' ),
    ),
    array(
        'category' => 'returns',
        'question' => __( 'How do I request a refund?', 'aqualuxe' ),
        'answer'   => __( 'To request a refund, please contact our support team through the contact form on our website or by emailing support@aqualuxe.com. Please include your purchase information and the reason for the refund request.', 'aqualuxe' ),
    ),
    
    // Account & Orders
    array(
        'category' => 'account',
        'question' => __( 'How do I create an account?', 'aqualuxe' ),
        'answer'   => __( 'You can create an account during the checkout process or by visiting the My Account page. Click on the "Register" tab, enter your email address and create a password. Once registered, you can track orders, save shipping addresses, and more.', 'aqualuxe' ),
    ),
    array(
        'category' => 'account',
        'question' => __( 'How can I track my order?', 'aqualuxe' ),
        'answer'   => __( 'Once you\'ve placed an order, you can track it by logging into your account and navigating to the Orders section. There you\'ll find all your orders and their current status. If tracking information is available, it will be displayed there as well.', 'aqualuxe' ),
    ),
    
    // Technical Support
    array(
        'category' => 'support',
        'question' => __( 'How do I get support for AquaLuxe?', 'aqualuxe' ),
        'answer'   => __( 'We offer support through our dedicated support portal. After purchasing AquaLuxe, you\'ll receive access to our support system where you can submit tickets, browse documentation, and access video tutorials. Our support team is available Monday through Friday, 9am to 5pm PST.', 'aqualuxe' ),
    ),
    array(
        'category' => 'support',
        'question' => __( 'Do you offer customization services?', 'aqualuxe' ),
        'answer'   => __( 'Yes, we offer customization services for AquaLuxe. If you need specific features or design changes that aren\'t included in the theme, our development team can help. Please contact us with your requirements for a quote.', 'aqualuxe' ),
    ),
);

// Get FAQs from custom post type if available, otherwise use defaults
$faqs = array();
$categories = array();

// Check if FAQ custom post type exists
if ( post_type_exists( 'faq' ) && taxonomy_exists( 'faq_category' ) ) {
    // Get all FAQ categories
    $terms = get_terms( array(
        'taxonomy'   => 'faq_category',
        'hide_empty' => true,
    ) );
    
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $categories[$term->slug] = array(
                'name'  => $term->name,
                'slug'  => $term->slug,
                'count' => $term->count,
                'term'  => $term,
            );
        }
    }
    
    // Get all FAQs
    $args = array(
        'post_type'      => 'faq',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );
    
    $faq_query = new WP_Query( $args );
    
    if ( $faq_query->have_posts() ) {
        while ( $faq_query->have_posts() ) {
            $faq_query->the_post();
            
            // Get FAQ category
            $faq_terms = get_the_terms( get_the_ID(), 'faq_category' );
            $category_slug = '';
            
            if ( ! empty( $faq_terms ) && ! is_wp_error( $faq_terms ) ) {
                $category_slug = $faq_terms[0]->slug;
            }
            
            $faqs[] = array(
                'category' => $category_slug,
                'question' => get_the_title(),
                'answer'   => get_the_content(),
            );
        }
        
        wp_reset_postdata();
    }
}

// Use default FAQs if none found
if ( empty( $faqs ) ) {
    $faqs = $default_faqs;
    
    // Build categories from default FAQs
    foreach ( $default_faqs as $faq ) {
        if ( ! isset( $categories[$faq['category']] ) ) {
            $categories[$faq['category']] = array(
                'name'  => ucfirst( $faq['category'] ),
                'slug'  => $faq['category'],
                'count' => 0,
            );
        }
        
        $categories[$faq['category']]['count']++;
    }
}

// Skip if no FAQs
if ( empty( $faqs ) ) {
    return;
}

// Group FAQs by category if enabled
$grouped_faqs = array();
if ( $group_by_category ) {
    foreach ( $faqs as $faq ) {
        $category = ! empty( $faq['category'] ) ? $faq['category'] : 'general';
        $grouped_faqs[$category][] = $faq;
    }
} else {
    $grouped_faqs['all'] = $faqs;
}

// FAQ container classes
$faq_container_classes = array( 'faq-container', 'layout-' . $layout_style );
?>

<section class="faq-list-section section" id="faq-list">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ( $show_category_tabs && $group_by_category && count( $categories ) > 1 ) : ?>
            <div class="faq-tabs">
                <ul class="nav nav-tabs" id="faqTabs" role="tablist">
                    <?php $first_tab = true; ?>
                    <?php foreach ( $categories as $slug => $category ) : ?>
                        <?php if ( isset( $grouped_faqs[$slug] ) ) : ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $first_tab ? 'active' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>-tab" data-bs-toggle="tab" data-bs-target="#<?php echo esc_attr( $slug ); ?>-content" type="button" role="tab" aria-controls="<?php echo esc_attr( $slug ); ?>-content" aria-selected="<?php echo $first_tab ? 'true' : 'false'; ?>">
                                    <?php echo esc_html( $category['name'] ); ?>
                                </button>
                            </li>
                            <?php $first_tab = false; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ( $group_by_category && $show_category_tabs && count( $categories ) > 1 ) : ?>
            <div class="tab-content" id="faqTabContent">
                <?php $first_tab = true; ?>
                <?php foreach ( $categories as $slug => $category ) : ?>
                    <?php if ( isset( $grouped_faqs[$slug] ) ) : ?>
                        <div class="tab-pane fade <?php echo $first_tab ? 'show active' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>-content" role="tabpanel" aria-labelledby="<?php echo esc_attr( $slug ); ?>-tab">
                            <div class="<?php echo esc_attr( implode( ' ', $faq_container_classes ) ); ?>">
                                <?php if ( $layout_style === 'accordion' ) : ?>
                                    <div class="accordion" id="faqAccordion-<?php echo esc_attr( $slug ); ?>">
                                        <?php foreach ( $grouped_faqs[$slug] as $index => $faq ) : ?>
                                            <div class="accordion-item">
                                                <h3 class="accordion-header" id="faqHeading-<?php echo esc_attr( $slug . '-' . $index ); ?>">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>" aria-expanded="false" aria-controls="faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>">
                                                        <?php echo esc_html( $faq['question'] ); ?>
                                                    </button>
                                                </h3>
                                                <div id="faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>" class="accordion-collapse collapse" aria-labelledby="faqHeading-<?php echo esc_attr( $slug . '-' . $index ); ?>" data-bs-parent="#faqAccordion-<?php echo esc_attr( $slug ); ?>">
                                                    <div class="accordion-body">
                                                        <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="faq-grid">
                                        <?php foreach ( $grouped_faqs[$slug] as $faq ) : ?>
                                            <div class="faq-item">
                                                <h3 class="faq-question"><?php echo esc_html( $faq['question'] ); ?></h3>
                                                <div class="faq-answer">
                                                    <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $first_tab = false; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php foreach ( $grouped_faqs as $slug => $category_faqs ) : ?>
                <?php if ( $group_by_category && count( $categories ) > 1 ) : ?>
                    <div class="faq-category-section" id="<?php echo esc_attr( $slug ); ?>">
                        <h2 class="category-title"><?php echo esc_html( isset( $categories[$slug] ) ? $categories[$slug]['name'] : ucfirst( $slug ) ); ?></h2>
                <?php endif; ?>
                
                <div class="<?php echo esc_attr( implode( ' ', $faq_container_classes ) ); ?>">
                    <?php if ( $layout_style === 'accordion' ) : ?>
                        <div class="accordion" id="faqAccordion-<?php echo esc_attr( $slug ); ?>">
                            <?php foreach ( $category_faqs as $index => $faq ) : ?>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqHeading-<?php echo esc_attr( $slug . '-' . $index ); ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>" aria-expanded="false" aria-controls="faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>">
                                            <?php echo esc_html( $faq['question'] ); ?>
                                        </button>
                                    </h3>
                                    <div id="faqCollapse-<?php echo esc_attr( $slug . '-' . $index ); ?>" class="accordion-collapse collapse" aria-labelledby="faqHeading-<?php echo esc_attr( $slug . '-' . $index ); ?>" data-bs-parent="#faqAccordion-<?php echo esc_attr( $slug ); ?>">
                                        <div class="accordion-body">
                                            <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="faq-grid">
                            <?php foreach ( $category_faqs as $faq ) : ?>
                                <div class="faq-item">
                                    <h3 class="faq-question"><?php echo esc_html( $faq['question'] ); ?></h3>
                                    <div class="faq-answer">
                                        <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ( $group_by_category && count( $categories ) > 1 ) : ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>