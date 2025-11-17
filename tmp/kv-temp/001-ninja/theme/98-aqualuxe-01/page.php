<?php
/** Page template */
get_header(); ?>
<div class="container mx-auto px-4 py-8">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class('prose dark:prose-invert'); ?>>
			<h1 class="text-3xl font-semibold mb-4"><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>
