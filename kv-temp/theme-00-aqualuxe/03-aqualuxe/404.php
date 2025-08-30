<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <div class="container">
        <div class="content-area">
            
            <section class="error-404 not-found">
                
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?></p>
                    
                    <?php get_search_form(); ?>
                    
                    <?php if (function_exists('the_widget')) : ?>
                        <div class="widget-area">
                            <div class="widget widget_recent_entries">
                                <?php
                                the_widget('WP_Widget_Recent_Posts', array(
                                    'title'  => esc_html__('Most Used Categories', 'aqualuxe'),
                                    'number' => 10,
                                ));
                                ?>
                            </div>
                            
                            <div class="widget widget_categories">
                                <?php
                                the_widget('WP_Widget_Categories', array(
                                    'title'        => esc_html__('Most Used Categories', 'aqualuxe'),
                                    'count'        => 1,
                                    'hierarchical' => 0,
                                    'dropdown'     => 0,
                                ));
                                ?>
                            </div>
                            
                            <div class="widget widget_archive">
                                <?php
                                the_widget('WP_Widget_Archives', array(
                                    'title'    => esc_html__('Archives', 'aqualuxe'),
                                    'count'    => 0,
                                    'dropdown' => 0,
                                ));
                                ?>
                            </div>
                            
                            <div class="widget widget_tag_cloud">
                                <?php
                                the_widget('WP_Widget_Tag_Cloud', array(
                                    'title' => esc_html__('Tag Cloud', 'aqualuxe'),
                                ));
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
            </section>
            
        </div>
    </div>
</main>

<?php
get_footer();