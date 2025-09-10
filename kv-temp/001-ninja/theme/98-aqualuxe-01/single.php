<?php
/** Single Post */
get_header(); ?>
<main id="content" class="container mx-auto px-4 py-8">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class('prose dark:prose-invert'); ?>>
			<h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</article>
		<?php comments_template(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
