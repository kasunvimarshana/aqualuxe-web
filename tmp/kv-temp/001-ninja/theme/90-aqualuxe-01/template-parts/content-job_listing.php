<?php
/**
 * The template for displaying the Loop for the 'job_listing' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('job-listing-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title"><a href="' . get_permalink() . '">', '</a></h3>'); ?>
    </header>
    <div class="entry-summary">
        <div class="job-location">
            <?php echo get_post_meta(get_the_ID(), '_job_location', true); ?>
        </div>
        <?php the_excerpt(); ?>
    </div>
</article>
