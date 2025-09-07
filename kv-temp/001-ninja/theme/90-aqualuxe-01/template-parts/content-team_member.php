<?php
/**
 * The template for displaying the Loop for the 'team_member' CPT.
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('team-member-item'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="team-member-photo">
            <?php the_post_thumbnail('thumbnail'); ?>
        </div>
    <?php endif; ?>
    <div class="team-member-details">
        <h3 class="team-member-name"><?php the_title(); ?></h3>
        <div class="team-member-position">
            <?php echo get_post_meta(get_the_ID(), '_team_member_position', true); ?>
        </div>
    </div>
</article>
