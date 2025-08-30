<?php
/**
 * Custom widgets for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget('AquaLuxe_Recent_Posts_Widget');
    register_widget('AquaLuxe_Social_Links_Widget');
    register_widget('AquaLuxe_Contact_Info_Widget');
    
    if (aqualuxe_is_woocommerce_active()) {
        register_widget('AquaLuxe_Featured_Products_Widget');
        register_widget('AquaLuxe_Product_Categories_Widget');
        register_widget('AquaLuxe_Product_Filter_Widget');
    }
}
add_action('widgets_init', 'aqualuxe_register_widgets');

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
            __('AquaLuxe: Recent Posts', 'aqualuxe'),
            array(
                'description' => __('Display recent posts with thumbnails.', 'aqualuxe'),
                'classname' => 'aqualuxe-recent-posts',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Recent Posts', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        );
        
        $recent_posts = new WP_Query($query_args);
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        if ($recent_posts->have_posts()) {
            echo '<ul class="aqualuxe-recent-posts-list">';
            
            while ($recent_posts->have_posts()) {
                $recent_posts->the_post();
                
                echo '<li class="aqualuxe-recent-post">';
                
                if ($show_thumbnail && has_post_thumbnail()) {
                    echo '<div class="aqualuxe-recent-post-thumbnail">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="aqualuxe-recent-post-content">';
                echo '<h4 class="aqualuxe-recent-post-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h4>';
                
                if ($show_date) {
                    echo '<span class="aqualuxe-recent-post-date">' . get_the_date() . '</span>';
                }
                
                if ($show_excerpt) {
                    echo '<div class="aqualuxe-recent-post-excerpt">' . wp_trim_words(get_the_excerpt(), 10) . '</div>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            
            echo '</ul>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No recent posts found.', 'aqualuxe') . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display post thumbnail?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Display post excerpt?', 'aqualuxe'); ?></label>
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? 1 : 0;
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? 1 : 0;
        $instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Social Links Widget
 */
class AquaLuxe_Social_Links_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_links',
            __('AquaLuxe: Social Links', 'aqualuxe'),
            array(
                'description' => __('Display social media links.', 'aqualuxe'),
                'classname' => 'aqualuxe-social-links-widget',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Follow Us', 'aqualuxe');
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
        $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<div class="aqualuxe-social-links">';
        
        if ($facebook) {
            echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noopener noreferrer" class="social-link facebook" aria-label="' . esc_attr__('Facebook', 'aqualuxe') . '">';
            echo '<i class="fab fa-facebook-f" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        if ($twitter) {
            echo '<a href="' . esc_url($twitter) . '" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="' . esc_attr__('Twitter', 'aqualuxe') . '">';
            echo '<i class="fab fa-twitter" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        if ($instagram) {
            echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noopener noreferrer" class="social-link instagram" aria-label="' . esc_attr__('Instagram', 'aqualuxe') . '">';
            echo '<i class="fab fa-instagram" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        if ($youtube) {
            echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noopener noreferrer" class="social-link youtube" aria-label="' . esc_attr__('YouTube', 'aqualuxe') . '">';
            echo '<i class="fab fa-youtube" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        if ($linkedin) {
            echo '<a href="' . esc_url($linkedin) . '" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="' . esc_attr__('LinkedIn', 'aqualuxe') . '">';
            echo '<i class="fab fa-linkedin-in" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        if ($pinterest) {
            echo '<a href="' . esc_url($pinterest) . '" target="_blank" rel="noopener noreferrer" class="social-link pinterest" aria-label="' . esc_attr__('Pinterest', 'aqualuxe') . '">';
            echo '<i class="fab fa-pinterest-p" aria-hidden="true"></i>';
            echo '</a>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Follow Us', 'aqualuxe');
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
        $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_html_e('Facebook URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="url" value="<?php echo esc_attr($facebook); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php esc_html_e('Twitter URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="url" value="<?php echo esc_attr($twitter); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php esc_html_e('Instagram URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="url" value="<?php echo esc_attr($instagram); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('YouTube URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="url" value="<?php echo esc_attr($youtube); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php esc_html_e('LinkedIn URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="url" value="<?php echo esc_attr($linkedin); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php esc_html_e('Pinterest URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="url" value="<?php echo esc_attr($pinterest); ?>">
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook'])) ? esc_url_raw($new_instance['facebook']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter'])) ? esc_url_raw($new_instance['twitter']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
        $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
        $instance['linkedin'] = (!empty($new_instance['linkedin'])) ? esc_url_raw($new_instance['linkedin']) : '';
        $instance['pinterest'] = (!empty($new_instance['pinterest'])) ? esc_url_raw($new_instance['pinterest']) : '';
        
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
            __('AquaLuxe: Contact Info', 'aqualuxe'),
            array(
                'description' => __('Display contact information.', 'aqualuxe'),
                'classname' => 'aqualuxe-contact-info-widget',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Contact Us', 'aqualuxe');
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $hours = !empty($instance['hours']) ? $instance['hours'] : '';
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<div class="aqualuxe-contact-info">';
        
        if ($address) {
            echo '<div class="contact-info-item address">';
            echo '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
            echo '<span>' . wp_kses_post($address) . '</span>';
            echo '</div>';
        }
        
        if ($phone) {
            echo '<div class="contact-info-item phone">';
            echo '<i class="fas fa-phone" aria-hidden="true"></i>';
            echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '">' . esc_html($phone) . '</a>';
            echo '</div>';
        }
        
        if ($email) {
            echo '<div class="contact-info-item email">';
            echo '<i class="fas fa-envelope" aria-hidden="true"></i>';
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            echo '</div>';
        }
        
        if ($hours) {
            echo '<div class="contact-info-item hours">';
            echo '<i class="fas fa-clock" aria-hidden="true"></i>';
            echo '<span>' . wp_kses_post($hours) . '</span>';
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
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Contact Us', 'aqualuxe');
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $hours = !empty($instance['hours']) ? $instance['hours'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" rows="3"><?php echo esc_textarea($address); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="email" value="<?php echo esc_attr($email); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('hours')); ?>"><?php esc_html_e('Business Hours:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('hours')); ?>" name="<?php echo esc_attr($this->get_field_name('hours')); ?>" rows="3"><?php echo esc_textarea($hours); ?></textarea>
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['address'] = (!empty($new_instance['address'])) ? wp_kses_post($new_instance['address']) : '';
        $instance['phone'] = (!empty($new_instance['phone'])) ? sanitize_text_field($new_instance['phone']) : '';
        $instance['email'] = (!empty($new_instance['email'])) ? sanitize_email($new_instance['email']) : '';
        $instance['hours'] = (!empty($new_instance['hours'])) ? wp_kses_post($new_instance['hours']) : '';
        
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
if (aqualuxe_is_woocommerce_active()) {
    class AquaLuxe_Featured_Products_Widget extends WP_Widget {
        /**
         * Register widget with WordPress.
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_featured_products',
                __('AquaLuxe: Featured Products', 'aqualuxe'),
                array(
                    'description' => __('Display featured products.', 'aqualuxe'),
                    'classname' => 'aqualuxe-featured-products-widget',
                )
            );
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Featured Products', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 4;
            $columns = !empty($instance['columns']) ? absint($instance['columns']) : 2;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order = !empty($instance['order']) ? $instance['order'] : 'desc';
            $hide_free = !empty($instance['hide_free']) ? (bool) $instance['hide_free'] : false;
            $show_hidden = !empty($instance['show_hidden']) ? (bool) $instance['show_hidden'] : false;
            
            $query_args = array(
                'posts_per_page' => $number,
                'post_status' => 'publish',
                'post_type' => 'product',
                'featured' => true,
                'orderby' => $orderby,
                'order' => $order,
            );
            
            if ($hide_free) {
                $query_args['meta_query'] = array(
                    array(
                        'key' => '_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'DECIMAL',
                    ),
                );
            }
            
            if (!$show_hidden) {
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'exclude-from-catalog',
                        'operator' => 'NOT IN',
                    ),
                );
            }
            
            $products = new WP_Query($query_args);
            
            echo $args['before_widget'];
            
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            
            if ($products->have_posts()) {
                echo '<div class="aqualuxe-featured-products columns-' . esc_attr($columns) . '">';
                echo '<ul class="products">';
                
                while ($products->have_posts()) {
                    $products->the_post();
                    global $product;
                    
                    echo '<li class="product">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="product-link">';
                    
                    // Product thumbnail
                    echo '<div class="product-thumbnail">';
                    echo woocommerce_get_product_thumbnail();
                    echo '</div>';
                    
                    // Product title
                    echo '<h3 class="product-title">' . get_the_title() . '</h3>';
                    
                    // Product price
                    echo '<div class="product-price">';
                    echo $product->get_price_html();
                    echo '</div>';
                    
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
                
                wp_reset_postdata();
            } else {
                echo '<p>' . __('No featured products found.', 'aqualuxe') . '</p>';
            }
            
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Featured Products', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 4;
            $columns = !empty($instance['columns']) ? absint($instance['columns']) : 2;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order = !empty($instance['order']) ? $instance['order'] : 'desc';
            $hide_free = !empty($instance['hide_free']) ? (bool) $instance['hide_free'] : false;
            $show_hidden = !empty($instance['show_hidden']) ? (bool) $instance['show_hidden'] : false;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                    <option value="1" <?php selected($columns, 1); ?>><?php esc_html_e('1', 'aqualuxe'); ?></option>
                    <option value="2" <?php selected($columns, 2); ?>><?php esc_html_e('2', 'aqualuxe'); ?></option>
                    <option value="3" <?php selected($columns, 3); ?>><?php esc_html_e('3', 'aqualuxe'); ?></option>
                    <option value="4" <?php selected($columns, 4); ?>><?php esc_html_e('4', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                    <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                    <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Price', 'aqualuxe'); ?></option>
                    <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
                    <option value="sales" <?php selected($orderby, 'sales'); ?>><?php esc_html_e('Sales', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                    <option value="asc" <?php selected($order, 'asc'); ?>><?php esc_html_e('ASC', 'aqualuxe'); ?></option>
                    <option value="desc" <?php selected($order, 'desc'); ?>><?php esc_html_e('DESC', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hide_free); ?> id="<?php echo esc_attr($this->get_field_id('hide_free')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_free')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hide_free')); ?>"><?php esc_html_e('Hide free products?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_hidden); ?> id="<?php echo esc_attr($this->get_field_id('show_hidden')); ?>" name="<?php echo esc_attr($this->get_field_name('show_hidden')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_hidden')); ?>"><?php esc_html_e('Show hidden products?', 'aqualuxe'); ?></label>
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
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 4;
            $instance['columns'] = (!empty($new_instance['columns'])) ? absint($new_instance['columns']) : 2;
            $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'date';
            $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'desc';
            $instance['hide_free'] = (!empty($new_instance['hide_free'])) ? 1 : 0;
            $instance['show_hidden'] = (!empty($new_instance['show_hidden'])) ? 1 : 0;
            
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
                __('AquaLuxe: Product Categories', 'aqualuxe'),
                array(
                    'description' => __('Display product categories with thumbnails.', 'aqualuxe'),
                    'classname' => 'aqualuxe-product-categories-widget',
                )
            );
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Product Categories', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 5;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
            $order = !empty($instance['order']) ? $instance['order'] : 'asc';
            $hide_empty = !empty($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
            $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : true;
            $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
            $hierarchical = !empty($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
            
            $cat_args = array(
                'orderby' => $orderby,
                'order' => $order,
                'hide_empty' => $hide_empty,
                'number' => $number,
                'hierarchical' => $hierarchical,
                'taxonomy' => 'product_cat',
            );
            
            $categories = get_terms($cat_args);
            
            echo $args['before_widget'];
            
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            
            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<ul class="aqualuxe-product-categories">';
                
                foreach ($categories as $category) {
                    echo '<li class="product-category">';
                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="category-link">';
                    
                    if ($show_thumbnail) {
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        
                        if ($thumbnail_id) {
                            echo '<div class="category-thumbnail">';
                            echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
                            echo '</div>';
                        }
                    }
                    
                    echo '<div class="category-info">';
                    echo '<h4 class="category-name">' . esc_html($category->name) . '</h4>';
                    
                    if ($show_count) {
                        echo '<span class="category-count">' . esc_html($category->count) . ' ' . esc_html(_n('product', 'products', $category->count, 'aqualuxe')) . '</span>';
                    }
                    
                    echo '</div>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
            } else {
                echo '<p>' . __('No product categories found.', 'aqualuxe') . '</p>';
            }
            
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Product Categories', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 5;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
            $order = !empty($instance['order']) ? $instance['order'] : 'asc';
            $hide_empty = !empty($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
            $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : true;
            $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
            $hierarchical = !empty($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of categories to show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                    <option value="name" <?php selected($orderby, 'name'); ?>><?php esc_html_e('Name', 'aqualuxe'); ?></option>
                    <option value="count" <?php selected($orderby, 'count'); ?>><?php esc_html_e('Count', 'aqualuxe'); ?></option>
                    <option value="id" <?php selected($orderby, 'id'); ?>><?php esc_html_e('ID', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                    <option value="asc" <?php selected($order, 'asc'); ?>><?php esc_html_e('ASC', 'aqualuxe'); ?></option>
                    <option value="desc" <?php selected($order, 'desc'); ?>><?php esc_html_e('DESC', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hide_empty); ?> id="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>"><?php esc_html_e('Hide empty categories?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show product counts?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Show category thumbnails?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hierarchical); ?> id="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>" name="<?php echo esc_attr($this->get_field_name('hierarchical')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>"><?php esc_html_e('Show hierarchy?', 'aqualuxe'); ?></label>
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
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
            $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'name';
            $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'asc';
            $instance['hide_empty'] = (!empty($new_instance['hide_empty'])) ? 1 : 0;
            $instance['show_count'] = (!empty($new_instance['show_count'])) ? 1 : 0;
            $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? 1 : 0;
            $instance['hierarchical'] = (!empty($new_instance['hierarchical'])) ? 1 : 0;
            
            return $instance;
        }
    }

    /**
     * Product Filter Widget
     */
    class AquaLuxe_Product_Filter_Widget extends WP_Widget {
        /**
         * Register widget with WordPress.
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_product_filter',
                __('AquaLuxe: Product Filter', 'aqualuxe'),
                array(
                    'description' => __('Display product filters.', 'aqualuxe'),
                    'classname' => 'aqualuxe-product-filter-widget',
                )
            );
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            if (!is_shop() && !is_product_category() && !is_product_tag()) {
                return;
            }
            
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Filter Products', 'aqualuxe');
            $show_categories = !empty($instance['show_categories']) ? (bool) $instance['show_categories'] : true;
            $show_price = !empty($instance['show_price']) ? (bool) $instance['show_price'] : true;
            $show_attributes = !empty($instance['show_attributes']) ? (bool) $instance['show_attributes'] : true;
            $show_rating = !empty($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
            
            echo $args['before_widget'];
            
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            
            echo '<div class="aqualuxe-product-filter">';
            
            // Categories filter
            if ($show_categories) {
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                ));
                
                if (!empty($categories) && !is_wp_error($categories)) {
                    echo '<div class="filter-section filter-categories">';
                    echo '<h4>' . esc_html__('Categories', 'aqualuxe') . '</h4>';
                    echo '<ul>';
                    
                    foreach ($categories as $category) {
                        echo '<li>';
                        echo '<label>';
                        echo '<input type="checkbox" name="product_cat" value="' . esc_attr($category->slug) . '" data-filter="category" data-value="' . esc_attr($category->slug) . '">';
                        echo esc_html($category->name);
                        echo '</label>';
                        echo '</li>';
                    }
                    
                    echo '</ul>';
                    echo '</div>';
                }
            }
            
            // Price filter
            if ($show_price) {
                echo '<div class="filter-section filter-price">';
                echo '<h4>' . esc_html__('Price', 'aqualuxe') . '</h4>';
                echo '<div class="price-slider-wrapper">';
                echo '<div class="price-slider"></div>';
                echo '<div class="price-slider-amount">';
                echo '<input type="text" id="min_price" name="min_price" value="0" data-min="0" placeholder="' . esc_attr__('Min price', 'aqualuxe') . '" />';
                echo '<input type="text" id="max_price" name="max_price" value="100" data-max="1000" placeholder="' . esc_attr__('Max price', 'aqualuxe') . '" />';
                echo '<button type="button" class="button">' . esc_html__('Filter', 'aqualuxe') . '</button>';
                echo '<div class="price-label">' . esc_html__('Price:', 'aqualuxe') . ' <span class="from"></span> &mdash; <span class="to"></span></div>';
                echo '<div class="clear"></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            // Attributes filter
            if ($show_attributes) {
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                
                if (!empty($attribute_taxonomies)) {
                    foreach ($attribute_taxonomies as $attribute) {
                        $taxonomy = 'pa_' . $attribute->attribute_name;
                        $terms = get_terms(array(
                            'taxonomy' => $taxonomy,
                            'hide_empty' => true,
                        ));
                        
                        if (!empty($terms) && !is_wp_error($terms)) {
                            echo '<div class="filter-section filter-attribute filter-' . esc_attr($attribute->attribute_name) . '">';
                            echo '<h4>' . esc_html($attribute->attribute_label) . '</h4>';
                            echo '<ul>';
                            
                            foreach ($terms as $term) {
                                echo '<li>';
                                echo '<label>';
                                echo '<input type="checkbox" name="' . esc_attr($taxonomy) . '" value="' . esc_attr($term->slug) . '" data-filter="attribute" data-taxonomy="' . esc_attr($taxonomy) . '" data-value="' . esc_attr($term->slug) . '">';
                                echo esc_html($term->name);
                                echo '</label>';
                                echo '</li>';
                            }
                            
                            echo '</ul>';
                            echo '</div>';
                        }
                    }
                }
            }
            
            // Rating filter
            if ($show_rating) {
                echo '<div class="filter-section filter-rating">';
                echo '<h4>' . esc_html__('Rating', 'aqualuxe') . '</h4>';
                echo '<ul>';
                
                for ($rating = 5; $rating >= 1; $rating--) {
                    echo '<li>';
                    echo '<label>';
                    echo '<input type="checkbox" name="rating" value="' . esc_attr($rating) . '" data-filter="rating" data-value="' . esc_attr($rating) . '">';
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<span class="star star-filled"></span>';
                        } else {
                            echo '<span class="star star-empty"></span>';
                        }
                    }
                    
                    echo ' ' . esc_html__('and up', 'aqualuxe');
                    echo '</label>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
            
            // Apply filters button
            echo '<div class="filter-actions">';
            echo '<button type="button" class="button apply-filters">' . esc_html__('Apply Filters', 'aqualuxe') . '</button>';
            echo '<button type="button" class="button reset-filters">' . esc_html__('Reset', 'aqualuxe') . '</button>';
            echo '</div>';
            
            echo '</div>';
            
            echo $args['after_widget'];
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Filter Products', 'aqualuxe');
            $show_categories = !empty($instance['show_categories']) ? (bool) $instance['show_categories'] : true;
            $show_price = !empty($instance['show_price']) ? (bool) $instance['show_price'] : true;
            $show_attributes = !empty($instance['show_attributes']) ? (bool) $instance['show_attributes'] : true;
            $show_rating = !empty($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories filter?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price filter?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_attributes); ?> id="<?php echo esc_attr($this->get_field_id('show_attributes')); ?>" name="<?php echo esc_attr($this->get_field_name('show_attributes')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_attributes')); ?>"><?php esc_html_e('Show attributes filter?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating filter?', 'aqualuxe'); ?></label>
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
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['show_categories'] = (!empty($new_instance['show_categories'])) ? 1 : 0;
            $instance['show_price'] = (!empty($new_instance['show_price'])) ? 1 : 0;
            $instance['show_attributes'] = (!empty($new_instance['show_attributes'])) ? 1 : 0;
            $instance['show_rating'] = (!empty($new_instance['show_rating'])) ? 1 : 0;
            
            return $instance;
        }
    }
}