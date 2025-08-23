<?php
/**
 * Template functions
 *
 * @package AquaLuxe
 */

/**
 * Get the page header
 *
 * @param string $title
 * @param string $subtitle
 * @param array $args
 */
function aqualuxe_page_header($title = '', $subtitle = '', $args = []) {
    // Set defaults
    $defaults = [
        'background' => '',
        'alignment' => 'center',
        'size' => 'default',
        'breadcrumbs' => true,
    ];

    // Parse args
    $args = wp_parse_args($args, $defaults);

    // Get title if not provided
    if (empty($title)) {
        if (is_home()) {
            $title = __('Blog', 'aqualuxe');
        } elseif (is_archive()) {
            $title = get_the_archive_title();
        } elseif (is_search()) {
            $title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
        } elseif (is_404()) {
            $title = __('Page Not Found', 'aqualuxe');
        } else {
            $title = get_the_title();
        }
    }

    // Get background image
    $background_image = '';
    if (!empty($args['background'])) {
        if (is_numeric($args['background'])) {
            $background_image = wp_get_attachment_image_url($args['background'], 'full');
        } else {
            $background_image = $args['background'];
        }
    }

    // Get classes
    $classes = [
        'page-header',
        'align-' . $args['alignment'],
        'size-' . $args['size'],
    ];

    if ($background_image) {
        $classes[] = 'has-background';
    }

    $class = implode(' ', $classes);

    // Output header
    ?>
    <header class="<?php echo esc_attr($class); ?>" <?php if ($background_image) : ?>style="background-image: url('<?php echo esc_url($background_image); ?>');"<?php endif; ?>>
        <div class="container">
            <h1 class="page-title"><?php echo wp_kses_post($title); ?></h1>
            
            <?php if ($subtitle) : ?>
                <div class="page-subtitle"><?php echo wp_kses_post($subtitle); ?></div>
            <?php endif; ?>
            
            <?php if ($args['breadcrumbs']) : ?>
                <?php aqualuxe_breadcrumbs(); ?>
            <?php endif; ?>
        </div>
    </header>
    <?php
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Skip on front page
    if (is_front_page()) {
        return;
    }

    // Get the breadcrumbs
    $breadcrumbs = [];

    // Add home link
    $breadcrumbs[] = [
        'title' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    ];

    // Add breadcrumbs based on page type
    if (is_home()) {
        // Blog page
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => '',
        ];
    } elseif (is_category()) {
        // Category archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_cat_title('', false),
            'url' => '',
        ];
    } elseif (is_tag()) {
        // Tag archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_tag_title('', false),
            'url' => '',
        ];
    } elseif (is_author()) {
        // Author archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_author(),
            'url' => '',
        ];
    } elseif (is_year()) {
        // Year archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => '',
        ];
    } elseif (is_month()) {
        // Month archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url' => '',
        ];
    } elseif (is_day()) {
        // Day archive
        $breadcrumbs[] = [
            'title' => __('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url' => get_month_link(get_the_date('Y'), get_the_date('m')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('j'),
            'url' => '',
        ];
    } elseif (is_tax()) {
        // Taxonomy archive
        $term = get_queried_object();
        $taxonomy = get_taxonomy($term->taxonomy);
        $breadcrumbs[] = [
            'title' => $taxonomy->labels->name,
            'url' => '',
        ];
        $breadcrumbs[] = [
            'title' => $term->name,
            'url' => '',
        ];
    } elseif (is_post_type_archive()) {
        // Post type archive
        $post_type = get_post_type_object(get_post_type());
        $breadcrumbs[] = [
            'title' => $post_type->labels->name,
            'url' => '',
        ];
    } elseif (is_single()) {
        // Single post
        $post_type = get_post_type();
        
        if ($post_type === 'post') {
            // Blog post
            $breadcrumbs[] = [
                'title' => __('Blog', 'aqualuxe'),
                'url' => get_permalink(get_option('page_for_posts')),
            ];
            
            // Add categories
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                ];
            }
        } elseif ($post_type === 'aqualuxe_service') {
            // Service post
            $breadcrumbs[] = [
                'title' => __('Services', 'aqualuxe'),
                'url' => get_post_type_archive_link('aqualuxe_service'),
            ];
            
            // Add service categories
            $terms = get_the_terms(get_the_ID(), 'aqualuxe_service_cat');
            if ($terms && !is_wp_error($terms)) {
                $term = $terms[0];
                $breadcrumbs[] = [
                    'title' => $term->name,
                    'url' => get_term_link($term),
                ];
            }
        } elseif ($post_type === 'aqualuxe_team') {
            // Team post
            $breadcrumbs[] = [
                'title' => __('Team', 'aqualuxe'),
                'url' => get_post_type_archive_link('aqualuxe_team'),
            ];
            
            // Add team roles
            $terms = get_the_terms(get_the_ID(), 'aqualuxe_team_role');
            if ($terms && !is_wp_error($terms)) {
                $term = $terms[0];
                $breadcrumbs[] = [
                    'title' => $term->name,
                    'url' => get_term_link($term),
                ];
            }
        } elseif ($post_type === 'aqualuxe_faq') {
            // FAQ post
            $breadcrumbs[] = [
                'title' => __('FAQs', 'aqualuxe'),
                'url' => get_post_type_archive_link('aqualuxe_faq'),
            ];
            
            // Add FAQ categories
            $terms = get_the_terms(get_the_ID(), 'aqualuxe_faq_cat');
            if ($terms && !is_wp_error($terms)) {
                $term = $terms[0];
                $breadcrumbs[] = [
                    'title' => $term->name,
                    'url' => get_term_link($term),
                ];
            }
        } elseif ($post_type === 'product' && aqualuxe_is_woocommerce_active()) {
            // Product post
            $breadcrumbs[] = [
                'title' => __('Shop', 'aqualuxe'),
                'url' => get_permalink(wc_get_page_id('shop')),
            ];
            
            // Add product categories
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $term = $terms[0];
                $breadcrumbs[] = [
                    'title' => $term->name,
                    'url' => get_term_link($term),
                ];
            }
        } else {
            // Other post types
            $post_type_obj = get_post_type_object($post_type);
            if ($post_type_obj) {
                $breadcrumbs[] = [
                    'title' => $post_type_obj->labels->name,
                    'url' => get_post_type_archive_link($post_type),
                ];
            }
        }
        
        // Add post title
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_page()) {
        // Page
        $parents = get_post_ancestors(get_the_ID());
        
        if ($parents) {
            $parents = array_reverse($parents);
            
            foreach ($parents as $parent) {
                $breadcrumbs[] = [
                    'title' => get_the_title($parent),
                    'url' => get_permalink($parent),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_search()) {
        // Search results
        $breadcrumbs[] = [
            'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        ];
    } elseif (is_404()) {
        // 404 page
        $breadcrumbs[] = [
            'title' => __('Page Not Found', 'aqualuxe'),
            'url' => '',
        ];
    }

    // Output breadcrumbs
    if ($breadcrumbs) {
        echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
        echo '<ol itemscope itemtype="http://schema.org/BreadcrumbList">';
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $position = $index + 1;
            
            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
            
            if ($breadcrumb['url']) {
                echo '<a href="' . esc_url($breadcrumb['url']) . '" itemprop="item"><span itemprop="name">' . esc_html($breadcrumb['title']) . '</span></a>';
            } else {
                echo '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
            }
            
            echo '<meta itemprop="position" content="' . esc_attr($position) . '" />';
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Display post meta
 *
 * @param int $post_id
 */
function aqualuxe_post_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Get post type
    $post_type = get_post_type($post_id);

    // Output meta based on post type
    if ($post_type === 'post') {
        // Blog post meta
        ?>
        <div class="post-meta">
            <span class="post-date">
                <i class="fas fa-calendar"></i>
                <?php echo get_the_date('', $post_id); ?>
            </span>
            
            <span class="post-author">
                <i class="fas fa-user"></i>
                <?php echo get_the_author_meta('display_name', get_post_field('post_author', $post_id)); ?>
            </span>
            
            <?php if (has_category('', $post_id)) : ?>
                <span class="post-categories">
                    <i class="fas fa-folder"></i>
                    <?php echo get_the_category_list(', ', '', $post_id); ?>
                </span>
            <?php endif; ?>
            
            <?php if (has_tag('', $post_id)) : ?>
                <span class="post-tags">
                    <i class="fas fa-tags"></i>
                    <?php echo get_the_tag_list('', ', ', '', $post_id); ?>
                </span>
            <?php endif; ?>
            
            <span class="post-comments">
                <i class="fas fa-comments"></i>
                <?php comments_number(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?>
            </span>
        </div>
        <?php
    } elseif ($post_type === 'aqualuxe_service') {
        // Service meta
        $price = get_post_meta($post_id, '_aqualuxe_service_price', true);
        $duration = get_post_meta($post_id, '_aqualuxe_service_duration', true);
        
        if ($price || $duration) {
            ?>
            <div class="service-meta">
                <?php if ($price) : ?>
                    <span class="service-price">
                        <i class="fas fa-tag"></i>
                        <?php echo esc_html($price); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($duration) : ?>
                    <span class="service-duration">
                        <i class="fas fa-clock"></i>
                        <?php echo esc_html($duration); ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php
        }
    } elseif ($post_type === 'aqualuxe_team') {
        // Team meta
        $position = get_post_meta($post_id, '_aqualuxe_team_position', true);
        
        if ($position) {
            ?>
            <div class="team-meta">
                <span class="team-position">
                    <?php echo esc_html($position); ?>
                </span>
            </div>
            <?php
        }
    } elseif ($post_type === 'aqualuxe_testimonial') {
        // Testimonial meta
        $author = get_post_meta($post_id, '_aqualuxe_testimonial_author', true);
        $position = get_post_meta($post_id, '_aqualuxe_testimonial_position', true);
        $company = get_post_meta($post_id, '_aqualuxe_testimonial_company', true);
        $rating = get_post_meta($post_id, '_aqualuxe_testimonial_rating', true);
        
        ?>
        <div class="testimonial-meta">
            <?php if ($rating) : ?>
                <div class="testimonial-rating">
                    <?php aqualuxe_star_rating($rating); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($author) : ?>
                <div class="testimonial-author">
                    <?php echo esc_html($author); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($position || $company) : ?>
                <div class="testimonial-info">
                    <?php if ($position) : ?>
                        <span class="testimonial-position"><?php echo esc_html($position); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($position && $company) : ?>
                        <span class="testimonial-separator">, </span>
                    <?php endif; ?>
                    
                    <?php if ($company) : ?>
                        <span class="testimonial-company"><?php echo esc_html($company); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display star rating
 *
 * @param float $rating
 * @param int $max
 */
function aqualuxe_star_rating($rating, $max = 5) {
    $html = '<div class="star-rating">';
    
    // Full stars
    for ($i = 1; $i <= floor($rating); $i++) {
        $html .= '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if ($rating - floor($rating) >= 0.5) {
        $html .= '<i class="fas fa-star-half-alt"></i>';
        $i++;
    }
    
    // Empty stars
    for (; $i <= $max; $i++) {
        $html .= '<i class="far fa-star"></i>';
    }
    
    $html .= '</div>';
    
    echo $html;
}

/**
 * Display social sharing buttons
 *
 * @param int $post_id
 */
function aqualuxe_social_sharing($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Get post data
    $permalink = get_permalink($post_id);
    $title = get_the_title($post_id);
    $excerpt = get_the_excerpt($post_id);
    
    // Encode data for URLs
    $encoded_permalink = urlencode($permalink);
    $encoded_title = urlencode($title);
    $encoded_excerpt = urlencode(wp_strip_all_tags($excerpt));
    
    // Get featured image
    $image = '';
    if (has_post_thumbnail($post_id)) {
        $image = wp_get_attachment_image_url(get_post_thumbnail_id($post_id), 'large');
    }
    $encoded_image = urlencode($image);
    
    // Build sharing URLs
    $facebook_url = "https://www.facebook.com/sharer/sharer.php?u={$encoded_permalink}";
    $twitter_url = "https://twitter.com/intent/tweet?url={$encoded_permalink}&text={$encoded_title}";
    $linkedin_url = "https://www.linkedin.com/shareArticle?mini=true&url={$encoded_permalink}&title={$encoded_title}&summary={$encoded_excerpt}";
    $pinterest_url = "https://pinterest.com/pin/create/button/?url={$encoded_permalink}&media={$encoded_image}&description={$encoded_title}";
    $email_url = "mailto:?subject={$encoded_title}&body={$encoded_excerpt}%20{$encoded_permalink}";
    
    // Output sharing buttons
    ?>
    <div class="social-sharing">
        <span class="sharing-label"><?php _e('Share:', 'aqualuxe'); ?></span>
        
        <a href="<?php echo esc_url($facebook_url); ?>" class="share-facebook" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
            <span class="screen-reader-text"><?php _e('Share on Facebook', 'aqualuxe'); ?></span>
        </a>
        
        <a href="<?php echo esc_url($twitter_url); ?>" class="share-twitter" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-twitter"></i>
            <span class="screen-reader-text"><?php _e('Share on Twitter', 'aqualuxe'); ?></span>
        </a>
        
        <a href="<?php echo esc_url($linkedin_url); ?>" class="share-linkedin" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-linkedin-in"></i>
            <span class="screen-reader-text"><?php _e('Share on LinkedIn', 'aqualuxe'); ?></span>
        </a>
        
        <?php if ($image) : ?>
            <a href="<?php echo esc_url($pinterest_url); ?>" class="share-pinterest" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-pinterest-p"></i>
                <span class="screen-reader-text"><?php _e('Share on Pinterest', 'aqualuxe'); ?></span>
            </a>
        <?php endif; ?>
        
        <a href="<?php echo esc_url($email_url); ?>" class="share-email">
            <i class="fas fa-envelope"></i>
            <span class="screen-reader-text"><?php _e('Share via Email', 'aqualuxe'); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Display related posts
 *
 * @param int $post_id
 * @param int $count
 */
function aqualuxe_related_posts($post_id = null, $count = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Get post type
    $post_type = get_post_type($post_id);
    
    // Get related posts based on post type
    $args = [
        'post_type' => $post_type,
        'post__not_in' => [$post_id],
        'posts_per_page' => $count,
        'orderby' => 'rand',
    ];
    
    if ($post_type === 'post') {
        // Get related posts by category
        $categories = get_the_category($post_id);
        
        if ($categories) {
            $category_ids = [];
            
            foreach ($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            $args['category__in'] = $category_ids;
        }
    } elseif ($post_type === 'aqualuxe_service') {
        // Get related services by category
        $terms = get_the_terms($post_id, 'aqualuxe_service_cat');
        
        if ($terms && !is_wp_error($terms)) {
            $term_ids = [];
            
            foreach ($terms as $term) {
                $term_ids[] = $term->term_id;
            }
            
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_service_cat',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ],
            ];
        }
    } elseif ($post_type === 'aqualuxe_team') {
        // Get related team members by role
        $terms = get_the_terms($post_id, 'aqualuxe_team_role');
        
        if ($terms && !is_wp_error($terms)) {
            $term_ids = [];
            
            foreach ($terms as $term) {
                $term_ids[] = $term->term_id;
            }
            
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_team_role',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ],
            ];
        }
    } elseif ($post_type === 'aqualuxe_faq') {
        // Get related FAQs by category
        $terms = get_the_terms($post_id, 'aqualuxe_faq_cat');
        
        if ($terms && !is_wp_error($terms)) {
            $term_ids = [];
            
            foreach ($terms as $term) {
                $term_ids[] = $term->term_id;
            }
            
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_faq_cat',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ],
            ];
        }
    } elseif ($post_type === 'product' && aqualuxe_is_woocommerce_active()) {
        // Get related products by category
        $terms = get_the_terms($post_id, 'product_cat');
        
        if ($terms && !is_wp_error($terms)) {
            $term_ids = [];
            
            foreach ($terms as $term) {
                $term_ids[] = $term->term_id;
            }
            
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ],
            ];
        }
    }
    
    // Get related posts
    $related_posts = new WP_Query($args);
    
    // Output related posts
    if ($related_posts->have_posts()) {
        ?>
        <div class="related-posts">
            <h3 class="related-posts-title">
                <?php
                if ($post_type === 'post') {
                    _e('Related Posts', 'aqualuxe');
                } elseif ($post_type === 'aqualuxe_service') {
                    _e('Related Services', 'aqualuxe');
                } elseif ($post_type === 'aqualuxe_team') {
                    _e('Related Team Members', 'aqualuxe');
                } elseif ($post_type === 'aqualuxe_faq') {
                    _e('Related FAQs', 'aqualuxe');
                } elseif ($post_type === 'product') {
                    _e('Related Products', 'aqualuxe');
                } else {
                    _e('Related Items', 'aqualuxe');
                }
                ?>
            </h3>
            
            <div class="related-posts-grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <div class="related-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="related-post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('aqualuxe-thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <h4 class="related-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <?php if ($post_type === 'post') : ?>
                            <div class="related-post-date">
                                <?php echo get_the_date(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        
        // Reset post data
        wp_reset_postdata();
    }
}

/**
 * Display pagination
 *
 * @param object $query
 */
function aqualuxe_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    // Don't print empty markup if there's only one page
    if ($query->max_num_pages < 2) {
        return;
    }

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $max = $query->max_num_pages;

    // Set up pagination args
    $links = paginate_links([
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $max,
        'type' => 'array',
        'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
        'mid_size' => 1,
        'end_size' => 1,
    ]);

    if ($links) {
        echo '<nav class="pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
        echo '<ul class="page-numbers">';
        
        foreach ($links as $link) {
            echo '<li>' . $link . '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if (!$prev_post && !$next_post) {
        return;
    }

    ?>
    <nav class="post-navigation" aria-label="<?php esc_attr_e('Post Navigation', 'aqualuxe'); ?>">
        <div class="post-navigation-links">
            <?php if ($prev_post) : ?>
                <div class="post-navigation-prev">
                    <a href="<?php echo get_permalink($prev_post); ?>" rel="prev">
                        <span class="post-navigation-label"><?php _e('Previous', 'aqualuxe'); ?></span>
                        <span class="post-navigation-title"><?php echo get_the_title($prev_post); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post) : ?>
                <div class="post-navigation-next">
                    <a href="<?php echo get_permalink($next_post); ?>" rel="next">
                        <span class="post-navigation-label"><?php _e('Next', 'aqualuxe'); ?></span>
                        <span class="post-navigation-title"><?php echo get_the_title($next_post); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Display author bio
 *
 * @param int $author_id
 */
function aqualuxe_author_bio($author_id = null) {
    if (!$author_id) {
        $author_id = get_the_author_meta('ID');
    }

    // Get author data
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 100);

    // Only display if author has a description
    if (!$author_description) {
        return;
    }

    ?>
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo $author_avatar; ?>
        </div>
        
        <div class="author-content">
            <h3 class="author-name">
                <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
            </h3>
            
            <div class="author-description">
                <?php echo wpautop($author_description); ?>
            </div>
            
            <a href="<?php echo esc_url($author_url); ?>" class="author-link">
                <?php printf(__('View all posts by %s', 'aqualuxe'), esc_html($author_name)); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Display comments template
 */
function aqualuxe_comments_template() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Display featured image
 *
 * @param int $post_id
 * @param string $size
 * @param bool $link
 */
function aqualuxe_featured_image($post_id = null, $size = 'large', $link = true) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (has_post_thumbnail($post_id)) {
        if ($link) {
            ?>
            <div class="featured-image">
                <a href="<?php echo get_permalink($post_id); ?>">
                    <?php echo get_the_post_thumbnail($post_id, $size, ['class' => 'img-fluid']); ?>
                </a>
            </div>
            <?php
        } else {
            ?>
            <div class="featured-image">
                <?php echo get_the_post_thumbnail($post_id, $size, ['class' => 'img-fluid']); ?>
            </div>
            <?php
        }
    }
}

/**
 * Display post excerpt
 *
 * @param int $post_id
 * @param int $length
 * @param bool $more_link
 */
function aqualuxe_excerpt($post_id = null, $length = 55, $more_link = true) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Get post excerpt
    $excerpt = get_the_excerpt($post_id);
    
    // Trim excerpt to specified length
    if ($length > 0) {
        $excerpt = wp_trim_words($excerpt, $length, '&hellip;');
    }
    
    // Output excerpt
    ?>
    <div class="post-excerpt">
        <?php echo $excerpt; ?>
        
        <?php if ($more_link) : ?>
            <p class="read-more">
                <a href="<?php echo get_permalink($post_id); ?>" class="read-more-link">
                    <?php _e('Read More', 'aqualuxe'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display social links
 */
function aqualuxe_social_links() {
    // Get social links from theme options
    $facebook = get_theme_mod('aqualuxe_social_facebook');
    $twitter = get_theme_mod('aqualuxe_social_twitter');
    $instagram = get_theme_mod('aqualuxe_social_instagram');
    $linkedin = get_theme_mod('aqualuxe_social_linkedin');
    $youtube = get_theme_mod('aqualuxe_social_youtube');
    $pinterest = get_theme_mod('aqualuxe_social_pinterest');
    
    // Output social links
    if ($facebook || $twitter || $instagram || $linkedin || $youtube || $pinterest) {
        ?>
        <div class="social-links">
            <?php if ($facebook) : ?>
                <a href="<?php echo esc_url($facebook); ?>" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-facebook-f"></i>
                    <span class="screen-reader-text"><?php _e('Facebook', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($twitter) : ?>
                <a href="<?php echo esc_url($twitter); ?>" class="social-link twitter" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-twitter"></i>
                    <span class="screen-reader-text"><?php _e('Twitter', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($instagram) : ?>
                <a href="<?php echo esc_url($instagram); ?>" class="social-link instagram" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-instagram"></i>
                    <span class="screen-reader-text"><?php _e('Instagram', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($linkedin) : ?>
                <a href="<?php echo esc_url($linkedin); ?>" class="social-link linkedin" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-linkedin-in"></i>
                    <span class="screen-reader-text"><?php _e('LinkedIn', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($youtube) : ?>
                <a href="<?php echo esc_url($youtube); ?>" class="social-link youtube" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-youtube"></i>
                    <span class="screen-reader-text"><?php _e('YouTube', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if ($pinterest) : ?>
                <a href="<?php echo esc_url($pinterest); ?>" class="social-link pinterest" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-pinterest-p"></i>
                    <span class="screen-reader-text"><?php _e('Pinterest', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display contact info
 */
function aqualuxe_contact_info() {
    // Get contact info from theme options
    $phone = get_theme_mod('aqualuxe_contact_phone');
    $email = get_theme_mod('aqualuxe_contact_email');
    $address = get_theme_mod('aqualuxe_contact_address');
    $hours = get_theme_mod('aqualuxe_contact_hours');
    
    // Output contact info
    if ($phone || $email || $address || $hours) {
        ?>
        <div class="contact-info">
            <?php if ($phone) : ?>
                <div class="contact-item phone">
                    <i class="fas fa-phone"></i>
                    <span class="contact-text"><?php echo esc_html($phone); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($email) : ?>
                <div class="contact-item email">
                    <i class="fas fa-envelope"></i>
                    <span class="contact-text">
                        <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                    </span>
                </div>
            <?php endif; ?>
            
            <?php if ($address) : ?>
                <div class="contact-item address">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="contact-text"><?php echo esc_html($address); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($hours) : ?>
                <div class="contact-item hours">
                    <i class="fas fa-clock"></i>
                    <span class="contact-text"><?php echo esc_html($hours); ?></span>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

/**
 * Display newsletter form
 */
function aqualuxe_newsletter_form() {
    // Get newsletter form shortcode from theme options
    $shortcode = get_theme_mod('aqualuxe_newsletter_shortcode');
    
    if ($shortcode) {
        ?>
        <div class="newsletter-form">
            <h3 class="newsletter-title"><?php _e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h3>
            <p class="newsletter-description"><?php _e('Stay updated with our latest news and offers.', 'aqualuxe'); ?></p>
            <?php echo do_shortcode($shortcode); ?>
        </div>
        <?php
    } else {
        // Default newsletter form
        ?>
        <div class="newsletter-form">
            <h3 class="newsletter-title"><?php _e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h3>
            <p class="newsletter-description"><?php _e('Stay updated with our latest news and offers.', 'aqualuxe'); ?></p>
            <form action="#" method="post" class="newsletter-form-fields">
                <div class="form-group">
                    <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" required>
                </div>
                <button type="submit" class="newsletter-submit"><?php _e('Subscribe', 'aqualuxe'); ?></button>
            </form>
            <p class="newsletter-privacy"><?php _e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }
}