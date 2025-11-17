<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
    <div class="search-result-inner">
        <?php
        // Featured Image
        if (has_post_thumbnail() && get_theme_mod('aqualuxe_show_search_featured_image', true)) {
            ?>
            <div class="entry-media">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                    <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                </a>
            </div>
            <?php
        }
        ?>

        <div class="entry-wrapper">
            <header class="entry-header">
                <?php
                // Post Type Label
                $post_type = get_post_type();
                $post_type_obj = get_post_type_object($post_type);
                if ($post_type_obj) {
                    ?>
                    <div class="entry-post-type">
                        <span class="post-type-label"><?php echo esc_html($post_type_obj->labels->singular_name); ?></span>
                    </div>
                    <?php
                }
                
                // Title
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                
                // Meta
                if ('post' === $post_type) {
                    ?>
                    <div class="entry-meta">
                        <?php
                        aqualuxe_posted_on();
                        aqualuxe_posted_by();
                        ?>
                    </div><!-- .entry-meta -->
                    <?php
                }
                ?>
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <?php
                // For products, show price
                if ($post_type === 'product' && aqualuxe_is_woocommerce_active()) {
                    global $product;
                    if ($product) {
                        ?>
                        <div class="product-price">
                            <?php echo wp_kses_post($product->get_price_html()); ?>
                        </div>
                        <?php
                    }
                }
                
                // Excerpt
                the_excerpt();
                ?>
            </div><!-- .entry-summary -->

            <footer class="entry-footer">
                <?php
                // Read More Button
                ?>
                <div class="read-more">
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                        <?php
                        if ($post_type === 'product' && aqualuxe_is_woocommerce_active()) {
                            echo esc_html__('View Product', 'aqualuxe');
                        } else {
                            echo esc_html__('Read More', 'aqualuxe');
                        }
                        ?>
                    </a>
                </div>
            </footer><!-- .entry-footer -->
        </div><!-- .entry-wrapper -->
    </div><!-- .search-result-inner -->
</article><!-- #post-<?php the_ID(); ?> -->