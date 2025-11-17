<?php
/**
 * Custom widgets for this theme
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
    register_widget('AquaLuxe_Social_Widget');
    register_widget('AquaLuxe_Contact_Info_Widget');
    
    if (aqualuxe_is_woocommerce_active()) {
        register_widget('AquaLuxe_Featured_Products_Widget');
        register_widget('AquaLuxe_Product_Categories_Widget');
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
            array(
                'description' => esc_html__('Display recent posts with thumbnails.', 'aqualuxe'),
                'classname' => 'aqualuxe-recent-posts',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Recent Posts', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 25;
        
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        );
        
        $recent_posts = new WP_Query($query_args);
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        if ($recent_posts->have_posts()) {
            echo '<ul class="aqualuxe-recent-posts-list">';
            
            while ($recent_posts->have_posts()) {
                $recent_posts->the_post();
                
                echo '<li class="aqualuxe-recent-post-item">';
                
                if ($show_thumbnail && has_post_thumbnail()) {
                    echo '<div class="aqualuxe-recent-post-thumbnail">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('thumbnail', array('class' => 'rounded-lg'));
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="aqualuxe-recent-post-content">';
                echo '<h4 class="aqualuxe-recent-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
                
                if ($show_date) {
                    echo '<div class="aqualuxe-recent-post-date">' . esc_html(get_the_date()) . '</div>';
                }
                
                if ($show_excerpt) {
                    $excerpt = wp_trim_words(get_the_excerpt(), $excerpt_length, '...');
                    echo '<div class="aqualuxe-recent-post-excerpt">' . esc_html($excerpt) . '</div>';
                }
                
                echo '</div>';
                echo '</li>';
            }
            
            echo '</ul>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No recent posts found.', 'aqualuxe') . '</p>';
        }
        
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 25;
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
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt length:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($excerpt_length); ?>" size="3">
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? (bool) $new_instance['show_thumbnail'] : true;
        $instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['excerpt_length'] = (!empty($new_instance['excerpt_length'])) ? absint($new_instance['excerpt_length']) : 25;
        
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
            array(
                'description' => esc_html__('Display social media icons.', 'aqualuxe'),
                'classname' => 'aqualuxe-social',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Follow Us', 'aqualuxe');
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : 'medium';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'default';
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        echo '<div class="aqualuxe-social-icons aqualuxe-social-icons-' . esc_attr($icon_size) . ' aqualuxe-social-icons-' . esc_attr($icon_style) . '">';
        
        if ($facebook) {
            echo '<a href="' . esc_url($facebook) . '" class="aqualuxe-social-icon aqualuxe-social-icon-facebook" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-facebook-f" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($twitter) {
            echo '<a href="' . esc_url($twitter) . '" class="aqualuxe-social-icon aqualuxe-social-icon-twitter" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-twitter" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($instagram) {
            echo '<a href="' . esc_url($instagram) . '" class="aqualuxe-social-icon aqualuxe-social-icon-instagram" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-instagram" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('Instagram', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($linkedin) {
            echo '<a href="' . esc_url($linkedin) . '" class="aqualuxe-social-icon aqualuxe-social-icon-linkedin" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-linkedin-in" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($youtube) {
            echo '<a href="' . esc_url($youtube) . '" class="aqualuxe-social-icon aqualuxe-social-icon-youtube" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-youtube" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('YouTube', 'aqualuxe') . '</span>';
            echo '</a>';
        }
        
        if ($pinterest) {
            echo '<a href="' . esc_url($pinterest) . '" class="aqualuxe-social-icon aqualuxe-social-icon-pinterest" target="_blank" rel="noopener noreferrer">';
            echo '<i class="fab fa-pinterest-p" aria-hidden="true"></i>';
            echo '<span class="screen-reader-text">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
            echo '</a>';
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
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Follow Us', 'aqualuxe');
        $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
        $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
        $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
        $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
        $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
        $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
        $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : 'medium';
        $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'default';
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
            <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php esc_html_e('LinkedIn URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="url" value="<?php echo esc_attr($linkedin); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('YouTube URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="url" value="<?php echo esc_attr($youtube); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php esc_html_e('Pinterest URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="url" value="<?php echo esc_attr($pinterest); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_size')); ?>"><?php esc_html_e('Icon Size:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_size')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_size')); ?>">
                <option value="small" <?php selected($icon_size, 'small'); ?>><?php esc_html_e('Small', 'aqualuxe'); ?></option>
                <option value="medium" <?php selected($icon_size, 'medium'); ?>><?php esc_html_e('Medium', 'aqualuxe'); ?></option>
                <option value="large" <?php selected($icon_size, 'large'); ?>><?php esc_html_e('Large', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('icon_style')); ?>"><?php esc_html_e('Icon Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_style')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_style')); ?>">
                <option value="default" <?php selected($icon_style, 'default'); ?>><?php esc_html_e('Default', 'aqualuxe'); ?></option>
                <option value="rounded" <?php selected($icon_style, 'rounded'); ?>><?php esc_html_e('Rounded', 'aqualuxe'); ?></option>
                <option value="circle" <?php selected($icon_style, 'circle'); ?>><?php esc_html_e('Circle', 'aqualuxe'); ?></option>
            </select>
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook'])) ? esc_url_raw($new_instance['facebook']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter'])) ? esc_url_raw($new_instance['twitter']) : '';
        $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
        $instance['linkedin'] = (!empty($new_instance['linkedin'])) ? esc_url_raw($new_instance['linkedin']) : '';
        $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
        $instance['pinterest'] = (!empty($new_instance['pinterest'])) ? esc_url_raw($new_instance['pinterest']) : '';
        $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? sanitize_text_field($new_instance['icon_size']) : 'medium';
        $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? sanitize_text_field($new_instance['icon_style']) : 'default';
        
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
            array(
                'description' => esc_html__('Display contact information.', 'aqualuxe'),
                'classname' => 'aqualuxe-contact-info',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Contact Info', 'aqualuxe');
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $hours = !empty($instance['hours']) ? $instance['hours'] : '';
        $show_icons = !empty($instance['show_icons']) ? (bool) $instance['show_icons'] : true;
        
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        
        echo '<div class="aqualuxe-contact-info-content">';
        
        if ($address) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-info-address">';
            if ($show_icons) {
                echo '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
            }
            echo '<span class="aqualuxe-contact-info-text">' . wp_kses_post($address) . '</span>';
            echo '</div>';
        }
        
        if ($phone) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-info-phone">';
            if ($show_icons) {
                echo '<i class="fas fa-phone-alt" aria-hidden="true"></i>';
            }
            echo '<span class="aqualuxe-contact-info-text"><a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '">' . esc_html($phone) . '</a></span>';
            echo '</div>';
        }
        
        if ($email) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-info-email">';
            if ($show_icons) {
                echo '<i class="fas fa-envelope" aria-hidden="true"></i>';
            }
            echo '<span class="aqualuxe-contact-info-text"><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></span>';
            echo '</div>';
        }
        
        if ($hours) {
            echo '<div class="aqualuxe-contact-info-item aqualuxe-contact-info-hours">';
            if ($show_icons) {
                echo '<i class="fas fa-clock" aria-hidden="true"></i>';
            }
            echo '<span class="aqualuxe-contact-info-text">' . wp_kses_post($hours) . '</span>';
            echo '</div>';
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
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Contact Info', 'aqualuxe');
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $hours = !empty($instance['hours']) ? $instance['hours'] : '';
        $show_icons = !empty($instance['show_icons']) ? (bool) $instance['show_icons'] : true;
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
            <label for="<?php echo esc_attr($this->get_field_id('hours')); ?>"><?php esc_html_e('Hours:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('hours')); ?>" name="<?php echo esc_attr($this->get_field_name('hours')); ?>" rows="3"><?php echo esc_textarea($hours); ?></textarea>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_icons); ?> id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>"><?php esc_html_e('Display icons?', 'aqualuxe'); ?></label>
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
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['address'] = (!empty($new_instance['address'])) ? wp_kses_post($new_instance['address']) : '';
        $instance['phone'] = (!empty($new_instance['phone'])) ? sanitize_text_field($new_instance['phone']) : '';
        $instance['email'] = (!empty($new_instance['email'])) ? sanitize_email($new_instance['email']) : '';
        $instance['hours'] = (!empty($new_instance['hours'])) ? wp_kses_post($new_instance['hours']) : '';
        $instance['show_icons'] = (!empty($new_instance['show_icons'])) ? (bool) $new_instance['show_icons'] : false;
        
        return $instance;
    }
}

/**
 * WooCommerce specific widgets
 */
if (aqualuxe_is_woocommerce_active()) {
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
                array(
                    'description' => esc_html__('Display featured products.', 'aqualuxe'),
                    'classname' => 'aqualuxe-featured-products',
                )
            );
        }

        /**
         * Widget output
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Featured Products', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 4;
            $columns = !empty($instance['columns']) ? absint($instance['columns']) : 2;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order = !empty($instance['order']) ? $instance['order'] : 'desc';
            $show_rating = !empty($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
            
            $query_args = array(
                'post_type' => 'product',
                'posts_per_page' => $number,
                'no_found_rows' => true,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                        'operator' => 'IN',
                    ),
                ),
                'orderby' => $orderby,
                'order' => $order,
            );
            
            $products = new WP_Query($query_args);
            
            echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            
            if ($title) {
                echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            
            if ($products->have_posts()) {
                echo '<div class="aqualuxe-featured-products-grid grid grid-cols-' . esc_attr($columns) . ' gap-4">';
                
                while ($products->have_posts()) {
                    $products->the_post();
                    global $product;
                    
                    echo '<div class="aqualuxe-featured-product">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="aqualuxe-featured-product-link">';
                    
                    if (has_post_thumbnail()) {
                        echo '<div class="aqualuxe-featured-product-thumbnail">';
                        echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                        echo '</div>';
                    }
                    
                    echo '<h4 class="aqualuxe-featured-product-title">' . esc_html(get_the_title()) . '</h4>';
                    
                    if ($product->get_price_html()) {
                        echo '<div class="aqualuxe-featured-product-price">' . $product->get_price_html() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    
                    if ($show_rating && $product->get_average_rating() > 0) {
                        echo '<div class="aqualuxe-featured-product-rating">';
                        echo wc_get_rating_html($product->get_average_rating());
                        echo '</div>';
                    }
                    
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '</div>';
                
                wp_reset_postdata();
            } else {
                echo '<p>' . esc_html__('No featured products found.', 'aqualuxe') . '</p>';
            }
            
            echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /**
         * Widget form
         *
         * @param array $instance Widget instance.
         * @return void
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Featured Products', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 4;
            $columns = !empty($instance['columns']) ? absint($instance['columns']) : 2;
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order = !empty($instance['order']) ? $instance['order'] : 'desc';
            $show_rating = !empty($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
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
                <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Number of columns:', 'aqualuxe'); ?></label>
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
                    <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
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
                <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Display product rating?', 'aqualuxe'); ?></label>
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
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 4;
            $instance['columns'] = (!empty($new_instance['columns'])) ? absint($new_instance['columns']) : 2;
            $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'date';
            $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'desc';
            $instance['show_rating'] = (!empty($new_instance['show_rating'])) ? (bool) $new_instance['show_rating'] : false;
            
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
                array(
                    'description' => esc_html__('Display product categories.', 'aqualuxe'),
                    'classname' => 'aqualuxe-product-categories',
                )
            );
        }

        /**
         * Widget output
         *
         * @param array $args Widget arguments.
         * @param array $instance Widget instance.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Product Categories', 'aqualuxe');
            $count = !empty($instance['count']) ? (bool) $instance['count'] : false;
            $hierarchical = !empty($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
            $show_children_only = !empty($instance['show_children_only']) ? (bool) $instance['show_children_only'] : false;
            $hide_empty = !empty($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
            $max_depth = !empty($instance['max_depth']) ? absint($instance['max_depth']) : 0;
            
            echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            
            if ($title) {
                echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
            
            $list_args = array(
                'taxonomy' => 'product_cat',
                'show_count' => $count,
                'hierarchical' => $hierarchical,
                'title_li' => '',
                'hide_empty' => $hide_empty,
                'depth' => $max_depth,
            );
            
            if ($show_children_only && is_tax('product_cat')) {
                $current_cat = get_queried_object();
                
                if ($current_cat && $current_cat->term_id) {
                    if ($hierarchical) {
                        $list_args['child_of'] = $current_cat->term_id;
                    } else {
                        $list_args['parent'] = $current_cat->term_id;
                    }
                }
            }
            
            echo '<ul class="aqualuxe-product-categories-list">';
            wp_list_categories($list_args);
            echo '</ul>';
            
            echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /**
         * Widget form
         *
         * @param array $instance Widget instance.
         * @return void
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Product Categories', 'aqualuxe');
            $count = !empty($instance['count']) ? (bool) $instance['count'] : false;
            $hierarchical = !empty($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
            $show_children_only = !empty($instance['show_children_only']) ? (bool) $instance['show_children_only'] : false;
            $hide_empty = !empty($instance['hide_empty']) ? (bool) $instance['hide_empty'] : true;
            $max_depth = !empty($instance['max_depth']) ? absint($instance['max_depth']) : 0;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($count); ?> id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('count')); ?>"><?php esc_html_e('Show product counts', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hierarchical); ?> id="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>" name="<?php echo esc_attr($this->get_field_name('hierarchical')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>"><?php esc_html_e('Show hierarchy', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_children_only); ?> id="<?php echo esc_attr($this->get_field_id('show_children_only')); ?>" name="<?php echo esc_attr($this->get_field_name('show_children_only')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_children_only')); ?>"><?php esc_html_e('Only show children of the current category', 'aqualuxe'); ?></label>
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hide_empty); ?> id="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>"><?php esc_html_e('Hide empty categories', 'aqualuxe'); ?></label>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('max_depth')); ?>"><?php esc_html_e('Maximum depth:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('max_depth')); ?>" name="<?php echo esc_attr($this->get_field_name('max_depth')); ?>" type="number" step="1" min="0" value="<?php echo esc_attr($max_depth); ?>" size="3">
                <span class="description"><?php esc_html_e('(0 = all)', 'aqualuxe'); ?></span>
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
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['count'] = (!empty($new_instance['count'])) ? (bool) $new_instance['count'] : false;
            $instance['hierarchical'] = (!empty($new_instance['hierarchical'])) ? (bool) $new_instance['hierarchical'] : false;
            $instance['show_children_only'] = (!empty($new_instance['show_children_only'])) ? (bool) $new_instance['show_children_only'] : false;
            $instance['hide_empty'] = (!empty($new_instance['hide_empty'])) ? (bool) $new_instance['hide_empty'] : false;
            $instance['max_depth'] = (!empty($new_instance['max_depth'])) ? absint($new_instance['max_depth']) : 0;
            
            return $instance;
        }
    }
}