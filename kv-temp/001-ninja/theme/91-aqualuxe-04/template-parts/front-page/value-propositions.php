<?php
/**
 * Front Page: Value Propositions Section
 *
 * @package AquaLuxe
 */

$value_props = array(
    array(
        'icon' => 'leaf',
        'title' => __( 'Sustainability', 'aqualuxe' ),
        'text' => __( 'Eco-friendly materials and water-saving technologies.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'sparkles',
        'title' => __( 'Innovation', 'aqualuxe' ),
        'text' => __( 'Cutting-edge features for a seamless user experience.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'gem',
        'title' => __( 'Luxury Design', 'aqualuxe' ),
        'text' => __( 'Exquisite craftsmanship and timeless aesthetics.', 'aqualuxe' ),
    ),
);
?>
<section class="py-20 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
            <?php foreach ( $value_props as $prop ) : ?>
                <div class="value-prop-item">
                    <div class="text-primary text-4xl mb-4">
                        <!-- Replace with actual SVG icons or a font icon library like Font Awesome -->
                        <span class="dashicons dashicons-<?php echo esc_attr( $prop['icon'] ); ?>"></span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2"><?php echo esc_html( $prop['title'] ); ?></h3>
                    <p class="text-gray-600"><?php echo esc_html( $prop['text'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
