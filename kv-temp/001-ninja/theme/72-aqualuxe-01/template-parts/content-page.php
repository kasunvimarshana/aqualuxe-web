<article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
  <header class="mb-6"><h1 class="text-3xl font-semibold"><?php the_title(); ?></h1></header>
  <div class="entry-content"><?php the_content(); ?></div>
</article>
