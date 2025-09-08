<?php
/**
 * Page Template
 *
 * Displays static pages with custom layouts, breadcrumbs,
 * and optional sidebar. Supports full-width and contained layouts.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); 

// Check for custom page layout
$page_layout = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true) ?: 'default';
$show_sidebar = get_post_meta(get_the_ID(), '_aqualuxe_show_sidebar', true) !== 'no';
$show_breadcrumbs = get_post_meta(get_the_ID(), '_aqualuxe_show_breadcrumbs', true) !== 'no';
$custom_header = get_post_meta(get_the_ID(), '_aqualuxe_custom_header', true);
?>

<main id="main" class="site-main <?php echo esc_attr($page_layout === 'full-width' ? 'full-width' : 'contained'); ?>" role="main" aria-label="<?php esc_attr_e('Main content', 'aqualuxe'); ?>">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Custom Header Section -->
        <?php if ($custom_header) : ?>
            <section class="page-hero bg-gradient-to-br from-blue-600 to-blue-800 text-white py-16 mb-8">
                <div class="container mx-auto px-4">
                    <div class="max-w-4xl mx-auto text-center">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4">
                            <?php the_title(); ?>
                        </h1>
                        <?php if (has_excerpt()) : ?>
                            <p class="text-xl md:text-2xl text-blue-100 leading-relaxed">
                                <?php the_excerpt(); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <div class="<?php echo $page_layout === 'full-width' ? 'w-full' : 'container mx-auto px-4'; ?>">
            
            <div class="<?php echo $show_sidebar ? 'grid lg:grid-cols-4 gap-8' : 'max-w-4xl mx-auto'; ?>">
                
                <!-- Main Content -->
                <div class="<?php echo $show_sidebar ? 'lg:col-span-3' : 'w-full'; ?>">
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?> itemscope itemtype="https://schema.org/WebPage">
                        
                        <!-- Breadcrumbs (if not custom header and enabled) -->
                        <?php if (!$custom_header && $show_breadcrumbs && !is_front_page()) : ?>
                            <nav class="breadcrumbs mb-8" aria-label="<?php esc_attr_e('Breadcrumb navigation', 'aqualuxe'); ?>">
                                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400" itemscope itemtype="https://schema.org/BreadcrumbList">
                                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                                            <span itemprop="name"><?php esc_html_e('Home', 'aqualuxe'); ?></span>
                                        </a>
                                        <meta itemprop="position" content="1">
                                    </li>
                                    
                                    <?php
                                    // Get parent pages
                                    $parents = get_post_ancestors(get_the_ID());
                                    $parents = array_reverse($parents);
                                    $position = 2;
                                    
                                    foreach ($parents as $parent_id) :
                                    ?>
                                        <li class="before:content-['›'] before:mx-2 before:text-gray-400" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                            <a href="<?php echo esc_url(get_permalink($parent_id)); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                                                <span itemprop="name"><?php echo esc_html(get_the_title($parent_id)); ?></span>
                                            </a>
                                            <meta itemprop="position" content="<?php echo esc_attr($position++); ?>">
                                        </li>
                                    <?php endforeach; ?>
                                    
                                    <li class="before:content-['›'] before:mx-2 before:text-gray-400" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                        <span class="text-gray-900 dark:text-white" itemprop="name"><?php the_title(); ?></span>
                                        <meta itemprop="position" content="<?php echo esc_attr($position); ?>">
                                    </li>
                                </ol>
                            </nav>
                        <?php endif; ?>
                        
                        <!-- Page Header (if not custom header) -->
                        <?php if (!$custom_header) : ?>
                            <header class="page-header mb-8">
                                <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4 leading-tight" itemprop="name">
                                    <?php the_title(); ?>
                                </h1>
                                
                                <?php if (has_excerpt()) : ?>
                                    <div class="page-excerpt text-xl text-gray-600 dark:text-gray-300 leading-relaxed" itemprop="description">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Featured Image -->
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="page-thumbnail mt-6">
                                        <figure class="relative overflow-hidden rounded-lg">
                                            <?php 
                                            the_post_thumbnail('aqualuxe-hero', [
                                                'class' => 'w-full h-auto object-cover',
                                                'alt' => get_the_title(),
                                                'itemprop' => 'image'
                                            ]); 
                                            ?>
                                            <?php if (get_post_thumbnail_caption()) : ?>
                                                <figcaption class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4 text-sm">
                                                    <?php echo wp_kses_post(get_post_thumbnail_caption()); ?>
                                                </figcaption>
                                            <?php endif; ?>
                                        </figure>
                                    </div>
                                <?php endif; ?>
                            </header>
                        <?php endif; ?>
                        
                        <!-- Page Content -->
                        <div class="page-content prose prose-lg max-w-none dark:prose-invert prose-blue" itemprop="mainContentOfPage">
                            <?php
                            the_content();
                            
                            wp_link_pages([
                                'before' => '<div class="page-links mt-8 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg"><span class="page-links-title font-semibold block mb-2">' . __('Pages:', 'aqualuxe') . '</span>',
                                'after' => '</div>',
                                'link_before' => '<span class="page-number inline-block px-3 py-1 mr-2 mb-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200">',
                                'link_after' => '</span>',
                                'next_or_number' => 'number',
                                'separator' => ''
                            ]);
                            ?>
                        </div>
                        
                        <!-- Page Meta -->
                        <?php if (is_user_logged_in()) : ?>
                            <footer class="page-meta mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                    
                                    <!-- Last Modified -->
                                    <?php if (get_the_modified_date() !== get_the_date()) : ?>
                                        <div class="modified-date flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            <span>
                                                <?php printf(esc_html__('Last updated: %s', 'aqualuxe'), get_the_modified_date()); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Edit Link -->
                                    <?php edit_post_link(
                                        sprintf(
                                            wp_kses(
                                                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                                                ['span' => ['class' => []]]
                                            ),
                                            wp_kses_post(get_the_title())
                                        ),
                                        '<div class="edit-link flex items-center"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                                        '</div>',
                                        get_the_ID(),
                                        'hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200'
                                    ); ?>
                                    
                                </div>
                            </footer>
                        <?php endif; ?>
                        
                        <!-- Schema.org JSON-LD -->
                        <script type="application/ld+json">
                        {
                            "@context": "https://schema.org",
                            "@type": "WebPage",
                            "name": "<?php echo esc_js(get_the_title()); ?>",
                            "description": "<?php echo esc_js(has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30)); ?>",
                            "url": "<?php echo esc_js(get_permalink()); ?>",
                            "mainEntity": {
                                "@type": "Article",
                                "headline": "<?php echo esc_js(get_the_title()); ?>",
                                "datePublished": "<?php echo esc_js(get_the_date('c')); ?>",
                                "dateModified": "<?php echo esc_js(get_the_modified_date('c')); ?>",
                                "author": {
                                    "@type": "Person",
                                    "name": "<?php echo esc_js(get_the_author()); ?>"
                                }
                            },
                            "breadcrumb": {
                                "@type": "BreadcrumbList",
                                "itemListElement": [
                                    {
                                        "@type": "ListItem",
                                        "position": 1,
                                        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
                                        "item": "<?php echo esc_js(home_url()); ?>"
                                    }
                                    <?php
                                    $parents = get_post_ancestors(get_the_ID());
                                    $parents = array_reverse($parents);
                                    $position = 2;
                                    
                                    foreach ($parents as $parent_id) : ?>
                                        ,{
                                            "@type": "ListItem",
                                            "position": <?php echo esc_js($position++); ?>,
                                            "name": "<?php echo esc_js(get_the_title($parent_id)); ?>",
                                            "item": "<?php echo esc_js(get_permalink($parent_id)); ?>"
                                        }
                                    <?php endforeach; ?>
                                    ,{
                                        "@type": "ListItem",
                                        "position": <?php echo esc_js($position); ?>,
                                        "name": "<?php echo esc_js(get_the_title()); ?>",
                                        "item": "<?php echo esc_js(get_permalink()); ?>"
                                    }
                                ]
                            }
                        }
                        </script>
                        
                    </article>
                    
                    <!-- Child Pages -->
                    <?php
                    $child_pages = get_pages([
                        'parent' => get_the_ID(),
                        'sort_column' => 'menu_order',
                        'sort_order' => 'ASC'
                    ]);
                    
                    if ($child_pages) : ?>
                        <section class="child-pages mt-12" aria-labelledby="child-pages-heading">
                            <h2 id="child-pages-heading" class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                                <?php esc_html_e('Explore More', 'aqualuxe'); ?>
                            </h2>
                            
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                <?php foreach ($child_pages as $child_page) : ?>
                                    <article class="child-page bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                                        
                                        <?php if (has_post_thumbnail($child_page->ID)) : ?>
                                            <div class="page-thumbnail">
                                                <a href="<?php echo esc_url(get_permalink($child_page->ID)); ?>">
                                                    <?php echo get_the_post_thumbnail($child_page->ID, 'aqualuxe-gallery', ['class' => 'w-full h-48 object-cover']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="page-content p-6">
                                            <h3 class="page-title text-xl font-semibold mb-3">
                                                <a href="<?php echo esc_url(get_permalink($child_page->ID)); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                                    <?php echo esc_html(get_the_title($child_page->ID)); ?>
                                                </a>
                                            </h3>
                                            
                                            <?php if (has_excerpt($child_page->ID)) : ?>
                                                <div class="page-excerpt text-gray-700 dark:text-gray-300 mb-4">
                                                    <?php echo wp_kses_post(get_the_excerpt($child_page->ID)); ?>
                                                </div>
                                            <?php else : ?>
                                                <div class="page-excerpt text-gray-700 dark:text-gray-300 mb-4">
                                                    <?php echo wp_trim_words(apply_filters('the_content', $child_page->post_content), 20); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo esc_url(get_permalink($child_page->ID)); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <!-- Comments (if enabled) -->
                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                    
                </div>
                
                <!-- Sidebar -->
                <?php if ($show_sidebar) : ?>
                    <aside class="page-sidebar lg:col-span-1" role="complementary" aria-label="<?php esc_attr_e('Page sidebar', 'aqualuxe'); ?>">
                        <?php
                        // Check for custom sidebar
                        $custom_sidebar = get_post_meta(get_the_ID(), '_aqualuxe_custom_sidebar', true);
                        
                        if ($custom_sidebar && is_active_sidebar($custom_sidebar)) {
                            dynamic_sidebar($custom_sidebar);
                        } elseif (is_active_sidebar('page-sidebar')) {
                            dynamic_sidebar('page-sidebar');
                        } else {
                            // Default page sidebar content
                            ?>
                            <div class="widget bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-200 dark:border-gray-700">
                                <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Quick Navigation', 'aqualuxe'); ?>
                                </h3>
                                
                                <!-- Table of Contents (if page has headings) -->
                                <div class="toc-placeholder">
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                                        <?php esc_html_e('Table of contents will be generated automatically based on page headings.', 'aqualuxe'); ?>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if ($child_pages) : ?>
                                <div class="widget bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="widget-title text-lg font-semibold mb-4 text-gray-900 dark:text-white">
                                        <?php esc_html_e('In This Section', 'aqualuxe'); ?>
                                    </h3>
                                    
                                    <ul class="space-y-2">
                                        <?php foreach ($child_pages as $child_page) : ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_permalink($child_page->ID)); ?>" class="block text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                                                    <?php echo esc_html(get_the_title($child_page->ID)); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php
                        }
                        ?>
                    </aside>
                <?php endif; ?>
                
            </div>
            
        </div>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer();
