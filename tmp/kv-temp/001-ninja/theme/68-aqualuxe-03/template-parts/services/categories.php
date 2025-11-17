<?php
/**
 * Services Page Categories Filter Section
 * @package AquaLuxe
 */
?>
<section class="services-categories">
    <div class="container">
        <h2>Service Categories</h2>
        <ul class="category-list">
            <?php
            $terms = get_terms([
                'taxonomy' => 'service_category',
                'hide_empty' => true,
            ]);
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                foreach ( $terms as $term ) :
            ?>
                <li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
            <?php
                endforeach;
            else:
            ?>
                <li>No categories found.</li>
            <?php endif; ?>
        </ul>
    </div>
</section>
