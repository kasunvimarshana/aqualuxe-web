<?php
/**
 * AquaLuxe Custom Widgets
 *
 * @package AquaLuxe
 */

/**
 * Register widget area.
 */
function aqualuxe_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'aqualuxe' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Add widgets here to appear in your shop pages.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Custom Recent Posts Widget
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'aqualuxe_recent_posts_widget',
			'description' => 'Display recent posts with thumbnails',
		);
		parent::__construct( 'aqualuxe_recent_posts_widget', 'AquaLuxe Recent Posts', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		
		$recent_posts = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => $number,
			'post_status'    => 'publish',
		) );
		
		if ( $recent_posts->have_posts() ) {
			echo '<ul class="aqualuxe-recent-posts">';
			while ( $recent_posts->have_posts() ) {
				$recent_posts->the_post();
				echo '<li>';
				if ( has_post_thumbnail() ) {
					echo '<div class="recent-post-thumbnail">';
					the_post_thumbnail( 'thumbnail' );
					echo '</div>';
				}
				echo '<div class="recent-post-content">';
				echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				echo '<span class="post-date">' . get_the_date() . '</span>';
				echo '</div>';
				echo '</li>';
			}
			echo '</ul>';
			wp_reset_postdata();
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Recent Posts';
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of posts to show:', 'aqualuxe' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
		return $instance;
	}
}

/**
 * Custom Product Categories Widget
 */
class AquaLuxe_Product_Categories_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'aqualuxe_product_categories_widget',
			'description' => 'Display product categories with thumbnails',
		);
		parent::__construct( 'aqualuxe_product_categories_widget', 'AquaLuxe Product Categories', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 10;
		if ( ! $number ) {
			$number = 10;
		}
		
		$product_categories = get_terms( 'product_cat', array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
			'number'     => $number,
		) );
		
		if ( ! empty( $product_categories ) && is_array( $product_categories ) ) {
			echo '<ul class="aqualuxe-product-categories">';
			foreach ( $product_categories as $category ) {
				echo '<li>';
				echo '<a href="' . get_term_link( $category ) . '">' . $category->name . '</a>';
				echo '<span class="category-count">(' . $category->count . ')</span>';
				echo '</li>';
			}
			echo '</ul>';
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'Product Categories';
		$number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 10;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'aqualuxe' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of categories to show:', 'aqualuxe' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 10;
		return $instance;
	}
}

/**
 * Register custom widgets
 */
function aqualuxe_register_custom_widgets() {
	register_widget( 'AquaLuxe_Recent_Posts_Widget' );
	register_widget( 'AquaLuxe_Product_Categories_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_custom_widgets' );