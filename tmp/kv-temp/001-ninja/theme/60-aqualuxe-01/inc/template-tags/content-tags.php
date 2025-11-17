<?php
/**
 * AquaLuxe Content Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display post thumbnail
 *
 * @param string $size Thumbnail size
 */
function aqualuxe_post_thumbnail($size = 'post-thumbnail') {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    
    if (is_singular()) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail($size, ['class' => 'img-fluid']); ?>
        </div>
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                $size,
                [
                    'alt' => the_title_attribute(['echo' => false]),
                    'class' => 'img-fluid',
                ]
            );
            ?>
        </a>
    <?php
    endif;
}

/**
 * Display post title
 */
function aqualuxe_post_title() {
    if (is_singular()) :
        the_title('<h1 class="entry-title">', '</h1>');
    else :
        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
    endif;
}

/**
 * Display post meta
 */
function aqualuxe_post_meta() {
    if ('post' === get_post_type()) :
        ?>
        <div class="entry-meta">
            <?php
            aqualuxe_posted_on();
            aqualuxe_posted_by();
            ?>
        </div>
        <?php
    endif;
}

/**
 * Display post date
 */
function aqualuxe_posted_on() {
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
    
    echo '<span class="posted-on"><i class="far fa-calendar-alt"></i> ' . $time_string . '</span>';
}

/**
 * Display post author
 */
function aqualuxe_posted_by() {
    echo '<span class="byline"><i class="far fa-user"></i> <span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span></span>';
}

/**
 * Display post categories
 */
function aqualuxe_post_categories() {
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(', ');
        
        if ($categories_list) {
            echo '<span class="cat-links"><i class="far fa-folder-open"></i> ' . $categories_list . '</span>';
        }
    }
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    if ('post' === get_post_type()) {
        $tags_list = get_the_tag_list('', ', ');
        
        if ($tags_list) {
            echo '<span class="tags-links"><i class="fas fa-tags"></i> ' . $tags_list . '</span>';
        }
    }
}

/**
 * Display post comments link
 */
function aqualuxe_post_comments() {
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link"><i class="far fa-comment"></i> ';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                get_the_title()
            )
        );
        echo '</span>';
    }
}

/**
 * Display post edit link
 */
function aqualuxe_post_edit() {
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            get_the_title()
        ),
        '<span class="edit-link"><i class="fas fa-edit"></i> ',
        '</span>'
    );
}

/**
 * Display post content
 */
function aqualuxe_post_content() {
    the_content(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            get_the_title()
        )
    );
    
    wp_link_pages(
        [
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
            'after'  => '</div>',
        ]
    );
}

/**
 * Display post excerpt
 */
function aqualuxe_post_excerpt() {
    the_excerpt();
}

/**
 * Display post read more link
 */
function aqualuxe_post_read_more() {
    echo '<div class="read-more"><a href="' . esc_url(get_permalink()) . '" class="btn btn-primary">' . esc_html__('Read More', 'aqualuxe') . '</a></div>';
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        [
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        ]
    );
}

/**
 * Display posts pagination
 */
function aqualuxe_posts_pagination() {
    the_posts_pagination(
        [
            'mid_size'  => 2,
            'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . esc_html__('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
        ]
    );
}

/**
 * Display post author bio
 */
function aqualuxe_post_author_bio() {
    if (!is_singular('post')) {
        return;
    }
    
    if (get_the_author_meta('description')) :
        ?>
        <div class="author-bio">
            <div class="author-avatar">
                <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
            </div>
            <div class="author-info">
                <h3 class="author-title"><?php echo esc_html(get_the_author()); ?></h3>
                <div class="author-description">
                    <?php echo wpautop(get_the_author_meta('description')); ?>
                </div>
                <a class="author-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <?php printf(esc_html__('View all posts by %s', 'aqualuxe'), get_the_author()); ?>
                </a>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    if (!is_singular('post')) {
        return;
    }
    
    $current_post_id = get_the_ID();
    $categories = get_the_category($current_post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = [];
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = [
        'post_type' => 'post',
        'posts_per_page' => 3,
        'post__not_in' => [$current_post_id],
        'category__in' => $category_ids,
        'orderby' => 'rand',
    ];
    
    $related_posts = new WP_Query($args);
    
    if ($related_posts->have_posts()) :
        ?>
        <div class="related-posts">
            <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            <div class="row">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <div class="col-md-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('related-post'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="related-post-thumbnail" href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'img-fluid']); ?>
                                </a>
                            <?php endif; ?>
                            <div class="related-post-content">
                                <?php the_title('<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h4>'); ?>
                                <div class="related-post-meta">
                                    <?php aqualuxe_posted_on(); ?>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
    endif;
    
    wp_reset_postdata();
}

/**
 * Display post sharing buttons
 */
function aqualuxe_post_sharing() {
    if (!is_singular('post')) {
        return;
    }
    
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full')) : '';
    
    ?>
    <div class="post-sharing">
        <h3 class="post-sharing-title"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
        <div class="post-sharing-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="post-sharing-button post-sharing-facebook">
                <i class="fab fa-facebook-f"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="post-sharing-button post-sharing-twitter">
                <i class="fab fa-twitter"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="post-sharing-button post-sharing-linkedin">
                <i class="fab fa-linkedin-in"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $post_thumbnail; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="post-sharing-button post-sharing-pinterest">
                <i class="fab fa-pinterest-p"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
            </a>
            <a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>" class="post-sharing-button post-sharing-email">
                <i class="far fa-envelope"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Display post comments
 */
function aqualuxe_post_comments_template() {
    if (!is_singular()) {
        return;
    }
    
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Display page title
 */
function aqualuxe_page_title() {
    if (is_front_page()) {
        return;
    }
    
    ?>
    <header class="page-header">
        <?php
        if (is_archive()) {
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
        } elseif (is_search()) {
            ?>
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
            <?php
        } elseif (is_404()) {
            ?>
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            <?php
        } elseif (is_home() && !is_front_page()) {
            $blog_page_id = get_option('page_for_posts');
            ?>
            <h1 class="page-title"><?php echo esc_html(get_the_title($blog_page_id)); ?></h1>
            <?php
        }
        ?>
    </header>
    <?php
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    // Use WooCommerce breadcrumbs if WooCommerce is active and we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        woocommerce_breadcrumb();
        return;
    }
    
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'title' => __('Home', 'aqualuxe'),
        'url'   => home_url('/'),
    ];
    
    if (is_home()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => '',
        ];
    } elseif (is_category()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_cat_title('', false),
            'url'   => '',
        ];
    } elseif (is_tag()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_tag_title('', false),
            'url'   => '',
        ];
    } elseif (is_author()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_author(),
            'url'   => '',
        ];
    } elseif (is_year()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => '',
        ];
    } elseif (is_month()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url'   => '',
        ];
    } elseif (is_day()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url'   => get_month_link(get_the_date('Y'), get_the_date('m')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('j'),
            'url'   => '',
        ];
    } elseif (is_single()) {
        if (get_post_type() === 'post') {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url'   => get_permalink(get_option('page_for_posts')),
            ];
            
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url'   => get_category_link($category->term_id),
                ];
            }
        } else {
            $post_type = get_post_type_object(get_post_type());
            if ($post_type) {
                $breadcrumbs[] = [
                    'title' => $post_type->labels->name,
                    'url'   => get_post_type_archive_link(get_post_type()),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = [
                    'title' => get_the_title($ancestor),
                    'url'   => get_permalink($ancestor),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url'   => '',
        ];
    } elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('Page Not Found', 'aqualuxe'),
            'url'   => '',
        ];
    }
    
    // Build breadcrumbs HTML
    echo '<nav class="aqualuxe-breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    
    foreach ($breadcrumbs as $index => $breadcrumb) {
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        
        if (!empty($breadcrumb['url'])) {
            echo '<a href="' . esc_url($breadcrumb['url']) . '" itemprop="item">';
            echo '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
            echo '</a>';
        } else {
            echo '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
        }
        
        echo '<meta itemprop="position" content="' . esc_attr($index + 1) . '" />';
        echo '</li>';
        
        if ($index < count($breadcrumbs) - 1) {
            echo '<li class="breadcrumb-separator">/</li>';
        }
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Display page content
 */
function aqualuxe_page_content() {
    the_content();
    
    wp_link_pages(
        [
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
            'after'  => '</div>',
        ]
    );
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    get_search_form();
}

/**
 * Display social links
 */
function aqualuxe_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (!$social_links) {
        return;
    }
    
    echo '<div class="social-links">';
    
    foreach ($social_links as $key => $social) {
        echo '<a href="' . esc_url($social['url']) . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr($key) . '">';
        echo '<i class="' . esc_attr($social['icon']) . '"></i>';
        echo '<span class="screen-reader-text">' . esc_html($social['label']) . '</span>';
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Display contact info
 */
function aqualuxe_contact_info() {
    $contact_info = aqualuxe_get_contact_info();
    
    if (!$contact_info) {
        return;
    }
    
    echo '<div class="contact-info">';
    
    foreach ($contact_info as $key => $contact) {
        echo '<div class="contact-info-item contact-info-' . esc_attr($key) . '">';
        
        if (!empty($contact['url'])) {
            echo '<a href="' . esc_url($contact['url']) . '" class="contact-info-link">';
        }
        
        echo '<i class="' . esc_attr($contact['icon']) . '"></i>';
        echo '<span class="contact-info-text">' . esc_html($contact['value']) . '</span>';
        
        if (!empty($contact['url'])) {
            echo '</a>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display copyright text
 */
function aqualuxe_copyright_text() {
    echo wp_kses_post(aqualuxe_get_copyright_text());
}

/**
 * Display footer credits
 */
function aqualuxe_footer_credits() {
    echo wp_kses_post(aqualuxe_get_footer_credits());
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top() {
    echo '<a href="#" id="back-to-top" class="back-to-top" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '"><i class="fas fa-chevron-up"></i></a>';
}

/**
 * Display site logo
 */
function aqualuxe_site_logo() {
    echo aqualuxe_get_logo();
}

/**
 * Display site title
 */
function aqualuxe_site_title() {
    if (is_front_page() && is_home()) :
        ?>
        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
        <?php
    else :
        ?>
        <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
        <?php
    endif;
}

/**
 * Display site description
 */
function aqualuxe_site_description() {
    $description = get_bloginfo('description', 'display');
    
    if ($description || is_customize_preview()) :
        ?>
        <p class="site-description"><?php echo $description; ?></p>
        <?php
    endif;
}

/**
 * Display primary menu
 */
function aqualuxe_primary_menu() {
    if (has_nav_menu('primary')) :
        ?>
        <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon"><span></span></span>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            </button>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'primary-menu',
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display secondary menu
 */
function aqualuxe_secondary_menu() {
    if (has_nav_menu('secondary')) :
        ?>
        <nav id="secondary-navigation" class="secondary-navigation" aria-label="<?php esc_attr_e('Secondary Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'secondary',
                    'menu_id'        => 'secondary-menu',
                    'container'      => false,
                    'menu_class'     => 'secondary-menu',
                    'depth'          => 1,
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display footer menu
 */
function aqualuxe_footer_menu() {
    if (has_nav_menu('footer')) :
        ?>
        <nav id="footer-navigation" class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display mobile menu
 */
function aqualuxe_mobile_menu() {
    if (has_nav_menu('mobile')) :
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" aria-label="<?php esc_attr_e('Mobile Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                ]
            );
            ?>
        </nav>
        <?php
    else :
        aqualuxe_primary_menu();
    endif;
}

/**
 * Display shop menu
 */
function aqualuxe_shop_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (has_nav_menu('shop')) :
        ?>
        <nav id="shop-navigation" class="shop-navigation" aria-label="<?php esc_attr_e('Shop Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'shop',
                    'menu_id'        => 'shop-menu',
                    'container'      => false,
                    'menu_class'     => 'shop-menu',
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display wholesale menu
 */
function aqualuxe_wholesale_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (has_nav_menu('wholesale')) :
        ?>
        <nav id="wholesale-navigation" class="wholesale-navigation" aria-label="<?php esc_attr_e('Wholesale Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'wholesale',
                    'menu_id'        => 'wholesale-menu',
                    'container'      => false,
                    'menu_class'     => 'wholesale-menu',
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display account menu
 */
function aqualuxe_account_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (has_nav_menu('account')) :
        ?>
        <nav id="account-navigation" class="account-navigation" aria-label="<?php esc_attr_e('Account Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'account',
                    'menu_id'        => 'account-menu',
                    'container'      => false,
                    'menu_class'     => 'account-menu',
                ]
            );
            ?>
        </nav>
        <?php
    endif;
}

/**
 * Display mini cart
 */
function aqualuxe_mini_cart() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="mini-cart">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mini-cart-link">
            <i class="fas fa-shopping-cart"></i>
            <span class="mini-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        </a>
        <div class="mini-cart-content">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display account link
 */
function aqualuxe_account_link() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="account-link">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
            <i class="fas fa-user"></i>
            <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Display search icon
 */
function aqualuxe_search_icon() {
    ?>
    <div class="search-icon">
        <a href="#" class="search-toggle">
            <i class="fas fa-search"></i>
            <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
        </a>
        <div class="search-dropdown">
            <?php get_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    if (!function_exists('pll_the_languages')) {
        return;
    }
    
    $languages = pll_the_languages(['raw' => 1]);
    
    if (!$languages) {
        return;
    }
    
    ?>
    <div class="language-switcher">
        <div class="language-switcher-current">
            <?php foreach ($languages as $language) : ?>
                <?php if ($language['current_lang']) : ?>
                    <a href="#" class="language-toggle">
                        <?php echo esc_html($language['name']); ?>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="language-switcher-dropdown">
            <ul class="language-switcher-list">
                <?php foreach ($languages as $language) : ?>
                    <li class="language-switcher-item<?php echo $language['current_lang'] ? ' current-lang' : ''; ?>">
                        <a href="<?php echo esc_url($language['url']); ?>">
                            <?php echo esc_html($language['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    if (!aqualuxe_is_woocommerce_active() || !function_exists('wmc_get_currencies')) {
        return;
    }
    
    $currencies = wmc_get_currencies();
    
    if (!$currencies) {
        return;
    }
    
    $current_currency = wmc_get_current_currency();
    
    ?>
    <div class="currency-switcher">
        <div class="currency-switcher-current">
            <a href="#" class="currency-toggle">
                <?php echo esc_html($current_currency); ?>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
        <div class="currency-switcher-dropdown">
            <ul class="currency-switcher-list">
                <?php foreach ($currencies as $currency => $data) : ?>
                    <li class="currency-switcher-item<?php echo $currency === $current_currency ? ' current-currency' : ''; ?>">
                        <a href="<?php echo esc_url(wmc_get_currency_url($currency)); ?>">
                            <?php echo esc_html($currency); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Display color mode switcher
 */
function aqualuxe_color_mode_switcher() {
    ?>
    <div class="color-mode-switcher">
        <button class="color-mode-toggle" aria-label="<?php esc_attr_e('Toggle color mode', 'aqualuxe'); ?>">
            <i class="fas fa-sun light-icon"></i>
            <i class="fas fa-moon dark-icon"></i>
        </button>
    </div>
    <?php
}

/**
 * Display page header
 */
function aqualuxe_page_header() {
    if (is_front_page()) {
        return;
    }
    
    ?>
    <div class="page-header">
        <div class="container">
            <div class="page-header-inner">
                <?php aqualuxe_breadcrumbs(); ?>
                <?php aqualuxe_page_title(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display sidebar
 */
function aqualuxe_sidebar() {
    if (!aqualuxe_has_sidebar()) {
        return;
    }
    
    ?>
    <aside id="secondary" class="widget-area <?php echo esc_attr(aqualuxe_get_sidebar_class()); ?>">
        <?php dynamic_sidebar('sidebar-main'); ?>
    </aside>
    <?php
}

/**
 * Display shop sidebar
 */
function aqualuxe_shop_sidebar() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (!aqualuxe_has_sidebar()) {
        return;
    }
    
    ?>
    <aside id="shop-sidebar" class="widget-area <?php echo esc_attr(aqualuxe_get_sidebar_class()); ?>">
        <?php dynamic_sidebar('sidebar-shop'); ?>
    </aside>
    <?php
}

/**
 * Display footer widgets
 */
function aqualuxe_footer_widgets() {
    if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
        return;
    }
    
    ?>
    <div class="footer-widgets">
        <div class="container">
            <div class="row">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="col-md-3">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="col-md-3">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="col-md-3">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-4')) : ?>
                    <div class="col-md-3">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer bottom
 */
function aqualuxe_footer_bottom() {
    ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <?php aqualuxe_copyright_text(); ?>
                </div>
                <div class="col-md-6">
                    <?php aqualuxe_footer_credits(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display WooCommerce notices
 */
function aqualuxe_woocommerce_notices() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="woocommerce-notices-wrapper">
        <?php wc_print_notices(); ?>
    </div>
    <?php
}

/**
 * Display WooCommerce product search
 */
function aqualuxe_woocommerce_product_search() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="product-search">
        <?php get_product_search_form(); ?>
    </div>
    <?php
}

/**
 * Display WooCommerce product categories
 */
function aqualuxe_woocommerce_product_categories() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="product-categories">
        <?php
        woocommerce_product_categories([
            'hide_empty' => 1,
            'parent'     => 0,
        ]);
        ?>
    </div>
    <?php
}

/**
 * Display WooCommerce products
 *
 * @param array $args Query args
 */
function aqualuxe_woocommerce_products($args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $default_args = [
        'limit'        => 4,
        'columns'      => 4,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'category'     => '',
        'cat_operator' => 'IN',
    ];
    
    $args = wp_parse_args($args, $default_args);
    
    woocommerce_product_loop_start();
    
    $products = wc_get_products($args);
    
    if ($products) {
        foreach ($products as $product) {
            $post_object = get_post($product->get_id());
            setup_postdata($GLOBALS['post'] =& $post_object);
            
            wc_get_template_part('content', 'product');
        }
    }
    
    woocommerce_product_loop_end();
    
    wp_reset_postdata();
}

/**
 * Display WooCommerce sale products
 *
 * @param array $args Query args
 */
function aqualuxe_woocommerce_sale_products($args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $default_args = [
        'limit'        => 4,
        'columns'      => 4,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'category'     => '',
        'cat_operator' => 'IN',
    ];
    
    $args = wp_parse_args($args, $default_args);
    $args['on_sale'] = true;
    
    aqualuxe_woocommerce_products($args);
}

/**
 * Display WooCommerce best selling products
 *
 * @param array $args Query args
 */
function aqualuxe_woocommerce_best_selling_products($args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $default_args = [
        'limit'        => 4,
        'columns'      => 4,
        'category'     => '',
        'cat_operator' => 'IN',
    ];
    
    $args = wp_parse_args($args, $default_args);
    $args['orderby'] = 'popularity';
    
    aqualuxe_woocommerce_products($args);
}

/**
 * Display WooCommerce featured products
 *
 * @param array $args Query args
 */
function aqualuxe_woocommerce_featured_products($args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $default_args = [
        'limit'        => 4,
        'columns'      => 4,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'category'     => '',
        'cat_operator' => 'IN',
    ];
    
    $args = wp_parse_args($args, $default_args);
    $args['featured'] = true;
    
    aqualuxe_woocommerce_products($args);
}

/**
 * Display WooCommerce new products
 *
 * @param array $args Query args
 */
function aqualuxe_woocommerce_new_products($args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $default_args = [
        'limit'        => 4,
        'columns'      => 4,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'category'     => '',
        'cat_operator' => 'IN',
    ];
    
    $args = wp_parse_args($args, $default_args);
    
    aqualuxe_woocommerce_products($args);
}

/**
 * Display WooCommerce related products
 */
function aqualuxe_woocommerce_related_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_related_products();
}

/**
 * Display WooCommerce upsell products
 */
function aqualuxe_woocommerce_upsell_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_upsell_display();
}

/**
 * Display WooCommerce cross sell products
 */
function aqualuxe_woocommerce_cross_sell_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_cross_sell_display();
}

/**
 * Display WooCommerce product tabs
 */
function aqualuxe_woocommerce_product_tabs() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_output_product_data_tabs();
}

/**
 * Display WooCommerce product meta
 */
function aqualuxe_woocommerce_product_meta() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_meta();
}

/**
 * Display WooCommerce product sharing
 */
function aqualuxe_woocommerce_product_sharing() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_sharing();
}

/**
 * Display WooCommerce product rating
 */
function aqualuxe_woocommerce_product_rating() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_rating();
}

/**
 * Display WooCommerce product price
 */
function aqualuxe_woocommerce_product_price() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_price();
}

/**
 * Display WooCommerce product excerpt
 */
function aqualuxe_woocommerce_product_excerpt() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_excerpt();
}

/**
 * Display WooCommerce product add to cart
 */
function aqualuxe_woocommerce_product_add_to_cart() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_add_to_cart();
}

/**
 * Display WooCommerce product images
 */
function aqualuxe_woocommerce_product_images() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_show_product_images();
}

/**
 * Display WooCommerce product title
 */
function aqualuxe_woocommerce_product_title() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_template_single_title();
}

/**
 * Display WooCommerce product reviews
 */
function aqualuxe_woocommerce_product_reviews() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    comments_template();
}

/**
 * Display WooCommerce product additional information
 */
function aqualuxe_woocommerce_product_additional_information() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_product_additional_information_tab();
}

/**
 * Display WooCommerce product description
 */
function aqualuxe_woocommerce_product_description() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_product_description_tab();
}

/**
 * Display WooCommerce product attributes
 */
function aqualuxe_woocommerce_product_attributes() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    $attributes = $product->get_attributes();
    
    if (!$attributes) {
        return;
    }
    
    wc_display_product_attributes($product);
}

/**
 * Display WooCommerce product categories
 */
function aqualuxe_woocommerce_product_categories_list() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>');
}

/**
 * Display WooCommerce product tags
 */
function aqualuxe_woocommerce_product_tags() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . ' ', '</span>');
}

/**
 * Display WooCommerce product SKU
 */
function aqualuxe_woocommerce_product_sku() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
        echo '<span class="sku_wrapper">' . esc_html__('SKU:', 'aqualuxe') . ' <span class="sku">' . ($product->get_sku() ? $product->get_sku() : esc_html__('N/A', 'aqualuxe')) . '</span></span>';
    }
}

/**
 * Display WooCommerce product stock
 */
function aqualuxe_woocommerce_product_stock() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    echo wc_get_stock_html($product);
}

/**
 * Display WooCommerce product dimensions
 */
function aqualuxe_woocommerce_product_dimensions() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if ($product->has_dimensions()) {
        echo '<div class="product-dimensions">' . esc_html__('Dimensions:', 'aqualuxe') . ' ' . esc_html($product->get_dimensions()) . '</div>';
    }
}

/**
 * Display WooCommerce product weight
 */
function aqualuxe_woocommerce_product_weight() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if ($product->has_weight()) {
        echo '<div class="product-weight">' . esc_html__('Weight:', 'aqualuxe') . ' ' . esc_html($product->get_weight()) . ' ' . esc_html(get_option('woocommerce_weight_unit')) . '</div>';
    }
}

/**
 * Display WooCommerce product shipping class
 */
function aqualuxe_woocommerce_product_shipping_class() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    $shipping_class_id = $product->get_shipping_class_id();
    
    if ($shipping_class_id) {
        $shipping_class = get_term($shipping_class_id, 'product_shipping_class');
        
        if ($shipping_class && !is_wp_error($shipping_class)) {
            echo '<div class="product-shipping-class">' . esc_html__('Shipping Class:', 'aqualuxe') . ' ' . esc_html($shipping_class->name) . '</div>';
        }
    }
}

/**
 * Display WooCommerce product downloads
 */
function aqualuxe_woocommerce_product_downloads() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if ($product->is_downloadable()) {
        $downloads = $product->get_downloads();
        
        if ($downloads) {
            echo '<div class="product-downloads">';
            echo '<h3>' . esc_html__('Downloads', 'aqualuxe') . '</h3>';
            echo '<ul>';
            
            foreach ($downloads as $download) {
                echo '<li><a href="' . esc_url($download['file']) . '">' . esc_html($download['name']) . '</a></li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product variations
 */
function aqualuxe_woocommerce_product_variations() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        
        if ($variations) {
            echo '<div class="product-variations">';
            echo '<h3>' . esc_html__('Variations', 'aqualuxe') . '</h3>';
            echo '<ul>';
            
            foreach ($variations as $variation) {
                echo '<li>';
                
                foreach ($variation['attributes'] as $key => $value) {
                    $taxonomy = str_replace('attribute_', '', $key);
                    $term = get_term_by('slug', $value, $taxonomy);
                    
                    if ($term && !is_wp_error($term)) {
                        echo esc_html($term->name) . ' ';
                    } else {
                        echo esc_html($value) . ' ';
                    }
                }
                
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product attributes table
 */
function aqualuxe_woocommerce_product_attributes_table() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    $attributes = $product->get_attributes();
    
    if (!$attributes) {
        return;
    }
    
    echo '<div class="product-attributes">';
    echo '<h3>' . esc_html__('Specifications', 'aqualuxe') . '</h3>';
    echo '<table class="shop_attributes">';
    
    foreach ($attributes as $attribute) {
        echo '<tr>';
        echo '<th>' . wc_attribute_label($attribute->get_name()) . '</th>';
        echo '<td>';
        
        if ($attribute->is_taxonomy()) {
            $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
            echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
        } else {
            $values = $attribute->get_options();
            echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
        }
        
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
}

/**
 * Display WooCommerce product short description
 */
function aqualuxe_woocommerce_product_short_description() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $post;
    
    $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);
    
    if ($short_description) {
        echo '<div class="product-short-description">';
        echo $short_description;
        echo '</div>';
    }
}

/**
 * Display WooCommerce product long description
 */
function aqualuxe_woocommerce_product_long_description() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $post;
    
    $long_description = apply_filters('the_content', $post->post_content);
    
    if ($long_description) {
        echo '<div class="product-long-description">';
        echo $long_description;
        echo '</div>';
    }
}

/**
 * Display WooCommerce product gallery
 */
function aqualuxe_woocommerce_product_gallery() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    $attachment_ids = $product->get_gallery_image_ids();
    
    if ($attachment_ids) {
        echo '<div class="product-gallery">';
        echo '<h3>' . esc_html__('Product Gallery', 'aqualuxe') . '</h3>';
        echo '<div class="product-gallery-images">';
        
        foreach ($attachment_ids as $attachment_id) {
            echo '<div class="product-gallery-image">';
            echo wp_get_attachment_image($attachment_id, 'medium');
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product video
 */
function aqualuxe_woocommerce_product_video() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_video = get_post_meta(get_the_ID(), '_aqualuxe_product_video', true);
    
    if ($product_video) {
        echo '<div class="product-video">';
        echo '<h3>' . esc_html__('Product Video', 'aqualuxe') . '</h3>';
        echo wp_oembed_get($product_video);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product 360 view
 */
function aqualuxe_woocommerce_product_360_view() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_360_view = get_post_meta(get_the_ID(), '_aqualuxe_product_360_view', true);
    
    if ($product_360_view) {
        $images = explode(',', $product_360_view);
        
        if ($images) {
            echo '<div class="product-360-view">';
            echo '<h3>' . esc_html__('360° View', 'aqualuxe') . '</h3>';
            echo '<div class="product-360-view-container">';
            
            foreach ($images as $image_id) {
                echo '<div class="product-360-view-image">';
                echo wp_get_attachment_image($image_id, 'medium');
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product virtual tour
 */
function aqualuxe_woocommerce_product_virtual_tour() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_virtual_tour = get_post_meta(get_the_ID(), '_aqualuxe_product_virtual_tour', true);
    
    if ($product_virtual_tour) {
        echo '<div class="product-virtual-tour">';
        echo '<h3>' . esc_html__('Virtual Tour', 'aqualuxe') . '</h3>';
        echo wp_oembed_get($product_virtual_tour);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product documents
 */
function aqualuxe_woocommerce_product_documents() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_documents = get_post_meta(get_the_ID(), '_aqualuxe_product_documents', true);
    
    if ($product_documents) {
        $documents = explode(',', $product_documents);
        
        if ($documents) {
            echo '<div class="product-documents">';
            echo '<h3>' . esc_html__('Documents', 'aqualuxe') . '</h3>';
            echo '<ul>';
            
            foreach ($documents as $document_id) {
                $document = get_post($document_id);
                
                if ($document) {
                    echo '<li>';
                    echo '<a href="' . esc_url(wp_get_attachment_url($document_id)) . '">';
                    echo esc_html($document->post_title);
                    echo '</a>';
                    echo '</li>';
                }
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product FAQ
 */
function aqualuxe_woocommerce_product_faq() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_faq = get_post_meta(get_the_ID(), '_aqualuxe_product_faq', true);
    
    if ($product_faq) {
        echo '<div class="product-faq">';
        echo '<h3>' . esc_html__('Frequently Asked Questions', 'aqualuxe') . '</h3>';
        echo wp_kses_post($product_faq);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product custom tab
 */
function aqualuxe_woocommerce_product_custom_tab() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_custom_tab_title = get_post_meta(get_the_ID(), '_aqualuxe_product_custom_tab_title', true);
    $product_custom_tab_content = get_post_meta(get_the_ID(), '_aqualuxe_product_custom_tab_content', true);
    
    if ($product_custom_tab_title && $product_custom_tab_content) {
        echo '<div class="product-custom-tab">';
        echo '<h3>' . esc_html($product_custom_tab_title) . '</h3>';
        echo wp_kses_post($product_custom_tab_content);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product custom fields
 */
function aqualuxe_woocommerce_product_custom_fields() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    $product_custom_fields = get_post_meta(get_the_ID(), '_aqualuxe_product_custom_fields', true);
    
    if ($product_custom_fields) {
        $fields = json_decode($product_custom_fields, true);
        
        if ($fields) {
            echo '<div class="product-custom-fields">';
            echo '<h3>' . esc_html__('Additional Information', 'aqualuxe') . '</h3>';
            echo '<table>';
            
            foreach ($fields as $field) {
                echo '<tr>';
                echo '<th>' . esc_html($field['label']) . '</th>';
                echo '<td>' . esc_html($field['value']) . '</td>';
                echo '</tr>';
            }
            
            echo '</table>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product reviews summary
 */
function aqualuxe_woocommerce_product_reviews_summary() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average = $product->get_average_rating();
    
    if ($rating_count > 0) {
        echo '<div class="product-reviews-summary">';
        echo wc_get_rating_html($average, $rating_count);
        echo '<span class="product-reviews-count">';
        printf(_n('%s review', '%s reviews', $review_count, 'aqualuxe'), $review_count);
        echo '</span>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product sale badge
 */
function aqualuxe_woocommerce_product_sale_badge() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product && $product->is_on_sale()) {
        echo '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
    }
}

/**
 * Display WooCommerce product new badge
 */
function aqualuxe_woocommerce_product_new_badge() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $newness_days = 30;
    $created = strtotime($product->get_date_created());
    
    if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
        echo '<span class="new-badge">' . esc_html__('New!', 'aqualuxe') . '</span>';
    }
}

/**
 * Display WooCommerce product featured badge
 */
function aqualuxe_woocommerce_product_featured_badge() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product && $product->is_featured()) {
        echo '<span class="featured-badge">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
}

/**
 * Display WooCommerce product out of stock badge
 */
function aqualuxe_woocommerce_product_out_of_stock_badge() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if ($product && !$product->is_in_stock()) {
        echo '<span class="out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
}

/**
 * Display WooCommerce product badges
 */
function aqualuxe_woocommerce_product_badges() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div class="product-badges">';
    aqualuxe_woocommerce_product_sale_badge();
    aqualuxe_woocommerce_product_new_badge();
    aqualuxe_woocommerce_product_featured_badge();
    aqualuxe_woocommerce_product_out_of_stock_badge();
    echo '</div>';
}

/**
 * Display WooCommerce product quick view button
 */
function aqualuxe_woocommerce_product_quick_view_button() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-quick-view">';
    echo '<button class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<i class="fas fa-eye"></i>';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Display WooCommerce product wishlist button
 */
function aqualuxe_woocommerce_product_wishlist_button() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-wishlist">';
    echo '<button class="wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<i class="far fa-heart"></i>';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Display WooCommerce product compare button
 */
function aqualuxe_woocommerce_product_compare_button() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-compare">';
    echo '<button class="compare-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<i class="fas fa-exchange-alt"></i>';
    echo '<span class="screen-reader-text">' . esc_html__('Compare', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Display WooCommerce product action buttons
 */
function aqualuxe_woocommerce_product_action_buttons() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div class="product-action-buttons">';
    aqualuxe_woocommerce_product_quick_view_button();
    aqualuxe_woocommerce_product_wishlist_button();
    aqualuxe_woocommerce_product_compare_button();
    echo '</div>';
}

/**
 * Display WooCommerce product countdown
 */
function aqualuxe_woocommerce_product_countdown() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $sale_end_date = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
    
    if ($sale_end_date) {
        $sale_end_date = date('Y-m-d H:i:s', $sale_end_date);
        
        echo '<div class="product-countdown" data-end-date="' . esc_attr($sale_end_date) . '">';
        echo '<div class="countdown-label">' . esc_html__('Sale Ends In:', 'aqualuxe') . '</div>';
        echo '<div class="countdown-timer">';
        echo '<div class="countdown-days"><span class="countdown-value">00</span><span class="countdown-label">' . esc_html__('Days', 'aqualuxe') . '</span></div>';
        echo '<div class="countdown-hours"><span class="countdown-value">00</span><span class="countdown-label">' . esc_html__('Hours', 'aqualuxe') . '</span></div>';
        echo '<div class="countdown-minutes"><span class="countdown-value">00</span><span class="countdown-label">' . esc_html__('Minutes', 'aqualuxe') . '</span></div>';
        echo '<div class="countdown-seconds"><span class="countdown-value">00</span><span class="countdown-label">' . esc_html__('Seconds', 'aqualuxe') . '</span></div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product stock progress bar
 */
function aqualuxe_woocommerce_product_stock_progress_bar() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    if ($product->managing_stock()) {
        $total_stock = get_post_meta($product->get_id(), '_stock', true);
        $stock_quantity = $product->get_stock_quantity();
        
        if ($total_stock > 0) {
            $percentage = round(($stock_quantity / $total_stock) * 100);
            
            echo '<div class="product-stock-progress">';
            echo '<div class="product-stock-progress-label">';
            echo esc_html__('Available:', 'aqualuxe') . ' ' . $stock_quantity . '/' . $total_stock;
            echo '</div>';
            echo '<div class="product-stock-progress-bar">';
            echo '<div class="product-stock-progress-bar-inner" style="width: ' . esc_attr($percentage) . '%"></div>';
            echo '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display WooCommerce product sold count
 */
function aqualuxe_woocommerce_product_sold_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $sold_count = get_post_meta($product->get_id(), 'total_sales', true);
    
    if ($sold_count) {
        echo '<div class="product-sold-count">';
        echo esc_html__('Sold:', 'aqualuxe') . ' ' . $sold_count;
        echo '</div>';
    }
}

/**
 * Display WooCommerce product viewed count
 */
function aqualuxe_woocommerce_product_viewed_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $viewed_count = get_post_meta($product->get_id(), '_aqualuxe_product_viewed_count', true);
    
    if ($viewed_count) {
        echo '<div class="product-viewed-count">';
        echo esc_html__('Viewed:', 'aqualuxe') . ' ' . $viewed_count;
        echo '</div>';
    }
}

/**
 * Display WooCommerce product stats
 */
function aqualuxe_woocommerce_product_stats() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div class="product-stats">';
    aqualuxe_woocommerce_product_sold_count();
    aqualuxe_woocommerce_product_viewed_count();
    echo '</div>';
}

/**
 * Display WooCommerce product brand
 */
function aqualuxe_woocommerce_product_brand() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        echo '<div class="product-brand">';
        echo '<span class="product-brand-label">' . esc_html__('Brand:', 'aqualuxe') . '</span> ';
        
        $brand_links = [];
        
        foreach ($brands as $brand) {
            $brand_links[] = '<a href="' . esc_url(get_term_link($brand)) . '">' . esc_html($brand->name) . '</a>';
        }
        
        echo implode(', ', $brand_links);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product vendor
 */
function aqualuxe_woocommerce_product_vendor() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $vendor_id = get_post_field('post_author', $product->get_id());
    $vendor = get_user_by('id', $vendor_id);
    
    if ($vendor) {
        echo '<div class="product-vendor">';
        echo '<span class="product-vendor-label">' . esc_html__('Vendor:', 'aqualuxe') . '</span> ';
        echo '<a href="' . esc_url(get_author_posts_url($vendor_id)) . '">' . esc_html($vendor->display_name) . '</a>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product delivery info
 */
function aqualuxe_woocommerce_product_delivery_info() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $delivery_info = get_post_meta($product->get_id(), '_aqualuxe_product_delivery_info', true);
    
    if ($delivery_info) {
        echo '<div class="product-delivery-info">';
        echo wp_kses_post($delivery_info);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product warranty info
 */
function aqualuxe_woocommerce_product_warranty_info() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $warranty_info = get_post_meta($product->get_id(), '_aqualuxe_product_warranty_info', true);
    
    if ($warranty_info) {
        echo '<div class="product-warranty-info">';
        echo wp_kses_post($warranty_info);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product return info
 */
function aqualuxe_woocommerce_product_return_info() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $return_info = get_post_meta($product->get_id(), '_aqualuxe_product_return_info', true);
    
    if ($return_info) {
        echo '<div class="product-return-info">';
        echo wp_kses_post($return_info);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product shipping info
 */
function aqualuxe_woocommerce_product_shipping_info() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $shipping_info = get_post_meta($product->get_id(), '_aqualuxe_product_shipping_info', true);
    
    if ($shipping_info) {
        echo '<div class="product-shipping-info">';
        echo wp_kses_post($shipping_info);
        echo '</div>';
    }
}

/**
 * Display WooCommerce product info
 */
function aqualuxe_woocommerce_product_info() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div class="product-info">';
    aqualuxe_woocommerce_product_brand();
    aqualuxe_woocommerce_product_vendor();
    aqualuxe_woocommerce_product_delivery_info();
    aqualuxe_woocommerce_product_warranty_info();
    aqualuxe_woocommerce_product_return_info();
    aqualuxe_woocommerce_product_shipping_info();
    echo '</div>';
}

/**
 * Display WooCommerce product social sharing
 */
function aqualuxe_woocommerce_product_social_sharing() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    global $product;
    
    if (!$product) {
        return;
    }
    
    $product_url = urlencode(get_permalink());
    $product_title = urlencode(get_the_title());
    $product_image = urlencode(wp_get_attachment_url($product->get_image_id()));
    
    ?>
    <div class="product-social-sharing">
        <h3 class="product-social-sharing-title"><?php esc_html_e('Share This Product', 'aqualuxe'); ?></h3>
        <div class="product-social-sharing-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $product_url; ?>" target="_blank" rel="noopener noreferrer" class="product-social-sharing-button product-social-sharing-facebook">
                <i class="fab fa-facebook-f"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $product_url; ?>&text=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer" class="product-social-sharing-button product-social-sharing-twitter">
                <i class="fab fa-twitter"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $product_url; ?>&title=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer" class="product-social-sharing-button product-social-sharing-linkedin">
                <i class="fab fa-linkedin-in"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $product_url; ?>&media=<?php echo $product_image; ?>&description=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer" class="product-social-sharing-button product-social-sharing-pinterest">
                <i class="fab fa-pinterest-p"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
            </a>
            <a href="mailto:?subject=<?php echo $product_title; ?>&body=<?php echo $product_url; ?>" class="product-social-sharing-button product-social-sharing-email">
                <i class="far fa-envelope"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Display WooCommerce product tabs
 */
function aqualuxe_woocommerce_product_tabs() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_output_product_data_tabs();
}

/**
 * Display WooCommerce product related products
 */
function aqualuxe_woocommerce_product_related_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_output_related_products();
}

/**
 * Display WooCommerce product upsells
 */
function aqualuxe_woocommerce_product_upsells() {
    if (!aqualuxe_is_woocommerce_active() || !is_singular('product')) {
        return;
    }
    
    woocommerce_upsell_display();
}

/**
 * Display WooCommerce product cross-sells
 */
function aqualuxe_woocommerce_product_cross_sells() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_cross_sell_display();
}

/**
 * Display WooCommerce product recently viewed
 */
function aqualuxe_woocommerce_product_recently_viewed() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : [];
    $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
    
    if (empty($viewed_products)) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'post__in'       => $viewed_products,
        'orderby'        => 'post__in',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-recently-viewed">';
        echo '<h3>' . esc_html__('Recently Viewed Products', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product best sellers
 */
function aqualuxe_woocommerce_product_best_sellers() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'meta_key'       => 'total_sales',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-best-sellers">';
        echo '<h3>' . esc_html__('Best Selling Products', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product featured
 */
function aqualuxe_woocommerce_product_featured() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'tax_query'      => [
            [
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            ],
        ],
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-featured">';
        echo '<h3>' . esc_html__('Featured Products', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product on sale
 */
function aqualuxe_woocommerce_product_on_sale() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'meta_query'     => [
            [
                'key'     => '_sale_price',
                'value'   => '',
                'compare' => '!=',
            ],
        ],
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-on-sale">';
        echo '<h3>' . esc_html__('Products on Sale', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product new arrivals
 */
function aqualuxe_woocommerce_product_new_arrivals() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-new-arrivals">';
        echo '<h3>' . esc_html__('New Arrivals', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product top rated
 */
function aqualuxe_woocommerce_product_top_rated() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'posts_per_page' => 4,
        'no_found_rows'  => 1,
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'meta_key'       => '_wc_average_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
    ];
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        echo '<div class="product-top-rated">';
        echo '<h3>' . esc_html__('Top Rated Products', 'aqualuxe') . '</h3>';
        echo '<div class="products columns-4">';
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display WooCommerce product categories
 */
function aqualuxe_woocommerce_product_categories() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'taxonomy'     => 'product_cat',
        'orderby'      => 'name',
        'show_count'   => 1,
        'pad_counts'   => 1,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 1,
    ];
    
    $all_categories = get_categories($args);
    
    if ($all_categories) {
        echo '<div class="product-categories">';
        echo '<h3>' . esc_html__('Product Categories', 'aqualuxe') . '</h3>';
        echo '<ul>';
        
        foreach ($all_categories as $cat) {
            echo '<li>';
            echo '<a href="' . esc_url(get_term_link($cat->slug, 'product_cat')) . '">';
            echo esc_html($cat->name);
            echo ' <span class="count">(' . esc_html($cat->count) . ')</span>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product tags
 */
function aqualuxe_woocommerce_product_tags_list() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $args = [
        'taxonomy'     => 'product_tag',
        'orderby'      => 'name',
        'show_count'   => 1,
        'pad_counts'   => 1,
        'hierarchical' => 0,
        'title_li'     => '',
        'hide_empty'   => 1,
    ];
    
    $all_tags = get_categories($args);
    
    if ($all_tags) {
        echo '<div class="product-tags">';
        echo '<h3>' . esc_html__('Product Tags', 'aqualuxe') . '</h3>';
        echo '<div class="product-tags-list">';
        
        foreach ($all_tags as $tag) {
            echo '<a href="' . esc_url(get_term_link($tag->slug, 'product_tag')) . '" class="product-tag">';
            echo esc_html($tag->name);
            echo ' <span class="count">(' . esc_html($tag->count) . ')</span>';
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product brands
 */
function aqualuxe_woocommerce_product_brands_list() {
    if (!aqualuxe_is_woocommerce_active() || !taxonomy_exists('product_brand')) {
        return;
    }
    
    $args = [
        'taxonomy'     => 'product_brand',
        'orderby'      => 'name',
        'show_count'   => 1,
        'pad_counts'   => 1,
        'hierarchical' => 0,
        'title_li'     => '',
        'hide_empty'   => 1,
    ];
    
    $all_brands = get_categories($args);
    
    if ($all_brands) {
        echo '<div class="product-brands">';
        echo '<h3>' . esc_html__('Product Brands', 'aqualuxe') . '</h3>';
        echo '<div class="product-brands-list">';
        
        foreach ($all_brands as $brand) {
            echo '<a href="' . esc_url(get_term_link($brand->slug, 'product_brand')) . '" class="product-brand">';
            echo esc_html($brand->name);
            echo ' <span class="count">(' . esc_html($brand->count) . ')</span>';
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display WooCommerce product filter
 */
function aqualuxe_woocommerce_product_filter() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    echo '<div class="product-filter">';
    echo '<div class="product-filter-inner">';
    
    // Sort by
    woocommerce_catalog_ordering();
    
    // Results count
    woocommerce_result_count();
    
    // View switcher
    echo '<div class="product-view-switcher">';
    echo '<a href="#" class="product-view-grid active" data-view="grid"><i class="fas fa-th"></i></a>';
    echo '<a href="#" class="product-view-list" data-view="list"><i class="fas fa-list"></i></a>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display WooCommerce product filter widget
 */
function aqualuxe_woocommerce_product_filter_widget() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    echo '<div class="product-filter-widget">';
    echo '<button class="product-filter-widget-toggle">';
    echo '<i class="fas fa-filter"></i>';
    echo '<span>' . esc_html__('Filter', 'aqualuxe') . '</span>';
    echo '</button>';
    echo '<div class="product-filter-widget-content">';
    
    if (is_active_sidebar('sidebar-shop')) {
        dynamic_sidebar('sidebar-shop');
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display WooCommerce product pagination
 */
function aqualuxe_woocommerce_product_pagination() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    woocommerce_pagination();
}

/**
 * Display WooCommerce cart
 */
function aqualuxe_woocommerce_cart() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_cart();
}

/**
 * Display WooCommerce checkout
 */
function aqualuxe_woocommerce_checkout() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_checkout();
}

/**
 * Display WooCommerce account
 */
function aqualuxe_woocommerce_account() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page()) {
        return;
    }
    
    woocommerce_account_content();
}

/**
 * Display WooCommerce order tracking
 */
function aqualuxe_woocommerce_order_tracking() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    woocommerce_order_tracking();
}

/**
 * Display WooCommerce thankyou
 */
function aqualuxe_woocommerce_thankyou() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout() || !isset($_GET['key'])) {
        return;
    }
    
    woocommerce_order_details_table(wc_get_order(wc_get_order_id_by_order_key($_GET['key'])));
}

/**
 * Display WooCommerce cart totals
 */
function aqualuxe_woocommerce_cart_totals() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_cart_totals();
}

/**
 * Display WooCommerce cart coupon
 */
function aqualuxe_woocommerce_cart_coupon() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_checkout_coupon_form();
}

/**
 * Display WooCommerce cart shipping calculator
 */
function aqualuxe_woocommerce_cart_shipping_calculator() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }
    
    woocommerce_shipping_calculator();
}

/**
 * Display WooCommerce cart empty message
 */
function aqualuxe_woocommerce_cart_empty_message() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart() || !WC()->cart->is_empty()) {
        return;
    }
    
    wc_get_template('cart/cart-empty.php');
}

/**
 * Display WooCommerce cart items
 */
function aqualuxe_woocommerce_cart_items() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart() || WC()->cart->is_empty()) {
        return;
    }
    
    woocommerce_cart_table();
}

/**
 * Display WooCommerce checkout payment
 */
function aqualuxe_woocommerce_checkout_payment() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_checkout_payment();
}

/**
 * Display WooCommerce checkout form
 */
function aqualuxe_woocommerce_checkout_form() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_checkout_form();
}

/**
 * Display WooCommerce checkout login form
 */
function aqualuxe_woocommerce_checkout_login_form() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_checkout_login_form();
}

/**
 * Display WooCommerce checkout coupon form
 */
function aqualuxe_woocommerce_checkout_coupon_form() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_checkout_coupon_form();
}

/**
 * Display WooCommerce checkout order review
 */
function aqualuxe_woocommerce_checkout_order_review() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }
    
    woocommerce_order_review();
}

/**
 * Display WooCommerce account navigation
 */
function aqualuxe_woocommerce_account_navigation() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page()) {
        return;
    }
    
    woocommerce_account_navigation();
}

/**
 * Display WooCommerce account content
 */
function aqualuxe_woocommerce_account_content() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page()) {
        return;
    }
    
    woocommerce_account_content();
}

/**
 * Display WooCommerce account dashboard
 */
function aqualuxe_woocommerce_account_dashboard() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || isset($_GET['view-order'])) {
        return;
    }
    
    woocommerce_account_dashboard();
}

/**
 * Display WooCommerce account orders
 */
function aqualuxe_woocommerce_account_orders() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || !isset($_GET['view-order'])) {
        return;
    }
    
    woocommerce_account_orders(10);
}

/**
 * Display WooCommerce account downloads
 */
function aqualuxe_woocommerce_account_downloads() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || !isset($_GET['downloads'])) {
        return;
    }
    
    woocommerce_account_downloads();
}

/**
 * Display WooCommerce account addresses
 */
function aqualuxe_woocommerce_account_addresses() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || !isset($_GET['edit-address'])) {
        return;
    }
    
    woocommerce_account_edit_address();
}

/**
 * Display WooCommerce account payment methods
 */
function aqualuxe_woocommerce_account_payment_methods() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || !isset($_GET['payment-methods'])) {
        return;
    }
    
    woocommerce_account_payment_methods();
}

/**
 * Display WooCommerce account edit account
 */
function aqualuxe_woocommerce_account_edit_account() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page() || !isset($_GET['edit-account'])) {
        return;
    }
    
    woocommerce_account_edit_account();
}

/**
 * Display WooCommerce login form
 */
function aqualuxe_woocommerce_login_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    woocommerce_login_form();
}

/**
 * Display WooCommerce registration form
 */
function aqualuxe_woocommerce_registration_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    woocommerce_registration_form();
}

/**
 * Display WooCommerce reset password form
 */
function aqualuxe_woocommerce_reset_password_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    woocommerce_reset_password_form();
}

/**
 * Display WooCommerce lost password form
 */
function aqualuxe_woocommerce_lost_password_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    woocommerce_lost_password_form();
}

/**
 * Display WooCommerce product search form
 */
function aqualuxe_woocommerce_product_search_form() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    get_product_search_form();
}

/**
 * Display WooCommerce product price filter
 */
function aqualuxe_woocommerce_product_price_filter() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Price_Filter');
}

/**
 * Display WooCommerce product attribute filter
 */
function aqualuxe_woocommerce_product_attribute_filter() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Layered_Nav');
}

/**
 * Display WooCommerce product rating filter
 */
function aqualuxe_woocommerce_product_rating_filter() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Rating_Filter');
}

/**
 * Display WooCommerce product category filter
 */
function aqualuxe_woocommerce_product_category_filter() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Product_Categories');
}

/**
 * Display WooCommerce product tag filter
 */
function aqualuxe_woocommerce_product_tag_filter() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Product_Tag_Cloud');
}

/**
 * Display WooCommerce active filters
 */
function aqualuxe_woocommerce_active_filters() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Layered_Nav_Filters');
}

/**
 * Display WooCommerce products
 */
function aqualuxe_woocommerce_products() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Products');
}

/**
 * Display WooCommerce top rated products
 */
function aqualuxe_woocommerce_top_rated_products() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Top_Rated_Products');
}

/**
 * Display WooCommerce recent reviews
 */
function aqualuxe_woocommerce_recent_reviews() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Recent_Reviews');
}

/**
 * Display WooCommerce recently viewed products
 */
function aqualuxe_woocommerce_recently_viewed_products() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Recently_Viewed');
}

/**
 * Display WooCommerce cart
 */
function aqualuxe_woocommerce_cart_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Cart');
}

/**
 * Display WooCommerce product categories widget
 */
function aqualuxe_woocommerce_product_categories_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Product_Categories');
}

/**
 * Display WooCommerce product search widget
 */
function aqualuxe_woocommerce_product_search_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Product_Search');
}

/**
 * Display WooCommerce product tag cloud widget
 */
function aqualuxe_woocommerce_product_tag_cloud_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Product_Tag_Cloud');
}

/**
 * Display WooCommerce layered nav widget
 */
function aqualuxe_woocommerce_layered_nav_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Layered_Nav');
}

/**
 * Display WooCommerce layered nav filters widget
 */
function aqualuxe_woocommerce_layered_nav_filters_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Layered_Nav_Filters');
}

/**
 * Display WooCommerce price filter widget
 */
function aqualuxe_woocommerce_price_filter_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Price_Filter');
}

/**
 * Display WooCommerce rating filter widget
 */
function aqualuxe_woocommerce_rating_filter_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Rating_Filter');
}

/**
 * Display WooCommerce products widget
 */
function aqualuxe_woocommerce_products_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Products');
}

/**
 * Display WooCommerce top rated products widget
 */
function aqualuxe_woocommerce_top_rated_products_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Top_Rated_Products');
}

/**
 * Display WooCommerce recent reviews widget
 */
function aqualuxe_woocommerce_recent_reviews_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Recent_Reviews');
}

/**
 * Display WooCommerce recently viewed products widget
 */
function aqualuxe_woocommerce_recently_viewed_products_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Recently_Viewed');
}

/**
 * Display WooCommerce cart widget
 */
function aqualuxe_woocommerce_cart_widget() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    the_widget('WC_Widget_Cart');
}