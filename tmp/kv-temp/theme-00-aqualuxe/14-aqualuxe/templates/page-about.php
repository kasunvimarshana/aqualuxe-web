<?php
/**
 * Template Name: About Page
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
				<div class="about-content">
					<div class="about-image">
						<?php
						$about_image = get_theme_mod( 'about_image', '' );
						if ( ! empty( $about_image ) ) :
							echo '<img src="' . esc_url( $about_image ) . '" alt="' . esc_attr__( 'About AquaLuxe', 'aqualuxe' ) . '">';
						endif;
						?>
					</div>
					<div class="about-text">
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</div>

				<!-- Company History -->
				<section class="company-history">
					<h2><?php echo esc_html( get_theme_mod( 'history_title', __( 'Our History', 'aqualuxe' ) ) ); ?></h2>
					<div class="history-content">
						<?php echo wp_kses_post( get_theme_mod( 'history_content', '' ) ); ?>
					</div>
				</section>

				<!-- Team Section -->
				<section class="team-section">
					<h2><?php echo esc_html( get_theme_mod( 'team_title', __( 'Meet Our Team', 'aqualuxe' ) ) ); ?></h2>
					<div class="team-members">
						<?php
						$team_members = get_theme_mod( 'team_members', array() );
						if ( ! empty( $team_members ) ) :
							foreach ( $team_members as $member ) :
								?>
								<div class="team-member">
									<div class="member-image">
										<img src="<?php echo esc_url( $member['image'] ); ?>" alt="<?php echo esc_attr( $member['name'] ); ?>">
									</div>
									<div class="member-info">
										<h3><?php echo esc_html( $member['name'] ); ?></h3>
										<p class="member-title"><?php echo esc_html( $member['title'] ); ?></p>
										<p class="member-bio"><?php echo esc_html( $member['bio'] ); ?></p>
									</div>
								</div>
								<?php
							endforeach;
						endif;
						?>
					</div>
				</section>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php edit_post_link( __( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();