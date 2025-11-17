<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				if ( is_home() && ! is_front_page() ) :
					?>
					<h1 class="page-title"><?php single_post_title(); ?></h1>
					<?php
				else :
					?>
					<h1 class="page-title"><?php esc_html_e( 'Latest Posts', 'aqualuxe' ); ?></h1>
					<?php
				endif;
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
					get_template_part( 'templates/content', get_post_type() );

				endwhile;
				?>
			</div><!-- .posts-grid -->

			<?php
			the_posts_pagination(
				array(
					'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg> <span class="screen-reader-text">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
					'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'aqualuxe' ) . '</span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
				)
			);

		else :

			get_template_part( 'templates/content', 'none' );

		endif;
		?>

	</main><!-- #primary -->

<?php
get_sidebar();
get_footer();