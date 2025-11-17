<?php
/**
 * AquaLuxe Template Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Template_Functions Class
 * 
 * Handles the theme template functions
 */
class AquaLuxe_Template_Functions {
    /**
     * Constructor
     */
    public function __construct() {
        // Add filters
        add_filter('body_class', array($this, 'body_classes'));
        add_filter('post_class', array($this, 'post_classes'));
        add_filter('excerpt_length', array($this, 'excerpt_length'));
        add_filter('excerpt_more', array($this, 'excerpt_more'));
        
        // Add WooCommerce filters (if WooCommerce is active)
        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start'));
            add_filter('woocommerce_product_loop_end', array($this, 'product_loop_end'));
            add_filter('woocommerce_loop_add_to_cart_link', array($this, 'loop_add_to_cart_link'), 10, 3);
            add_filter('woocommerce_sale_flash', array($this, 'sale_flash'), 10, 3);
            add_filter('woocommerce_product_tabs', array($this, 'product_tabs'), 10, 1);
            add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
            add_filter('woocommerce_cross_sells_columns', array($this, 'cross_sells_columns'));
            add_filter('woocommerce_cross_sells_total', array($this, 'cross_sells_total'));
            add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
            add_filter('woocommerce_product_review_comment_form_args', array($this, 'review_form_args'));
            
            // Change number of products per row
            add_filter('loop_shop_columns', array($this, 'loop_shop_columns'));
            
            // Change number of products per page
            add_filter('loop_shop_per_page', array($this, 'loop_shop_per_page'));
        }
    }

    /**
     * Add custom classes to the body tag
     * 
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes($classes) {
        // Add a class for the header layout
        $header_layout = aqualuxe_get_option('header_layout', 'default');
        $classes[] = 'header-layout-' . $header_layout;
        
        // Add a class for the footer layout
        $footer_layout = aqualuxe_get_option('footer_layout', '4-columns');
        $classes[] = 'footer-layout-' . $footer_layout;
        
        // Add a class for the sidebar position
        $sidebar_position = aqualuxe_get_option('sidebar_position', 'right');
        $classes[] = 'sidebar-' . $sidebar_position;
        
        // Add a class for the shop sidebar position (if WooCommerce is active)
        if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag())) {
            $shop_sidebar_position = aqualuxe_get_option('shop_sidebar_position', 'right');
            if (aqualuxe_get_option('show_shop_sidebar', true)) {
                $classes[] = 'shop-sidebar-' . $shop_sidebar_position;
            } else {
                $classes[] = 'shop-sidebar-none';
            }
        }
        
        // Add a class for the product layout (if WooCommerce is active)
        if (class_exists('WooCommerce') && is_product()) {
            $product_layout = aqualuxe_get_option('product_layout', 'standard');
            $classes[] = 'product-layout-' . $product_layout;
            
            if (aqualuxe_get_option('show_product_sidebar', false)) {
                $classes[] = 'product-has-sidebar';
            } else {
                $classes[] = 'product-no-sidebar';
            }
        }
        
        // Add a class for the shop layout (if WooCommerce is active)
        if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag())) {
            $shop_layout = aqualuxe_get_option('shop_layout', 'grid');
            $classes[] = 'shop-layout-' . $shop_layout;
        }
        
        // Add a class for the blog layout
        if (is_home() || is_archive() || is_search()) {
            $blog_layout = aqualuxe_get_option('blog_layout', 'grid');
            $classes[] = 'blog-layout-' . $blog_layout;
        }
        
        // Add a class for the single post layout
        if (is_singular('post')) {
            $single_post_layout = aqualuxe_get_option('single_post_layout', 'standard');
            $classes[] = 'single-post-layout-' . $single_post_layout;
        }
        
        // Add a class for the button style
        $button_style = aqualuxe_get_option('button_style', 'rounded');
        $classes[] = 'button-style-' . $button_style;
        
        // Add a class for the sticky header
        if (aqualuxe_get_option('sticky_header', true)) {
            $classes[] = 'has-sticky-header';
        }
        
        return $classes;
    }

    /**
     * Add custom classes to post
     * 
     * @param array $classes Post classes.
     * @return array
     */
    public function post_classes($classes) {
        // Add a class for the blog layout
        if (is_home() || is_archive() || is_search()) {
            $blog_layout = aqualuxe_get_option('blog_layout', 'grid');
            $classes[] = 'post-layout-' . $blog_layout;
            
            // Add a class for posts per row
            if ($blog_layout === 'grid' || $blog_layout === 'masonry') {
                $posts_per_row = aqualuxe_get_option('posts_per_row', '3');
                $classes[] = 'post-column-' . $posts_per_row;
            }
        }
        
        return $classes;
    }

    /**
     * Change excerpt length
     * 
     * @param int $length Excerpt length.
     * @return int
     */
    public function excerpt_length($length) {
        if (is_admin()) {
            return $length;
        }
        
        return aqualuxe_get_option('excerpt_length', 30);
    }

    /**
     * Change excerpt more
     * 
     * @param string $more Excerpt more.
     * @return string
     */
    public function excerpt_more($more) {
        if (is_admin()) {
            return $more;
        }
        
        return '...';
    }

    /**
     * Modify product loop start
     * 
     * @param string $html Loop start HTML.
     * @return string
     */
    public function product_loop_start($html) {
        $shop_layout = aqualuxe_get_option('shop_layout', 'grid');
        $products_per_row = aqualuxe_get_option('products_per_row', 3);
        
        $classes = array(
            'products',
            'columns-' . $products_per_row,
            'shop-layout-' . $shop_layout
        );
        
        return '<ul class="' . esc_attr(implode(' ', $classes)) . '">';
    }

    /**
     * Modify product loop end
     * 
     * @param string $html Loop end HTML.
     * @return string
     */
    public function product_loop_end($html) {
        return '</ul>';
    }

    /**
     * Modify add to cart link
     * 
     * @param string $html    Add to cart HTML.
     * @param object $product Product object.
     * @param array  $args    Arguments.
     * @return string
     */
    public function loop_add_to_cart_link($html, $product, $args) {
        // Add custom classes to the add to cart button
        $button_style = aqualuxe_get_option('button_style', 'rounded');
        $html = str_replace('class="button', 'class="button button-style-' . $button_style, $html);
        
        return $html;
    }

    /**
     * Modify sale flash
     * 
     * @param string $html    Sale flash HTML.
     * @param object $post    Post object.
     * @param object $product Product object.
     * @return string
     */
    public function sale_flash($html, $post, $product) {
        if ($product->is_on_sale()) {
            $sale_text = esc_html__('Sale', 'aqualuxe');
            
            // If the product has a sale percentage, show it
            if ($product->get_type() === 'variable') {
                $percentages = array();
                
                // Get all variation prices
                $prices = $product->get_variation_prices();
                
                // Loop through variation prices
                foreach ($prices['price'] as $key => $price) {
                    // Only on sale variations
                    if ($prices['regular_price'][$key] !== $price) {
                        // Calculate and set percentage
                        $percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
                    }
                }
                
                // Get highest percentage for variable products
                if (!empty($percentages)) {
                    $percentage = max($percentages) . '%';
                    $sale_text = sprintf(esc_html__('-%s', 'aqualuxe'), $percentage);
                }
            } elseif ($product->get_type() === 'simple') {
                $regular_price = (float) $product->get_regular_price();
                $sale_price = (float) $product->get_sale_price();
                
                if ($regular_price > 0) {
                    $percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
                    $sale_text = sprintf(esc_html__('-%s', 'aqualuxe'), $percentage);
                }
            }
            
            $html = '<span class="onsale">' . $sale_text . '</span>';
        }
        
        return $html;
    }

    /**
     * Modify product tabs
     * 
     * @param array $tabs Product tabs.
     * @return array
     */
    public function product_tabs($tabs) {
        // Reorder tabs
        $tabs['description']['priority'] = 10;
        $tabs['additional_information']['priority'] = 20;
        $tabs['reviews']['priority'] = 30;
        
        return $tabs;
    }

    /**
     * Modify related products args
     * 
     * @param array $args Related products args.
     * @return array
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = aqualuxe_get_option('related_products_count', 4);
        $args['columns'] = min(4, $args['posts_per_page']);
        
        return $args;
    }

    /**
     * Modify cross sells columns
     * 
     * @param int $columns Cross sells columns.
     * @return int
     */
    public function cross_sells_columns($columns) {
        return 2;
    }

    /**
     * Modify cross sells total
     * 
     * @param int $total Cross sells total.
     * @return int
     */
    public function cross_sells_total($total) {
        return 2;
    }

    /**
     * Modify checkout fields
     * 
     * @param array $fields Checkout fields.
     * @return array
     */
    public function checkout_fields($fields) {
        // Make the order notes field optional
        if (!aqualuxe_get_option('show_order_notes', true)) {
            unset($fields['order']['order_comments']);
        }
        
        return $fields;
    }

    /**
     * Modify review form args
     * 
     * @param array $args Review form args.
     * @return array
     */
    public function review_form_args($args) {
        // Add custom classes to the review form
        $args['class_submit'] .= ' button button-primary';
        
        return $args;
    }

    /**
     * Change number of products per row
     * 
     * @return int
     */
    public function loop_shop_columns() {
        return aqualuxe_get_option('products_per_row', 3);
    }

    /**
     * Change number of products per page
     * 
     * @return int
     */
    public function loop_shop_per_page() {
        return aqualuxe_get_option('products_per_page', 12);
    }
}

// Initialize the class
new AquaLuxe_Template_Functions();

/**
 * Helper functions
 */

/**
 * Display the site logo
 * 
 * @param string $class Additional classes.
 */
function aqualuxe_site_logo($class = '') {
    $logo_class = 'site-logo';
    if ($class) {
        $logo_class .= ' ' . $class;
    }
    
    $html = '';
    
    if (has_custom_logo()) {
        $html .= '<div class="' . esc_attr($logo_class) . '">';
        $html .= get_custom_logo();
        $html .= '</div>';
    } else {
        $html .= '<div class="' . esc_attr($logo_class) . '">';
        $html .= '<a href="' . esc_url(home_url('/')) . '" rel="home">';
        $html .= '<span class="site-title">' . esc_html(get_bloginfo('name')) . '</span>';
        $html .= '</a>';
        $html .= '</div>';
    }
    
    echo $html;
}

/**
 * Display the site title
 */
function aqualuxe_site_title() {
    $html = '';
    
    $html .= '<div class="site-branding">';
    if (is_front_page() && is_home()) {
        $html .= '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a></h1>';
    } else {
        $html .= '<p class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a></p>';
    }
    
    $description = get_bloginfo('description', 'display');
    if ($description || is_customize_preview()) {
        $html .= '<p class="site-description">' . $description . '</p>';
    }
    $html .= '</div><!-- .site-branding -->';
    
    echo $html;
}

/**
 * Display the primary navigation
 */
function aqualuxe_primary_navigation() {
    if (has_nav_menu('primary')) {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            </button>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'menu_class'     => 'primary-menu menu',
            ));
            ?>
        </nav><!-- #site-navigation -->
        <?php
    }
}

/**
 * Display the mobile navigation
 */
function aqualuxe_mobile_navigation() {
    if (has_nav_menu('mobile')) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e('Mobile Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'mobile',
                'menu_id'        => 'mobile-menu',
                'container'      => false,
                'menu_class'     => 'mobile-menu menu',
            ));
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    } elseif (has_nav_menu('primary')) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e('Mobile Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'mobile-menu',
                'container'      => false,
                'menu_class'     => 'mobile-menu menu',
            ));
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    }
}

/**
 * Display the footer navigation
 */
function aqualuxe_footer_navigation() {
    if (has_nav_menu('footer')) {
        ?>
        <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'container'      => false,
                'menu_class'     => 'footer-menu menu',
                'depth'          => 1,
            ));
            ?>
        </nav><!-- .footer-navigation -->
        <?php
    }
}

/**
 * Display the breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (!aqualuxe_get_option('enable_breadcrumbs', true)) {
        return;
    }
    
    // Check if Yoast SEO breadcrumbs are enabled
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
        return;
    }
    
    // Check if WooCommerce breadcrumbs are available
    if (function_exists('woocommerce_breadcrumb') && (is_woocommerce() || is_cart() || is_checkout())) {
        woocommerce_breadcrumb(array(
            'wrap_before' => '<div class="breadcrumbs">',
            'wrap_after'  => '</div>',
            'before'      => '<span>',
            'after'       => '</span>',
            'delimiter'   => '<span class="breadcrumb-delimiter">/</span>',
        ));
        return;
    }
    
    // Custom breadcrumbs
    $html = '<div class="breadcrumbs">';
    
    // Home link
    $html .= '<span><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></span>';
    $html .= '<span class="breadcrumb-delimiter">/</span>';
    
    // Page for posts
    if (is_home()) {
        $posts_page_id = get_option('page_for_posts');
        if ($posts_page_id) {
            $html .= '<span>' . esc_html(get_the_title($posts_page_id)) . '</span>';
        } else {
            $html .= '<span>' . esc_html__('Blog', 'aqualuxe') . '</span>';
        }
    }
    // Single post
    elseif (is_singular('post')) {
        $posts_page_id = get_option('page_for_posts');
        if ($posts_page_id) {
            $html .= '<span><a href="' . esc_url(get_permalink($posts_page_id)) . '">' . esc_html(get_the_title($posts_page_id)) . '</a></span>';
            $html .= '<span class="breadcrumb-delimiter">/</span>';
        }
        $html .= '<span>' . esc_html(get_the_title()) . '</span>';
    }
    // Page
    elseif (is_page()) {
        $html .= '<span>' . esc_html(get_the_title()) . '</span>';
    }
    // Category archive
    elseif (is_category()) {
        $html .= '<span>' . esc_html(single_cat_title('', false)) . '</span>';
    }
    // Tag archive
    elseif (is_tag()) {
        $html .= '<span>' . esc_html(single_tag_title('', false)) . '</span>';
    }
    // Author archive
    elseif (is_author()) {
        $html .= '<span>' . esc_html(get_the_author()) . '</span>';
    }
    // Date archive
    elseif (is_date()) {
        if (is_day()) {
            $html .= '<span>' . esc_html(get_the_date()) . '</span>';
        } elseif (is_month()) {
            $html .= '<span>' . esc_html(get_the_date('F Y')) . '</span>';
        } elseif (is_year()) {
            $html .= '<span>' . esc_html(get_the_date('Y')) . '</span>';
        }
    }
    // Search results
    elseif (is_search()) {
        $html .= '<span>' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    }
    // 404 page
    elseif (is_404()) {
        $html .= '<span>' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    }
    
    $html .= '</div>';
    
    echo $html;
}

/**
 * Display the post meta
 */
function aqualuxe_post_meta() {
    if (!aqualuxe_get_option('show_post_meta', true)) {
        return;
    }
    
    $html = '<div class="entry-meta">';
    
    // Author
    $html .= '<span class="meta-author">';
    $html .= '<i class="fas fa-user"></i> ';
    $html .= '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
    $html .= '</span>';
    
    // Date
    $html .= '<span class="meta-date">';
    $html .= '<i class="fas fa-calendar"></i> ';
    $html .= '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_date()) . '</a>';
    $html .= '</span>';
    
    // Categories
    $categories = get_the_category();
    if ($categories) {
        $html .= '<span class="meta-categories">';
        $html .= '<i class="fas fa-folder"></i> ';
        $cats = array();
        foreach ($categories as $category) {
            $cats[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }
        $html .= implode(', ', $cats);
        $html .= '</span>';
    }
    
    // Comments
    if (comments_open()) {
        $html .= '<span class="meta-comments">';
        $html .= '<i class="fas fa-comment"></i> ';
        $html .= '<a href="' . esc_url(get_permalink()) . '#comments">' . esc_html(get_comments_number_text('0', '1', '%')) . '</a>';
        $html .= '</span>';
    }
    
    $html .= '</div>';
    
    echo $html;
}

/**
 * Display the post tags
 */
function aqualuxe_post_tags() {
    $tags = get_the_tags();
    if ($tags) {
        echo '<div class="entry-tags">';
        echo '<span class="tags-title">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display the post author bio
 */
function aqualuxe_author_bio() {
    if (!aqualuxe_get_option('show_author_bio', true)) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_description = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 100);
    
    if (!$author_description) {
        return;
    }
    
    ?>
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo $author_avatar; ?>
        </div>
        <div class="author-info">
            <h3 class="author-title"><?php echo esc_html($author_name); ?></h3>
            <div class="author-description">
                <?php echo wpautop(esc_html($author_description)); ?>
            </div>
            <a class="author-link" href="<?php echo esc_url($author_url); ?>">
                <?php printf(esc_html__('View all posts by %s', 'aqualuxe'), esc_html($author_name)); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Display the post navigation
 */
function aqualuxe_post_navigation() {
    if (!aqualuxe_get_option('show_post_nav', true)) {
        return;
    }
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    ?>
    <nav class="post-navigation">
        <div class="nav-links">
            <?php if ($prev_post) : ?>
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                        <span class="nav-title"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                        <span class="post-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($next_post) : ?>
                <div class="nav-next">
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                        <span class="nav-title"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                        <span class="post-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    if (!aqualuxe_get_option('show_related_posts', true)) {
        return;
    }
    
    $post_id = get_the_ID();
    $cat_ids = wp_get_post_categories($post_id);
    $related_count = aqualuxe_get_option('related_posts_count', 3);
    
    if (empty($cat_ids)) {
        return;
    }
    
    $args = array(
        'category__in'        => $cat_ids,
        'post__not_in'        => array($post_id),
        'posts_per_page'      => $related_count,
        'ignore_sticky_posts' => 1,
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) {
        ?>
        <div class="related-posts">
            <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            <div class="related-posts-wrapper columns-<?php echo esc_attr($related_count); ?>">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="related-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="related-post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="related-post-content">
                            <h4 class="related-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="related-post-meta">
                                <?php echo esc_html(get_the_date()); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    }
}

/**
 * Display the social share buttons
 */
function aqualuxe_social_share() {
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    
    ?>
    <div class="social-share">
        <span class="share-title"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
        <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a class="share-twitter" href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-twitter"></i>
        </a>
        <a class="share-pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $post_thumbnail; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-pinterest-p"></i>
        </a>
        <a class="share-linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-linkedin-in"></i>
        </a>
        <a class="share-email" href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>">
            <i class="fas fa-envelope"></i>
        </a>
    </div>
    <?php
}

/**
 * Display the read more button
 */
function aqualuxe_read_more() {
    if (!aqualuxe_get_option('show_read_more', true)) {
        return;
    }
    
    $read_more_text = aqualuxe_get_option('read_more_text', esc_html__('Read More', 'aqualuxe'));
    $button_style = aqualuxe_get_option('button_style', 'rounded');
    
    echo '<div class="read-more-wrapper">';
    echo '<a href="' . esc_url(get_permalink()) . '" class="read-more button button-style-' . esc_attr($button_style) . '">' . esc_html($read_more_text) . '</a>';
    echo '</div>';
}

/**
 * Display the dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    if (!aqualuxe_get_option('dark_mode_toggle', true)) {
        return;
    }
    
    ?>
    <div class="dark-mode-toggle">
        <button class="dark-mode-button" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
            <span class="dark-mode-icon light"><i class="fas fa-sun"></i></span>
            <span class="dark-mode-icon dark"><i class="fas fa-moon"></i></span>
        </button>
    </div>
    <?php
}

/**
 * Display the search form
 */
function aqualuxe_search_form() {
    ?>
    <div class="search-form-wrapper">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <label>
                <span class="screen-reader-text"><?php esc_html_e('Search for:', 'aqualuxe'); ?></span>
                <input type="search" class="search-field" placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <button type="submit" class="search-submit">
                <i class="fas fa-search"></i>
                <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
            </button>
        </form>
    </div>
    <?php
}

/**
 * Display the copyright text
 */
function aqualuxe_copyright_text() {
    $copyright_text = aqualuxe_get_option('copyright_text', sprintf(esc_html__('© %s AquaLuxe. All Rights Reserved.', 'aqualuxe'), date('Y')));
    $copyright_text = str_replace('{year}', date('Y'), $copyright_text);
    
    echo '<div class="copyright-text">' . wp_kses_post($copyright_text) . '</div>';
}

/**
 * Display the payment icons
 */
function aqualuxe_payment_icons() {
    if (!aqualuxe_get_option('show_payment_icons', true)) {
        return;
    }
    
    ?>
    <div class="payment-icons">
        <span class="payment-icon visa"><i class="fab fa-cc-visa"></i></span>
        <span class="payment-icon mastercard"><i class="fab fa-cc-mastercard"></i></span>
        <span class="payment-icon amex"><i class="fab fa-cc-amex"></i></span>
        <span class="payment-icon discover"><i class="fab fa-cc-discover"></i></span>
        <span class="payment-icon paypal"><i class="fab fa-cc-paypal"></i></span>
        <span class="payment-icon apple-pay"><i class="fab fa-cc-apple-pay"></i></span>
    </div>
    <?php
}

/**
 * Display the social icons
 */
function aqualuxe_social_icons() {
    ?>
    <div class="social-icons">
        <?php if (aqualuxe_get_option('social_facebook', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_facebook', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('social_twitter', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_twitter', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon twitter">
                <i class="fab fa-twitter"></i>
            </a>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('social_instagram', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_instagram', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon instagram">
                <i class="fab fa-instagram"></i>
            </a>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('social_pinterest', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_pinterest', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon pinterest">
                <i class="fab fa-pinterest-p"></i>
            </a>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('social_youtube', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_youtube', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon youtube">
                <i class="fab fa-youtube"></i>
            </a>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('social_linkedin', '')) : ?>
            <a href="<?php echo esc_url(aqualuxe_get_option('social_linkedin', '')); ?>" target="_blank" rel="noopener noreferrer" class="social-icon linkedin">
                <i class="fab fa-linkedin-in"></i>
            </a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the contact information
 */
function aqualuxe_contact_info() {
    ?>
    <div class="contact-info">
        <?php if (aqualuxe_get_option('header_phone', '')) : ?>
            <div class="contact-phone">
                <i class="fas fa-phone"></i>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', aqualuxe_get_option('header_phone', ''))); ?>">
                    <?php echo esc_html(aqualuxe_get_option('header_phone', '')); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('header_email', '')) : ?>
            <div class="contact-email">
                <i class="fas fa-envelope"></i>
                <a href="mailto:<?php echo esc_attr(aqualuxe_get_option('header_email', '')); ?>">
                    <?php echo esc_html(aqualuxe_get_option('header_email', '')); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('header_address', '')) : ?>
            <div class="contact-address">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo esc_html(aqualuxe_get_option('header_address', '')); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (aqualuxe_get_option('header_hours', '')) : ?>
            <div class="contact-hours">
                <i class="fas fa-clock"></i>
                <span><?php echo esc_html(aqualuxe_get_option('header_hours', '')); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the header cart
 */
function aqualuxe_header_cart() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_cart', true)) {
        return;
    }
    
    ?>
    <div class="header-cart">
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
        </a>
        <div class="cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the header account
 */
function aqualuxe_header_account() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_account', true)) {
        return;
    }
    
    ?>
    <div class="header-account">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" title="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
            <i class="fas fa-user"></i>
        </a>
        <?php if (!is_user_logged_in()) : ?>
            <div class="account-dropdown">
                <div class="account-dropdown-inner">
                    <h3><?php esc_html_e('Login', 'aqualuxe'); ?></h3>
                    <?php woocommerce_login_form(array('redirect' => wc_get_page_permalink('myaccount'))); ?>
                    <p class="register-link">
                        <?php esc_html_e('Don\'t have an account?', 'aqualuxe'); ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('Register', 'aqualuxe'); ?></a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the header wishlist
 */
function aqualuxe_header_wishlist() {
    if (!class_exists('WooCommerce') || !aqualuxe_get_option('header_wishlist', true)) {
        return;
    }
    
    // Check if YITH WooCommerce Wishlist is active
    if (defined('YITH_WCWL') && YITH_WCWL) {
        $wishlist_url = YITH_WCWL()->get_wishlist_url();
        $count = yith_wcwl_count_all_products();
    } else {
        // Fallback to account page
        $wishlist_url = wc_get_account_endpoint_url('dashboard');
        $count = 0;
    }
    
    ?>
    <div class="header-wishlist">
        <a href="<?php echo esc_url($wishlist_url); ?>" title="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
            <i class="fas fa-heart"></i>
            <span class="wishlist-count"><?php echo esc_html($count); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Display the header search
 */
function aqualuxe_header_search() {
    if (!aqualuxe_get_option('header_search', true)) {
        return;
    }
    
    ?>
    <div class="header-search">
        <button class="search-toggle" aria-expanded="false">
            <i class="fas fa-search"></i>
        </button>
        <div class="search-dropdown">
            <?php aqualuxe_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display the quick view button
 * 
 * @param int $product_id Product ID.
 */
function aqualuxe_quick_view_button($product_id) {
    if (!aqualuxe_get_option('show_quick_view', true)) {
        return;
    }
    
    echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
}

/**
 * Display the wishlist button
 * 
 * @param int $product_id Product ID.
 */
function aqualuxe_wishlist_button($product_id) {
    if (!aqualuxe_get_option('show_wishlist', true)) {
        return;
    }
    
    // Check if YITH WooCommerce Wishlist is active
    if (defined('YITH_WCWL') && YITH_WCWL) {
        echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product_id . '"]');
    } else {
        // Fallback wishlist button
        echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
    }
}

/**
 * Display the product badges
 * 
 * @param object $product Product object.
 */
function aqualuxe_product_badges($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-badges">';
    
    // Sale badge
    if ($product->is_on_sale()) {
        echo '<span class="badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    // New badge (products published within the last 30 days)
    $days_as_new = 30;
    $post_date = get_the_time('U');
    $current_date = current_time('timestamp');
    $seconds_in_day = 86400;
    
    if (($current_date - $post_date) < ($days_as_new * $seconds_in_day)) {
        echo '<span class="badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
    }
    
    // Out of stock badge
    if (!$product->is_in_stock()) {
        echo '<span class="badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
    }
    
    // Featured badge
    if ($product->is_featured()) {
        echo '<span class="badge featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
    }
    
    echo '</div>';
}

/**
 * Display the product stock status
 * 
 * @param object $product Product object.
 */
function aqualuxe_product_stock_status($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return;
    }
    
    if ($product->is_in_stock()) {
        echo '<div class="stock in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</div>';
    } else {
        echo '<div class="stock out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</div>';
    }
}

/**
 * Display the product share buttons
 */
function aqualuxe_product_share() {
    $product_url = urlencode(get_permalink());
    $product_title = urlencode(get_the_title());
    $product_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
    
    ?>
    <div class="product-share">
        <span class="share-title"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
        <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $product_url; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a class="share-twitter" href="https://twitter.com/intent/tweet?url=<?php echo $product_url; ?>&text=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-twitter"></i>
        </a>
        <a class="share-pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo $product_url; ?>&media=<?php echo $product_thumbnail; ?>&description=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-pinterest-p"></i>
        </a>
        <a class="share-email" href="mailto:?subject=<?php echo $product_title; ?>&body=<?php echo $product_url; ?>">
            <i class="fas fa-envelope"></i>
        </a>
    </div>
    <?php
}

/**
 * Display the cart progress
 */
function aqualuxe_cart_progress() {
    ?>
    <div class="cart-progress">
        <div class="cart-progress-step current">
            <span class="step-number">1</span>
            <span class="step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
        </div>
        <div class="cart-progress-step">
            <span class="step-number">2</span>
            <span class="step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
        </div>
        <div class="cart-progress-step">
            <span class="step-number">3</span>
            <span class="step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}

/**
 * Display the checkout progress
 */
function aqualuxe_checkout_progress() {
    ?>
    <div class="checkout-progress">
        <div class="checkout-progress-step completed">
            <span class="step-number">1</span>
            <span class="step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-progress-step current">
            <span class="step-number">2</span>
            <span class="step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
        </div>
        <div class="checkout-progress-step">
            <span class="step-number">3</span>
            <span class="step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
        </div>
    </div>
    <?php
}