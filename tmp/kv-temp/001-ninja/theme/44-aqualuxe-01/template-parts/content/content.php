<?php
/**
 * Template part for displaying posts in archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get archive layout options
$archive_layout = get_theme_mod('aqualuxe_archive_layout', 'grid');
$column_class = 'col-lg-4 col-md-6';

if ($archive_layout === 'list') {
    $column_class = 'col-lg-12';
} elseif ($archive_layout === 'grid-2') {
    $column_class = 'col-lg-6 col-md-6';
} elseif ($archive_layout === 'grid-3') {
    $column_class = 'col-lg-4 col-md-6';
} elseif ($archive_layout === 'grid-4') {
    $column_class = 'col-lg-3 col-md-6';
}
?>

<div class="<?php echo esc_attr($column_class); ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
        <?php
        // Featured Image
        if (has_post_thumbnail() && get_theme_mod('aqualuxe_show_archive_featured_image', true)) {
            ?>
            <div class="entry-media">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                    <?php
                    if ($archive_layout === 'list') {
                        the_post_thumbnail('aqualuxe-list-thumbnail', array('class' => 'img-fluid'));
                    } else {
                        the_post_thumbnail('aqualuxe-grid-thumbnail', array('class' => 'img-fluid'));
                    }
                    ?>
                </a>
                
                <?php
                // Post Format Icon
                $post_format = get_post_format() ?: 'standard';
                $format_icon = 'fa-file-alt';
                
                switch ($post_format) {
                    case 'video':
                        $format_icon = 'fa-video';
                        break;
                    case 'audio':
                        $format_icon = 'fa-music';
                        break;
                    case 'gallery':
                        $format_icon = 'fa-images';
                        break;
                    case 'quote':
                        $format_icon = 'fa-quote-right';
                        break;
                    case 'link':
                        $format_icon = 'fa-link';
                        break;
                }
                ?>
                <div class="post-format">
                    <i class="fas <?php echo esc_attr($format_icon); ?>"></i>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="entry-wrapper">
            <header class="entry-header">
                <?php
                // Categories
                if (get_theme_mod('aqualuxe_show_archive_categories', true)) {
                    $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
                    if ($categories_list) {
                        ?>
                        <div class="entry-categories">
                            <span class="cat-links"><?php echo wp_kses_post($categories_list); ?></span>
                        </div>
                        <?php
                    }
                }
                
                // Title
                if (get_theme_mod('aqualuxe_show_archive_title', true)) {
                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                }
                
                // Meta
                if ('post' === get_post_type() && get_theme_mod('aqualuxe_show_archive_meta', true)) {
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

            <?php
            // Excerpt
            if (get_theme_mod('aqualuxe_show_archive_excerpt', true)) {
                ?>
                <div class="entry-summary">
                    <?php
                    if ($archive_layout === 'list') {
                        the_excerpt();
                    } else {
                        echo wp_kses_post(aqualuxe_get_excerpt(20));
                    }
                    ?>
                </div><!-- .entry-summary -->
                <?php
            }
            ?>

            <footer class="entry-footer">
                <?php
                // Read More Button
                if (get_theme_mod('aqualuxe_show_archive_readmore', true)) {
                    ?>
                    <div class="read-more">
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                            <?php echo esc_html(get_theme_mod('aqualuxe_readmore_text', __('Read More', 'aqualuxe'))); ?>
                        </a>
                    </div>
                    <?php
                }
                
                // Comments Count
                if (get_theme_mod('aqualuxe_show_archive_comments_count', true) && comments_open()) {
                    ?>
                    <div class="comments-link">
                        <i class="far fa-comment"></i>
                        <?php comments_popup_link('0', '1', '%'); ?>
                    </div>
                    <?php
                }
                ?>
            </footer><!-- .entry-footer -->
        </div><!-- .entry-wrapper -->
    </article><!-- #post-<?php the_ID(); ?> -->
</div>