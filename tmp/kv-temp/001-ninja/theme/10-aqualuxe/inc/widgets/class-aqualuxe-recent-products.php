<?php
/**
 * Recent Products Widget
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe_Recent_Products_Widget class
 *
 * @extends WP_Widget
 */
class AquaLuxe_Recent_Products_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_products',
            esc_html__( 'AquaLuxe Recent Products', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display recent products with thumbnails.', 'aqualuxe' ),
                'classname'   => 'widget_recent_products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args     Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Products', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
        $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'recent';

        // Query arguments
        $query_args = array(
            'posts_per_page' => $number,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'no_found_rows'  => true,
        );

        // Product type
        if ( $product_type === 'featured' ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ),
            );
        } elseif ( $product_type === 'sale' ) {
            $product_ids_on_sale = wc_get_product_ids_on_sale();
            $query_args['post__in'] = $product_ids_on_sale;
        } elseif ( $product_type === 'best_selling' ) {
            $query_args['meta_key'] = 'total_sales';
            $query_args['orderby'] = 'meta_value_num';
        } elseif ( $product_type === 'top_rated' ) {
            $query_args['meta_key'] = '_wc_average_rating';
            $query_args['orderby'] = 'meta_value_num';
        }

        // Get products
        $products = new WP_Query( $query_args );

        if ( ! $products->have_posts() ) {
            return;
        }

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        echo '<ul class="product-list">';

        while ( $products->have_posts() ) {
            $products->the_post();
            $product = wc_get_product( get_the_ID() );

            echo '<li class="product-item">';
            
            if ( $show_thumbnail && has_post_thumbnail() ) {
                echo '<div class="product-thumbnail">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'img-fluid' ) );
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="product-content">';
            
            if ( $show_categories ) {
                echo '<div class="product-categories">';
                echo wc_get_product_category_list( get_the_ID(), ', ', '<span class="product-category">', '</span>' );
                echo '</div>';
            }
            
            echo '<h4 class="product-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h4>';
            
            if ( $show_price ) {
                echo '<div class="product-price">';
                echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</div>';
            }
            
            if ( $show_rating ) {
                echo '<div class="product-rating">';
                echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</div>';
            }
            
            echo '</div>';
            echo '</li>';
        }

        echo '</ul>';

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        wp_reset_postdata();
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Products', 'aqualuxe' );
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
        $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : true;
        $product_type = isset( $instance['product_type'] ) ? $instance['product_type'] : 'recent';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>"><?php esc_html_e( 'Product Type:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_type' ) ); ?>">
                <option value="recent" <?php selected( $product_type, 'recent' ); ?>><?php esc_html_e( 'Recent Products', 'aqualuxe' ); ?></option>
                <option value="featured" <?php selected( $product_type, 'featured' ); ?>><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></option>
                <option value="sale" <?php selected( $product_type, 'sale' ); ?>><?php esc_html_e( 'Sale Products', 'aqualuxe' ); ?></option>
                <option value="best_selling" <?php selected( $product_type, 'best_selling' ); ?>><?php esc_html_e( 'Best Selling Products', 'aqualuxe' ); ?></option>
                <option value="top_rated" <?php selected( $product_type, 'top_rated' ); ?>><?php esc_html_e( 'Top Rated Products', 'aqualuxe' ); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display product thumbnail', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Display product price', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_categories ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_categories' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_categories' ) ); ?>"><?php esc_html_e( 'Display product categories', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = absint( $new_instance['number'] );
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : false;
        $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_categories'] = isset( $new_instance['show_categories'] ) ? (bool) $new_instance['show_categories'] : false;
        $instance['product_type'] = sanitize_text_field( $new_instance['product_type'] );
        return $instance;
    }
}

/**
 * Register widget
 */
function aqualuxe_register_recent_products_widget() {
    register_widget( 'AquaLuxe_Recent_Products_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_recent_products_widget' );