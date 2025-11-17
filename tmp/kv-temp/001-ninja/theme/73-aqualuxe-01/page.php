<?php
/**
 * Page template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php
        while (have_posts()):
            the_post();
            
            // Breadcrumbs
            aqualuxe_breadcrumbs();
        ?>
            
            <div class="max-w-6xl mx-auto">
                
                <?php if (has_post_thumbnail()): ?>
                    <div class="page-hero mb-8">
                        <div class="relative h-96 rounded-2xl overflow-hidden">
                            <?php
                            the_post_thumbnail('aqualuxe-hero', [
                                'class' => 'w-full h-full object-cover',
                                'loading' => 'eager'
                            ]);
                            ?>
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                <div class="text-center text-white">
                                    <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                                    <?php if (get_the_excerpt()): ?>
                                        <p class="text-xl opacity-90 max-w-2xl"><?php echo esc_html(get_the_excerpt()); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <header class="page-header text-center mb-12">
                        <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                        <?php if (get_the_excerpt()): ?>
                            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <?php endif; ?>
                    </header>
                <?php endif; ?>
                
                <div class="grid lg:grid-cols-4 gap-8">
                    
                    <!-- Main Content -->
                    <div class="lg:col-span-3">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
                            
                            <div class="entry-content prose prose-lg max-w-none">
                                <?php
                                the_content();
                                
                                wp_link_pages([
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                    'after'  => '</div>',
                                ]);
                                ?>
                            </div><!-- .entry-content -->
                            
                            <?php if (get_edit_post_link()): ?>
                                <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
                                    <?php
                                    edit_post_link(
                                        sprintf(
                                            wp_kses(
                                                /* translators: %s: Name of current post. Only visible to screen readers */
                                                __('Edit <span class="sr-only">"%s"</span>', 'aqualuxe'),
                                                [
                                                    'span' => [
                                                        'class' => [],
                                                    ],
                                                ]
                                            ),
                                            get_the_title()
                                        ),
                                        '<span class="edit-link">',
                                        '</span>'
                                    );
                                    ?>
                                </footer><!-- .entry-footer -->
                            <?php endif; ?>
                            
                        </article><!-- #post-<?php the_ID(); ?> -->
                        
                        <!-- Comments -->
                        <?php
                        if (comments_open() || get_comments_number()):
                            comments_template();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <?php
                        // Check if page has specific sidebar
                        $page_sidebar = get_post_meta(get_the_ID(), '_aqualuxe_sidebar', true);
                        if ($page_sidebar && is_active_sidebar($page_sidebar)) {
                            dynamic_sidebar($page_sidebar);
                        } elseif (is_active_sidebar('page-sidebar')) {
                            dynamic_sidebar('page-sidebar');
                        } else {
                            get_sidebar();
                        }
                        ?>
                    </div>
                    
                </div>
                
                <!-- Related Pages (if any) -->
                <?php
                $related_pages = get_post_meta(get_the_ID(), '_aqualuxe_related_pages', true);
                if ($related_pages && is_array($related_pages)):
                ?>
                    <section class="related-pages mt-16 pt-8 border-t border-gray-200 dark:border-dark-700">
                        <h3 class="text-2xl font-bold mb-8 text-center"><?php esc_html_e('Related Pages', 'aqualuxe'); ?></h3>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php
                            foreach ($related_pages as $page_id):
                                if (get_post_status($page_id) === 'publish'):
                            ?>
                                <div class="related-page card p-6">
                                    <?php if (has_post_thumbnail($page_id)): ?>
                                        <div class="page-thumb mb-4">
                                            <a href="<?php echo esc_url(get_permalink($page_id)); ?>">
                                                <?php echo get_the_post_thumbnail($page_id, 'aqualuxe-card', ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <h4 class="text-lg font-semibold mb-2">
                                        <a href="<?php echo esc_url(get_permalink($page_id)); ?>" class="hover:text-primary-600 transition-colors">
                                            <?php echo esc_html(get_the_title($page_id)); ?>
                                        </a>
                                    </h4>
                                    <?php
                                    $excerpt = get_the_excerpt($page_id);
                                    if ($excerpt):
                                    ?>
                                        <p class="text-gray-600 dark:text-gray-400 mb-4"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url(get_permalink($page_id)); ?>" class="btn btn-outline btn-sm">
                                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                    </a>
                                </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                <!-- Call to Action (if enabled) -->
                <?php
                $show_cta = get_post_meta(get_the_ID(), '_aqualuxe_show_cta', true);
                $cta_title = get_post_meta(get_the_ID(), '_aqualuxe_cta_title', true);
                $cta_text = get_post_meta(get_the_ID(), '_aqualuxe_cta_text', true);
                $cta_button_text = get_post_meta(get_the_ID(), '_aqualuxe_cta_button_text', true);
                $cta_button_url = get_post_meta(get_the_ID(), '_aqualuxe_cta_button_url', true);
                
                if ($show_cta && ($cta_title || $cta_text)):
                ?>
                    <section class="page-cta mt-16 p-8 bg-gradient-to-r from-primary-600 to-secondary-600 rounded-2xl text-white text-center">
                        <?php if ($cta_title): ?>
                            <h3 class="text-3xl font-bold mb-4"><?php echo esc_html($cta_title); ?></h3>
                        <?php endif; ?>
                        <?php if ($cta_text): ?>
                            <p class="text-xl mb-6 opacity-90"><?php echo esc_html($cta_text); ?></p>
                        <?php endif; ?>
                        <?php if ($cta_button_text && $cta_button_url): ?>
                            <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-white btn-lg">
                                <?php echo esc_html($cta_button_text); ?>
                            </a>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>
                
            </div>
            
        <?php
        endwhile;
        ?>
        
    </div>
</main>

<?php
get_footer();
?>
