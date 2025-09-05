<article id="<?php echo 'post-' . ( function_exists('get_the_ID') ? (int) get_the_ID() : '' ); ?>" <?php if ( function_exists('post_class') ) { post_class( 'prose max-w-none' ); } else { echo 'class="prose max-w-none"'; } ?>>
    <h2 class="entry-title text-2xl font-semibold mb-2"><a href="<?php echo function_exists('the_permalink') ? the_permalink() : '#'; ?>"><?php echo function_exists('the_title') ? the_title('', '', false) : ''; ?></a></h2>
    <div class="entry-summary"><?php echo function_exists('the_excerpt') ? the_excerpt() : ''; ?></div>
</article>
