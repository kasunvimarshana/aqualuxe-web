<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	/**
	 * Hook: aqualuxe_before_main_content.
	 *
	 * @hooked aqualuxe_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_before_main_content' );
	?>

	<div class="container">
		<div class="content-area">
			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="posts-grid">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;
					?>
				</div><!-- .posts-grid -->

				<?php
				/**
				 * Hook: aqualuxe_pagination.
				 *
				 * @hooked aqualuxe_numeric_pagination - 10
				 */
				do_action( 'aqualuxe_pagination' );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div><!-- .content-area -->

		<?php
		/**
		 * Hook: aqualuxe_sidebar.
		 *
		 * @hooked aqualuxe_get_sidebar - 10
		 */
		do_action( 'aqualuxe_sidebar' );
		?>
	</div><!-- .container -->

	<?php
	/**
	 * Hook: aqualuxe_after_main_content.
	 */
	do_action( 'aqualuxe_after_main_content' );
	?>

</main><!-- #primary -->

<?php
get_footer();