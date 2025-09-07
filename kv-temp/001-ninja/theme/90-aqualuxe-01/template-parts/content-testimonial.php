<?php
/**
 * The template for displaying the Loop for the 'testimonial' CPT.
 *
 * @package AquaLuxe
 */
?>

<blockquote id="post-<?php the_ID(); ?>" <?php post_class('testimonial-item'); ?>>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
    <footer class="entry-footer">
        <cite><?php the_title(); ?></cite>
    </footer>
</blockquote>
