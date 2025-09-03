<?php
/** Generic card component */
?>
<article class="card">
  <?php if (!empty($image)) : ?>
    <div class="card__media"><?php echo $image; ?></div>
  <?php endif; ?>
  <div class="card__body">
    <?php if (!empty($title)) : ?><h3 class="card__title"><?php echo esc_html($title); ?></h3><?php endif; ?>
    <?php if (!empty($content)) : ?><div class="card__content"><?php echo wp_kses_post($content); ?></div><?php endif; ?>
  </div>
</article>
