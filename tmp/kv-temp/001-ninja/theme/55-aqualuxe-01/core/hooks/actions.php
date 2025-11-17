<?php
/**
 * AquaLuxe Actions
 *
 * This file contains action hooks used throughout the theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header actions
 */

/**
 * Display header
 */
if (!function_exists('aqualuxe_header')) {
    function aqualuxe_header() {
        ?>
        <header id="masthead" class="site-header">
            <?php do_action('aqualuxe_header'); ?>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_main_content', 'aqualuxe_header', 10);

/**
 * Display header top
 */
if (!function_exists('aqualuxe_header_top')) {
    function aqualuxe_header_top() {
        do_action('aqualuxe_header_top');
    }
}
add_action('aqualuxe_header', 'aqualuxe_header_top', 10);

/**
 * Display header main
 */
if (!function_exists('aqualuxe_header_main')) {
    function aqualuxe_header_main() {
        ?>
        <div class="header-main">
            <div class="container">
                <div class="header-main-inner">
                    <?php do_action('aqualuxe_header_main'); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('aqualuxe_header', 'aqualuxe_header_main', 20);

/**
 * Display header bottom
 */
if (!function_exists('aqualuxe_header_bottom')) {
    function aqualuxe_header_bottom() {
        do_action('aqualuxe_header_bottom');
    }
}
add_action('aqualuxe_header', 'aqualuxe_header_bottom', 30);

/**
 * Display header logo
 */
if (!function_exists('aqualuxe_header_logo')) {
    function aqualuxe_header_logo() {
        ?>
        <div class="site-branding">
            <?php aqualuxe_site_logo(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_header_main', 'aqualuxe_header_logo', 10);

/**
 * Display header navigation
 */
if (!function_exists('aqualuxe_header_navigation')) {
    function aqualuxe_header_navigation() {
        ?>
        <div class="header-navigation">
            <?php aqualuxe_primary_navigation(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_header_main', 'aqualuxe_header_navigation', 20);

/**
 * Display header actions
 */
if (!function_exists('aqualuxe_header_actions')) {
    function aqualuxe_header_actions() {
        ?>
        <div class="header-actions">
            <?php do_action('aqualuxe_header_actions'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_header_main', 'aqualuxe_header_actions', 30);

/**
 * Footer actions
 */

/**
 * Display footer
 */
if (!function_exists('aqualuxe_footer')) {
    function aqualuxe_footer() {
        ?>
        <footer id="colophon" class="site-footer">
            <?php do_action('aqualuxe_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_main_content', 'aqualuxe_footer', 10);

/**
 * Display footer widgets
 */
if (!function_exists('aqualuxe_footer_widgets')) {
    function aqualuxe_footer_widgets() {
        // Check if footer widgets are enabled
        if (!aqualuxe_get_option('footer_widgets_enable', true)) {
            return;
        }

        // Check if any footer widget area has widgets
        if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
            return;
        }

        // Get footer columns
        $columns = aqualuxe_get_option('footer_widgets_columns', 4);
        ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="footer-widgets-inner footer-widgets-columns-<?php echo esc_attr($columns); ?>">
                    <?php
                    // Footer widget areas
                    for ($i = 1; $i <= $columns; $i++) {
                        ?>
                        <div class="footer-widget-area footer-widget-area-<?php echo esc_attr($i); ?>">
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);

/**
 * Display footer bottom
 */
if (!function_exists('aqualuxe_footer_bottom')) {
    function aqualuxe_footer_bottom() {
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <?php do_action('aqualuxe_footer_bottom'); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer', 'aqualuxe_footer_bottom', 20);

/**
 * Display footer copyright
 */
if (!function_exists('aqualuxe_footer_copyright')) {
    function aqualuxe_footer_copyright() {
        // Check if footer copyright is enabled
        if (!aqualuxe_get_option('footer_copyright_enable', true)) {
            return;
        }

        // Get copyright text
        $copyright_text = aqualuxe_get_option('footer_copyright_text', sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        ));
        ?>
        <div class="footer-copyright">
            <?php echo wp_kses_post($copyright_text); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_copyright', 10);

/**
 * Display footer navigation
 */
if (!function_exists('aqualuxe_footer_navigation')) {
    function aqualuxe_footer_navigation() {
        // Check if footer navigation is enabled
        if (!aqualuxe_get_option('footer_navigation_enable', true)) {
            return;
        }
        ?>
        <div class="footer-navigation">
            <?php aqualuxe_footer_navigation(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_navigation', 20);

/**
 * Display footer social
 */
if (!function_exists('aqualuxe_footer_social')) {
    function aqualuxe_footer_social() {
        // Check if footer social is enabled
        if (!aqualuxe_get_option('footer_social_enable', true)) {
            return;
        }
        ?>
        <div class="footer-social">
            <?php aqualuxe_social_links(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_social', 30);

/**
 * Page actions
 */

/**
 * Display page header
 */
if (!function_exists('aqualuxe_page_header')) {
    function aqualuxe_page_header() {
        // Check if page header is enabled
        if (!aqualuxe_get_option('page_header_enable', true)) {
            return;
        }
        ?>
        <header class="page-header">
            <div class="container">
                <?php do_action('aqualuxe_page_header'); ?>
            </div>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_page_content', 'aqualuxe_page_header', 10);

/**
 * Display page title
 */
if (!function_exists('aqualuxe_page_title')) {
    function aqualuxe_page_title() {
        ?>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <?php
    }
}
add_action('aqualuxe_page_header', 'aqualuxe_page_title', 10);

/**
 * Display page breadcrumbs
 */
if (!function_exists('aqualuxe_page_breadcrumbs')) {
    function aqualuxe_page_breadcrumbs() {
        // Check if breadcrumbs are enabled
        if (!aqualuxe_get_option('breadcrumbs_enable', true)) {
            return;
        }

        aqualuxe_breadcrumbs();
    }
}
add_action('aqualuxe_page_header', 'aqualuxe_page_breadcrumbs', 20);

/**
 * Display page content
 */
if (!function_exists('aqualuxe_page_content')) {
    function aqualuxe_page_content() {
        ?>
        <div class="page-content">
            <?php do_action('aqualuxe_page_content'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_page', 'aqualuxe_page_content', 20);

/**
 * Display page main content
 */
if (!function_exists('aqualuxe_page_main_content')) {
    function aqualuxe_page_main_content() {
        the_content();

        wp_link_pages([
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
            'after'  => '</div>',
        ]);
    }
}
add_action('aqualuxe_page_content', 'aqualuxe_page_main_content', 10);

/**
 * Display page footer
 */
if (!function_exists('aqualuxe_page_footer')) {
    function aqualuxe_page_footer() {
        ?>
        <footer class="page-footer">
            <?php do_action('aqualuxe_page_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_page_content', 'aqualuxe_page_footer', 10);

/**
 * Display page comments
 */
if (!function_exists('aqualuxe_page_comments')) {
    function aqualuxe_page_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }
}
add_action('aqualuxe_page_footer', 'aqualuxe_page_comments', 10);

/**
 * Single post actions
 */

/**
 * Display single post header
 */
if (!function_exists('aqualuxe_single_post_header')) {
    function aqualuxe_single_post_header() {
        ?>
        <header class="single-post-header">
            <?php do_action('aqualuxe_single_post_header'); ?>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_single_post_content', 'aqualuxe_single_post_header', 10);

/**
 * Display single post title
 */
if (!function_exists('aqualuxe_single_post_title')) {
    function aqualuxe_single_post_title() {
        ?>
        <h1 class="single-post-title"><?php the_title(); ?></h1>
        <?php
    }
}
add_action('aqualuxe_single_post_header', 'aqualuxe_single_post_title', 10);

/**
 * Display single post meta
 */
if (!function_exists('aqualuxe_single_post_meta')) {
    function aqualuxe_single_post_meta() {
        ?>
        <div class="single-post-meta">
            <?php aqualuxe_post_meta(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_single_post_header', 'aqualuxe_single_post_meta', 20);

/**
 * Display single post content
 */
if (!function_exists('aqualuxe_single_post_content')) {
    function aqualuxe_single_post_content() {
        ?>
        <div class="single-post-content">
            <?php do_action('aqualuxe_single_post_content'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_single_post', 'aqualuxe_single_post_content', 20);

/**
 * Display single post featured image
 */
if (!function_exists('aqualuxe_single_post_featured_image')) {
    function aqualuxe_single_post_featured_image() {
        // Check if featured image is enabled
        if (!aqualuxe_get_option('single_post_featured_image_enable', true)) {
            return;
        }

        if (has_post_thumbnail()) {
            ?>
            <div class="single-post-featured-image">
                <?php the_post_thumbnail('aqualuxe-featured'); ?>
            </div>
            <?php
        }
    }
}
add_action('aqualuxe_single_post_content', 'aqualuxe_single_post_featured_image', 10);

/**
 * Display single post main content
 */
if (!function_exists('aqualuxe_single_post_main_content')) {
    function aqualuxe_single_post_main_content() {
        the_content();

        wp_link_pages([
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
            'after'  => '</div>',
        ]);
    }
}
add_action('aqualuxe_single_post_content', 'aqualuxe_single_post_main_content', 20);

/**
 * Display single post footer
 */
if (!function_exists('aqualuxe_single_post_footer')) {
    function aqualuxe_single_post_footer() {
        ?>
        <footer class="single-post-footer">
            <?php do_action('aqualuxe_single_post_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_single_post_content', 'aqualuxe_single_post_footer', 10);

/**
 * Display single post tags
 */
if (!function_exists('aqualuxe_single_post_tags')) {
    function aqualuxe_single_post_tags() {
        // Check if tags are enabled
        if (!aqualuxe_get_option('single_post_tags_enable', true)) {
            return;
        }

        $tags = get_the_tags();

        if ($tags) {
            ?>
            <div class="single-post-tags">
                <span class="single-post-tags-title"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                <ul class="single-post-tags-list">
                    <?php
                    foreach ($tags as $tag) {
                        ?>
                        <li class="single-post-tags-item">
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="single-post-tags-link"><?php echo esc_html($tag->name); ?></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_tags', 10);

/**
 * Display single post share
 */
if (!function_exists('aqualuxe_single_post_share')) {
    function aqualuxe_single_post_share() {
        // Check if share links are enabled
        if (!aqualuxe_get_option('single_post_share_enable', true)) {
            return;
        }

        aqualuxe_share_links();
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_share', 20);

/**
 * Display single post author
 */
if (!function_exists('aqualuxe_single_post_author')) {
    function aqualuxe_single_post_author() {
        // Check if author box is enabled
        if (!aqualuxe_get_option('single_post_author_enable', true)) {
            return;
        }

        aqualuxe_post_author_box();
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_author', 30);

/**
 * Display single post navigation
 */
if (!function_exists('aqualuxe_single_post_navigation')) {
    function aqualuxe_single_post_navigation() {
        // Check if post navigation is enabled
        if (!aqualuxe_get_option('single_post_navigation_enable', true)) {
            return;
        }

        aqualuxe_post_navigation();
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_navigation', 40);

/**
 * Display single post related
 */
if (!function_exists('aqualuxe_single_post_related')) {
    function aqualuxe_single_post_related() {
        // Check if related posts are enabled
        if (!aqualuxe_get_option('single_post_related_enable', true)) {
            return;
        }

        aqualuxe_related_posts();
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_related', 50);

/**
 * Display single post comments
 */
if (!function_exists('aqualuxe_single_post_comments')) {
    function aqualuxe_single_post_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }
}
add_action('aqualuxe_single_post_footer', 'aqualuxe_single_post_comments', 60);

/**
 * Archive actions
 */

/**
 * Display archive header
 */
if (!function_exists('aqualuxe_archive_header')) {
    function aqualuxe_archive_header() {
        ?>
        <header class="archive-header">
            <div class="container">
                <?php do_action('aqualuxe_archive_header'); ?>
            </div>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_archive_content', 'aqualuxe_archive_header', 10);

/**
 * Display archive title
 */
if (!function_exists('aqualuxe_archive_title')) {
    function aqualuxe_archive_title() {
        ?>
        <h1 class="archive-title"><?php the_archive_title(); ?></h1>
        <?php
    }
}
add_action('aqualuxe_archive_header', 'aqualuxe_archive_title', 10);

/**
 * Display archive description
 */
if (!function_exists('aqualuxe_archive_description')) {
    function aqualuxe_archive_description() {
        the_archive_description('<div class="archive-description">', '</div>');
    }
}
add_action('aqualuxe_archive_header', 'aqualuxe_archive_description', 20);

/**
 * Display archive content
 */
if (!function_exists('aqualuxe_archive_content')) {
    function aqualuxe_archive_content() {
        ?>
        <div class="archive-content">
            <?php do_action('aqualuxe_archive_content'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_archive', 'aqualuxe_archive_content', 20);

/**
 * Display archive posts
 */
if (!function_exists('aqualuxe_archive_posts')) {
    function aqualuxe_archive_posts() {
        if (have_posts()) {
            ?>
            <div class="archive-posts">
                <?php
                while (have_posts()) {
                    the_post();

                    // Get template part based on post type
                    get_template_part('templates/content/content', get_post_type());
                }
                ?>
            </div>
            <?php
        } else {
            get_template_part('templates/content/content', 'none');
        }
    }
}
add_action('aqualuxe_archive_content', 'aqualuxe_archive_posts', 10);

/**
 * Display archive footer
 */
if (!function_exists('aqualuxe_archive_footer')) {
    function aqualuxe_archive_footer() {
        ?>
        <footer class="archive-footer">
            <?php do_action('aqualuxe_archive_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_archive_content', 'aqualuxe_archive_footer', 10);

/**
 * Display archive pagination
 */
if (!function_exists('aqualuxe_archive_pagination')) {
    function aqualuxe_archive_pagination() {
        aqualuxe_pagination();
    }
}
add_action('aqualuxe_archive_footer', 'aqualuxe_archive_pagination', 10);

/**
 * Search actions
 */

/**
 * Display search header
 */
if (!function_exists('aqualuxe_search_header')) {
    function aqualuxe_search_header() {
        ?>
        <header class="search-header">
            <div class="container">
                <?php do_action('aqualuxe_search_header'); ?>
            </div>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_search_content', 'aqualuxe_search_header', 10);

/**
 * Display search title
 */
if (!function_exists('aqualuxe_search_title')) {
    function aqualuxe_search_title() {
        ?>
        <h1 class="search-title">
            <?php
            /* translators: %s: search query */
            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
            ?>
        </h1>
        <?php
    }
}
add_action('aqualuxe_search_header', 'aqualuxe_search_title', 10);

/**
 * Display search form
 */
if (!function_exists('aqualuxe_search_form')) {
    function aqualuxe_search_form() {
        ?>
        <div class="search-form-wrapper">
            <?php get_search_form(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_search_header', 'aqualuxe_search_form', 20);

/**
 * Display search content
 */
if (!function_exists('aqualuxe_search_content')) {
    function aqualuxe_search_content() {
        ?>
        <div class="search-content">
            <?php do_action('aqualuxe_search_content'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_search', 'aqualuxe_search_content', 20);

/**
 * Display search results
 */
if (!function_exists('aqualuxe_search_results')) {
    function aqualuxe_search_results() {
        if (have_posts()) {
            ?>
            <div class="search-results">
                <?php
                while (have_posts()) {
                    the_post();

                    // Get template part based on post type
                    get_template_part('templates/content/content', 'search');
                }
                ?>
            </div>
            <?php
        } else {
            get_template_part('templates/content/content', 'none');
        }
    }
}
add_action('aqualuxe_search_content', 'aqualuxe_search_results', 10);

/**
 * Display search footer
 */
if (!function_exists('aqualuxe_search_footer')) {
    function aqualuxe_search_footer() {
        ?>
        <footer class="search-footer">
            <?php do_action('aqualuxe_search_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_search_content', 'aqualuxe_search_footer', 10);

/**
 * Display search pagination
 */
if (!function_exists('aqualuxe_search_pagination')) {
    function aqualuxe_search_pagination() {
        aqualuxe_pagination();
    }
}
add_action('aqualuxe_search_footer', 'aqualuxe_search_pagination', 10);

/**
 * 404 actions
 */

/**
 * Display 404 header
 */
if (!function_exists('aqualuxe_404_header')) {
    function aqualuxe_404_header() {
        ?>
        <header class="error-404-header">
            <div class="container">
                <?php do_action('aqualuxe_404_header'); ?>
            </div>
        </header>
        <?php
    }
}
add_action('aqualuxe_before_404_content', 'aqualuxe_404_header', 10);

/**
 * Display 404 title
 */
if (!function_exists('aqualuxe_404_title')) {
    function aqualuxe_404_title() {
        ?>
        <h1 class="error-404-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
        <?php
    }
}
add_action('aqualuxe_404_header', 'aqualuxe_404_title', 10);

/**
 * Display 404 content
 */
if (!function_exists('aqualuxe_404_content')) {
    function aqualuxe_404_content() {
        ?>
        <div class="error-404-content">
            <?php do_action('aqualuxe_404_content'); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_404', 'aqualuxe_404_content', 20);

/**
 * Display 404 message
 */
if (!function_exists('aqualuxe_404_message')) {
    function aqualuxe_404_message() {
        ?>
        <div class="error-404-message">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}
add_action('aqualuxe_404_content', 'aqualuxe_404_message', 10);

/**
 * Display 404 search
 */
if (!function_exists('aqualuxe_404_search')) {
    function aqualuxe_404_search() {
        ?>
        <div class="error-404-search">
            <?php get_search_form(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_404_content', 'aqualuxe_404_search', 20);

/**
 * Display 404 footer
 */
if (!function_exists('aqualuxe_404_footer')) {
    function aqualuxe_404_footer() {
        ?>
        <footer class="error-404-footer">
            <?php do_action('aqualuxe_404_footer'); ?>
        </footer>
        <?php
    }
}
add_action('aqualuxe_after_404_content', 'aqualuxe_404_footer', 10);

/**
 * Display 404 navigation
 */
if (!function_exists('aqualuxe_404_navigation')) {
    function aqualuxe_404_navigation() {
        ?>
        <div class="error-404-navigation">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button"><?php esc_html_e('Back to Home', 'aqualuxe'); ?></a>
        </div>
        <?php
    }
}
add_action('aqualuxe_404_footer', 'aqualuxe_404_navigation', 10);

/**
 * Comments actions
 */

/**
 * Display comments
 */
if (!function_exists('aqualuxe_comments')) {
    function aqualuxe_comments() {
        ?>
        <div id="comments" class="comments-area">
            <?php do_action('aqualuxe_comments'); ?>
        </div>
        <?php
    }
}

/**
 * Display comments title
 */
if (!function_exists('aqualuxe_comments_title')) {
    function aqualuxe_comments_title() {
        $comments_count = get_comments_number();

        if ($comments_count === 0) {
            echo '<h2 class="comments-title">' . esc_html__('No Comments', 'aqualuxe') . '</h2>';
        } elseif ($comments_count === 1) {
            echo '<h2 class="comments-title">' . esc_html__('1 Comment', 'aqualuxe') . '</h2>';
        } else {
            echo '<h2 class="comments-title">';
            printf(
                /* translators: %s: Comment count */
                esc_html(_n('%s Comment', '%s Comments', $comments_count, 'aqualuxe')),
                esc_html(number_format_i18n($comments_count))
            );
            echo '</h2>';
        }
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_title', 10);

/**
 * Display comments list
 */
if (!function_exists('aqualuxe_comments_list')) {
    function aqualuxe_comments_list() {
        wp_list_comments([
            'style' => 'ol',
            'short_ping' => true,
            'avatar_size' => 60,
        ]);
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_list', 20);

/**
 * Display comments navigation
 */
if (!function_exists('aqualuxe_comments_navigation')) {
    function aqualuxe_comments_navigation() {
        the_comments_navigation();
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_navigation', 30);

/**
 * Display comments form
 */
if (!function_exists('aqualuxe_comments_form')) {
    function aqualuxe_comments_form() {
        comment_form([
            'title_reply' => esc_html__('Leave a Comment', 'aqualuxe'),
            'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
            'title_reply_after' => '</h3>',
        ]);
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_form', 40);

/**
 * Sidebar actions
 */

/**
 * Display sidebar
 */
if (!function_exists('aqualuxe_sidebar')) {
    function aqualuxe_sidebar() {
        do_action('aqualuxe_sidebar');
    }
}

/**
 * Get sidebar
 */
if (!function_exists('aqualuxe_get_sidebar')) {
    function aqualuxe_get_sidebar() {
        get_sidebar();
    }
}
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10);

/**
 * WooCommerce actions
 */

if (aqualuxe_is_woocommerce_active()) {
    /**
     * Display shop header
     */
    if (!function_exists('aqualuxe_woocommerce_shop_header')) {
        function aqualuxe_woocommerce_shop_header() {
            ?>
            <header class="woocommerce-shop-header">
                <div class="container">
                    <?php do_action('aqualuxe_woocommerce_shop_header'); ?>
                </div>
            </header>
            <?php
        }
    }
    add_action('aqualuxe_before_woocommerce_shop_content', 'aqualuxe_woocommerce_shop_header', 10);

    /**
     * Display shop content
     */
    if (!function_exists('aqualuxe_woocommerce_shop_content')) {
        function aqualuxe_woocommerce_shop_content() {
            ?>
            <div class="woocommerce-shop-content">
                <?php do_action('aqualuxe_woocommerce_shop_content'); ?>
            </div>
            <?php
        }
    }
    add_action('aqualuxe_woocommerce_shop', 'aqualuxe_woocommerce_shop_content', 20);

    /**
     * Display shop footer
     */
    if (!function_exists('aqualuxe_woocommerce_shop_footer')) {
        function aqualuxe_woocommerce_shop_footer() {
            ?>
            <footer class="woocommerce-shop-footer">
                <?php do_action('aqualuxe_woocommerce_shop_footer'); ?>
            </footer>
            <?php
        }
    }
    add_action('aqualuxe_after_woocommerce_shop_content', 'aqualuxe_woocommerce_shop_footer', 10);
}

/**
 * After setup theme actions
 */

/**
 * Theme setup
 */
if (!function_exists('aqualuxe_theme_setup')) {
    function aqualuxe_theme_setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Set default thumbnail size
        set_post_thumbnail_size(1200, 9999);

        // Add custom image sizes
        add_image_size('aqualuxe-featured', 1600, 900, true);
        add_image_size('aqualuxe-card', 600, 400, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);

        // Register navigation menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Menu', 'aqualuxe'),
        ]);

        // Switch default core markup to output valid HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Enqueue editor styles
        add_editor_style('assets/dist/css/editor-style.css');

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
        ]);

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
        ]);

        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image' => '',
            'width' => 1920,
            'height' => 500,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => false,
        ]);

        // Add support for post formats
        add_theme_support('post-formats', [
            'gallery',
            'image',
            'video',
            'audio',
            'quote',
            'link',
        ]);

        // Add support for block styles
        add_theme_support('wp-block-styles');

        // Add support for custom line height
        add_theme_support('custom-line-height');

        // Add support for custom spacing
        add_theme_support('custom-spacing');

        // Add support for custom units
        add_theme_support('custom-units');

        // Add support for editor color palette
        add_theme_support('editor-color-palette', [
            [
                'name' => esc_html__('Primary', 'aqualuxe'),
                'slug' => 'primary',
                'color' => '#0072B5',
            ],
            [
                'name' => esc_html__('Primary Light', 'aqualuxe'),
                'slug' => 'primary-light',
                'color' => '#0090E1',
            ],
            [
                'name' => esc_html__('Primary Dark', 'aqualuxe'),
                'slug' => 'primary-dark',
                'color' => '#005A8E',
            ],
            [
                'name' => esc_html__('Secondary', 'aqualuxe'),
                'slug' => 'secondary',
                'color' => '#00B2A9',
            ],
            [
                'name' => esc_html__('Secondary Light', 'aqualuxe'),
                'slug' => 'secondary-light',
                'color' => '#00D6CC',
            ],
            [
                'name' => esc_html__('Secondary Dark', 'aqualuxe'),
                'slug' => 'secondary-dark',
                'color' => '#008E87',
            ],
            [
                'name' => esc_html__('Accent', 'aqualuxe'),
                'slug' => 'accent',
                'color' => '#F8C630',
            ],
            [
                'name' => esc_html__('Accent Light', 'aqualuxe'),
                'slug' => 'accent-light',
                'color' => '#FFDA6A',
            ],
            [
                'name' => esc_html__('Accent Dark', 'aqualuxe'),
                'slug' => 'accent-dark',
                'color' => '#D9A400',
            ],
            [
                'name' => esc_html__('Luxe', 'aqualuxe'),
                'slug' => 'luxe',
                'color' => '#2C3E50',
            ],
            [
                'name' => esc_html__('Luxe Light', 'aqualuxe'),
                'slug' => 'luxe-light',
                'color' => '#3E5871',
            ],
            [
                'name' => esc_html__('Luxe Dark', 'aqualuxe'),
                'slug' => 'luxe-dark',
                'color' => '#1A2530',
            ],
            [
                'name' => esc_html__('White', 'aqualuxe'),
                'slug' => 'white',
                'color' => '#FFFFFF',
            ],
            [
                'name' => esc_html__('Black', 'aqualuxe'),
                'slug' => 'black',
                'color' => '#000000',
            ],
        ]);

        // Add support for editor font sizes
        add_theme_support('editor-font-sizes', [
            [
                'name' => esc_html__('Small', 'aqualuxe'),
                'size' => 14,
                'slug' => 'small',
            ],
            [
                'name' => esc_html__('Normal', 'aqualuxe'),
                'size' => 16,
                'slug' => 'normal',
            ],
            [
                'name' => esc_html__('Medium', 'aqualuxe'),
                'size' => 20,
                'slug' => 'medium',
            ],
            [
                'name' => esc_html__('Large', 'aqualuxe'),
                'size' => 24,
                'slug' => 'large',
            ],
            [
                'name' => esc_html__('Extra Large', 'aqualuxe'),
                'size' => 32,
                'slug' => 'extra-large',
            ],
            [
                'name' => esc_html__('Huge', 'aqualuxe'),
                'size' => 48,
                'slug' => 'huge',
            ],
        ]);

        // Add support for WooCommerce
        if (aqualuxe_is_woocommerce_active()) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_theme_setup');

/**
 * Register widget areas
 */
if (!function_exists('aqualuxe_widgets_init')) {
    function aqualuxe_widgets_init() {
        register_sidebar([
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => esc_html__('Footer 1', 'aqualuxe'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here to appear in footer column 1.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => esc_html__('Footer 2', 'aqualuxe'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here to appear in footer column 2.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => esc_html__('Footer 3', 'aqualuxe'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here to appear in footer column 3.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        register_sidebar([
            'name' => esc_html__('Footer 4', 'aqualuxe'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here to appear in footer column 4.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);

        // Shop sidebar (only if WooCommerce is active)
        if (aqualuxe_is_woocommerce_active()) {
            register_sidebar([
                'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id' => 'shop-sidebar',
                'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ]);
        }
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Enqueue scripts and styles
 */
if (!function_exists('aqualuxe_scripts')) {
    function aqualuxe_scripts() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-google-fonts',
            'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=JetBrains+Mono&display=swap',
            [],
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            aqualuxe_get_asset_url('css/main.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue Tailwind CSS
        wp_enqueue_style(
            'aqualuxe-tailwind',
            aqualuxe_get_asset_url('css/tailwind.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue WooCommerce styles if active
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                aqualuxe_get_asset_url('css/woocommerce.css'),
                [],
                AQUALUXE_VERSION
            );
        }

        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            aqualuxe_get_asset_url('js/app.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Add script data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isRtl' => is_rtl(),
            'isWooCommerce' => aqualuxe_is_woocommerce_active(),
            'isMobile' => wp_is_mobile(),
            'i18n' => [
                'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                'searchNoResults' => esc_html__('No results found', 'aqualuxe'),
                'menuToggle' => esc_html__('Toggle Menu', 'aqualuxe'),
                'cartEmpty' => esc_html__('Your cart is empty', 'aqualuxe'),
                'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
                'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
                'addedToCart' => esc_html__('Added to Cart', 'aqualuxe'),
                'viewCart' => esc_html__('View Cart', 'aqualuxe'),
                'checkout' => esc_html__('Checkout', 'aqualuxe'),
                'lightMode' => esc_html__('Light Mode', 'aqualuxe'),
                'darkMode' => esc_html__('Dark Mode', 'aqualuxe'),
            ],
        ]);

        // Add comment-reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_scripts');

/**
 * Enqueue admin scripts and styles
 */
if (!function_exists('aqualuxe_admin_scripts')) {
    function aqualuxe_admin_scripts() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-admin-style',
            aqualuxe_get_asset_url('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            aqualuxe_get_asset_url('js/admin.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Add admin script data
        wp_localize_script('aqualuxe-admin-script', 'aqualuxeAdminData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUrl' => AQUALUXE_URI,
            'assetsUrl' => AQUALUXE_ASSETS_URI,
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        ]);
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Add preload links
 */
if (!function_exists('aqualuxe_preload_links')) {
    function aqualuxe_preload_links() {
        // Preload Google Fonts
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

        // Preload main stylesheet
        echo '<link rel="preload" href="' . esc_url(aqualuxe_get_asset_url('css/main.css')) . '" as="style">' . "\n";

        // Preload main script
        echo '<link rel="preload" href="' . esc_url(aqualuxe_get_asset_url('js/app.js')) . '" as="script">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_preload_links', 1);

/**
 * Add inline scripts
 */
if (!function_exists('aqualuxe_inline_scripts')) {
    function aqualuxe_inline_scripts() {
        // Add dark mode script
        ?>
        <script>
            (function() {
                // Check for saved theme preference or respect OS preference
                const savedTheme = localStorage.getItem("theme");
                const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
                
                if (savedTheme === "dark" || (!savedTheme && prefersDark)) {
                    document.documentElement.classList.add("dark");
                } else {
                    document.documentElement.classList.remove("dark");
                }
            })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_inline_scripts');

/**
 * Add body classes
 */
if (!function_exists('aqualuxe_body_classes')) {
    function aqualuxe_body_classes($classes) {
        // Add a class if there is a custom header image
        if (has_header_image()) {
            $classes[] = 'has-header-image';
        }

        // Add a class if the site is using a custom logo
        if (has_custom_logo()) {
            $classes[] = 'has-custom-logo';
        }

        // Add a class for the layout
        $layout = aqualuxe_get_option('layout_type', 'right-sidebar');
        $classes[] = 'layout-' . $layout;

        // Add a class for the header layout
        $header_layout = aqualuxe_get_option('header_layout', 'default');
        $classes[] = 'header-layout-' . $header_layout;

        // Add a class for the footer layout
        $footer_layout = aqualuxe_get_option('footer_layout', 'default');
        $classes[] = 'footer-layout-' . $footer_layout;

        // Add a class if WooCommerce is active
        if (aqualuxe_is_woocommerce_active()) {
            $classes[] = 'woocommerce-active';
        }

        return $classes;
    }
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add pingback header
 */
if (!function_exists('aqualuxe_pingback_header')) {
    function aqualuxe_pingback_header() {
        if (is_singular() && pings_open()) {
            printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
        }
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add theme info
 */
if (!function_exists('aqualuxe_theme_info')) {
    function aqualuxe_theme_info() {
        echo '<!-- Theme: AquaLuxe by NinjaTech AI -->' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_theme_info', 1);

/**
 * Add meta viewport
 */
if (!function_exists('aqualuxe_meta_viewport')) {
    function aqualuxe_meta_viewport() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_meta_viewport', 1);

/**
 * Add theme color meta
 */
if (!function_exists('aqualuxe_theme_color')) {
    function aqualuxe_theme_color() {
        $primary_color = aqualuxe_get_color('primary');
        echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_theme_color', 1);

/**
 * Add scroll to top button
 */
if (!function_exists('aqualuxe_scroll_to_top')) {
    function aqualuxe_scroll_to_top() {
        // Check if scroll to top is enabled
        if (!aqualuxe_get_option('scroll_to_top_enable', true)) {
            return;
        }
        ?>
        <button id="scroll-to-top" class="scroll-to-top" aria-label="<?php esc_attr_e('Scroll to top', 'aqualuxe'); ?>">
            <span class="screen-reader-text"><?php esc_html_e('Scroll to top', 'aqualuxe'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
        </button>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_scroll_to_top');

/**
 * Add mobile menu
 */
if (!function_exists('aqualuxe_mobile_menu')) {
    function aqualuxe_mobile_menu() {
        ?>
        <div id="mobile-menu" class="mobile-menu">
            <div class="mobile-menu-inner">
                <div class="mobile-menu-header">
                    <div class="mobile-menu-logo">
                        <?php aqualuxe_site_logo(); ?>
                    </div>
                    <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="mobile-menu-content">
                    <div class="mobile-menu-search">
                        <?php get_search_form(); ?>
                    </div>
                    <div class="mobile-menu-navigation">
                        <?php aqualuxe_mobile_navigation(); ?>
                    </div>
                    <div class="mobile-menu-actions">
                        <?php
                        // Theme toggle
                        if (aqualuxe_get_option('header_theme_toggle_enable', true)) {
                            aqualuxe_theme_toggle([
                                'container' => false,
                            ]);
                        }

                        // Account
                        if (aqualuxe_get_option('header_account_enable', true) && aqualuxe_is_woocommerce_active()) {
                            ?>
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="mobile-menu-account">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span><?php esc_html_e('Account', 'aqualuxe'); ?></span>
                            </a>
                            <?php
                        }

                        // Cart
                        if (aqualuxe_get_option('header_cart_enable', true) && aqualuxe_is_woocommerce_active()) {
                            $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                            ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mobile-menu-cart">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                <span><?php esc_html_e('Cart', 'aqualuxe'); ?> (<?php echo esc_html($cart_count); ?>)</span>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    // Contact info
                    $contact_info = aqualuxe_get_contact_info();

                    if (!empty($contact_info['phone']) || !empty($contact_info['email'])) {
                        ?>
                        <div class="mobile-menu-contact">
                            <?php
                            if (!empty($contact_info['phone'])) {
                                ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])); ?>" class="mobile-menu-phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <span><?php echo esc_html($contact_info['phone']); ?></span>
                                </a>
                                <?php
                            }

                            if (!empty($contact_info['email'])) {
                                ?>
                                <a href="mailto:<?php echo esc_attr($contact_info['email']); ?>" class="mobile-menu-email">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <span><?php echo esc_html($contact_info['email']); ?></span>
                                </a>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }

                    // Social links
                    $social_links = aqualuxe_get_social_links();

                    if (!empty($social_links)) {
                        ?>
                        <div class="mobile-menu-social">
                            <?php aqualuxe_social_links(); ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_mobile_menu');

/**
 * Add search modal
 */
if (!function_exists('aqualuxe_search_modal')) {
    function aqualuxe_search_modal() {
        // Check if search modal is enabled
        if (!aqualuxe_get_option('search_modal_enable', true)) {
            return;
        }
        ?>
        <div id="search-modal" class="search-modal">
            <div class="search-modal-inner">
                <div class="search-modal-header">
                    <h2 class="search-modal-title"><?php esc_html_e('Search', 'aqualuxe'); ?></h2>
                    <button class="search-modal-close" aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="search-modal-content">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_search_modal');

/**
 * Add cart modal
 */
if (!function_exists('aqualuxe_cart_modal')) {
    function aqualuxe_cart_modal() {
        // Check if cart modal is enabled and WooCommerce is active
        if (!aqualuxe_get_option('cart_modal_enable', true) || !aqualuxe_is_woocommerce_active()) {
            return;
        }
        ?>
        <div id="cart-modal" class="cart-modal">
            <div class="cart-modal-inner">
                <div class="cart-modal-header">
                    <h2 class="cart-modal-title"><?php esc_html_e('Your Cart', 'aqualuxe'); ?></h2>
                    <button class="cart-modal-close" aria-label="<?php esc_attr_e('Close cart', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="cart-modal-content">
                    <div class="widget_shopping_cart_content"></div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_cart_modal');

/**
 * Add quick view modal
 */
if (!function_exists('aqualuxe_quick_view_modal')) {
    function aqualuxe_quick_view_modal() {
        // Check if quick view is enabled and WooCommerce is active
        if (!aqualuxe_get_option('quick_view_enable', true) || !aqualuxe_is_woocommerce_active()) {
            return;
        }
        ?>
        <div id="quick-view-modal" class="quick-view-modal">
            <div class="quick-view-modal-inner">
                <div class="quick-view-modal-content">
                    <div class="quick-view-loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_quick_view_modal');

/**
 * Add newsletter modal
 */
if (!function_exists('aqualuxe_newsletter_modal')) {
    function aqualuxe_newsletter_modal() {
        // Check if newsletter modal is enabled
        if (!aqualuxe_get_option('newsletter_modal_enable', true)) {
            return;
        }
        ?>
        <div id="newsletter-modal" class="newsletter-modal">
            <div class="newsletter-modal-inner">
                <div class="newsletter-modal-content">
                    <button class="newsletter-modal-close" aria-label="<?php esc_attr_e('Close newsletter', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <div class="newsletter-modal-image">
                        <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/newsletter.jpg'); ?>" alt="<?php esc_attr_e('Newsletter', 'aqualuxe'); ?>">
                    </div>
                    <div class="newsletter-modal-form">
                        <h3 class="newsletter-modal-title"><?php esc_html_e('Subscribe to our newsletter', 'aqualuxe'); ?></h3>
                        <p class="newsletter-modal-description"><?php esc_html_e('Stay updated with our latest news and offers.', 'aqualuxe'); ?></p>
                        <?php aqualuxe_newsletter_form(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_newsletter_modal');

/**
 * Add cookie notice
 */
if (!function_exists('aqualuxe_cookie_notice')) {
    function aqualuxe_cookie_notice() {
        // Check if cookie notice is enabled
        if (!aqualuxe_get_option('cookie_notice_enable', true)) {
            return;
        }
        ?>
        <div id="cookie-notice" class="cookie-notice">
            <div class="container">
                <div class="cookie-notice-inner">
                    <div class="cookie-notice-content">
                        <p><?php echo wp_kses_post(aqualuxe_get_option('cookie_notice_text', __('We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'aqualuxe'))); ?></p>
                    </div>
                    <div class="cookie-notice-actions">
                        <button id="cookie-notice-accept" class="cookie-notice-accept"><?php esc_html_e('Accept', 'aqualuxe'); ?></button>
                        <?php
                        $privacy_page_id = get_option('wp_page_for_privacy_policy');
                        if ($privacy_page_id) {
                            ?>
                            <a href="<?php echo esc_url(get_permalink($privacy_page_id)); ?>" class="cookie-notice-more"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_cookie_notice');

/**
 * Add custom CSS
 */
if (!function_exists('aqualuxe_custom_css')) {
    function aqualuxe_custom_css() {
        $custom_css = aqualuxe_get_option('custom_css', '');

        if (!empty($custom_css)) {
            echo '<style type="text/css">' . wp_strip_all_tags($custom_css) . '</style>' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_custom_css');

/**
 * Add custom JavaScript
 */
if (!function_exists('aqualuxe_custom_js')) {
    function aqualuxe_custom_js() {
        $custom_js = aqualuxe_get_option('custom_js', '');

        if (!empty($custom_js)) {
            echo '<script>' . wp_strip_all_tags($custom_js) . '</script>' . "\n";
        }
    }
}
add_action('wp_footer', 'aqualuxe_custom_js');