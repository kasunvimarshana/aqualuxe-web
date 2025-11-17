<?php get_header(); ?>
<div class="container mx-auto max-w-screen-xl px-4 py-8">
    <?php if ( function_exists('have_posts') && have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="<?php echo 'post-' . ( function_exists('get_the_ID') ? (int) get_the_ID() : '' ); ?>" <?php if ( function_exists('post_class') ) { post_class( 'prose max-w-none' ); } else { echo 'class="prose max-w-none"'; } ?>>
            <h1 class="entry-title text-3xl font-bold mb-4"><?php echo function_exists('the_title') ? the_title('', '', false) : ''; ?></h1>
            <div class="entry-content"><?php echo function_exists('the_content') ? the_content() : ''; ?></div>
        </article>
    <?php endwhile; else: ?>
        <p><?php echo function_exists('esc_html_e') ? esc_html_e( 'No content found.', 'aqualuxe' ) : 'No content found.'; ?></p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
