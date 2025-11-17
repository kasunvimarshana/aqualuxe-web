<?php
/**
 * Main template file.
 *
 * @package Aqualuxe
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main id="primary" class="site-main container" role="main">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
				<header class="entry-header">
					<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
		<?php the_posts_navigation(); ?>
	<?php else : ?>
		<?php get_template_part('templates/parts/content', 'none'); ?>
	<?php endif; ?>

	<?php get_template_part('templates/forms/contact'); ?>
</main>
<?php get_footer();
