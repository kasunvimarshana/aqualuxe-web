<?php
/**
 * Custom widgets for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
	register_widget( 'AquaLuxe_Recent_Posts_Widget' );
	register_widget( 'AquaLuxe_Social_Widget' );
	register_widget( 'AquaLuxe_Contact_Info_Widget' );
	
	// Register WooCommerce widgets if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		register_widget( 'AquaLuxe_Featured_Products_Widget' );
		register_widget( 'AquaLuxe_Product_Categories_Widget' );
	}
}
add_action( 'widgets_init', 'aqualuxe_register_widgets' );

/**
 * Recent Posts Widget
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_recent_posts',
			esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-recent-posts',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Recent Posts', 'aqualuxe' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;

		$recent_posts = new WP_Query(
			array(
				'post_type'           => 'post',
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			)
		);

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( $recent_posts->have_posts() ) :
			?>
			<ul class="aqualuxe-recent-posts-list">
				<?php
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					?>
					<li class="aqualuxe-recent-post">
						<?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="aqualuxe-recent-post-thumbnail">
								<?php the_post_thumbnail( 'thumbnail' ); ?>
							</a>
						<?php endif; ?>
						<div class="aqualuxe-recent-post-content">
							<a href="<?php the_permalink(); ?>" class="aqualuxe-recent-post-title"><?php the_title(); ?></a>
							<?php if ( $show_date ) : ?>
								<span class="aqualuxe-recent-post-date"><?php echo esc_html( get_the_date() ); ?></span>
							<?php endif; ?>
						</div>
					</li>
					<?php
				endwhile;
				?>
			</ul>
			<?php
			wp_reset_postdata();
		else :
			esc_html_e( 'No posts found.', 'aqualuxe' );
		endif;

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'aqualuxe' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
		$show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'aqualuxe' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'aqualuxe' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : true;

		return $instance;
	}
}

/**
 * Social Media Widget
 */
class AquaLuxe_Social_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_social',
			esc_html__( 'AquaLuxe: Social Media', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Display social media icons.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-social',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Follow Us', 'aqualuxe' );
		
		// Get social media URLs from theme customizer
		$facebook = get_theme_mod( 'aqualuxe_social_facebook', '' );
		$twitter = get_theme_mod( 'aqualuxe_social_twitter', '' );
		$instagram = get_theme_mod( 'aqualuxe_social_instagram', '' );
		$youtube = get_theme_mod( 'aqualuxe_social_youtube', '' );
		$linkedin = get_theme_mod( 'aqualuxe_social_linkedin', '' );
		$pinterest = get_theme_mod( 'aqualuxe_social_pinterest', '' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="aqualuxe-social-icons">';
		
		if ( $facebook ) {
			echo '<a href="' . esc_url( $facebook ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-facebook" aria-label="' . esc_attr__( 'Facebook', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-facebook" aria-hidden="true" focusable="false"><use xlink:href="#icon-facebook"></use></svg>';
			echo '</a>';
		}
		
		if ( $twitter ) {
			echo '<a href="' . esc_url( $twitter ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-twitter" aria-label="' . esc_attr__( 'Twitter', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-twitter" aria-hidden="true" focusable="false"><use xlink:href="#icon-twitter"></use></svg>';
			echo '</a>';
		}
		
		if ( $instagram ) {
			echo '<a href="' . esc_url( $instagram ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-instagram" aria-label="' . esc_attr__( 'Instagram', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-instagram" aria-hidden="true" focusable="false"><use xlink:href="#icon-instagram"></use></svg>';
			echo '</a>';
		}
		
		if ( $youtube ) {
			echo '<a href="' . esc_url( $youtube ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-youtube" aria-label="' . esc_attr__( 'YouTube', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-youtube" aria-hidden="true" focusable="false"><use xlink:href="#icon-youtube"></use></svg>';
			echo '</a>';
		}
		
		if ( $linkedin ) {
			echo '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-linkedin" aria-label="' . esc_attr__( 'LinkedIn', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-linkedin" aria-hidden="true" focusable="false"><use xlink:href="#icon-linkedin"></use></svg>';
			echo '</a>';
		}
		
		if ( $pinterest ) {
			echo '<a href="' . esc_url( $pinterest ) . '" target="_blank" rel="noopener noreferrer" class="aqualuxe-social-icon aqualuxe-social-pinterest" aria-label="' . esc_attr__( 'Pinterest', 'aqualuxe' ) . '">';
			echo '<svg class="icon icon-pinterest" aria-hidden="true" focusable="false"><use xlink:href="#icon-pinterest"></use></svg>';
			echo '</a>';
		}
		
		echo '</div>';

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<?php esc_html_e( 'Social media URLs are configured in the Theme Customizer under "AquaLuxe Theme Options" > "Footer Settings".', 'aqualuxe' ); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
}

/**
 * Contact Info Widget
 */
class AquaLuxe_Contact_Info_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_contact_info',
			esc_html__( 'AquaLuxe: Contact Info', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Display contact information.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-contact-info',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Contact Us', 'aqualuxe' );
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';
		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$email = ! empty( $instance['email'] ) ? $instance['email'] : '';
		$hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="aqualuxe-contact-info-content">';
		
		if ( $address ) {
			echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-address">';
			echo '<svg class="icon icon-map-marker" aria-hidden="true" focusable="false"><use xlink:href="#icon-map-marker"></use></svg>';
			echo '<span>' . wp_kses_post( nl2br( $address ) ) . '</span>';
			echo '</div>';
		}
		
		if ( $phone ) {
			echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-phone">';
			echo '<svg class="icon icon-phone" aria-hidden="true" focusable="false"><use xlink:href="#icon-phone"></use></svg>';
			echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
			echo '</div>';
		}
		
		if ( $email ) {
			echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-email">';
			echo '<svg class="icon icon-envelope" aria-hidden="true" focusable="false"><use xlink:href="#icon-envelope"></use></svg>';
			echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			echo '</div>';
		}
		
		if ( $hours ) {
			echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-hours">';
			echo '<svg class="icon icon-clock" aria-hidden="true" focusable="false"><use xlink:href="#icon-clock"></use></svg>';
			echo '<span>' . wp_kses_post( nl2br( $hours ) ) . '</span>';
			echo '</div>';
		}
		
		echo '</div>';

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Us', 'aqualuxe' );
		$address = ! empty( $instance['address'] ) ? $instance['address'] : '';
		$phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
		$email = ! empty( $instance['email'] ) ? $instance['email'] : '';
		$hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" rows="3"><?php echo esc_textarea( $address ); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="email" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>"><?php esc_html_e( 'Business Hours:', 'aqualuxe' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>" rows="3"><?php echo esc_textarea( $hours ); ?></textarea>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? sanitize_textarea_field( $new_instance['address'] ) : '';
		$instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
		$instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_email( $new_instance['email'] ) : '';
		$instance['hours'] = ( ! empty( $new_instance['hours'] ) ) ? sanitize_textarea_field( $new_instance['hours'] ) : '';

		return $instance;
	}
}

/**
 * Featured Products Widget
 */
class AquaLuxe_Featured_Products_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_featured_products',
			esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Display featured products.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-featured-products',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Featured Products', 'aqualuxe' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
		$show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $number,
			'tax_query'           => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				),
			),
		);

		$products = new WP_Query( $query_args );

		if ( ! $products->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<ul class="aqualuxe-featured-products-list">';

		while ( $products->have_posts() ) {
			$products->the_post();
			global $product;

			echo '<li class="aqualuxe-featured-product">';
			
			// Product image
			echo '<a href="' . esc_url( get_permalink() ) . '" class="aqualuxe-featured-product-image">';
			echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' );
			echo '</a>';
			
			// Product details
			echo '<div class="aqualuxe-featured-product-content">';
			
			// Title
			echo '<a href="' . esc_url( get_permalink() ) . '" class="aqualuxe-featured-product-title">';
			echo esc_html( get_the_title() );
			echo '</a>';
			
			// Price
			echo '<span class="aqualuxe-featured-product-price">';
			echo wp_kses_post( $product->get_price_html() );
			echo '</span>';
			
			// Rating
			if ( $show_rating && $product->get_average_rating() > 0 ) {
				echo '<div class="aqualuxe-featured-product-rating">';
				echo wc_get_rating_html( $product->get_average_rating() );
				echo '</div>';
			}
			
			echo '</div>';
			echo '</li>';
		}

		echo '</ul>';

		wp_reset_postdata();

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Products', 'aqualuxe' );
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
		$show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating?', 'aqualuxe' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 4;
		$instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : false;

		return $instance;
	}
}

/**
 * Product Categories Widget
 */
class AquaLuxe_Product_Categories_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'aqualuxe_product_categories',
			esc_html__( 'AquaLuxe: Product Categories', 'aqualuxe' ),
			array(
				'description' => esc_html__( 'Display product categories with custom styling.', 'aqualuxe' ),
				'classname'   => 'aqualuxe-product-categories',
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Product Categories', 'aqualuxe' );
		$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
		$show_children_only = isset( $instance['show_children_only'] ) ? (bool) $instance['show_children_only'] : false;
		$hide_empty = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
		$max_depth = ! empty( $instance['max_depth'] ) ? absint( $instance['max_depth'] ) : 0;

		$list_args = array(
			'taxonomy'     => 'product_cat',
			'show_count'   => $count,
			'hierarchical' => $hierarchical,
			'title_li'     => '',
			'hide_empty'   => $hide_empty,
			'max_depth'    => $max_depth,
		);

		// Only display child categories if requested and if on a product category page
		if ( $show_children_only && is_tax( 'product_cat' ) ) {
			$current_cat = get_queried_object();
			
			if ( $current_cat && $current_cat->term_id ) {
				$list_args['child_of'] = $current_cat->term_id;
			}
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<ul class="aqualuxe-product-categories-list">';
		wp_list_categories( apply_filters( 'aqualuxe_product_categories_widget_args', $list_args ) );
		echo '</ul>';

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Product Categories', 'aqualuxe' );
		$count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
		$show_children_only = isset( $instance['show_children_only'] ) ? (bool) $instance['show_children_only'] : false;
		$hide_empty = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
		$max_depth = ! empty( $instance['max_depth'] ) ? absint( $instance['max_depth'] ) : 0;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts?', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $hierarchical ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show hierarchy?', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_children_only ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_children_only' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_children_only' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_children_only' ) ); ?>"><?php esc_html_e( 'Only show children of the current category?', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $hide_empty ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>">
			<label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Hide empty categories?', 'aqualuxe' ); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'max_depth' ) ); ?>"><?php esc_html_e( 'Maximum depth:', 'aqualuxe' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'max_depth' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_depth' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( $max_depth ); ?>" size="3">
			<span class="description"><?php esc_html_e( '(0 for unlimited)', 'aqualuxe' ); ?></span>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['count'] = isset( $new_instance['count'] ) ? (bool) $new_instance['count'] : false;
		$instance['hierarchical'] = isset( $new_instance['hierarchical'] ) ? (bool) $new_instance['hierarchical'] : false;
		$instance['show_children_only'] = isset( $new_instance['show_children_only'] ) ? (bool) $new_instance['show_children_only'] : false;
		$instance['hide_empty'] = isset( $new_instance['hide_empty'] ) ? (bool) $new_instance['hide_empty'] : false;
		$instance['max_depth'] = ( ! empty( $new_instance['max_depth'] ) ) ? absint( $new_instance['max_depth'] ) : 0;

		return $instance;
	}
}