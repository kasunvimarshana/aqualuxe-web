<?php
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>
<div class="container mx-auto max-w-screen-xl px-4 py-8">
    <?php while ( have_posts() ) : the_post(); ?>
    <?php echo function_exists('wc_get_template_part') ? call_user_func('wc_get_template_part', 'content', 'single-product' ) : ''; ?>
    <?php endwhile; // end of the loop. ?>
</div>
<?php get_footer( 'shop' ); ?>
