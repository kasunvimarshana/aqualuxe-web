<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add body classes
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_body_classes($classes) {
    // Add theme body classes
    $theme_classes = explode(' ', aqualuxe_get_body_class());
    $classes = array_merge($classes, $theme_classes);
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add page title wrapper
 */
function aqualuxe_page_title_wrapper() {
    // Don't show page title on front page
    if (is_front_page()) {
        return;
    }
    
    // Don't show page title if disabled
    if (is_singular() && get_post_meta(get_the_ID(), '_aqualuxe_disable_page_title', true)) {
        return;
    }
    
    // Get page title
    $title = aqualuxe_get_page_title();
    
    // Get page subtitle
    $subtitle = '';
    if (is_singular()) {
        $subtitle = get_post_meta(get_the_ID(), '_aqualuxe_page_subtitle', true);
    }
    
    // Show breadcrumbs
    $show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);
    
    // Get page title layout
    $layout = get_theme_mod('aqualuxe_page_title_layout', 'default');
    
    // Get page title alignment
    $alignment = get_theme_mod('aqualuxe_page_title_alignment', 'center');
    
    // Get container class
    $container_class = aqualuxe_get_container_class();
    
    // Page title classes
    $classes = [
        'aqualuxe-page-title',
        'aqualuxe-page-title-' . $layout,
        'aqualuxe-page-title-align-' . $alignment,
    ];
    
    // Get background image
    $bg_image = '';
    if (is_singular()) {
        $bg_image_id = get_post_meta(get_the_ID(), '_aqualuxe_page_title_bg', true);
        if ($bg_image_id) {
            $bg_image = wp_get_attachment_image_url($bg_image_id, 'full');
        }
    }
    
    if ($bg_image) {
        $classes[] = 'aqualuxe-page-title-bg';
        $bg_style = 'style="background-image: url(' . esc_url($bg_image) . ');"';
    } else {
        $bg_style = '';
    }
    
    // Output page title
    ?>
    <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo $bg_style; ?>>
        <div class="<?php echo esc_attr($container_class); ?>">
            <div class="aqualuxe-page-title-inner">
                <?php if ($show_breadcrumbs) : ?>
                    <div class="aqualuxe-breadcrumbs-wrapper">
                        <?php echo aqualuxe_get_breadcrumbs(); ?>
                    </div>
                <?php endif; ?>
                
                <h1 class="page-title"><?php echo wp_kses_post($title); ?></h1>
                
                <?php if ($subtitle) : ?>
                    <div class="page-subtitle"><?php echo wp_kses_post($subtitle); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_before_main_content', 'aqualuxe_page_title_wrapper');

/**
 * Add schema markup to HTML tag
 *
 * @param array $attributes HTML tag attributes
 * @return array Modified HTML tag attributes
 */
function aqualuxe_add_schema_markup($attributes) {
    $attributes['itemscope'] = '';
    $attributes['itemtype'] = 'http://schema.org/WebSite';
    
    return $attributes;
}
add_filter('aqualuxe_html_attributes', 'aqualuxe_add_schema_markup');

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_open_graph_tags() {
    global $post;
    
    if (!is_singular()) {
        return;
    }
    
    $og_title = get_the_title();
    $og_description = get_the_excerpt();
    $og_url = get_permalink();
    $og_type = is_single() ? 'article' : 'website';
    
    $og_image = '';
    if (has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_tags');

/**
 * Add schema.org markup
 */
function aqualuxe_add_schema_org_markup() {
    // Organization schema
    $schema = [
        '@context' => 'http://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    ];
    
    // Add logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($logo) {
            $schema['logo'] = $logo[0];
        }
    }
    
    // Add social profiles
    $social_links = aqualuxe_get_social_links();
    if ($social_links) {
        $schema['sameAs'] = [];
        
        foreach ($social_links as $social) {
            $schema['sameAs'][] = $social['url'];
        }
    }
    
    // Add contact info
    $contact_info = aqualuxe_get_contact_info();
    if (isset($contact_info['phone'])) {
        $schema['telephone'] = $contact_info['phone']['value'];
    }
    
    if (isset($contact_info['email'])) {
        $schema['email'] = $contact_info['email']['value'];
    }
    
    if (isset($contact_info['address'])) {
        $schema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $contact_info['address']['value'],
        ];
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    
    // WebSite schema
    $website_schema = [
        '@context' => 'http://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ],
    ];
    
    echo '<script type="application/ld+json">' . wp_json_encode($website_schema) . '</script>' . "\n";
    
    // BreadcrumbList schema
    if (!is_front_page()) {
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'title' => __('Home', 'aqualuxe'),
            'url' => home_url('/'),
        ];
        
        if (is_home()) {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url' => '',
            ];
        } elseif (is_category()) {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            $breadcrumbs[] = [
                'title' => single_cat_title('', false),
                'url' => '',
            ];
        } elseif (is_tag()) {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            $breadcrumbs[] = [
                'title' => single_tag_title('', false),
                'url' => '',
            ];
        } elseif (is_author()) {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            $breadcrumbs[] = [
                'title' => get_the_author(),
                'url' => '',
            ];
        } elseif (is_single()) {
            if (get_post_type() === 'post') {
                $breadcrumbs[] = [
                    'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                    'url' => get_permalink(get_option('page_for_posts')),
                ];
                
                $categories = get_the_category();
                if ($categories) {
                    $category = $categories[0];
                    $breadcrumbs[] = [
                        'title' => $category->name,
                        'url' => get_category_link($category->term_id),
                    ];
                }
            } else {
                $post_type = get_post_type_object(get_post_type());
                if ($post_type) {
                    $breadcrumbs[] = [
                        'title' => $post_type->labels->name,
                        'url' => get_post_type_archive_link(get_post_type()),
                    ];
                }
            }
            
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => '',
            ];
        } elseif (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            if ($ancestors) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    $breadcrumbs[] = [
                        'title' => get_the_title($ancestor),
                        'url' => get_permalink($ancestor),
                    ];
                }
            }
            
            $breadcrumbs[] = [
                'title' => get_the_title(),
                'url' => '',
            ];
        } elseif (is_search()) {
            $breadcrumbs[] = [
                'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
                'url' => '',
            ];
        } elseif (is_404()) {
            $breadcrumbs[] = [
                'title' => __('Page Not Found', 'aqualuxe'),
                'url' => '',
            ];
        }
        
        $breadcrumb_schema = [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $breadcrumb_schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@id' => !empty($breadcrumb['url']) ? $breadcrumb['url'] : get_permalink(),
                    'name' => $breadcrumb['title'],
                ],
            ];
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema) . '</script>' . "\n";
    }
    
    // Article schema for single posts
    if (is_single() && get_post_type() === 'post') {
        global $post;
        
        $article_schema = [
            '@context' => 'http://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ],
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
            ],
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                $article_schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
                
                $article_schema['publisher']['logo'] = [
                    '@type' => 'ImageObject',
                    'url' => $image[0],
                    'width' => $image[1],
                    'height' => $image[2],
                ];
            }
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($article_schema) . '</script>' . "\n";
    }
}
add_action('wp_footer', 'aqualuxe_add_schema_org_markup');

/**
 * Add pingback URL
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">';
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add mobile viewport meta tag
 */
function aqualuxe_mobile_viewport() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
}
add_action('wp_head', 'aqualuxe_mobile_viewport', 0);

/**
 * Add theme color meta tag
 */
function aqualuxe_theme_color() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
    echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">';
}
add_action('wp_head', 'aqualuxe_theme_color');

/**
 * Add preconnect for Google Fonts
 */
function aqualuxe_preconnect_google_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'aqualuxe_preconnect_google_fonts', 1);

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array Modified image sizes
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, [
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-square' => __('Square', 'aqualuxe'),
        'aqualuxe-portrait' => __('Portrait', 'aqualuxe'),
        'aqualuxe-gallery' => __('Gallery', 'aqualuxe'),
    ]);
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add lazy loading to images
 *
 * @param string $content Post content
 * @return string Modified post content
 */
function aqualuxe_add_lazy_loading($content) {
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // Add loading="lazy" to img tags
    $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_add_lazy_loading');

/**
 * Add responsive class to embeds
 *
 * @param string $html Embed HTML
 * @return string Modified embed HTML
 */
function aqualuxe_responsive_embeds($html) {
    return '<div class="aqualuxe-responsive-embed">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_responsive_embeds', 10, 1);

/**
 * Add excerpt length filter
 *
 * @param int $length Excerpt length
 * @return int Modified excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return get_theme_mod('aqualuxe_excerpt_length', 55);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Add excerpt more filter
 *
 * @param string $more Excerpt more
 * @return string Modified excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom excerpt
 *
 * @param string $text Excerpt text
 * @param int    $length Excerpt length
 * @return string Modified excerpt text
 */
function aqualuxe_custom_excerpt($text, $length = 55) {
    if ($length < 1) {
        return '';
    }
    
    $text = strip_shortcodes($text);
    $text = excerpt_remove_blocks($text);
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    
    $excerpt_length = apply_filters('excerpt_length', $length);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[&hellip;]');
    
    return wp_trim_words($text, $excerpt_length, $excerpt_more);
}

/**
 * Add post navigation
 */
function aqualuxe_post_navigation() {
    if (!is_singular('post')) {
        return;
    }
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    ?>
    <nav class="aqualuxe-post-navigation" aria-label="<?php esc_attr_e('Post Navigation', 'aqualuxe'); ?>">
        <div class="aqualuxe-post-navigation-inner">
            <?php if ($prev_post) : ?>
                <div class="aqualuxe-post-navigation-prev">
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev">
                        <span class="aqualuxe-post-navigation-label"><?php echo esc_html__('Previous Post', 'aqualuxe'); ?></span>
                        <span class="aqualuxe-post-navigation-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post) : ?>
                <div class="aqualuxe-post-navigation-next">
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next">
                        <span class="aqualuxe-post-navigation-label"><?php echo esc_html__('Next Post', 'aqualuxe'); ?></span>
                        <span class="aqualuxe-post-navigation-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}
add_action('aqualuxe_after_single_content', 'aqualuxe_post_navigation');

/**
 * Add related posts
 */
function aqualuxe_related_posts() {
    if (!is_singular('post')) {
        return;
    }
    
    $show_related = get_theme_mod('aqualuxe_show_related_posts', true);
    
    if (!$show_related) {
        return;
    }
    
    $related_count = get_theme_mod('aqualuxe_related_posts_count', 3);
    $related_title = get_theme_mod('aqualuxe_related_posts_title', __('Related Posts', 'aqualuxe'));
    
    // Get current post categories
    $categories = get_the_category();
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = [];
    
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    // Query related posts
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $related_count,
        'post__not_in' => [get_the_ID()],
        'category__in' => $category_ids,
        'orderby' => 'rand',
    ];
    
    $related_query = new WP_Query($args);
    
    if (!$related_query->have_posts()) {
        return;
    }
    
    ?>
    <div class="aqualuxe-related-posts">
        <h3 class="aqualuxe-related-posts-title"><?php echo esc_html($related_title); ?></h3>
        
        <div class="aqualuxe-related-posts-grid">
            <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                <div class="aqualuxe-related-post">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-related-post-article'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aqualuxe-related-post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('aqualuxe-square'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="aqualuxe-related-post-content">
                            <h4 class="aqualuxe-related-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            
                            <div class="aqualuxe-related-post-meta">
                                <?php echo get_the_date(); ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    
    wp_reset_postdata();
}
add_action('aqualuxe_after_single_content', 'aqualuxe_related_posts', 20);

/**
 * Add author box
 */
function aqualuxe_author_box() {
    if (!is_singular('post')) {
        return;
    }
    
    $show_author_box = get_theme_mod('aqualuxe_show_author_box', true);
    
    if (!$show_author_box) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_description = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 96);
    
    if (!$author_description) {
        return;
    }
    
    ?>
    <div class="aqualuxe-author-box">
        <div class="aqualuxe-author-box-inner">
            <div class="aqualuxe-author-box-avatar">
                <?php echo $author_avatar; ?>
            </div>
            
            <div class="aqualuxe-author-box-content">
                <h4 class="aqualuxe-author-box-title">
                    <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
                </h4>
                
                <div class="aqualuxe-author-box-description">
                    <?php echo wpautop($author_description); ?>
                </div>
                
                <div class="aqualuxe-author-box-link">
                    <a href="<?php echo esc_url($author_url); ?>"><?php printf(__('View all posts by %s', 'aqualuxe'), $author_name); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_after_single_content', 'aqualuxe_author_box', 10);

/**
 * Add comments template
 */
function aqualuxe_comments_template() {
    if (!is_singular()) {
        return;
    }
    
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}
add_action('aqualuxe_after_single_content', 'aqualuxe_comments_template', 30);

/**
 * Add post thumbnail
 */
function aqualuxe_post_thumbnail() {
    if (!has_post_thumbnail()) {
        return;
    }
    
    $thumbnail_size = 'aqualuxe-featured';
    
    if (is_singular()) {
        $thumbnail_size = 'full';
    }
    
    ?>
    <div class="aqualuxe-post-thumbnail">
        <?php if (is_singular()) : ?>
            <?php the_post_thumbnail($thumbnail_size, ['class' => 'img-fluid']); ?>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail($thumbnail_size, ['class' => 'img-fluid']); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php
}
add_action('aqualuxe_before_post_content', 'aqualuxe_post_thumbnail');

/**
 * Add post meta
 */
function aqualuxe_post_meta() {
    if (!is_singular('post') && !is_home() && !is_archive() && !is_search()) {
        return;
    }
    
    $show_date = get_theme_mod('aqualuxe_show_post_date', true);
    $show_author = get_theme_mod('aqualuxe_show_post_author', true);
    $show_categories = get_theme_mod('aqualuxe_show_post_categories', true);
    $show_comments = get_theme_mod('aqualuxe_show_post_comments', true);
    
    if (!$show_date && !$show_author && !$show_categories && !$show_comments) {
        return;
    }
    
    ?>
    <div class="aqualuxe-post-meta">
        <?php if ($show_date) : ?>
            <span class="aqualuxe-post-date">
                <i class="far fa-calendar-alt"></i>
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
            </span>
        <?php endif; ?>
        
        <?php if ($show_author) : ?>
            <span class="aqualuxe-post-author">
                <i class="far fa-user"></i>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
            </span>
        <?php endif; ?>
        
        <?php if ($show_categories && has_category()) : ?>
            <span class="aqualuxe-post-categories">
                <i class="far fa-folder-open"></i>
                <?php the_category(', '); ?>
            </span>
        <?php endif; ?>
        
        <?php if ($show_comments && comments_open()) : ?>
            <span class="aqualuxe-post-comments">
                <i class="far fa-comment"></i>
                <?php comments_popup_link(
                    __('No Comments', 'aqualuxe'),
                    __('1 Comment', 'aqualuxe'),
                    __('% Comments', 'aqualuxe'),
                    'comments-link',
                    __('Comments Closed', 'aqualuxe')
                ); ?>
            </span>
        <?php endif; ?>
    </div>
    <?php
}
add_action('aqualuxe_before_post_content', 'aqualuxe_post_meta', 20);

/**
 * Add post tags
 */
function aqualuxe_post_tags() {
    if (!is_singular('post')) {
        return;
    }
    
    $show_tags = get_theme_mod('aqualuxe_show_post_tags', true);
    
    if (!$show_tags || !has_tag()) {
        return;
    }
    
    ?>
    <div class="aqualuxe-post-tags">
        <span class="aqualuxe-post-tags-title"><?php echo esc_html__('Tags:', 'aqualuxe'); ?></span>
        <?php the_tags('', ', ', ''); ?>
    </div>
    <?php
}
add_action('aqualuxe_after_post_content', 'aqualuxe_post_tags');

/**
 * Add post sharing
 */
function aqualuxe_post_sharing() {
    if (!is_singular('post')) {
        return;
    }
    
    $show_sharing = get_theme_mod('aqualuxe_show_post_sharing', true);
    
    if (!$show_sharing) {
        return;
    }
    
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full')) : '';
    
    ?>
    <div class="aqualuxe-post-sharing">
        <span class="aqualuxe-post-sharing-title"><?php echo esc_html__('Share:', 'aqualuxe'); ?></span>
        
        <div class="aqualuxe-post-sharing-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-post-sharing-button aqualuxe-post-sharing-facebook">
                <i class="fab fa-facebook-f"></i>
                <span class="screen-reader-text"><?php echo esc_html__('Share on Facebook', 'aqualuxe'); ?></span>
            </a>
            
            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-post-sharing-button aqualuxe-post-sharing-twitter">
                <i class="fab fa-twitter"></i>
                <span class="screen-reader-text"><?php echo esc_html__('Share on Twitter', 'aqualuxe'); ?></span>
            </a>
            
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-post-sharing-button aqualuxe-post-sharing-linkedin">
                <i class="fab fa-linkedin-in"></i>
                <span class="screen-reader-text"><?php echo esc_html__('Share on LinkedIn', 'aqualuxe'); ?></span>
            </a>
            
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $post_thumbnail; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-post-sharing-button aqualuxe-post-sharing-pinterest">
                <i class="fab fa-pinterest-p"></i>
                <span class="screen-reader-text"><?php echo esc_html__('Share on Pinterest', 'aqualuxe'); ?></span>
            </a>
            
            <a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>" class="aqualuxe-post-sharing-button aqualuxe-post-sharing-email">
                <i class="far fa-envelope"></i>
                <span class="screen-reader-text"><?php echo esc_html__('Share via Email', 'aqualuxe'); ?></span>
            </a>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_after_post_content', 'aqualuxe_post_sharing', 20);

/**
 * Add read more link
 *
 * @param string $link Read more link
 * @return string Modified read more link
 */
function aqualuxe_read_more_link($link) {
    return '<div class="aqualuxe-read-more"><a href="' . esc_url(get_permalink()) . '" class="btn btn-primary">' . esc_html__('Read More', 'aqualuxe') . '</a></div>';
}
add_filter('the_content_more_link', 'aqualuxe_read_more_link');

/**
 * Add pagination
 */
function aqualuxe_pagination() {
    if (is_singular()) {
        return;
    }
    
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $max = $wp_query->max_num_pages;
    
    if ($paged >= 1) {
        $links[] = $paged;
    }
    
    if ($paged >= 3) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
    
    if (($paged + 2) <= $max) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
    
    echo '<nav class="aqualuxe-pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
    echo '<ul class="pagination">';
    
    // Previous
    if (get_previous_posts_link()) {
        printf(
            '<li class="page-item page-item-prev">%s</li>',
            get_previous_posts_link('<span aria-hidden="true">&laquo;</span><span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span>')
        );
    }
    
    // First page
    if (!in_array(1, $links)) {
        $class = 1 == $paged ? ' active' : '';
        printf(
            '<li class="page-item%s"><a class="page-link" href="%s">%s</a></li>',
            $class,
            esc_url(get_pagenum_link(1)),
            '1'
        );
        
        if (!in_array(2, $links)) {
            echo '<li class="page-item page-item-dots"><span class="page-link">&hellip;</span></li>';
        }
    }
    
    // Page numbers
    sort($links);
    foreach ((array) $links as $link) {
        $class = $paged == $link ? ' active' : '';
        printf(
            '<li class="page-item%s"><a class="page-link" href="%s">%s</a></li>',
            $class,
            esc_url(get_pagenum_link($link)),
            $link
        );
    }
    
    // Last page
    if (!in_array($max, $links)) {
        if (!in_array($max - 1, $links)) {
            echo '<li class="page-item page-item-dots"><span class="page-link">&hellip;</span></li>';
        }
        
        $class = $paged == $max ? ' active' : '';
        printf(
            '<li class="page-item%s"><a class="page-link" href="%s">%s</a></li>',
            $class,
            esc_url(get_pagenum_link($max)),
            $max
        );
    }
    
    // Next
    if (get_next_posts_link()) {
        printf(
            '<li class="page-item page-item-next">%s</li>',
            get_next_posts_link('<span aria-hidden="true">&raquo;</span><span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span>')
        );
    }
    
    echo '</ul>';
    echo '</nav>';
}
add_action('aqualuxe_after_content', 'aqualuxe_pagination');

/**
 * Add back to top button
 */
function aqualuxe_back_to_top() {
    $show_back_to_top = get_theme_mod('aqualuxe_show_back_to_top', true);
    
    if (!$show_back_to_top) {
        return;
    }
    
    ?>
    <a href="#" id="aqualuxe-back-to-top" class="aqualuxe-back-to-top" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <i class="fas fa-chevron-up"></i>
    </a>
    <?php
}
add_action('wp_footer', 'aqualuxe_back_to_top');

/**
 * Add search form
 *
 * @param string $form Search form HTML
 * @return string Modified search form HTML
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="aqualuxe-search-form" action="' . esc_url(home_url('/')) . '">
        <div class="aqualuxe-search-form-inner">
            <label class="screen-reader-text" for="s">' . __('Search for:', 'aqualuxe') . '</label>
            <input type="search" class="aqualuxe-search-field" placeholder="' . esc_attr__('Search&hellip;', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
            <button type="submit" class="aqualuxe-search-submit" aria-label="' . esc_attr__('Search', 'aqualuxe') . '">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Add search modal
 */
function aqualuxe_search_modal() {
    $show_search_modal = get_theme_mod('aqualuxe_show_search_modal', true);
    
    if (!$show_search_modal) {
        return;
    }
    
    ?>
    <div id="aqualuxe-search-modal" class="aqualuxe-search-modal">
        <div class="aqualuxe-search-modal-inner">
            <button id="aqualuxe-search-modal-close" class="aqualuxe-search-modal-close" aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="aqualuxe-search-modal-content">
                <h3 class="aqualuxe-search-modal-title"><?php echo esc_html__('Search', 'aqualuxe'); ?></h3>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_search_modal');

/**
 * Add mobile menu
 */
function aqualuxe_mobile_menu() {
    ?>
    <div id="aqualuxe-mobile-menu" class="aqualuxe-mobile-menu">
        <div class="aqualuxe-mobile-menu-inner">
            <div class="aqualuxe-mobile-menu-header">
                <div class="aqualuxe-mobile-menu-logo">
                    <?php echo aqualuxe_get_logo(); ?>
                </div>
                
                <button id="aqualuxe-mobile-menu-close" class="aqualuxe-mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="aqualuxe-mobile-menu-content">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'menu_id' => 'aqualuxe-mobile-menu-nav',
                    'menu_class' => 'aqualuxe-mobile-menu-nav',
                    'container' => 'nav',
                    'container_class' => 'aqualuxe-mobile-menu-nav-container',
                    'fallback_cb' => function() {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id' => 'aqualuxe-mobile-menu-nav',
                            'menu_class' => 'aqualuxe-mobile-menu-nav',
                            'container' => 'nav',
                            'container_class' => 'aqualuxe-mobile-menu-nav-container',
                        ]);
                    },
                ]);
                ?>
                
                <div class="aqualuxe-mobile-menu-search">
                    <?php get_search_form(); ?>
                </div>
                
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <div class="aqualuxe-mobile-menu-account">
                        <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="aqualuxe-mobile-menu-account-link">
                                <i class="fas fa-user"></i>
                                <?php echo esc_html__('My Account', 'aqualuxe'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>" class="aqualuxe-mobile-menu-account-link">
                                <i class="fas fa-box"></i>
                                <?php echo esc_html__('My Orders', 'aqualuxe'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url(wc_logout_url()); ?>" class="aqualuxe-mobile-menu-account-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <?php echo esc_html__('Logout', 'aqualuxe'); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="aqualuxe-mobile-menu-account-link">
                                <i class="fas fa-user"></i>
                                <?php echo esc_html__('Login / Register', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="aqualuxe-mobile-menu-social">
                    <?php
                    $social_links = aqualuxe_get_social_links();
                    
                    if ($social_links) {
                        echo '<div class="aqualuxe-social-links">';
                        
                        foreach ($social_links as $social) {
                            echo '<a href="' . esc_url($social['url']) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-link aqualuxe-social-' . esc_attr(key($social)) . '">';
                            echo '<i class="' . esc_attr($social['icon']) . '"></i>';
                            echo '<span class="screen-reader-text">' . esc_html($social['label']) . '</span>';
                            echo '</a>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div class="aqualuxe-mobile-menu-contact">
                    <?php
                    $contact_info = aqualuxe_get_contact_info();
                    
                    if ($contact_info) {
                        echo '<div class="aqualuxe-contact-info">';
                        
                        foreach ($contact_info as $contact) {
                            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-info-' . esc_attr(key($contact)) . '">';
                            
                            if (!empty($contact['url'])) {
                                echo '<a href="' . esc_url($contact['url']) . '" class="aqualuxe-contact-info-link">';
                            }
                            
                            echo '<i class="' . esc_attr($contact['icon']) . '"></i>';
                            echo '<span class="aqualuxe-contact-info-text">' . esc_html($contact['value']) . '</span>';
                            
                            if (!empty($contact['url'])) {
                                echo '</a>';
                            }
                            
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_mobile_menu');

/**
 * Add WooCommerce support
 */
function aqualuxe_woocommerce_support() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Add theme support for WooCommerce features
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 400,
        'single_image_width' => 800,
        'product_grid' => [
            'default_rows' => 3,
            'min_rows' => 1,
            'max_rows' => 6,
            'default_columns' => 4,
            'min_columns' => 1,
            'max_columns' => 6,
        ],
    ]);
    
    // Add product gallery features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove default WooCommerce wrappers
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    
    // Add custom WooCommerce wrappers
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10);
    
    // Add custom WooCommerce hooks
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_before_shop_loop', 10);
    add_action('woocommerce_after_shop_loop', 'aqualuxe_woocommerce_after_shop_loop', 10);
    add_action('woocommerce_before_single_product', 'aqualuxe_woocommerce_before_single_product', 10);
    add_action('woocommerce_after_single_product', 'aqualuxe_woocommerce_after_single_product', 10);
    
    // Add custom WooCommerce filters
    add_filter('woocommerce_product_loop_start', 'aqualuxe_woocommerce_product_loop_start', 10);
    add_filter('woocommerce_product_loop_end', 'aqualuxe_woocommerce_product_loop_end', 10);
    add_filter('woocommerce_show_page_title', 'aqualuxe_woocommerce_show_page_title', 10);
    add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults', 10);
    
    // Add custom WooCommerce template hooks
    add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_before_shop_loop_item', 10);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_after_shop_loop_item', 10);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_before_shop_loop_item_title', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_after_shop_loop_item_title', 10);
    
    // Add custom WooCommerce cart fragments
    add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments', 10);
    
    // Add custom WooCommerce checkout fields
    add_filter('woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields', 10);
    
    // Add custom WooCommerce account menu items
    add_filter('woocommerce_account_menu_items', 'aqualuxe_woocommerce_account_menu_items', 10);
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_support');

/**
 * WooCommerce wrapper start
 */
function aqualuxe_woocommerce_wrapper_start() {
    $container_class = aqualuxe_get_container_class();
    $content_class = aqualuxe_get_content_class();
    
    echo '<div class="' . esc_attr($container_class) . '">';
    echo '<div class="row">';
    echo '<div class="' . esc_attr($content_class) . '">';
}

/**
 * WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_end() {
    echo '</div>'; // .col
    
    if (aqualuxe_has_sidebar()) {
        get_sidebar('shop');
    }
    
    echo '</div>'; // .row
    echo '</div>'; // .container
}

/**
 * WooCommerce before shop loop
 */
function aqualuxe_woocommerce_before_shop_loop() {
    echo '<div class="aqualuxe-shop-header">';
    echo '<div class="aqualuxe-shop-header-inner">';
}

/**
 * WooCommerce after shop loop
 */
function aqualuxe_woocommerce_after_shop_loop() {
    echo '</div>'; // .aqualuxe-shop-header-inner
    echo '</div>'; // .aqualuxe-shop-header
}

/**
 * WooCommerce before single product
 */
function aqualuxe_woocommerce_before_single_product() {
    echo '<div class="aqualuxe-single-product">';
}

/**
 * WooCommerce after single product
 */
function aqualuxe_woocommerce_after_single_product() {
    echo '</div>'; // .aqualuxe-single-product
}

/**
 * WooCommerce product loop start
 *
 * @param string $html Product loop start HTML
 * @return string Modified product loop start HTML
 */
function aqualuxe_woocommerce_product_loop_start($html) {
    $shop_layout = aqualuxe_get_shop_layout();
    $columns = get_option('woocommerce_catalog_columns', 4);
    
    return '<ul class="products aqualuxe-products aqualuxe-products-' . esc_attr($shop_layout) . ' columns-' . esc_attr($columns) . '">';
}

/**
 * WooCommerce product loop end
 *
 * @param string $html Product loop end HTML
 * @return string Modified product loop end HTML
 */
function aqualuxe_woocommerce_product_loop_end($html) {
    return '</ul>';
}

/**
 * WooCommerce show page title
 *
 * @param bool $show Show page title
 * @return bool Modified show page title
 */
function aqualuxe_woocommerce_show_page_title($show) {
    return false;
}

/**
 * WooCommerce breadcrumb defaults
 *
 * @param array $defaults Breadcrumb defaults
 * @return array Modified breadcrumb defaults
 */
function aqualuxe_woocommerce_breadcrumb_defaults($defaults) {
    $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="aqualuxe-breadcrumbs woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '"><ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $defaults['wrap_after'] = '</ol></nav>';
    $defaults['before'] = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $defaults['after'] = '</li>';
    
    return $defaults;
}

/**
 * WooCommerce before shop loop item
 */
function aqualuxe_woocommerce_before_shop_loop_item() {
    echo '<div class="aqualuxe-product-inner">';
}

/**
 * WooCommerce after shop loop item
 */
function aqualuxe_woocommerce_after_shop_loop_item() {
    echo '</div>'; // .aqualuxe-product-inner
}

/**
 * WooCommerce before shop loop item title
 */
function aqualuxe_woocommerce_before_shop_loop_item_title() {
    echo '<div class="aqualuxe-product-thumbnail">';
    echo '<div class="aqualuxe-product-thumbnail-inner">';
}

/**
 * WooCommerce after shop loop item title
 */
function aqualuxe_woocommerce_after_shop_loop_item_title() {
    echo '</div>'; // .aqualuxe-product-thumbnail-inner
    echo '</div>'; // .aqualuxe-product-thumbnail
    
    echo '<div class="aqualuxe-product-content">';
    echo '<div class="aqualuxe-product-content-inner">';
}

/**
 * WooCommerce cart fragments
 *
 * @param array $fragments Cart fragments
 * @return array Modified cart fragments
 */
function aqualuxe_woocommerce_cart_fragments($fragments) {
    ob_start();
    ?>
    <span class="aqualuxe-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.aqualuxe-cart-count'] = ob_get_clean();
    
    ob_start();
    ?>
    <span class="aqualuxe-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
    <?php
    $fragments['.aqualuxe-cart-total'] = ob_get_clean();
    
    return $fragments;
}

/**
 * WooCommerce checkout fields
 *
 * @param array $fields Checkout fields
 * @return array Modified checkout fields
 */
function aqualuxe_woocommerce_checkout_fields($fields) {
    // Add placeholder to fields
    foreach ($fields as $section => $section_fields) {
        foreach ($section_fields as $key => $field) {
            if (!isset($field['placeholder']) && isset($field['label'])) {
                $fields[$section][$key]['placeholder'] = $field['label'];
            }
        }
    }
    
    return $fields;
}

/**
 * WooCommerce account menu items
 *
 * @param array $items Account menu items
 * @return array Modified account menu items
 */
function aqualuxe_woocommerce_account_menu_items($items) {
    // Add custom menu items
    $items['wholesale'] = __('Wholesale', 'aqualuxe');
    $items['trade-in'] = __('Trade-In', 'aqualuxe');
    
    return $items;
}