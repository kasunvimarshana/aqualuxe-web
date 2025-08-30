<?php /* Hero partial for pages */ ?>
<section class="relative bg-gradient-to-br from-aqua-100/60 to-transparent dark:from-aqua-900/20 py-10">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-extrabold mb-3"><?php the_title(); ?></h1>
    <?php if (has_excerpt()) : ?><p class="opacity-80 max-w-2xl"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>
  </div>
</section>
