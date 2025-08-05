<?php

/**
 * Main Template File
 *
 * @package AquaLuxe
 */
get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        get_template_part('template-parts/content', get_post_type());
    endwhile;

    the_posts_navigation();
else :
    get_template_part('template-parts/content', 'none');
endif;

get_footer();
