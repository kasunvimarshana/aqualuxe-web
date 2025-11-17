<?php
/**
 * AquaLuxe Widgets Setup
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    // Register custom widgets
    register_widget('AquaLuxe_Recent_Posts_Widget');
    register_widget('AquaLuxe_Social_Widget');
    register_widget('AquaLuxe_Contact_Info_Widget');
    
    // Register WooCommerce widgets if active
    if (aqualuxe_is_woocommerce_active()) {
        register_widget('AquaLuxe_Product_Categories_Widget');
        register_widget('AquaLuxe_Featured_Products_Widget');
    }
}
add_action('widgets_init', 'aqualuxe_register_widgets');

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
            esc_html__('AquaLuxe Recent Posts', 'aqualuxe'),
            [
                'description' => esc_html__('Display recent posts with thumbnails.', 'aqualuxe'),
                'classname'   => 'aqualuxe-recent-posts-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        
        $query_args = [
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ];
        
        $posts = new WP_Query($query_args);
        
        if (!$posts->have_posts()) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="aqualuxe-recent-posts">';
        
        while ($posts->have_posts()) {
            $posts->the_post();
            
            echo '<li class="aqualuxe-recent-post">';
            
            if ($show_thumbnail && has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-recent-post-thumbnail">';
                the_post_thumbnail('thumbnail');
                echo '</a>';
            }
            
            echo '<div class="aqualuxe-recent-post-content">';
            
            echo '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-recent-post-title">' . get_the_title() . '</a>';
            
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
        
        echo $args['after_widget'];
        
        wp_reset_postdata();
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display post thumbnail?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Display post excerpt?', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
        
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
            esc_html__('AquaLuxe Social', 'aqualuxe'),
            [
                'description' => esc_html__('Display social media links.', 'aqualuxe'),
                'classname'   => 'aqualuxe-social-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Follow Us', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
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
            echo '<a href="' . esc_url($facebook) . '" class="aqualuxe-social-link aqualuxe-social-facebook" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('facebook');
            echo '<span class="screen-reader-text">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($twitter) {
            echo '<a href="' . esc_url($twitter) . '" class="aqualuxe-social-link aqualuxe-social-twitter" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('twitter');
            echo '<span class="screen-reader-text">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($instagram) {
            echo '<a href="' . esc_url($instagram) . '" class="aqualuxe-social-link aqualuxe-social-instagram" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('instagram');
            echo '<span class="screen-reader-text">' . esc_html__('Instagram', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($youtube) {
            echo '<a href="' . esc_url($youtube) . '" class="aqualuxe-social-link aqualuxe-social-youtube" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('youtube');
            echo '<span class="screen-reader-text">' . esc_html__('YouTube', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($linkedin) {
            echo '<a href="' . esc_url($linkedin) . '" class="aqualuxe-social-link aqualuxe-social-linkedin" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('linkedin');
            echo '<span class="screen-reader-text">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($pinterest) {
            echo '<a href="' . esc_url($pinterest) . '" class="aqualuxe-social-link aqualuxe-social-pinterest" target="_blank" rel="noopener noreferrer">';
            aqualuxe_svg_icon('pinterest');
            echo '<span class="screen-reader-text">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Follow Us', 'aqualuxe');
        $facebook = isset($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = isset($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = isset($instance['instagram']) ? $instance['instagram'] : '';
        $youtube = isset($instance['youtube']) ? $instance['youtube'] : '';
        $linkedin = isset($instance['linkedin']) ? $instance['linkedin'] : '';
        $pinterest = isset($instance['pinterest']) ? $instance['pinterest'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_html_e('Facebook:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="url" value="<?php echo esc_attr($facebook); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php esc_html_e('Twitter:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="url" value="<?php echo esc_attr($twitter); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php esc_html_e('Instagram:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="url" value="<?php echo esc_attr($instagram); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('YouTube:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="url" value="<?php echo esc_attr($youtube); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php esc_html_e('LinkedIn:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="url" value="<?php echo esc_attr($linkedin); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php esc_html_e('Pinterest:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="url" value="<?php echo esc_attr($pinterest); ?>" />
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['facebook'] = esc_url_raw($new_instance['facebook']);
        $instance['twitter'] = esc_url_raw($new_instance['twitter']);
        $instance['instagram'] = esc_url_raw($new_instance['instagram']);
        $instance['youtube'] = esc_url_raw($new_instance['youtube']);
        $instance['linkedin'] = esc_url_raw($new_instance['linkedin']);
        $instance['pinterest'] = esc_url_raw($new_instance['pinterest']);
        
        return $instance;
    }
}

/**
 * Contact Info Widget
 */
class AquaLuxe_Contact_Info_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_contact_info',
            esc_html__('AquaLuxe Contact Info', 'aqualuxe'),
            [
                'description' => esc_html__('Display contact information.', 'aqualuxe'),
                'classname'   => 'aqualuxe-contact-info-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Contact Info', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
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
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-address">';
            aqualuxe_svg_icon('map-pin');
            echo '<span>' . wp_kses_post($address) . '</span>';
            echo '</div>';
        }
        
        if ($phone) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-phone">';
            aqualuxe_svg_icon('phone');
            echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '">' . esc_html($phone) . '</a>';
            echo '</div>';
        }
        
        if ($email) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-email">';
            aqualuxe_svg_icon('mail');
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            echo '</div>';
        }
        
        if ($hours) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-hours">';
            aqualuxe_svg_icon('clock');
            echo '<span>' . wp_kses_post($hours) . '</span>';
            echo '</div>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Contact Info', 'aqualuxe');
        $address = isset($instance['address']) ? $instance['address'] : '';
        $phone = isset($instance['phone']) ? $instance['phone'] : '';
        $email = isset($instance['email']) ? $instance['email'] : '';
        $hours = isset($instance['hours']) ? $instance['hours'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" rows="3"><?php echo esc_textarea($address); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="email" value="<?php echo esc_attr($email); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('hours')); ?>"><?php esc_html_e('Hours:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('hours')); ?>" name="<?php echo esc_attr($this->get_field_name('hours')); ?>" rows="3"><?php echo esc_textarea($hours); ?></textarea>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['address'] = wp_kses_post($new_instance['address']);
        $instance['phone'] = sanitize_text_field($new_instance['phone']);
        $instance['email'] = sanitize_email($new_instance['email']);
        $instance['hours'] = wp_kses_post($new_instance['hours']);
        
        return $instance;
    }
}

/**
 * Product Categories Widget
 */
class AquaLuxe_Product_Categories_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_product_categories',
            esc_html__('AquaLuxe Product Categories', 'aqualuxe'),
            [
                'description' => esc_html__('Display product categories with thumbnails.', 'aqualuxe'),
                'classname'   => 'aqualuxe-product-categories-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Product Categories', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $hide_empty = isset($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
        
        $query_args = [
            'taxonomy'   => 'product_cat',
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => $hide_empty,
            'number'     => $number,
        ];
        
        $categories = get_terms($query_args);
        
        if (is_wp_error($categories) || empty($categories)) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="aqualuxe-product-categories">';
        
        foreach ($categories as $category) {
            $category_link = get_term_link($category, 'product_cat');
            
            if (is_wp_error($category_link)) {
                continue;
            }
            
            echo '<li class="aqualuxe-product-category">';
            
            if ($show_thumbnail) {
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                
                if ($thumbnail_id) {
                    echo '<a href="' . esc_url($category_link) . '" class="aqualuxe-product-category-thumbnail">';
                    echo wp_get_attachment_image($thumbnail_id, 'thumbnail');
                    echo '</a>';
                }
            }
            
            echo '<div class="aqualuxe-product-category-content">';
            
            echo '<a href="' . esc_url($category_link) . '" class="aqualuxe-product-category-title">' . esc_html($category->name) . '</a>';
            
            if ($show_count) {
                echo '<span class="aqualuxe-product-category-count">' . esc_html($category->count) . ' ' . esc_html__('products', 'aqualuxe') . '</span>';
            }
            
            echo '</div>';
            echo '</li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Product Categories', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $hide_empty = isset($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of categories to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Display product counts?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display category thumbnails?', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($hide_empty); ?> id="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>"><?php esc_html_e('Hide empty categories?', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['show_count'] = isset($new_instance['show_count']) ? (bool) $new_instance['show_count'] : false;
        $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['hide_empty'] = isset($new_instance['hide_empty']) ? (bool) $new_instance['hide_empty'] : false;
        
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
class AquaLuxe_Featured_Products_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_featured_products',
            esc_html__('AquaLuxe Featured Products', 'aqualuxe'),
            [
                'description' => esc_html__('Display featured products.', 'aqualuxe'),
                'classname'   => 'aqualuxe-featured-products-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Featured Products', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        
        $query_args = [
            'posts_per_page' => $number,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'tax_query'      => [
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ],
            ],
        ];
        
        $products = new WP_Query($query_args);
        
        if (!$products->have_posts()) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="aqualuxe-featured-products">';
        
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            
            echo '<li class="aqualuxe-featured-product">';
            
            echo '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-featured-product-thumbnail">';
            echo woocommerce_get_product_thumbnail('thumbnail');
            echo '</a>';
            
            echo '<div class="aqualuxe-featured-product-content">';
            
            echo '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-featured-product-title">' . get_the_title() . '</a>';
            
            echo '<span class="aqualuxe-featured-product-price">' . $product->get_price_html() . '</span>';
            
            if ($show_rating && $product->get_rating_count() > 0) {
                echo wc_get_rating_html($product->get_average_rating());
            }
            
            echo '</div>';
            echo '</li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
        
        wp_reset_postdata();
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Featured Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Display product rating?', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        
        return $instance;
    }
}