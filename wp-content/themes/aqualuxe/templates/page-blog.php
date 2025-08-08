<?php
/**
 * Template Name: Blog Page
 *
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
						'after'  => '</div>',
					)
				);
				?>

				<!-- Blog Posts -->
				<section class="blog-posts">
					<div class="blog-posts-grid">
						<?php
						// Get blog posts
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$blog_posts = new WP_Query( array(
							'post_type'      => 'post',
							'posts_per_page' => 6,
							'paged'          => $paged,
						) );

						if ( $blog_posts->have_posts() ) :
							while ( $blog_posts->have_posts() ) :
								$blog_posts->the_post();
								?>
								<div class="blog-post-item">
									<div class="post-thumbnail">
										<a href="<?php the_permalink(); ?>">
											<?php if ( has_post_thumbnail() ) : ?>
												<?php the_post_thumbnail( 'medium' ); ?>
											<?php else : ?>
												<img src="<?php echo esc_url( get_theme_mod( 'default_post_image', '' ) ); ?>" alt="<?php the_title(); ?>">
											<?php endif; ?>
										</a>
									</div>
									<div class="post-content">
										<div class="post-meta">
											<span class="post-date"><?php echo get_the_date(); ?></span>
											<span class="post-author"><?php echo get_the_author(); ?></span>
										</div>
										<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<div class="post-excerpt">
											<?php the_excerpt(); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html__( 'Read More', 'aqualuxe' ); ?></a>
									</div>
								</div>
								<?php
							endwhile;

							// Pagination
							echo '<div class="pagination">';
							echo paginate_links( array(
								'total'     => $blog_posts->max_num_pages,
								'current'   => $paged,
								'prev_text' => '&laquo; ' . __( 'Previous', 'aqualuxe' ),
								'next_text' => __( 'Next', 'aqualuxe' ) . ' &raquo;',
							) );
							echo '</div>';

							wp_reset_postdata();
						else :
							echo '<p>' . esc_html__( 'No blog posts found.', 'aqualuxe' ) . '</p>';
						endif;
						?>
					</div>
				</section>

				<!-- Categories -->
				<section class="blog-categories">
					<h2><?php echo esc_html( get_theme_mod( 'categories_title', __( 'Categories', 'aqualuxe' ) ) ); ?></h2>
					<div class="categories-list">
						<?php
						$categories = get_categories