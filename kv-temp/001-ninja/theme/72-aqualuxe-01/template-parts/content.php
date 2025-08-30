<article id="post-<?php the_ID(); ?>" <?php post_class('card p-4 bg-white dark:bg-slate-800 rounded-lg'); ?>>
  <header>
    <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  </header>
  <div class="prose dark:prose-invert"><?php the_excerpt(); ?></div>
</article>
