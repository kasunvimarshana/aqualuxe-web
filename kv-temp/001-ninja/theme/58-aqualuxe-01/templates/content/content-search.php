<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Search options
$show_featured_image = isset($options['show_featured_image']) ? $options['show_featured_image'] : true;
$show_post_meta = isset($options['show_post_meta']) ? $options['show_post_meta'] : true;
$excerpt_length = isset($options['excerpt_length']) ? $options['excerpt_length'] : 55;
$read_more_text = isset($options['read_more_text']) ? $options['read_more_text'] : __('Read More', 'aqualuxe');

// Get post meta elements
$post_meta_elements = isset($options['post_meta_elements']) ? $options['post_meta_elements'] : array('date', 'author', 'categories', 'comments');

// Get post type for different styling
$post_type = get_post_type();
$post_type_obj = get_post_type_object($post_type);
$post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : '';

// Set post classes
$post_classes = array('search-result-item');
$post_classes[] = 'search-result-' . $post_type;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($post_classes); ?>>
    <div class="search-result-inner">
        <?php if ($show_featured_image && has_post_thumbnail()) : ?>
            <div class="search-thumbnail">
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php the_post_thumbnail('thumbnail', array('class' => 'search-thumbnail-image')); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="search-content">
            <header class="entry-header">
                <?php if ($post_type_name) : ?>
                    <div class="result-type">
                        <span class="post-type-label"><?php echo esc_html($post_type_name); ?></span>
                    </div>
                <?php endif; ?>

                <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                <?php if ($show_post_meta) : ?>
                    <div class="entry-meta">
                        <?php
                        if (in_array('date', $post_meta_elements)) {
                            aqualuxe_posted_on();
                        }
                        
                        if (in_array('author', $post_meta_elements) && 'post' === get_post_type()) {
                            aqualuxe_posted_by();
                        }
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
            </header><!-- .entry-header -->

            <div class="entry-summary">
                <?php 
                // Highlight search terms in excerpt
                $excerpt = get_the_excerpt();
                $keys = explode(' ', get_search_query());
                $excerpt = preg_replace('/(' . implode('|', $keys) . ')/iu', '<span class="search-highlight">$0</span>', $excerpt);
                
                echo '<p>' . wp_kses_post($excerpt) . '</p>';
                ?>
                
                <div class="read-more-link">
                    <a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html($read_more_text); ?> <span class="icon-arrow-right"></span></a>
                </div>
            </div><!-- .entry-summary -->

            <footer class="entry-footer">
                <?php
                // Show categories and tags for posts
                if ('post' === get_post_type()) {
                    if (in_array('categories', $post_meta_elements) && has_category()) {
                        echo '<div class="post-categories">';
                        echo '<span class="meta-icon"><span class="icon-folder"></span></span>';
                        the_category(', ');
                        echo '</div>';
                    }
                    
                    if (in_array('tags', $post_meta_elements) && has_tag()) {
                        echo '<div class="post-tags">';
                        echo '<span class="meta-icon"><span class="icon-tag"></span></span>';
                        the_tags('', ', ', '');
                        echo '</div>';
                    }
                }
                
                // Show custom taxonomies for other post types
                $taxonomies = get_object_taxonomies($post_type, 'objects');
                foreach ($taxonomies as $taxonomy) {
                    if (!$taxonomy->public || $taxonomy->name === 'category' || $taxonomy->name === 'post_tag') {
                        continue;
                    }
                    
                    $terms = get_the_terms(get_the_ID(), $taxonomy->name);
                    if (!empty($terms) && !is_wp_error($terms)) {
                        echo '<div class="post-' . esc_attr($taxonomy->name) . '">';
                        echo '<span class="meta-icon"><span class="icon-tag"></span></span>';
                        echo '<span class="taxonomy-name">' . esc_html($taxonomy->labels->singular_name) . ': </span>';
                        
                        $term_links = array();
                        foreach ($terms as $term) {
                            $term_links[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                        }
                        
                        echo implode(', ', $term_links);
                        echo '</div>';
                    }
                }
                ?>
            </footer><!-- .entry-footer -->
        </div><!-- .search-content -->
    </div><!-- .search-result-inner -->
</article><!-- #post-<?php the_ID(); ?> -->