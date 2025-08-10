<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :
            ?>

            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: 1: link to WP admin new post page. */
                        __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url( admin_url( 'post-new.php' ) )
                );
                ?>
            </p>

        <?php elseif ( is_search() ) : ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>
            
            <div class="search-suggestions">
                <h3><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h3>
                <ul>
                    <li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Try different keywords.', 'aqualuxe' ); ?></li>
                    <li><?php esc_html_e( 'Try fewer keywords.', 'aqualuxe' ); ?></li>
                </ul>
            </div>
            
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <div class="product-categories-wrapper">
                    <h3><?php esc_html_e( 'Browse Product Categories:', 'aqualuxe' ); ?></h3>
                    <?php
                    $product_categories = get_terms( array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'parent'     => 0,
                        'number'     => 6,
                    ) );
                    
                    if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                        echo '<ul class="product-categories">';
                        
                        foreach ( $product_categories as $category ) {
                            echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></li>';
                        }
                        
                        echo '</ul>';
                    }
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>
            
            <div class="recent-posts-wrapper">
                <h3><?php esc_html_e( 'Recent Posts:', 'aqualuxe' ); ?></h3>
                <?php
                $recent_posts = new WP_Query( array(
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                ) );
                
                if ( $recent_posts->have_posts() ) {
                    echo '<ul class="recent-posts">';
                    
                    while ( $recent_posts->have_posts() ) {
                        $recent_posts->the_post();
                        echo '<li><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></li>';
                    }
                    
                    echo '</ul>';
                    
                    wp_reset_postdata();
                }
                ?>
            </div>

        <?php endif; ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->