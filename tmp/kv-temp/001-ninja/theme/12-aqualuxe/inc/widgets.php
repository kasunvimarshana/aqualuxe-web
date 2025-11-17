<?php
/**
 * Custom Widgets for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget( 'AquaLuxe_Recent_Posts_Widget' );
    register_widget( 'AquaLuxe_Featured_Products_Widget' );
    register_widget( 'AquaLuxe_Testimonials_Widget' );
    register_widget( 'AquaLuxe_Contact_Info_Widget' );
    register_widget( 'AquaLuxe_Social_Icons_Widget' );
    register_widget( 'AquaLuxe_Species_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_widgets' );

/**
 * Recent Posts Widget Class
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts', // Base ID
            esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? $instance['show_thumbnail'] : true;
        $show_excerpt = isset( $instance['show_excerpt'] ) ? $instance['show_excerpt'] : false;
        $excerpt_length = ! empty( $instance['excerpt_length'] ) ? absint( $instance['excerpt_length'] ) : 20;

        $recent_posts = wp_get_recent_posts(
            array(
                'numberposts' => $number,
                'post_status' => 'publish',
            )
        );

        if ( ! empty( $recent_posts ) ) {
            echo '<ul class="aqualuxe-recent-posts">';

            foreach ( $recent_posts as $recent ) {
                echo '<li class="recent-post-item">';
                
                if ( $show_thumbnail && has_post_thumbnail( $recent['ID'] ) ) {
                    echo '<div class="post-thumbnail">';
                    echo '<a href="' . esc_url( get_permalink( $recent['ID'] ) ) . '">';
                    echo get_the_post_thumbnail( $recent['ID'], 'thumbnail', array( 'class' => 'img-fluid' ) );
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="post-content">';
                echo '<h4 class="post-title"><a href="' . esc_url( get_permalink( $recent['ID'] ) ) . '">' . esc_html( $recent['post_title'] ) . '</a></h4>';
                
                if ( $show_date ) {
                    echo '<span class="post-date">' . esc_html( get_the_date( '', $recent['ID'] ) ) . '</span>';
                }
                
                if ( $show_excerpt ) {
                    $excerpt = wp_trim_words( get_the_excerpt( $recent['ID'] ), $excerpt_length, '...' );
                    echo '<div class="post-excerpt">' . esc_html( $excerpt ) . '</div>';
                }
                
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        } else {
            echo '<p>' . esc_html__( 'No recent posts found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget'];
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
        $show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = ! empty( $instance['excerpt_length'] ) ? absint( $instance['excerpt_length'] ) : 20;
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
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_excerpt ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Display post excerpt?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt length:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $excerpt_length ); ?>" size="3">
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
        $instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['excerpt_length'] = ( ! empty( $new_instance['excerpt_length'] ) ) ? absint( $new_instance['excerpt_length'] ) : 20;

        return $instance;
    }
}

/**
 * Featured Products Widget Class
 */
class AquaLuxe_Featured_Products_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_featured_products', // Base ID
            esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display featured products.', 'aqualuxe' ) ) // Args
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

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = isset( $instance['show_rating'] ) ? $instance['show_rating'] : true;
        $show_price = isset( $instance['show_price'] ) ? $instance['show_price'] : true;
        $show_button = isset( $instance['show_button'] ) ? $instance['show_button'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';

        $query_args = array(
            'posts_per_page' => $number,
            'post_status'    => 'publish',
            'post_type'      => 'product',
        );

        switch ( $product_type ) {
            case 'featured':
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                        'operator' => 'IN',
                    ),
                );
                break;
            case 'sale':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $query_args['post__in'] = $product_ids_on_sale;
                break;
            case 'best_selling':
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby'] = 'meta_value_num';
                break;
            case 'top_rated':
                $query_args['meta_key'] = '_wc_average_rating';
                $query_args['orderby'] = 'meta_value_num';
                break;
            case 'newest':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
        }

        $products = new WP_Query( $query_args );

        if ( $products->have_posts() ) {
            echo '<ul class="aqualuxe-featured-products">';

            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;

                echo '<li class="product-item">';
                
                echo '<div class="product-thumbnail">';
                echo '<a href="' . esc_url( get_permalink() ) . '">';
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'img-fluid' ) );
                } else {
                    echo wc_placeholder_img( 'woocommerce_thumbnail' );
                }
                echo '</a>';
                echo '</div>';
                
                echo '<div class="product-content">';
                echo '<h4 class="product-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
                
                if ( $show_rating && $product->get_average_rating() > 0 ) {
                    echo wc_get_rating_html( $product->get_average_rating() );
                }
                
                if ( $show_price ) {
                    echo '<span class="product-price">' . $product->get_price_html() . '</span>';
                }
                
                if ( $show_button ) {
                    echo '<div class="product-button">';
                    echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No products found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget'];
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
        $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
        $show_button = isset( $instance['show_button'] ) ? (bool) $instance['show_button'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>"><?php esc_html_e( 'Product Type:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_type' ) ); ?>">
                <option value="featured" <?php selected( $product_type, 'featured' ); ?>><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></option>
                <option value="sale" <?php selected( $product_type, 'sale' ); ?>><?php esc_html_e( 'On Sale Products', 'aqualuxe' ); ?></option>
                <option value="best_selling" <?php selected( $product_type, 'best_selling' ); ?>><?php esc_html_e( 'Best Selling Products', 'aqualuxe' ); ?></option>
                <option value="top_rated" <?php selected( $product_type, 'top_rated' ); ?>><?php esc_html_e( 'Top Rated Products', 'aqualuxe' ); ?></option>
                <option value="newest" <?php selected( $product_type, 'newest' ); ?>><?php esc_html_e( 'Newest Products', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Display product price?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_button ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_button' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>"><?php esc_html_e( 'Display add to cart button?', 'aqualuxe' ); ?></label>
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
        $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : true;
        $instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : true;
        $instance['show_button'] = isset( $new_instance['show_button'] ) ? (bool) $new_instance['show_button'] : true;
        $instance['product_type'] = ( ! empty( $new_instance['product_type'] ) ) ? sanitize_text_field( $new_instance['product_type'] ) : 'featured';

        return $instance;
    }
}

/**
 * Testimonials Widget Class
 */
class AquaLuxe_Testimonials_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_testimonials', // Base ID
            esc_html__( 'AquaLuxe: Testimonials', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display testimonials.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $show_rating = isset( $instance['show_rating'] ) ? $instance['show_rating'] : true;
        $show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
        $order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';

        $query_args = array(
            'post_type'      => 'testimonial',
            'posts_per_page' => $number,
            'orderby'        => $orderby,
            'order'          => $order,
        );

        if ( $category > 0 ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'testimonial_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        }

        $testimonials = new WP_Query( $query_args );

        if ( $testimonials->have_posts() ) {
            echo '<div class="aqualuxe-testimonials">';

            while ( $testimonials->have_posts() ) {
                $testimonials->the_post();
                
                $client_name = get_post_meta( get_the_ID(), '_testimonial_client_name', true );
                $client_company = get_post_meta( get_the_ID(), '_testimonial_client_company', true );
                $client_position = get_post_meta( get_the_ID(), '_testimonial_client_position', true );
                $client_rating = get_post_meta( get_the_ID(), '_testimonial_client_rating', true );
                
                echo '<div class="testimonial-item">';
                
                if ( $show_rating && $client_rating ) {
                    echo '<div class="testimonial-rating">';
                    for ( $i = 1; $i <= 5; $i++ ) {
                        if ( $i <= $client_rating ) {
                            echo '<i class="fas fa-star"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    echo '</div>';
                }
                
                echo '<div class="testimonial-content">';
                the_content();
                echo '</div>';
                
                echo '<div class="testimonial-author">';
                
                if ( $show_image && has_post_thumbnail() ) {
                    echo '<div class="testimonial-author-image">';
                    the_post_thumbnail( 'thumbnail', array( 'class' => 'img-fluid rounded-circle' ) );
                    echo '</div>';
                }
                
                echo '<div class="testimonial-author-info">';
                
                if ( $client_name ) {
                    echo '<h4 class="testimonial-author-name">' . esc_html( $client_name ) . '</h4>';
                }
                
                if ( $client_position && $client_company ) {
                    echo '<p class="testimonial-author-position">' . esc_html( $client_position ) . ', ' . esc_html( $client_company ) . '</p>';
                } elseif ( $client_position ) {
                    echo '<p class="testimonial-author-position">' . esc_html( $client_position ) . '</p>';
                } elseif ( $client_company ) {
                    echo '<p class="testimonial-author-company">' . esc_html( $client_company ) . '</p>';
                }
                
                echo '</div>';
                echo '</div>';
                
                echo '</div>';
            }

            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No testimonials found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Testimonials', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_image = isset( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
        $order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';
        
        // Get testimonial categories
        $categories = get_terms( array(
            'taxonomy'   => 'testimonial_category',
            'hide_empty' => false,
        ) );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of testimonials to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display rating?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display author image?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
                <option value="0"><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                <?php
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                    foreach ( $categories as $cat ) {
                        echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . selected( $category, $cat->term_id, false ) . '>' . esc_html( $cat->name ) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
                <option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Date', 'aqualuxe' ); ?></option>
                <option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Title', 'aqualuxe' ); ?></option>
                <option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php esc_html_e( 'Random', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                <option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'aqualuxe' ); ?></option>
                <option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'aqualuxe' ); ?></option>
            </select>
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
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;
        $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : true;
        $instance['show_image'] = isset( $new_instance['show_image'] ) ? (bool) $new_instance['show_image'] : true;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
        $instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? sanitize_text_field( $new_instance['orderby'] ) : 'date';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';

        return $instance;
    }
}

/**
 * Contact Info Widget Class
 */
class AquaLuxe_Contact_Info_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_contact_info', // Base ID
            esc_html__( 'AquaLuxe: Contact Info', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display contact information.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        $show_icons = isset( $instance['show_icons'] ) ? $instance['show_icons'] : true;

        echo '<div class="aqualuxe-contact-info">';
        
        if ( $address ) {
            echo '<div class="contact-info-item address">';
            if ( $show_icons ) {
                echo '<i class="fas fa-map-marker-alt"></i>';
            }
            echo '<span>' . wp_kses_post( nl2br( $address ) ) . '</span>';
            echo '</div>';
        }
        
        if ( $phone ) {
            echo '<div class="contact-info-item phone">';
            if ( $show_icons ) {
                echo '<i class="fas fa-phone"></i>';
            }
            echo '<span><a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a></span>';
            echo '</div>';
        }
        
        if ( $email ) {
            echo '<div class="contact-info-item email">';
            if ( $show_icons ) {
                echo '<i class="fas fa-envelope"></i>';
            }
            echo '<span><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></span>';
            echo '</div>';
        }
        
        if ( $hours ) {
            echo '<div class="contact-info-item hours">';
            if ( $show_icons ) {
                echo '<i class="fas fa-clock"></i>';
            }
            echo '<span>' . wp_kses_post( nl2br( $hours ) ) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Info', 'aqualuxe' );
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        $show_icons = isset( $instance['show_icons'] ) ? (bool) $instance['show_icons'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>"><?php echo esc_textarea( $address ); ?></textarea>
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
            <textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>"><?php echo esc_textarea( $hours ); ?></textarea>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_icons ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_icons' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_icons' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_icons' ) ); ?>"><?php esc_html_e( 'Display icons?', 'aqualuxe' ); ?></label>
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
        $instance['show_icons'] = isset( $new_instance['show_icons'] ) ? (bool) $new_instance['show_icons'] : true;

        return $instance;
    }
}

/**
 * Social Icons Widget Class
 */
class AquaLuxe_Social_Icons_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_icons', // Base ID
            esc_html__( 'AquaLuxe: Social Icons', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display social media icons.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'rounded';
        $size = ! empty( $instance['size'] ) ? $instance['size'] : 'medium';
        $target = isset( $instance['target_blank'] ) && $instance['target_blank'] ? '_blank' : '_self';

        $classes = 'social-icons';
        $classes .= ' style-' . $style;
        $classes .= ' size-' . $size;

        echo '<div class="' . esc_attr( $classes ) . '">';
        
        if ( $facebook ) {
            echo '<a href="' . esc_url( $facebook ) . '" target="' . esc_attr( $target ) . '" class="social-icon facebook" aria-label="' . esc_attr__( 'Facebook', 'aqualuxe' ) . '"><i class="fab fa-facebook-f"></i></a>';
        }
        
        if ( $twitter ) {
            echo '<a href="' . esc_url( $twitter ) . '" target="' . esc_attr( $target ) . '" class="social-icon twitter" aria-label="' . esc_attr__( 'Twitter', 'aqualuxe' ) . '"><i class="fab fa-twitter"></i></a>';
        }
        
        if ( $instagram ) {
            echo '<a href="' . esc_url( $instagram ) . '" target="' . esc_attr( $target ) . '" class="social-icon instagram" aria-label="' . esc_attr__( 'Instagram', 'aqualuxe' ) . '"><i class="fab fa-instagram"></i></a>';
        }
        
        if ( $linkedin ) {
            echo '<a href="' . esc_url( $linkedin ) . '" target="' . esc_attr( $target ) . '" class="social-icon linkedin" aria-label="' . esc_attr__( 'LinkedIn', 'aqualuxe' ) . '"><i class="fab fa-linkedin-in"></i></a>';
        }
        
        if ( $youtube ) {
            echo '<a href="' . esc_url( $youtube ) . '" target="' . esc_attr( $target ) . '" class="social-icon youtube" aria-label="' . esc_attr__( 'YouTube', 'aqualuxe' ) . '"><i class="fab fa-youtube"></i></a>';
        }
        
        if ( $pinterest ) {
            echo '<a href="' . esc_url( $pinterest ) . '" target="' . esc_attr( $target ) . '" class="social-icon pinterest" aria-label="' . esc_attr__( 'Pinterest', 'aqualuxe' ) . '"><i class="fab fa-pinterest-p"></i></a>';
        }
        
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $style = ! empty( $instance['style'] ) ? $instance['style'] : 'rounded';
        $size = ! empty( $instance['size'] ) ? $instance['size'] : 'medium';
        $target_blank = isset( $instance['target_blank'] ) ? (bool) $instance['target_blank'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_url( $facebook ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_url( $twitter ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_url( $instagram ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_url( $linkedin ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_url( $youtube ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="url" value="<?php echo esc_url( $pinterest ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>">
                <option value="rounded" <?php selected( $style, 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'aqualuxe' ); ?></option>
                <option value="square" <?php selected( $style, 'square' ); ?>><?php esc_html_e( 'Square', 'aqualuxe' ); ?></option>
                <option value="circle" <?php selected( $style, 'circle' ); ?>><?php esc_html_e( 'Circle', 'aqualuxe' ); ?></option>
                <option value="simple" <?php selected( $style, 'simple' ); ?>><?php esc_html_e( 'Simple', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Size:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>">
                <option value="small" <?php selected( $size, 'small' ); ?>><?php esc_html_e( 'Small', 'aqualuxe' ); ?></option>
                <option value="medium" <?php selected( $size, 'medium' ); ?>><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                <option value="large" <?php selected( $size, 'large' ); ?>><?php esc_html_e( 'Large', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $target_blank ); ?> id="<?php echo esc_attr( $this->get_field_id( 'target_blank' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target_blank' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'target_blank' ) ); ?>"><?php esc_html_e( 'Open links in new tab?', 'aqualuxe' ); ?></label>
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
        $instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? esc_url_raw( $new_instance['pinterest'] ) : '';
        $instance['style'] = ( ! empty( $new_instance['style'] ) ) ? sanitize_text_field( $new_instance['style'] ) : 'rounded';
        $instance['size'] = ( ! empty( $new_instance['size'] ) ) ? sanitize_text_field( $new_instance['size'] ) : 'medium';
        $instance['target_blank'] = isset( $new_instance['target_blank'] ) ? (bool) $new_instance['target_blank'] : true;

        return $instance;
    }
}

/**
 * Species Widget Class
 */
class AquaLuxe_Species_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_species', // Base ID
            esc_html__( 'AquaLuxe: Species', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display fish species.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : true;
        $show_scientific_name = isset( $instance['show_scientific_name'] ) ? $instance['show_scientific_name'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
        $order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';

        $query_args = array(
            'post_type'      => 'species',
            'posts_per_page' => $number,
            'orderby'        => $orderby,
            'order'          => $order,
        );

        if ( $category > 0 ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'species_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        }

        $species = new WP_Query( $query_args );

        if ( $species->have_posts() ) {
            echo '<ul class="aqualuxe-species-list">';

            while ( $species->have_posts() ) {
                $species->the_post();
                
                $scientific_name = get_post_meta( get_the_ID(), '_species_scientific_name', true );
                
                echo '<li class="species-item">';
                
                if ( $show_image && has_post_thumbnail() ) {
                    echo '<div class="species-thumbnail">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    the_post_thumbnail( 'thumbnail', array( 'class' => 'img-fluid' ) );
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="species-content">';
                echo '<h4 class="species-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
                
                if ( $show_scientific_name && $scientific_name ) {
                    echo '<p class="species-scientific-name"><em>' . esc_html( $scientific_name ) . '</em></p>';
                }
                
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No species found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Fish Species', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_image = isset( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $show_scientific_name = isset( $instance['show_scientific_name'] ) ? (bool) $instance['show_scientific_name'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $orderby = ! empty( $instance['orderby'] ) ? $instance['orderby'] : 'date';
        $order = ! empty( $instance['order'] ) ? $instance['order'] : 'DESC';
        
        // Get species categories
        $categories = get_terms( array(
            'taxonomy'   => 'species_category',
            'hide_empty' => false,
        ) );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of species to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display image?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_scientific_name ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_scientific_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_scientific_name' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_scientific_name' ) ); ?>"><?php esc_html_e( 'Display scientific name?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
                <option value="0"><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                <?php
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                    foreach ( $categories as $cat ) {
                        echo '<option value="' . esc_attr( $cat->term_id ) . '" ' . selected( $category, $cat->term_id, false ) . '>' . esc_html( $cat->name ) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
                <option value="date" <?php selected( $orderby, 'date' ); ?>><?php esc_html_e( 'Date', 'aqualuxe' ); ?></option>
                <option value="title" <?php selected( $orderby, 'title' ); ?>><?php esc_html_e( 'Title', 'aqualuxe' ); ?></option>
                <option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php esc_html_e( 'Random', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                <option value="DESC" <?php selected( $order, 'DESC' ); ?>><?php esc_html_e( 'Descending', 'aqualuxe' ); ?></option>
                <option value="ASC" <?php selected( $order, 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'aqualuxe' ); ?></option>
            </select>
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
        $instance['show_image'] = isset( $new_instance['show_image'] ) ? (bool) $new_instance['show_image'] : true;
        $instance['show_scientific_name'] = isset( $new_instance['show_scientific_name'] ) ? (bool) $new_instance['show_scientific_name'] : true;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
        $instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? sanitize_text_field( $new_instance['orderby'] ) : 'date';
        $instance['order'] = ( ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC';

        return $instance;
    }
}