<?php
/**
 * The template for displaying the Loop for the 'consultation_service' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('consultation-service-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-summary">
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Book Consultation', 'aqualuxe'); ?></a>
    </div>
</article>
