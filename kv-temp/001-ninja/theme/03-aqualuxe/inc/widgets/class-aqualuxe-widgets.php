<?php
/**
 * AquaLuxe Widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Widgets Class
 */
class AquaLuxe_Widgets {
    /**
     * Constructor
     */
    public function __construct() {
        // Register widgets
        add_action( 'widgets_init', array( $this, 'register_widgets' ) );
        
        // Include custom widgets
        $this->includes();
    }

    /**
     * Include custom widgets
     */
    private function includes() {
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-recent-posts-widget.php';
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-social-widget.php';
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-about-widget.php';
        
        // Include WooCommerce widgets if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-featured-products-widget.php';
            require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-product-categories-widget.php';
        }
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget( 'AquaLuxe_Recent_Posts_Widget' );
        register_widget( 'AquaLuxe_Social_Widget' );
        register_widget( 'AquaLuxe_About_Widget' );
        
        // Register WooCommerce widgets if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            register_widget( 'AquaLuxe_Featured_Products_Widget' );
            register_widget( 'AquaLuxe_Product_Categories_Widget' );
        }
    }
}

// Initialize the class
new AquaLuxe_Widgets();

/**
 * Recent Posts Widget
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts',
            esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ),
                'classname'   => 'widget_recent_posts',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        
        $recent_posts = new WP_Query(
            array(
                'posts_per_page'      => $number,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            )
        );
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        if ( $recent_posts->have_posts() ) {
            echo '<ul class="recent-posts">';
            
            while ( $recent_posts->have_posts() ) {
                $recent_posts->the_post();
                
                echo '<li class="recent-post">';
                
                if ( $show_thumbnail && has_post_thumbnail() ) {
                    echo '<div class="post-thumbnail">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    the_post_thumbnail( 'thumbnail' );
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="post-content">';
                echo '<h4 class="post-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h4>';
                
                if ( $show_date ) {
                    echo '<span class="post-date">' . get_the_date() . '</span>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            
            echo '</ul>';
            
            wp_reset_postdata();
        }
        
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'aqualuxe' );
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'aqualuxe' ); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'aqualuxe' ); ?></label>
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
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
        
        return $instance;
    }
}

/**
 * Social Widget
 */
class AquaLuxe_Social_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social',
            esc_html__( 'AquaLuxe: Social Links', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display social media links.', 'aqualuxe' ),
                'classname'   => 'widget_social',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        echo '<div class="social-links">';
        
        if ( $facebook ) {
            echo '<a href="' . esc_url( $facebook ) . '" class="social-link facebook" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i><span class="screen-reader-text">' . esc_html__( 'Facebook', 'aqualuxe' ) . '</span></a>';
        }
        
        if ( $twitter ) {
            echo '<a href="' . esc_url( $twitter ) . '" class="social-link twitter" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter"></i><span class="screen-reader-text">' . esc_html__( 'Twitter', 'aqualuxe' ) . '</span></a>';
        }
        
        if ( $instagram ) {
            echo '<a href="' . esc_url( $instagram ) . '" class="social-link instagram" target="_blank" rel="noopener noreferrer"><i class="fa fa-instagram"></i><span class="screen-reader-text">' . esc_html__( 'Instagram', 'aqualuxe' ) . '</span></a>';
        }
        
        if ( $linkedin ) {
            echo '<a href="' . esc_url( $linkedin ) . '" class="social-link linkedin" target="_blank" rel="noopener noreferrer"><i class="fa fa-linkedin"></i><span class="screen-reader-text">' . esc_html__( 'LinkedIn', 'aqualuxe' ) . '</span></a>';
        }
        
        if ( $youtube ) {
            echo '<a href="' . esc_url( $youtube ) . '" class="social-link youtube" target="_blank" rel="noopener noreferrer"><i class="fa fa-youtube"></i><span class="screen-reader-text">' . esc_html__( 'YouTube', 'aqualuxe' ) . '</span></a>';
        }
        
        if ( $pinterest ) {
            echo '<a href="' . esc_url( $pinterest ) . '" class="social-link pinterest" target="_blank" rel="noopener noreferrer"><i class="fa fa-pinterest"></i><span class="screen-reader-text">' . esc_html__( 'Pinterest', 'aqualuxe' ) . '</span></a>';
        }
        
        echo '</div>';
        
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
        $facebook = isset( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = isset( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = isset( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = isset( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = isset( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = isset( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_url( $facebook ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_url( $twitter ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_url( $instagram ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_url( $linkedin ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_url( $youtube ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="url" value="<?php echo esc_url( $pinterest ); ?>" />
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
        $instance['facebook'] = esc_url_raw( $new_instance['facebook'] );
        $instance['twitter'] = esc_url_raw( $new_instance['twitter'] );
        $instance['instagram'] = esc_url_raw( $new_instance['instagram'] );
        $instance['linkedin'] = esc_url_raw( $new_instance['linkedin'] );
        $instance['youtube'] = esc_url_raw( $new_instance['youtube'] );
        $instance['pinterest'] = esc_url_raw( $new_instance['pinterest'] );
        
        return $instance;
    }
}

/**
 * About Widget
 */
class AquaLuxe_About_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_about',
            esc_html__( 'AquaLuxe: About', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display about information with logo and text.', 'aqualuxe' ),
                'classname'   => 'widget_about',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'About Us', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $logo = ! empty( $instance['logo'] ) ? $instance['logo'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $show_social = isset( $instance['show_social'] ) ? (bool) $instance['show_social'] : true;
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        echo '<div class="about-widget">';
        
        if ( $logo ) {
            echo '<div class="about-logo">';
            echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
            echo '</div>';
        }
        
        if ( $text ) {
            echo '<div class="about-text">';
            echo wp_kses_post( wpautop( $text ) );
            echo '</div>';
        }
        
        if ( $show_social ) {
            echo aqualuxe_get_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        echo '</div>';
        
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'About Us', 'aqualuxe' );
        $logo = isset( $instance['logo'] ) ? $instance['logo'] : '';
        $text = isset( $instance['text'] ) ? $instance['text'] : '';
        $show_social = isset( $instance['show_social'] ) ? (bool) $instance['show_social'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>"><?php esc_html_e( 'Logo URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'logo' ) ); ?>" type="url" value="<?php echo esc_url( $logo ); ?>" />
            <button class="upload_image_button button button-secondary" data-target="#<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>"><?php esc_html_e( 'Upload Image', 'aqualuxe' ); ?></button>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'About Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" rows="5"><?php echo esc_textarea( $text ); ?></textarea>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_social ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_social' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>"><?php esc_html_e( 'Display social links?', 'aqualuxe' ); ?></label>
        </p>
        
        <script>
            jQuery(document).ready(function($) {
                $('.upload_image_button').click(function(e) {
                    e.preventDefault();
                    
                    var target = $(this).data('target');
                    var frame = wp.media({
                        title: '<?php esc_html_e( 'Select or Upload Image', 'aqualuxe' ); ?>',
                        button: {
                            text: '<?php esc_html_e( 'Use this image', 'aqualuxe' ); ?>'
                        },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        $(target).val(attachment.url);
                    });
                    
                    frame.open();
                });
            });
        </script>
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
        $instance['logo'] = esc_url_raw( $new_instance['logo'] );
        $instance['text'] = wp_kses_post( $new_instance['text'] );
        $instance['show_social'] = isset( $new_instance['show_social'] ) ? (bool) $new_instance['show_social'] : false;
        
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
if ( class_exists( 'WooCommerce' ) ) {
    class AquaLuxe_Featured_Products_Widget extends WP_Widget {
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_featured_products',
                esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ),
                array(
                    'description' => esc_html__( 'Display featured products.', 'aqualuxe' ),
                    'classname'   => 'widget_featured_products',
                )
            );
        }

        /**
         * Widget output
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         */
        public function widget( $args, $instance ) {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Products', 'aqualuxe' );
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
            $columns = ! empty( $instance['columns'] ) ? absint( $instance['columns'] ) : 2;
            $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
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
            
            if ( $products->have_posts() ) {
                echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                
                if ( $title ) {
                    echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                
                echo '<div class="featured-products columns-' . esc_attr( $columns ) . '">';
                echo '<ul class="products">';
                
                while ( $products->have_posts() ) {
                    $products->the_post();
                    global $product;
                    
                    echo '<li class="product">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' );
                    echo '<h3>' . get_the_title() . '</h3>';
                    
                    if ( $show_price ) {
                        echo wp_kses_post( $product->get_price_html() );
                    }
                    
                    if ( $show_rating && $product->get_rating_count() > 0 ) {
                        echo wc_get_rating_html( $product->get_average_rating() );
                    }
                    
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
                
                wp_reset_postdata();
                
                echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        /**
         * Widget form
         *
         * @param array $instance Widget instance.
         * @return void
         */
        public function form( $instance ) {
            $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Products', 'aqualuxe' );
            $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
            $columns = isset( $instance['columns'] ) ? absint( $instance['columns'] ) : 2;
            $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
            $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
            </p>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Number of columns:', 'aqualuxe' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>">
                    <option value="1" <?php selected( $columns, 1 ); ?>><?php esc_html_e( '1', 'aqualuxe' ); ?></option>
                    <option value="2" <?php selected( $columns, 2 ); ?>><?php esc_html_e( '2', 'aqualuxe' ); ?></option>
                    <option value="3" <?php selected( $columns, 3 ); ?>><?php esc_html_e( '3', 'aqualuxe' ); ?></option>
                    <option value="4" <?php selected( $columns, 4 ); ?>><?php esc_html_e( '4', 'aqualuxe' ); ?></option>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>" />
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Display product price?', 'aqualuxe' ); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>" />
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating?', 'aqualuxe' ); ?></label>
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
            $instance['columns'] = absint( $new_instance['columns'] );
            $instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : false;
            $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : false;
            
            return $instance;
        }
    }
}

/**
 * Product Categories Widget
 */
if ( class_exists( 'WooCommerce' ) ) {
    class AquaLuxe_Product_Categories_Widget extends WP_Widget {
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_product_categories',
                esc_html__( 'AquaLuxe: Product Categories', 'aqualuxe' ),
                array(
                    'description' => esc_html__( 'Display product categories with thumbnails.', 'aqualuxe' ),
                    'classname'   => 'widget_product_categories',
                )
            );
        }

        /**
         * Widget output
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         */
        public function widget( $args, $instance ) {
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Product Categories', 'aqualuxe' );
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
            $columns = ! empty( $instance['columns'] ) ? absint( $instance['columns'] ) : 2;
            $hide_empty = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
            $show_count = isset( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
            $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
            
            $categories = get_terms(
                array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => $hide_empty,
                    'number'     => $number,
                    'orderby'    => 'name',
                    'order'      => 'ASC',
                )
            );
            
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                
                if ( $title ) {
                    echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                
                echo '<div class="product-categories columns-' . esc_attr( $columns ) . '">';
                echo '<ul>';
                
                foreach ( $categories as $category ) {
                    echo '<li class="product-category">';
                    echo '<a href="' . esc_url( get_term_link( $category ) ) . '">';
                    
                    if ( $show_thumbnail ) {
                        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                        
                        if ( $thumbnail_id ) {
                            echo wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail' );
                        } else {
                            echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" alt="' . esc_attr( $category->name ) . '" />';
                        }
                    }
                    
                    echo '<h3>' . esc_html( $category->name ) . '</h3>';
                    
                    if ( $show_count ) {
                        echo '<span class="count">' . esc_html( $category->count ) . ' ' . esc_html__( 'products', 'aqualuxe' ) . '</span>';
                    }
                    
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
                
                echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        /**
         * Widget form
         *
         * @param array $instance Widget instance.
         * @return void
         */
        public function form( $instance ) {
            $title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Product Categories', 'aqualuxe' );
            $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
            $columns = isset( $instance['columns'] ) ? absint( $instance['columns'] ) : 2;
            $hide_empty = isset( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
            $show_count = isset( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
            $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of categories to show:', 'aqualuxe' ); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
            </p>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Number of columns:', 'aqualuxe' ); ?></label>
                <select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>">
                    <option value="1" <?php selected( $columns, 1 ); ?>><?php esc_html_e( '1', 'aqualuxe' ); ?></option>
                    <option value="2" <?php selected( $columns, 2 ); ?>><?php esc_html_e( '2', 'aqualuxe' ); ?></option>
                    <option value="3" <?php selected( $columns, 3 ); ?>><?php esc_html_e( '3', 'aqualuxe' ); ?></option>
                    <option value="4" <?php selected( $columns, 4 ); ?>><?php esc_html_e( '4', 'aqualuxe' ); ?></option>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $hide_empty ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" />
                <label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Hide empty categories?', 'aqualuxe' ); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $show_count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" />
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Display product counts?', 'aqualuxe' ); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>" />
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display category thumbnails?', 'aqualuxe' ); ?></label>
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
            $instance['columns'] = absint( $new_instance['columns'] );
            $instance['hide_empty'] = isset( $new_instance['hide_empty'] ) ? (bool) $new_instance['hide_empty'] : false;
            $instance['show_count'] = isset( $new_instance['show_count'] ) ? (bool) $new_instance['show_count'] : false;
            $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
            
            return $instance;
        }
    }
}