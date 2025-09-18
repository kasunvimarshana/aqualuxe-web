<?php
/**
 * Template Hooks
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * HTML Head hooks
 */
add_action('wp_head', 'aqualuxe_pingback_header');
add_action('wp_head', 'aqualuxe_schema_markup');
add_action('wp_head', 'aqualuxe_open_graph_tags');

/**
 * Header hooks
 */
add_action('aqualuxe_header_before', 'aqualuxe_skip_link');
add_action('aqualuxe_header_inside', 'aqualuxe_site_branding', 10);
add_action('aqualuxe_header_inside', 'aqualuxe_primary_navigation', 20);
add_action('aqualuxe_header_actions', 'aqualuxe_search_toggle', 10);
add_action('aqualuxe_header_actions', 'aqualuxe_cart_icon', 20);
add_action('aqualuxe_header_actions', 'aqualuxe_account_icon', 30);
add_action('aqualuxe_header_actions', 'aqualuxe_dark_mode_toggle', 40);

/**
 * Content hooks
 */
add_action('aqualuxe_content_before', 'aqualuxe_breadcrumbs');
add_action('aqualuxe_entry_header', 'aqualuxe_entry_title', 10);
add_action('aqualuxe_entry_header', 'aqualuxe_entry_meta', 20);
add_action('aqualuxe_entry_footer', 'aqualuxe_entry_tags', 10);
add_action('aqualuxe_entry_footer', 'aqualuxe_social_share_buttons', 20);

/**
 * Footer hooks
 */
add_action('aqualuxe_footer_before', 'aqualuxe_back_to_top');
add_action('aqualuxe_footer_inside', 'aqualuxe_footer_widgets', 10);
add_action('aqualuxe_footer_inside', 'aqualuxe_footer_info', 20);

/**
 * Archive hooks
 */
add_action('aqualuxe_archive_header', 'aqualuxe_archive_title', 10);
add_action('aqualuxe_archive_header', 'aqualuxe_archive_description', 20);

/**
 * Comments hooks
 */
add_action('aqualuxe_comment_meta', 'aqualuxe_comment_author', 10);
add_action('aqualuxe_comment_meta', 'aqualuxe_comment_date', 20);

/**
 * Hook implementations
 */

/**
 * Add pingback URL for single posts
 */
function aqualuxe_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}

/**
 * Add Open Graph tags
 */
function aqualuxe_open_graph_tags()
{
    if (!get_option('aqualuxe_enable_opengraph', true)) {
        return;
    }

    $og_title = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url = home_url('/');
    $og_image = '';
    $og_type = 'website';

    if (is_singular()) {
        $og_title = get_the_title();
        $og_description = get_the_excerpt() ?: wp_trim_words(get_the_content(), 30);
        $og_url = get_permalink();
        $og_type = 'article';
        
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $og_title = $term->name;
        $og_description = $term->description ?: $og_title;
        $og_url = get_term_link($term);
    } elseif (is_author()) {
        $author = get_queried_object();
        $og_title = $author->display_name;
        $og_description = get_the_author_meta('description', $author->ID) ?: sprintf(esc_html__('Posts by %s', 'aqualuxe'), $author->display_name);
        $og_url = get_author_posts_url($author->ID);
    }

    // Output Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($og_description)) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
    }

    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags($og_description)) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
    }
}

/**
 * Skip link
 */
function aqualuxe_skip_link()
{
    echo '<a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded z-50" href="#main">' . esc_html__('Skip to content', 'aqualuxe') . '</a>';
}

/**
 * Site branding
 */
function aqualuxe_site_branding()
{
    echo aqualuxe_get_logo();
}

/**
 * Primary navigation
 */
function aqualuxe_primary_navigation()
{
    if (has_nav_menu('primary')) {
        ?>
        <nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id' => 'primary-menu',
                'container' => false,
                'menu_class' => 'flex space-x-8',
                'fallback_cb' => false,
            ]);
            ?>
        </nav>
        <?php
    }
}

/**
 * Search toggle
 */
function aqualuxe_search_toggle()
{
    ?>
    <button type="button" class="search-toggle p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </button>
    <?php
}

/**
 * Cart icon
 */
function aqualuxe_cart_icon()
{
    if (!class_exists('WooCommerce')) {
        return;
    }
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon relative p-2 text-gray-600 hover:text-primary-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m7.5-5v5a2 2 0 01-2 2H9a2 2 0 01-2-2v-5m7.5 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4h7.5z"></path>
        </svg>
        <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
            <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
            </span>
        <?php endif; ?>
    </a>
    <?php
}

/**
 * Account icon
 */
function aqualuxe_account_icon()
{
    if (!class_exists('WooCommerce')) {
        return;
    }
    ?>
    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="account-icon p-2 text-gray-600 hover:text-primary-600 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
    </a>
    <?php
}

/**
 * Dark mode toggle
 */
function aqualuxe_dark_mode_toggle()
{
    if (!get_option('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    ?>
    <button type="button" class="dark-mode-toggle p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
    </button>
    <?php
}

/**
 * Display breadcrumbs wrapper
 */
function aqualuxe_display_breadcrumbs()
{
    if (is_front_page()) {
        return;
    }
    
    aqualuxe_breadcrumbs();
}

/**
 * Entry title
 */
function aqualuxe_entry_title()
{
    if (is_singular()) {
        the_title('<h1 class="entry-title text-4xl lg:text-5xl font-bold text-gray-900 mb-6">', '</h1>');
    } else {
        the_title('<h2 class="entry-title text-2xl font-bold mb-4"><a href="' . esc_url(get_permalink()) . '" class="text-gray-900 hover:text-primary-600 transition-colors">', '</a></h2>');
    }
}

/**
 * Entry meta
 */
function aqualuxe_entry_meta()
{
    if (get_post_type() === 'post') {
        aqualuxe_post_meta();
    }
}

/**
 * Entry tags
 */
function aqualuxe_entry_tags()
{
    if (is_singular('post') && has_tag()) {
        ?>
        <div class="entry-tags mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e('Tags', 'aqualuxe'); ?></h3>
            <div class="flex flex-wrap gap-2">
                <?php
                $tags = get_the_tags();
                foreach ($tags as $tag) :
                    ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                       class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-primary-100 hover:text-primary-700 transition-colors">
                        #<?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Social share buttons
 */
function aqualuxe_social_share_buttons()
{
    if (is_singular() && get_theme_mod('aqualuxe_show_social_share', true)) {
        aqualuxe_social_share();
    }
}

/**
 * Back to top button
 */
function aqualuxe_back_to_top()
{
    ?>
    <button type="button" class="scroll-to-top fixed bottom-8 right-8 w-12 h-12 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-all duration-300 z-40 hidden" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
    <?php
}

/**
 * Footer widgets
 */
function aqualuxe_footer_widgets()
{
    if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) {
        ?>
        <div class="footer-widgets py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <?php if (is_active_sidebar("footer-{$i}")) : ?>
                            <div class="footer-widget">
                                <?php dynamic_sidebar("footer-{$i}"); ?>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Footer info
 */
function aqualuxe_footer_info()
{
    ?>
    <div class="footer-bottom border-t border-gray-800 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="site-info text-sm text-gray-400">
                    <?php
                    $footer_text = get_theme_mod('aqualuxe_footer_copyright');
                    if ($footer_text) {
                        echo wp_kses_post($footer_text);
                    } else {
                        printf(
                            esc_html__('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
                            date('Y'),
                            get_bloginfo('name')
                        );
                    }
                    ?>
                </div>

                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_id' => 'footer-menu',
                            'container' => false,
                            'menu_class' => 'flex space-x-6 text-sm',
                            'fallback_cb' => false,
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Archive title
 */
function aqualuxe_archive_title()
{
    $title = aqualuxe_get_archive_title();
    if ($title) {
        echo '<h1 class="archive-title text-4xl lg:text-5xl font-bold text-gray-900 mb-6">' . esc_html($title) . '</h1>';
    }
}

/**
 * Archive description
 */
function aqualuxe_archive_description()
{
    $description = aqualuxe_get_archive_description();
    if ($description) {
        echo '<div class="archive-description text-lg text-gray-600 mb-8">' . wp_kses_post($description) . '</div>';
    }
}

/**
 * Comment author
 */
function aqualuxe_comment_author($comment, $args, $depth)
{
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body flex space-x-4 p-6 bg-gray-50 rounded-lg">
            <div class="comment-author-avatar">
                <?php echo get_avatar($comment, $args['avatar_size'], '', '', ['class' => 'rounded-full']); ?>
            </div>
            <div class="comment-content flex-1">
                <footer class="comment-meta mb-4">
                    <div class="comment-author vcard">
                        <?php
                        $comment_author = get_comment_author_link($comment);
                        echo '<cite class="fn font-semibold text-gray-900">' . $comment_author . '</cite>';
                        ?>
                    </div>
                    <div class="comment-metadata text-sm text-gray-600">
                        <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php
                                printf(
                                    esc_html__('%1$s at %2$s', 'aqualuxe'),
                                    get_comment_date('', $comment),
                                    get_comment_time()
                                );
                                ?>
                            </time>
                        </a>
                    </div>
                </footer>

                <div class="comment-text prose prose-sm max-w-none">
                    <?php comment_text(); ?>
                </div>

                <div class="comment-actions mt-4 flex space-x-4">
                    <?php
                    edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link text-sm">', '</span>');
                    
                    comment_reply_link(array_merge($args, [
                        'add_below' => 'div-comment',
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'],
                        'before' => '<span class="reply text-sm">',
                        'after' => '</span>',
                    ]));
                    ?>
                </div>
                
                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation text-sm text-yellow-600 mt-4">
                        <?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?>
                    </p>
                <?php endif; ?>
            </div>
        </article>
    <?php
}

/**
 * Comment date
 */
function aqualuxe_comment_date($comment)
{
    ?>
    <time datetime="<?php comment_time('c'); ?>" class="comment-date text-sm text-gray-600">
        <?php comment_date(); ?>
    </time>
    <?php
}