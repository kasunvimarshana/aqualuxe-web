<?php
/** Template part: listing card */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--listing' ); ?> role="article">
    <a href="<?php the_permalink(); ?>" class="card__link">
        <?php if ( has_post_thumbnail() ) : ?>
            <figure class="card__media"><?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?></figure>
        <?php endif; ?>
        <header class="card__header"><h2 class="card__title"><?php the_title(); ?></h2></header>
        <div class="card__excerpt"><?php the_excerpt(); ?></div>
    </a>
</article>
