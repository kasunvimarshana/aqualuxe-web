<?php
/** Listing card component */
?>
<article <?php post_class('card listing-card'); ?>>
  <a href="<?php the_permalink(); ?>" class="card__link" aria-label="<?php the_title_attribute(); ?>">
    <?php if (has_post_thumbnail()) : ?>
      <div class="card__media"><?php the_post_thumbnail('medium'); ?></div>
    <?php endif; ?>
    <div class="card__body">
      <h2 class="card__title"><?php the_title(); ?></h2>
      <div class="card__excerpt"><?php the_excerpt(); ?></div>
    </div>
  </a>
</article>
