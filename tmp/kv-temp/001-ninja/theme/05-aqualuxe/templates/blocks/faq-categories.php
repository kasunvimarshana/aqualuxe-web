<?php
/**
 * FAQ Page Categories Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Demo FAQ categories
$faq_categories = array(
    array(
        'id' => 'ordering',
        'title' => 'Ordering & Shipping',
        'icon' => 'icon-shipping',
        'description' => 'Questions about placing orders and shipping processes',
    ),
    array(
        'id' => 'products',
        'title' => 'Products',
        'icon' => 'icon-fish',
        'description' => 'Information about our fish and aquatic products',
    ),
    array(
        'id' => 'services',
        'title' => 'Services',
        'icon' => 'icon-services',
        'description' => 'Details about our professional aquarium services',
    ),
    array(
        'id' => 'care',
        'title' => 'Fish Care',
        'icon' => 'icon-care',
        'description' => 'Guidance on caring for your aquatic pets',
    ),
    array(
        'id' => 'returns',
        'title' => 'Returns & Guarantees',
        'icon' => 'icon-guarantee',
        'description' => 'Information about our policies and guarantees',
    ),
    array(
        'id' => 'account',
        'title' => 'Account & Orders',
        'icon' => 'icon-account',
        'description' => 'Help with your account and order management',
    ),
);

// Filter FAQ categories through a hook to allow customization
$faq_categories = apply_filters( 'aqualuxe_faq_categories', $faq_categories );

// Return if no FAQ categories
if ( empty( $faq_categories ) ) {
    return;
}
?>

<section class="faq-categories-section">
    <div class="container">
        <div class="faq-categories">
            <?php foreach ( $faq_categories as $category ) : ?>
                <div class="faq-category">
                    <a href="#<?php echo esc_attr( $category['id'] ); ?>" class="faq-category-link">
                        <?php if ( ! empty( $category['icon'] ) ) : ?>
                            <div class="category-icon">
                                <span class="<?php echo esc_attr( $category['icon'] ); ?>"></span>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="category-title"><?php echo esc_html( $category['title'] ); ?></h3>
                        
                        <?php if ( ! empty( $category['description'] ) ) : ?>
                            <div class="category-description"><?php echo esc_html( $category['description'] ); ?></div>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>