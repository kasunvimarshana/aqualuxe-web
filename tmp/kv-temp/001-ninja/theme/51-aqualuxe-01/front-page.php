<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

// Check if we're showing the static front page
if (is_front_page() && !is_home()) :
    // Check if the page has content
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            
            // Get the page content
            $content = get_the_content();
            
            // If the page has content, display it
            if (!empty($content)) :
                ?>
                <div class="container">
                    <div class="aqualuxe-page-content">
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php
            endif;
        endwhile;
    endif;
    
    // Display homepage sections
    do_action('aqualuxe_homepage');

// If showing the blog posts index
else :
    get_template_part('index');
endif;

get_footer();