<?php
/**
 * The template for displaying care guide archives
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php _e('Fish Care Guides', 'aqualuxe'); ?></h1>
                <div class="archive-description">
                    <p><?php _e('Browse our comprehensive collection of fish care guides to learn how to properly care for your aquatic pets.', 'aqualuxe'); ?></p>
                </div>
            </header><!-- .page-header -->

            <div class="care-guide-filters">
                <form action="<?php echo esc_url(home_url('/')); ?>" method="get" class="care-guide-filter-form">
                    <input type="hidden" name="post_type" value="care_guide">
                    
                    <div class="filter-group">
                        <label for="fish-species-filter"><?php _e('Fish Species:', 'aqualuxe'); ?></label>
                        <?php
                        wp_dropdown_categories(array(
                            'show_option_all' => __('All Species', 'aqualuxe'),
                            'taxonomy' => 'fish_species',
                            'name' => 'fish_species',
                            'id' => 'fish-species-filter',
                            'selected' => isset($_GET['fish_species']) ? $_GET['fish_species'] : 0,
                            'hierarchical' => true,
                            'show_count' => true,
                            'hide_empty' => true,
                        ));
                        ?>
                    </div>
                    
                    <div class="filter-group">
                        <label for="care-category-filter"><?php _e('Care Category:', 'aqualuxe'); ?></label>
                        <?php
                        wp_dropdown_categories(array(
                            'show_option_all' => __('All Categories', 'aqualuxe'),
                            'taxonomy' => 'care_category',
                            'name' => 'care_category',
                            'id' => 'care-category-filter',
                            'selected' => isset($_GET['care_category']) ? $_GET['care_category'] : 0,
                            'hierarchical' => true,
                            'show_count' => true,
                            'hide_empty' => true,
                        ));
                        ?>
                    </div>
                    
                    <div class="filter-group">
                        <label for="difficulty-level-filter"><?php _e('Difficulty Level:', 'aqualuxe'); ?></label>
                        <?php
                        wp_dropdown_categories(array(
                            'show_option_all' => __('All Levels', 'aqualuxe'),
                            'taxonomy' => 'difficulty_level',
                            'name' => 'difficulty_level',
                            'id' => 'difficulty-level-filter',
                            'selected' => isset($_GET['difficulty_level']) ? $_GET['difficulty_level'] : 0,
                            'hierarchical' => true,
                            'show_count' => true,
                            'hide_empty' => true,
                        ));
                        ?>
                    </div>
                    
                    <div class="filter-group filter-submit">
                        <button type="submit" class="button filter-button"><?php _e('Filter Guides', 'aqualuxe'); ?></button>
                        <a href="<?php echo esc_url(get_post_type_archive_link('care_guide')); ?>" class="button reset-button"><?php _e('Reset Filters', 'aqualuxe'); ?></a>
                    </div>
                </form>
            </div>

            <div class="care-guides-grid">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('care-guide-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="care-guide-card-link">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="care-guide-card-image">
                                    <?php the_post_thumbnail('medium'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="care-guide-card-content">
                                <h2 class="care-guide-card-title"><?php the_title(); ?></h2>
                                
                                <div class="care-guide-card-meta">
                                    <?php
                                    // Display difficulty level
                                    $difficulty_terms = get_the_terms(get_the_ID(), 'difficulty_level');
                                    if ($difficulty_terms && !is_wp_error($difficulty_terms)) {
                                        echo '<div class="care-guide-difficulty">';
                                        echo '<span class="meta-label">' . __('Difficulty:', 'aqualuxe') . '</span> ';
                                        echo esc_html($difficulty_terms[0]->name);
                                        echo '</div>';
                                    }
                                    
                                    // Display maintenance level
                                    $maintenance_level = get_post_meta(get_the_ID(), '_maintenance_level', true);
                                    if (!empty($maintenance_level)) {
                                        echo '<div class="care-guide-maintenance">';
                                        echo '<span class="meta-label">' . __('Maintenance:', 'aqualuxe') . '</span> ';
                                        
                                        switch ($maintenance_level) {
                                            case 'low':
                                                _e('Low', 'aqualuxe');
                                                break;
                                            case 'medium':
                                                _e('Medium', 'aqualuxe');
                                                break;
                                            case 'high':
                                                _e('High', 'aqualuxe');
                                                break;
                                            default:
                                                echo esc_html($maintenance_level);
                                        }
                                        
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                                
                                <div class="care-guide-card-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <div class="care-guide-card-footer">
                                    <span class="read-more-link"><?php _e('Read Full Guide', 'aqualuxe'); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'aqualuxe'),
                'next_text' => __('Next', 'aqualuxe'),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
            ));
            ?>

        <?php else : ?>

            <div class="no-results">
                <h2><?php _e('No Care Guides Found', 'aqualuxe'); ?></h2>
                <p><?php _e('We couldn\'t find any care guides matching your criteria. Please try different filters or check back later.', 'aqualuxe'); ?></p>
            </div>

        <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();