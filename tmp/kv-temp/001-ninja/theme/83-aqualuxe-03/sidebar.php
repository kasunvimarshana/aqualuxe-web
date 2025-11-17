<?php
/**
 * The sidebar containing the main widget area
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if sidebar should be displayed
$sidebar_position = kv_get_theme_option('sidebar_position', 'right');
$hide_sidebar_on = kv_get_theme_option('hide_sidebar_on', []);
$current_page_type = kv_get_current_page_type();

// Check if sidebar should be hidden on current page type
if (in_array($current_page_type, $hide_sidebar_on)) {
    return;
}

// Check if any sidebar widgets are active
if (!is_active_sidebar('sidebar-1') && !is_active_sidebar('sidebar-2')) {
    return;
}

?>

<aside id="secondary" class="widget-area sidebar sidebar-<?php echo esc_attr($sidebar_position); ?>" role="complementary" aria-label="<?php esc_attr_e('Sidebar', KV_THEME_TEXTDOMAIN); ?>">
    
    <?php
    // Mobile sidebar toggle for off-canvas
    if (kv_get_theme_option('enable_offcanvas_sidebar', true)) : ?>
        <div class="sidebar-header d-md-none">
            <h3 class="sidebar-title"><?php esc_html_e('Sidebar', KV_THEME_TEXTDOMAIN); ?></h3>
            <button class="sidebar-close" aria-label="<?php esc_attr_e('Close sidebar', KV_THEME_TEXTDOMAIN); ?>">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="sidebar-content">
        
        <?php
        // Search widget if enabled and not already in header
        if (kv_get_theme_option('show_sidebar_search', true) && !kv_get_theme_option('show_header_search', true)) : ?>
            <div class="widget widget-search">
                <h3 class="widget-title"><?php esc_html_e('Search', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Recent posts widget
        if (kv_get_theme_option('show_recent_posts', true)) : ?>
            <div class="widget widget-recent-posts">
                <h3 class="widget-title"><?php esc_html_e('Recent Posts', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <?php
                    $recent_posts = wp_get_recent_posts(array(
                        'numberposts' => kv_get_theme_option('recent_posts_count', 5),
                        'post_status' => 'publish'
                    ));
                    
                    if ($recent_posts) : ?>
                        <ul class="recent-posts-list">
                            <?php foreach ($recent_posts as $recent) : ?>
                                <li class="recent-post-item">
                                    <a href="<?php echo esc_url(get_permalink($recent['ID'])); ?>" class="recent-post-link">
                                        <?php if (has_post_thumbnail($recent['ID']) && kv_get_theme_option('show_recent_posts_thumbnails', true)) : ?>
                                            <div class="recent-post-thumbnail">
                                                <?php echo get_the_post_thumbnail($recent['ID'], 'thumbnail', array(
                                                    'alt' => esc_attr(get_the_title($recent['ID'])),
                                                    'loading' => 'lazy'
                                                )); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="recent-post-content">
                                            <h4 class="recent-post-title"><?php echo esc_html($recent['post_title']); ?></h4>
                                            <time class="recent-post-date" datetime="<?php echo esc_attr(get_the_date('c', $recent['ID'])); ?>">
                                                <?php echo esc_html(get_the_date('', $recent['ID'])); ?>
                                            </time>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><?php esc_html_e('No recent posts found.', KV_THEME_TEXTDOMAIN); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Categories widget
        if (kv_get_theme_option('show_categories', true)) : ?>
            <div class="widget widget-categories">
                <h3 class="widget-title"><?php esc_html_e('Categories', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <?php
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                        'number'  => kv_get_theme_option('categories_count', 10),
                        'hide_empty' => true
                    ));
                    
                    if ($categories) : ?>
                        <ul class="categories-list">
                            <?php foreach ($categories as $category) : ?>
                                <li class="category-item">
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-link">
                                        <?php echo esc_html($category->name); ?>
                                        <span class="category-count">(<?php echo esc_html($category->count); ?>)</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p><?php esc_html_e('No categories found.', KV_THEME_TEXTDOMAIN); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Tags cloud widget
        if (kv_get_theme_option('show_tags_cloud', true)) : ?>
            <div class="widget widget-tags">
                <h3 class="widget-title"><?php esc_html_e('Tags', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <?php
                    $tags = get_tags(array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                        'number'  => kv_get_theme_option('tags_count', 20)
                    ));
                    
                    if ($tags) : ?>
                        <div class="tags-cloud">
                            <?php foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                   class="tag-link" 
                                   title="<?php printf(esc_attr__('%d posts tagged with %s', KV_THEME_TEXTDOMAIN), $tag->count, $tag->name); ?>">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p><?php esc_html_e('No tags found.', KV_THEME_TEXTDOMAIN); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Archive widget
        if (kv_get_theme_option('show_archives', true)) : ?>
            <div class="widget widget-archives">
                <h3 class="widget-title"><?php esc_html_e('Archives', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <?php
                    $archives = wp_get_archives(array(
                        'type'      => 'monthly',
                        'limit'     => kv_get_theme_option('archives_count', 12),
                        'echo'      => false,
                        'format'    => 'custom'
                    ));
                    
                    if ($archives) : ?>
                        <ul class="archives-list">
                            <?php echo $archives; ?>
                        </ul>
                    <?php else : ?>
                        <p><?php esc_html_e('No archives found.', KV_THEME_TEXTDOMAIN); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Social media widget
        $social_networks = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'github'];
        $has_social = false;
        
        foreach ($social_networks as $network) {
            if (kv_get_theme_option("social_{$network}", '')) {
                $has_social = true;
                break;
            }
        }
        
        if ($has_social && kv_get_theme_option('show_sidebar_social', true)) : ?>
            <div class="widget widget-social">
                <h3 class="widget-title"><?php esc_html_e('Follow Us', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <div class="social-links">
                        <?php foreach ($social_networks as $network) :
                            $url = kv_get_theme_option("social_{$network}", '');
                            if ($url) : ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="social-link social-<?php echo esc_attr($network); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php printf(esc_attr__('Follow us on %s', KV_THEME_TEXTDOMAIN), ucfirst($network)); ?>">
                                    <i class="fab fa-<?php echo esc_attr($network === 'github' ? 'github' : $network); ?>" aria-hidden="true"></i>
                                    <span class="social-name"><?php echo esc_html(ucfirst($network)); ?></span>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Newsletter signup widget
        if (kv_get_theme_option('show_newsletter_signup', true)) : ?>
            <div class="widget widget-newsletter">
                <h3 class="widget-title"><?php esc_html_e('Newsletter', KV_THEME_TEXTDOMAIN); ?></h3>
                <div class="widget-content">
                    <p class="newsletter-description">
                        <?php echo esc_html(kv_get_theme_option('newsletter_description', __('Subscribe to our newsletter to get the latest updates.', KV_THEME_TEXTDOMAIN))); ?>
                    </p>
                    <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                        <div class="form-group">
                            <label for="newsletter-email" class="sr-only">
                                <?php esc_html_e('Email Address', KV_THEME_TEXTDOMAIN); ?>
                            </label>
                            <input type="email" 
                                   id="newsletter-email" 
                                   name="newsletter_email" 
                                   class="form-control" 
                                   placeholder="<?php esc_attr_e('Enter your email', KV_THEME_TEXTDOMAIN); ?>" 
                                   required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <?php esc_html_e('Subscribe', KV_THEME_TEXTDOMAIN); ?>
                        </button>
                        <input type="hidden" name="action" value="kv_newsletter_signup">
                        <?php wp_nonce_field('kv_newsletter_signup', 'newsletter_nonce'); ?>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Custom HTML widget
        $custom_html = kv_get_theme_option('sidebar_custom_html', '');
        if ($custom_html) : ?>
            <div class="widget widget-custom-html">
                <div class="widget-content">
                    <?php echo wp_kses_post($custom_html); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Primary sidebar area
        if (is_active_sidebar('sidebar-1')) : ?>
            <div class="sidebar-section sidebar-primary">
                <?php dynamic_sidebar('sidebar-1'); ?>
            </div>
        <?php endif; ?>
        
        <?php
        // Secondary sidebar area
        if (is_active_sidebar('sidebar-2')) : ?>
            <div class="sidebar-section sidebar-secondary">
                <?php dynamic_sidebar('sidebar-2'); ?>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php
    // Sticky sidebar elements
    if (kv_get_theme_option('enable_sticky_sidebar', true)) : ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof KVEnterprise !== 'undefined' && KVEnterprise.initStickySidebar) {
                KVEnterprise.initStickySidebar();
            }
        });
        </script>
    <?php endif; ?>
    
</aside><!-- #secondary -->
