<?php
/**
 * The template for displaying the Loop for the 'contact_inquiry' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('contact-inquiry-item'); ?>>
    <header class="entry-header">
        <?php the_title('<h3 class="entry-title">', '</h3>'); ?>
    </header>
    <div class="entry-content">
        <div class="inquiry-details">
            <strong><?php _e('Name:', 'aqualuxe'); ?></strong> <?php echo get_post_meta(get_the_ID(), '_inquiry_name', true); ?><br>
            <strong><?php _e('Email:', 'aqualuxe'); ?></strong> <?php echo get_post_meta(get_the_ID(), '_inquiry_email', true); ?><br>
            <strong><?php _e('Message:', 'aqualuxe'); ?></strong>
            <p><?php echo get_the_content(); ?></p>
        </div>
    </div>
</article>
