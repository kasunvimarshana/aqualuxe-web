<?php
/**
 * The template for displaying the Loop for the 'care_faq' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('care-faq-item'); ?>>
    <header class="entry-header">
        <h3 class="faq-question"><?php the_title(); ?></h3>
    </header>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
</article>
