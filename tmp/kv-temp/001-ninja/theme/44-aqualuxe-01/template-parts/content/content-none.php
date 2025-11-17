<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('Nothing Found', 'aqualuxe'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <?php
        if (is_home() && current_user_can('publish_posts')) :
            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe'),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url(admin_url('post-new.php'))
            );
        elseif (is_search()) :
            ?>
            <div class="search-no-results">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                
                <div class="search-suggestions">
                    <h3><?php esc_html_e('Search Suggestions:', 'aqualuxe'); ?></h3>
                    <ul>
                        <li><?php esc_html_e('Check your spelling.', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Try more general keywords.', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Try different keywords that mean the same thing.', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Try searching with short and simple keywords.', 'aqualuxe'); ?></li>
                    </ul>
                </div>
                
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
                
                <div class="popular-searches">
                    <h3><?php esc_html_e('Popular Searches:', 'aqualuxe'); ?></h3>
                    <?php
                    // Get popular search terms if available
                    $popular_searches = get_option('aqualuxe_popular_searches');
                    
                    if (!empty($popular_searches) && is_array($popular_searches)) {
                        echo '<ul class="popular-search-terms">';
                        foreach ($popular_searches as $term => $count) {
                            printf(
                                '<li><a href="%1$s">%2$s</a></li>',
                                esc_url(add_query_arg('s', urlencode($term), home_url('/'))),
                                esc_html($term)
                            );
                        }
                        echo '</ul>';
                    } else {
                        // Fallback to popular categories
                        $categories = get_categories(array(
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'number'     => 5,
                            'hide_empty' => true,
                        ));
                        
                        if (!empty($categories)) {
                            echo '<ul class="popular-categories">';
                            foreach ($categories as $category) {
                                printf(
                                    '<li><a href="%1$s">%2$s</a></li>',
                                    esc_url(get_category_link($category->term_id)),
                                    esc_html($category->name)
                                );
                            }
                            echo '</ul>';
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        elseif (is_archive()) :
            ?>
            <div class="archive-no-results">
                <div class="no-results-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <p><?php esc_html_e('We couldn\'t find any posts in this archive. Perhaps searching can help.', 'aqualuxe'); ?></p>
                
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
            </div>
            <?php
        else :
            ?>
            <div class="general-no-results">
                <div class="no-results-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe'); ?></p>
                
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
            </div>
            <?php
        endif;
        ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->