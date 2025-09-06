<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div class="alx-container py-8">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php $pid = function_exists('get_the_ID') ? get_the_ID() : 0; ?>
		<article id="post-<?php echo (int) $pid; ?>" <?php post_class( 'prose max-w-none' ); ?>>
			<h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>
