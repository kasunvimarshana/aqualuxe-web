<?php
/**
 * Featured Products Widget
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
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
            'aqualuxe_featured_products',
            esc_html__('AquaLuxe: Featured Products', 'aqualuxe'),
            array(
                'description' => esc_html__('Display featured products.', 'aqualuxe'),
                'classname'   => 'widget_featured_products',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Check if WooCommerce is active
        if (!function_exists('aqualuxe_is_woocommerce_active') || !aqualuxe_is_woocommerce_active()) {
            // Display fallback content if WooCommerce is not active
            $this->display_fallback($args, $instance);
            return;
        }

        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        $show_rating = !empty($instance['show_rating']) ? true : false;

        $query_args = array(
            'posts_per_page' => $number,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'meta_query'     => array(
                array(
                    'key'   => '_featured',
                    'value' => 'yes',
                ),
            ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ),
            ),
        );

        $products = new WP_Query($query_args);

        if ($products->have_posts()) {
            echo '<ul class="featured-products-list">';

            while ($products->have_posts()) {
                $products->the_post();
                global $product;

                echo '<li class="featured-product-item mb-4 flex">';
                echo '<div class="featured-product-inner flex">';

                if (has_post_thumbnail()) {
                    echo '<div class="featured-product-thumbnail mr-3">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 object-cover rounded'));
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="featured-product-content">';
                echo '<h4 class="featured-product-title text-base font-medium mb-1"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
                
                // Safely display price
                if (method_exists($product, 'get_price_html')) {
                    echo '<div class="featured-product-price mb-1">' . $product->get_price_html() . '</div>';
                }

                // Safely display rating
                if ($show_rating && method_exists($product, 'get_rating_count') && $product->get_rating_count() > 0) {
                    echo '<div class="featured-product-rating">';
                    if (function_exists('aqualuxe_get_rating_html')) {
                        echo aqualuxe_get_rating_html($product->get_average_rating());
                    } elseif (function_exists('wc_get_rating_html')) {
                        echo wc_get_rating_html($product->get_average_rating());
                    }
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Display fallback content when WooCommerce is not active
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    private function display_fallback($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Display recent posts as fallback
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        
        $recent_posts = wp_get_recent_posts(array(
            'numberposts' => $number,
            'post_status' => 'publish'
        ), OBJECT);

        if (!empty($recent_posts)) {
            echo '<ul class="featured-posts-list">';
            
            foreach ($recent_posts as $post) {
                echo '<li class="featured-post-item mb-4 flex">';
                echo '<div class="featured-post-inner flex">';

                if (has_post_thumbnail($post->ID)) {
                    echo '<div class="featured-post-thumbnail mr-3">';
                    echo '<a href="' . esc_url(get_permalink($post->ID)) . '">';
                    echo get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => 'w-16 h-16 object-cover rounded'));
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="featured-post-content">';
                echo '<h4 class="featured-post-title text-base font-medium mb-1"><a href="' . esc_url(get_permalink($post->ID)) . '">' . esc_html(get_the_title($post->ID)) . '</a></h4>';
                echo '<div class="featured-post-date text-sm text-gray-600">' . get_the_date('', $post->ID) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No featured content available.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Featured Products', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        $show_rating = !empty($instance['show_rating']) ? (bool) $instance['show_rating'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of items to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <?php if (function_exists('aqualuxe_is_woocommerce_active') && aqualuxe_is_woocommerce_active()): ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Display product rating?', 'aqualuxe'); ?></label>
        </p>
        <p class="description">
            <?php esc_html_e('Note: When WooCommerce is not active, this widget will display recent posts instead.', 'aqualuxe'); ?>
        </p>
        <?php else: ?>
        <p class="description">
            <?php esc_html_e('WooCommerce is not active. This widget will display recent posts instead of products.', 'aqualuxe'); ?>
        </p>
        <?php endif; ?>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 4;
        $instance['show_rating'] = (!empty($new_instance['show_rating'])) ? 1 : 0;

        return $instance;
    }
}