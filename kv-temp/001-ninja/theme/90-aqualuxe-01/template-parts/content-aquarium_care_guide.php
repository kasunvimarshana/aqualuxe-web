<?php
/**
 * The template for displaying the Loop for the 'aquarium_care_guide' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aquarium-care-guide-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title"><a href="' . get_permalink() . '">', '</a></h3>'); ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div>
</article>
