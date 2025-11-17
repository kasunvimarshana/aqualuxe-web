<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
				<?php do_action( 'aqualuxe_after_content' ); ?>
			</div><!-- .content-wrapper -->
		</div><!-- .container -->
	</div><!-- #content -->

	<?php do_action( 'aqualuxe_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<div class="site-footer-inner">
				<div class="footer-widgets">
					<div class="footer-widgets-row">
						<?php
						$footer_columns = get_theme_mod( 'footer_columns', 4 );
						for ( $i = 1; $i <= $footer_columns; $i++ ) :
							?>
							<div class="footer-widget-column footer-widget-column-<?php echo esc_attr( $i ); ?>">
								<?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
									<?php dynamic_sidebar( 'footer-' . $i ); ?>
								<?php else : ?>
									<?php if ( 1 === $i ) : ?>
										<div class="widget widget_text">
											<h2 class="widget-title"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h2>
											<div class="textwidget">
												<p><?php esc_html_e( 'Bringing elegance to aquatic life – globally. Premium ornamental fish, aquatic plants, and high-quality aquarium supplies.', 'aqualuxe' ); ?></p>
											</div>
										</div>
									<?php elseif ( 2 === $i ) : ?>
										<div class="widget widget_nav_menu">
											<h2 class="widget-title"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h2>
											<nav class="menu-footer-menu-container">
												<ul id="menu-footer-menu" class="menu">
													<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
													<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'aqualuxe' ); ?></a></li>
													<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
													<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
												</ul>
											</nav>
										</div>
									<?php elseif ( 3 === $i ) : ?>
										<div class="widget widget_text">
											<h2 class="widget-title"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h2>
											<div class="textwidget">
												<p><?php esc_html_e( '123 Aquarium Street', 'aqualuxe' ); ?><br>
												<?php esc_html_e( 'Coral City, Ocean State 12345', 'aqualuxe' ); ?><br>
												<?php esc_html_e( 'Phone:', 'aqualuxe' ); ?> <a href="tel:+11234567890">+1 (123) 456-7890</a><br>
												<?php esc_html_e( 'Email:', 'aqualuxe' ); ?> <a href="mailto:info@aqualuxe.example.com">info@aqualuxe.example.com</a></p>
											</div>
										</div>
									<?php elseif ( 4 === $i ) : ?>
										<div class="widget widget_text">
											<h2 class="widget-title"><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h2>
											<div class="textwidget">
												<p><?php esc_html_e( 'Monday - Friday: 9:00 AM - 6:00 PM', 'aqualuxe' ); ?><br>
												<?php esc_html_e( 'Saturday: 10:00 AM - 5:00 PM', 'aqualuxe' ); ?><br>
												<?php esc_html_e( 'Sunday: Closed', 'aqualuxe' ); ?></p>
											</div>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						<?php endfor; ?>
					</div>
				</div><!-- .footer-widgets -->

				<div class="footer-bottom">
					<div class="footer-bottom-row">
						<div class="footer-copyright">
							<?php
							$footer_text = get_theme_mod( 'footer_text', sprintf( esc_html__( 'Copyright &copy; %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) );
							echo wp_kses_post( str_replace( '{year}', date( 'Y' ), $footer_text ) );
							?>
						</div><!-- .footer-copyright -->

						<div class="footer-social">
							<?php
							$social_platforms = array(
								'facebook'  => array(
									'url'   => get_theme_mod( 'social_facebook' ),
									'label' => esc_html__( 'Facebook', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>',
								),
								'twitter'   => array(
									'url'   => get_theme_mod( 'social_twitter' ),
									'label' => esc_html__( 'Twitter', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>',
								),
								'instagram' => array(
									'url'   => get_theme_mod( 'social_instagram' ),
									'label' => esc_html__( 'Instagram', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0-2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm6.5-.25a1.25 1.25 0 0 1-2.5 0 1.25 1.25 0 0 1 2.5 0zM12 4c-2.474 0-2.878.007-4.029.058-.784.037-1.31.142-1.798.332-.434.168-.747.369-1.08.703a2.89 2.89 0 0 0-.704 1.08c-.19.49-.295 1.015-.331 1.798C4.006 9.075 4 9.461 4 12c0 2.474.007 2.878.058 4.029.037.783.142 1.31.331 1.797.17.435.37.748.702 1.08.337.336.65.537 1.08.703.494.191 1.02.297 1.8.333C9.075 19.994 9.461 20 12 20c2.474 0 2.878-.007 4.029-.058.782-.037 1.309-.142 1.797-.331.433-.169.748-.37 1.08-.702.337-.337.538-.65.704-1.08.19-.493.296-1.02.332-1.8.052-1.104.058-1.49.058-4.029 0-2.474-.007-2.878-.058-4.029-.037-.782-.142-1.31-.332-1.798a2.911 2.911 0 0 0-.703-1.08 2.884 2.884 0 0 0-1.08-.704c-.49-.19-1.016-.295-1.798-.331C14.925 4.006 14.539 4 12 4zm0-2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2z"/></svg>',
								),
								'linkedin'  => array(
									'url'   => get_theme_mod( 'social_linkedin' ),
									'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>',
								),
								'youtube'   => array(
									'url'   => get_theme_mod( 'social_youtube' ),
									'label' => esc_html__( 'YouTube', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>',
								),
								'pinterest' => array(
									'url'   => get_theme_mod( 'social_pinterest' ),
									'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
									'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>',
								),
							);

							// Filter out empty profiles.
							$social_profiles = array_filter( $social_platforms, function( $profile ) {
								return ! empty( $profile['url'] );
							} );

							if ( ! empty( $social_profiles ) ) :
								?>
								<ul class="social-icons-list">
									<?php foreach ( $social_profiles as $platform => $profile ) : ?>
										<li class="social-icons-item social-icons-item-<?php echo esc_attr( $platform ); ?>">
											<a href="<?php echo esc_url( $profile['url'] ); ?>" class="social-icons-link social-icons-link-<?php echo esc_attr( $platform ); ?>" target="_blank" rel="noopener noreferrer">
												<span class="social-icons-icon social-icons-icon-<?php echo esc_attr( $platform ); ?>"><?php echo $profile['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
												<span class="screen-reader-text"><?php echo esc_html( $profile['label'] ); ?></span>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div><!-- .footer-social -->
					</div><!-- .footer-bottom-row -->
				</div><!-- .footer-bottom -->
			</div><!-- .site-footer-inner -->
		</div><!-- .container -->
	</footer><!-- #colophon -->

	<?php do_action( 'aqualuxe_after_footer' ); ?>

	<div id="search-modal" class="search-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
		<div class="search-modal-dialog">
			<div class="search-modal-content">
				<div class="search-modal-header">
					<h2 id="search-modal-title" class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></h2>
					<button type="button" class="search-modal-close" aria-label="<?php esc_attr_e( 'Close', 'aqualuxe' ); ?>">
						<span aria-hidden="true">&times;</span>
						<span class="screen-reader-text"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
					</button>
				</div>
				<div class="search-modal-body">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>

	<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
		<span class="back-to-top-icon">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'aqualuxe' ); ?></span>
	</button>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>