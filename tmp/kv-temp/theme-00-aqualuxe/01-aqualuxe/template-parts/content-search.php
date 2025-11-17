
<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-search-result'); ?>>
    <div class="search-result-inner">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="search-content">
            <header class="entry-header">
                <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-meta">
                        <span class="posted-on">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo esc_html(get_the_date()); ?>
                        </span>
                        
                        <span class="posted-by">
                            <i class="fas fa-user"></i>
                            <?php the_author_posts_link(); ?>
                        </span>
                        
                        <?php if (has_category()) : ?>
                            <span class="post-categories">
                                <i class="fas fa-folder"></i>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div><!-- .entry-meta -->
                <?php elseif ('product' === get_post_type() && class_exists('WooCommerce')) : ?>
                    <div class="entry-meta">
                        <span class="product-price">
                            <i class="fas fa-tag"></i>
                            <?php wc_get_template('loop/price.php'); ?>
                        </span>
                        
                        <?php if (wc_get_product()->get_average_rating() > 0) : ?>
                            <span class="product-rating">
                                <i class="fas fa-star"></i>
                                <?php wc_get_template('loop/rating.php'); ?>
                            </span>
                        <?php endif; ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="read-more-link">
                    <?php 
                    if ('product' === get_post_type() && class_exists('WooCommerce')) {
                        esc_html_e('View Product', 'aqualuxe');
                    } else {
                        esc_html_e('Read More', 'aqualuxe');
                    }
                    ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div><!-- .entry-summary -->

            <footer class="entry-footer">
                <?php
                $post_type = get_post_type();
                echo '<span class="post-type"><i class="fas fa-file"></i> ';
                
                if ('post' === $post_type) {
                    esc_html_e('Blog Post', 'aqualuxe');
                } elseif ('page' === $post_type) {
                    esc_html_e('Page', 'aqualuxe');
                } elseif ('product' === $post_type) {
                    esc_html_e('Product', 'aqualuxe');
                } else {
                    echo esc_html(ucfirst($post_type));
                }
                
                echo '</span>';
                ?>
            </footer><!-- .entry-footer -->
        </div><!-- .search-content -->
    </div><!-- .search-result-inner -->
</article><!-- #post-<?php the_ID(); ?> -->
