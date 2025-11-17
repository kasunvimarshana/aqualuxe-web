<?php /** Generic content card */ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?> role="article">
  <header class="entry-header"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></header>
  <div class="entry-summary"><?php the_excerpt(); ?></div>
</article>
