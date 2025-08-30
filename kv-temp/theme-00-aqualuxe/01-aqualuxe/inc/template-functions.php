<?php

/**
 * AquaLuxe Template Functions
 *
 * Functions for the templating system.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site logo
 */
function aqualuxe_site_logo()
{
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
?>
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-title">
            <?php bloginfo('name'); ?>
        </a>
        <?php
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
        ?>
            <p class="site-description"><?php echo esc_html($description); ?></p>
    <?php
        }
    }
}

/**
 * Display primary navigation
 */
function aqualuxe_primary_navigation()
{
    ?>
    <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="menu-toggle-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
        </button>
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'container'      => false,
            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
        ));
        ?>
    </nav>
<?php
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback()
{
?>
    <ul id="primary-menu" class="menu">
        <li class="menu-item">
            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></a>
        </li>
    </ul>
    <?php
}

/**
 * Display secondary navigation
 */
function aqualuxe_secondary_navigation()
{
    if (has_nav_menu('secondary')) {
    ?>
        <nav id="secondary-navigation" class="secondary-navigation" aria-label="<?php esc_attr_e('Secondary Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'secondary',
                'menu_id'        => 'secondary-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
    <?php
    }
}

/**
 * Display footer navigation
 */
function aqualuxe_footer_navigation()
{
    if (has_nav_menu('footer')) {
    ?>
        <nav id="footer-navigation" class="footer-navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'container'      => false,
                'depth'          => 1,
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
    <?php
    }
}

/**
 * Display social navigation
 */
function aqualuxe_social_navigation()
{
    if (has_nav_menu('social')) {
    ?>
        <nav id="social-navigation" class="social-navigation" aria-label="<?php esc_attr_e('Social Links', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'social',
                'menu_id'        => 'social-menu',
                'container'      => false,
                'depth'          => 1,
                'link_before'    => '<span class="screen-reader-text">',
                'link_after'     => '</span>',
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
    <?php
    }
}

/**
 * Display page header
 */
function aqualuxe_page_header()
{
    do_action('aqualuxe_page_header');
}

/**
 * Display page title
 */
function aqualuxe_page_title()
{
    if (is_front_page()) {
        return;
    }

    ?>
    <header class="page-header">
        <?php
        if (is_home()) {
            $page_for_posts = get_option('page_for_posts');
            if ($page_for_posts) {
                echo '<h1 class="page-title">' . esc_html(get_the_title($page_for_posts)) . '</h1>';
            } else {
                echo '<h1 class="page-title">' . esc_html__('Blog', 'aqualuxe') . '</h1>';
            }
        } elseif (is_archive()) {
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
        } elseif (is_search()) {
            echo '<h1 class="page-title">';
            /* translators: %s: search query */
            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
            echo '</h1>';
        } elseif (is_404()) {
            echo '<h1 class="page-title">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';
        } elseif (is_page()) {
            echo '<h1 class="page-title">' . esc_html(get_the_title()) . '</h1>';
        } elseif (is_singular('post')) {
            echo '<h1 class="page-title">' . esc_html(get_the_title()) . '</h1>';
        }
        ?>
    </header>
<?php
}
////////////////////////////////////////////////////

/**
 * Display site logo or title
 */
function aqualuxe_site_branding()
{
?>
    <div class="site-branding">
        <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
        ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) {
            ?>
                <p class="site-description"><?php echo $description; ?></p>
        <?php
            }
        }
        ?>
    </div><!-- .site-branding -->
    <?php
}

/**
 * Display post thumbnail with fallback
 */
function aqualuxe_post_thumbnail()
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }

    if (is_singular()) {
    ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div><!-- .post-thumbnail -->
    <?php
    } else {
    ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail('post-thumbnail', array(
                'alt' => the_title_attribute(array(
                    'echo' => false,
                )),
            ));
            ?>
        </a>
    <?php
    }
}

/**
 * Display fish placeholder image
 * 
 * @param string $size Image size.
 * @param array $attr Image attributes.
 */
function aqualuxe_fish_placeholder($size = 'medium', $attr = array())
{
    $placeholder_html = '<div class="fish-placeholder">';
    $placeholder_html .= '<div class="fish-placeholder-eye"></div>';
    $placeholder_html .= '<div class="fish-placeholder-text">' . esc_html__('No Image Available', 'aqualuxe') . '</div>';
    $placeholder_html .= '</div>';

    echo $placeholder_html;
}

/**
 * Display fish thumbnail with fallback
 * 
 * @param int $post_id Post ID.
 * @param string $size Image size.
 * @param array $attr Image attributes.
 */
function aqualuxe_fish_thumbnail($post_id = null, $size = 'medium', $attr = array())
{
    if (null === $post_id) {
        $post_id = get_the_ID();
    }

    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, $size, $attr);
    } else {
        aqualuxe_fish_placeholder($size, $attr);
    }
}

/**
 * Display post meta information
 */
function aqualuxe_post_meta()
{
    ?>
    <div class="entry-meta">
        <?php
        // Posted by
        aqualuxe_posted_by();

        // Posted on
        aqualuxe_posted_on();

        // Comments link
        if (!post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        // Edit link
        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
        ?>
    </div><!-- .entry-meta -->
<?php
}

/**
 * Display posted by
 */
function aqualuxe_posted_by()
{
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display posted on
 */
function aqualuxe_posted_on()
{
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('on %s', 'post date', 'aqualuxe'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on"> ' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display entry footer
 */
function aqualuxe_entry_footer()
{
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation()
{
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        )
    );
}

/**
 * Display posts pagination
 */
function aqualuxe_posts_pagination()
{
    the_posts_pagination(
        array(
            'mid_size'  => 2,
            'prev_text' => sprintf(
                '%s <span class="nav-prev-text">%s</span>',
                '<i class="fas fa-chevron-left"></i>',
                __('Newer posts', 'aqualuxe')
            ),
            'next_text' => sprintf(
                '<span class="nav-next-text">%s</span> %s',
                __('Older posts', 'aqualuxe'),
                '<i class="fas fa-chevron-right"></i>'
            ),
        )
    );
}

/**
 * Display breadcrumbs
 */
// function aqualuxe_breadcrumbs() {
//     do_action('aqualuxe_breadcrumbs');
// }
function aqualuxe_breadcrumbs()
{
    // Check if Yoast SEO or Rank Math breadcrumbs are available
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
        return;
    } elseif (function_exists('rank_math_the_breadcrumbs')) {
        rank_math_the_breadcrumbs();
        return;
    }

    // Custom breadcrumbs implementation
    if (is_front_page()) {
        return;
    }

    echo '<div class="breadcrumbs">';
    echo '<span class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></span>';

    if (is_category() || is_single()) {
        if (is_single()) {
            if (get_post_type() === 'post') {
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<span class="breadcrumb-separator">/</span>';
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></span>';
                }
            } elseif (get_post_type() === 'product') {
                $terms = get_the_terms(get_the_ID(), 'product_cat');
                if (!empty($terms)) {
                    echo '<span class="breadcrumb-separator">/</span>';
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_term_link($terms[0])) . '">' . esc_html($terms[0]->name) . '</a></span>';
                }
            } elseif (get_post_type() === 'fish_species') {
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('fish_species')) . '">' . esc_html__('Fish Species', 'aqualuxe') . '</a></span>';

                $terms = get_the_terms(get_the_ID(), 'fish_category');
                if (!empty($terms)) {
                    echo '<span class="breadcrumb-separator">/</span>';
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_term_link($terms[0])) . '">' . esc_html($terms[0]->name) . '</a></span>';
                }
            }
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
        } elseif (is_category()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . single_cat_title('', false) . '</span>';
        }
    } elseif (is_page()) {
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a></span>';
            }
        }
        echo '<span class="breadcrumb-separator">/</span>';
        echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
    } elseif (is_tag()) {
        echo '<span class="breadcrumb-separator">/</span>';
        echo '<span class="breadcrumb-item current">' . single_tag_title('', false) . '</span>';
    } elseif (is_author()) {
        echo '<span class="breadcrumb-separator">/</span>';
        echo '<span class="breadcrumb-item current">' . get_the_author() . '</span>';
    } elseif (is_search()) {
        echo '<span class="breadcrumb-separator">/</span>';
        echo '<span class="breadcrumb-item current">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    } elseif (is_404()) {
        echo '<span class="breadcrumb-separator">/</span>';
        echo '<span class="breadcrumb-item current">' . esc_html__('404 Not Found', 'aqualuxe') . '</span>';
    } elseif (is_archive()) {
        if (is_post_type_archive('fish_species')) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Fish Species', 'aqualuxe') . '</span>';
        } elseif (is_tax('fish_category')) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('fish_species')) . '">' . esc_html__('Fish Species', 'aqualuxe') . '</a></span>';
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . single_term_title('', false) . '</span>';
        } elseif (is_tax('care_level')) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('fish_species')) . '">' . esc_html__('Fish Species', 'aqualuxe') . '</a></span>';
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Care Level:', 'aqualuxe') . ' ' . single_term_title('', false) . '</span>';
        } else {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_archive_title() . '</span>';
        }
    }

    echo '</div>';
}

/**
 * Get fish specifications
 * 
 * @param int $post_id Post ID.
 * @return array Fish specifications.
 */
function aqualuxe_get_fish_specs($post_id = null)
{
    if (null === $post_id) {
        $post_id = get_the_ID();
    }

    return array(
        'scientific_name' => get_post_meta($post_id, '_scientific_name', true),
        'adult_size' => get_post_meta($post_id, '_adult_size', true),
        'lifespan' => get_post_meta($post_id, '_lifespan', true),
        'min_tank_size' => get_post_meta($post_id, '_min_tank_size', true),
        'temperature_min' => get_post_meta($post_id, '_temperature_min', true),
        'temperature_max' => get_post_meta($post_id, '_temperature_max', true),
        'ph_min' => get_post_meta($post_id, '_ph_min', true),
        'ph_max' => get_post_meta($post_id, '_ph_max', true),
        'hardness_min' => get_post_meta($post_id, '_hardness_min', true),
        'hardness_max' => get_post_meta($post_id, '_hardness_max', true),
        'diet' => get_post_meta($post_id, '_diet', true),
        'temperament' => get_post_meta($post_id, '_temperament', true),
        'swimming_level' => get_post_meta($post_id, '_swimming_level', true),
        'breeding_difficulty' => get_post_meta($post_id, '_breeding_difficulty', true),
    );
}

/**
 * Lazy load images
 * 
 * Modifies image HTML to support lazy loading
 * 
 * @param string $html The HTML image tag
 * @param int $id The attachment ID
 * @param string $alt The image alt text
 * @param string $title The image title
 * @return string Modified HTML image tag with lazy loading
 */
function aqualuxe_lazy_load_images($html, $id, $alt, $title)
{
    // Skip lazy loading for admin, feed, or if the image is already lazy loaded
    if (is_admin() || is_feed() || strpos($html, 'data-src') !== false) {
        return $html;
    }

    // Don't lazy load images in the admin bar
    if (strpos($html, 'admin-bar') !== false) {
        return $html;
    }

    // Get image source
    preg_match('/src="([^"]+)"/', $html, $src_matches);

    // If no src found, return original HTML
    if (!isset($src_matches[1])) {
        return $html;
    }

    $src = $src_matches[1];

    // Create placeholder image (1x1 transparent GIF)
    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    // Replace src with placeholder and add data-src
    $html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $html);

    // Add lazy class
    $html = str_replace('<img', '<img class="lazy"', $html);

    // Handle srcset if present
    if (strpos($html, 'srcset') !== false) {
        preg_match('/srcset="([^"]+)"/', $html, $srcset_matches);

        if (isset($srcset_matches[1])) {
            $srcset = $srcset_matches[1];
            $html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $html);
        }
    }

    // Add noscript fallback
    $noscript = '<noscript>' . str_replace('class="lazy"', '', str_replace('src="' . $placeholder . '" data-src="', 'src="', $html)) . '</noscript>';

    return $html . $noscript;
}
add_filter('get_image_tag', 'aqualuxe_lazy_load_images', 10, 4);

/**
 * Lazy load post thumbnails
 * 
 * Adds lazy loading to post thumbnails
 * 
 * @param string $html The post thumbnail HTML
 * @return string Modified HTML with lazy loading
 */
function aqualuxe_lazy_load_post_thumbnails($html)
{
    // Skip lazy loading for admin or feed
    if (is_admin() || is_feed()) {
        return $html;
    }

    // If already lazy loaded, return original HTML
    if (strpos($html, 'data-src') !== false) {
        return $html;
    }

    // Get image source
    preg_match('/src="([^"]+)"/', $html, $src_matches);

    // If no src found, return original HTML
    if (!isset($src_matches[1])) {
        return $html;
    }

    $src = $src_matches[1];

    // Create placeholder image (1x1 transparent GIF)
    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    // Replace src with placeholder and add data-src
    $html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $html);

    // Add lazy class
    if (strpos($html, 'class="') !== false) {
        $html = str_replace('class="', 'class="lazy ', $html);
    } else {
        $html = str_replace('<img', '<img class="lazy"', $html);
    }

    // Handle srcset if present
    if (strpos($html, 'srcset') !== false) {
        preg_match('/srcset="([^"]+)"/', $html, $srcset_matches);

        if (isset($srcset_matches[1])) {
            $srcset = $srcset_matches[1];
            $html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $html);
        }
    }

    // Add noscript fallback
    $noscript = '<noscript>' . str_replace(array('class="lazy ', 'class="lazy"'), array('class="', ''), str_replace('src="' . $placeholder . '" data-src="', 'src="', $html)) . '</noscript>';

    return $html . $noscript;
}
add_filter('post_thumbnail_html', 'aqualuxe_lazy_load_post_thumbnails', 10, 1);

/**
 * Lazy load content images
 * 
 * Adds lazy loading to images in post content
 * 
 * @param string $content The post content
 * @return string Modified content with lazy loaded images
 */
function aqualuxe_lazy_load_content_images($content)
{
    // Skip lazy loading for admin or feed
    if (is_admin() || is_feed()) {
        return $content;
    }

    // If no image in content, return original content
    if (strpos($content, '<img') === false) {
        return $content;
    }

    // Regular expression to find all images in content
    $pattern = '/<img([^>]+)>/i';

    // Replace each image with lazy loading version
    $content = preg_replace_callback($pattern, function ($matches) {
        $image_html = $matches[0];

        // If already lazy loaded, return original HTML
        if (strpos($image_html, 'data-src') !== false) {
            return $image_html;
        }

        // Get image source
        preg_match('/src="([^"]+)"/', $image_html, $src_matches);

        // If no src found, return original HTML
        if (!isset($src_matches[1])) {
            return $image_html;
        }

        $src = $src_matches[1];

        // Create placeholder image (1x1 transparent GIF)
        $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

        // Replace src with placeholder and add data-src
        $image_html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $image_html);

        // Add lazy class
        if (strpos($image_html, 'class="') !== false) {
            $image_html = str_replace('class="', 'class="lazy ', $image_html);
        } else {
            $image_html = str_replace('<img', '<img class="lazy"', $image_html);
        }

        // Handle srcset if present
        if (strpos($image_html, 'srcset') !== false) {
            preg_match('/srcset="([^"]+)"/', $image_html, $srcset_matches);

            if (isset($srcset_matches[1])) {
                $srcset = $srcset_matches[1];
                $image_html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $image_html);
            }
        }

        // Add noscript fallback
        $noscript = '<noscript>' . str_replace(array('class="lazy ', 'class="lazy"'), array('class="', ''), str_replace('src="' . $placeholder . '" data-src="', 'src="', $image_html)) . '</noscript>';

        return $image_html . $noscript;
    }, $content);

    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_load_content_images', 99);
