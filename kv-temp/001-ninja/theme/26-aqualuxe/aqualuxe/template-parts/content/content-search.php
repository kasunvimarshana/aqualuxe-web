<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md' ); ?>>
    <div class="entry-content-wrapper p-6">
        <header class="entry-header mb-4">
            <?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold mb-2"><a href="%s" rel="bookmark" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

            <?php if ( 'post' === get_post_type() ) : ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php elseif ( 'page' === get_post_type() ) : ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <span class="post-type">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <?php esc_html_e( 'Page', 'aqualuxe' ); ?>
                    </span>
                </div>
            <?php elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) : ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <span class="post-type">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <?php esc_html_e( 'Product', 'aqualuxe' ); ?>
                    </span>
                    <?php
                    $product = wc_get_product( get_the_ID() );
                    if ( $product ) {
                        echo '<span class="mx-2">•</span>';
                        echo '<span class="product-price font-medium">' . wp_kses_post( $product->get_price_html() ) . '</span>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary prose dark:prose-invert max-w-none">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer mt-4">
            <?php
            if ( 'post' === get_post_type() ) {
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
                if ( $categories_list ) {
                    echo '<div class="cat-links text-sm text-gray-600 dark:text-gray-400 mb-2">';
                    echo '<span class="font-medium mr-1">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
                    echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '</div>';
                }
            } elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) {
                $product_cats = get_the_terms( get_the_ID(), 'product_cat' );
                if ( $product_cats && ! is_wp_error( $product_cats ) ) {
                    echo '<div class="product-cats text-sm text-gray-600 dark:text-gray-400 mb-2">';
                    echo '<span class="font-medium mr-1">' . esc_html__( 'Categories:', 'aqualuxe' ) . '</span>';
                    
                    $cat_names = array();
                    foreach ( $product_cats as $cat ) {
                        $cat_names[] = '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
                    }
                    
                    echo implode( ', ', $cat_names );
                    echo '</div>';
                }
            }
            ?>

            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary hover:text-primary-dark dark:hover:text-primary-light font-medium transition-colors">
                <?php 
                if ( 'product' === get_post_type() ) {
                    esc_html_e( 'View Product', 'aqualuxe' );
                } elseif ( 'page' === get_post_type() ) {
                    esc_html_e( 'View Page', 'aqualuxe' );
                } else {
                    esc_html_e( 'Read More', 'aqualuxe' );
                }
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </footer><!-- .entry-footer -->
    </div><!-- .entry-content-wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->