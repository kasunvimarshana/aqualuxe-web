<?php
/**
 * Custom Widgets - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('AquaLuxe_Recent_Posts_Widget')) {
    /**
     * Recent Posts Widget
     *
     * @since 1.0.0
     */
    class AquaLuxe_Recent_Posts_Widget extends WP_Widget {
        
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_recent_posts',
                __('AquaLuxe Recent Posts', 'aqualuxe'),
                array(
                    'description' => __('Display recent blog posts with thumbnails.', 'aqualuxe'),
                )
            );
        }
        
        /**
         * Front-end display of widget.
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            echo $args['before_widget'];
            
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
            
            $number = !empty($instance['number']) ? absint($instance['number']) : 5;
            
            $recent_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => $number,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($recent_posts->have_posts()) {
                echo '<ul class="aqualuxe-recent-posts">';
                
                while ($recent_posts->have_posts()) {
                    $recent_posts->the_post();
                    ?>
                    <li class="recent-post-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="post-content">
                            <h4 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                            </div>
                        </div>
                    </li>
                    <?php
                }
                
                echo '</ul>';
                
                wp_reset_postdata();
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
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of posts to show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
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
            
            return $instance;
        }
    }
}

if (!class_exists('AquaLuxe_Product_Categories_Widget')) {
    /**
     * Product Categories Widget
     *
     * @since 1.0.0
     */
    class AquaLuxe_Product_Categories_Widget extends WP_Widget {
        
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_product_categories',
                __('AquaLuxe Product Categories', 'aqualuxe'),
                array(
                    'description' => __('Display product categories with thumbnails.', 'aqualuxe'),
                )
            );
        }
        
        /**
         * Front-end display of widget.
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            if (!class_exists('WooCommerce')) {
                return;
            }
            
            echo $args['before_widget'];
            
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
            
            $number = !empty($instance['number']) ? absint($instance['number']) : 10;
            $hide_empty = !empty($instance['hide_empty']) ? true : false;
            
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'number' => $number,
                'hide_empty' => $hide_empty,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<ul class="aqualuxe-product-categories">';
                
                foreach ($categories as $category) {
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image = wp_get_attachment_url($thumbnail_id);
                    ?>
                    <li class="category-item">
                        <a href="<?php echo get_term_link($category); ?>">
                            <?php if ($image) : ?>
                                <div class="category-thumbnail">
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="category-content">
                                <h4 class="category-name"><?php echo esc_html($category->name); ?></h4>
                                <span class="category-count"><?php echo esc_html($category->count); ?> <?php _e('products', 'aqualuxe'); ?></span>
                            </div>
                        </a>
                    </li>
                    <?php
                }
                
                echo '</ul>';
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
            $number = !empty($instance['number']) ? absint($instance['number']) : 10;
            $hide_empty = !empty($instance['hide_empty']) ? true : false;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of categories to show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($hide_empty); ?> id="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>" name="<?php echo esc_attr($this->get_field_name('hide_empty')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('hide_empty')); ?>"><?php _e('Hide empty categories', 'aqualuxe'); ?></label>
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
            $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 10;
            $instance['hide_empty'] = (!empty($new_instance['hide_empty'])) ? true : false;
            
            return $instance;
        }
    }
}

if (!class_exists('AquaLuxe_Social_Links_Widget')) {
    /**
     * Social Links Widget
     *
     * @since 1.0.0
     */
    class AquaLuxe_Social_Links_Widget extends WP_Widget {
        
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_social_links',
                __('AquaLuxe Social Links', 'aqualuxe'),
                array(
                    'description' => __('Display social media links.', 'aqualuxe'),
                )
            );
        }
        
        /**
         * Front-end display of widget.
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            echo $args['before_widget'];
            
            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
            
            $social_links = array(
                'facebook' => !empty($instance['facebook']) ? $instance['facebook'] : '',
                'twitter' => !empty($instance['twitter']) ? $instance['twitter'] : '',
                'instagram' => !empty($instance['instagram']) ? $instance['instagram'] : '',
                'pinterest' => !empty($instance['pinterest']) ? $instance['pinterest'] : '',
                'youtube' => !empty($instance['youtube']) ? $instance['youtube'] : '',
            );
            
            $social_icons = array(
                'facebook' => 'fab fa-facebook-f',
                'twitter' => 'fab fa-twitter',
                'instagram' => 'fab fa-instagram',
                'pinterest' => 'fab fa-pinterest',
                'youtube' => 'fab fa-youtube',
            );
            
            echo '<ul class="aqualuxe-social-links">';
            
            foreach ($social_links as $network => $url) {
                if (!empty($url)) {
                    ?>
                    <li class="social-item <?php echo esc_attr($network); ?>">
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="<?php echo esc_attr($social_icons[$network]); ?>"></i>
                            <span class="screen-reader-text"><?php echo esc_html(ucfirst($network)); ?></span>
                        </a>
                    </li>
                    <?php
                }
            }
            
            echo '</ul>';
            
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
            $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
            $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php _e('Facebook URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_attr($facebook); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php _e('Twitter URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_attr($twitter); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php _e('Instagram URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="text" value="<?php echo esc_attr($instagram); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php _e('Pinterest URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="text" value="<?php echo esc_attr($pinterest); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php _e('YouTube URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="text" value="<?php echo esc_attr($youtube); ?>">
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
            $instance['pinterest'] = (!empty($new_instance['pinterest'])) ? esc_url_raw($new_instance['pinterest']) : '';
            $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
            
            return $instance;
        }
    }
}

if (!function_exists('aqualuxe_register_widgets')) {
    /**
     * Register custom widgets.
     *
     * @since 1.0.0
     */
    function aqualuxe_register_widgets() {
        register_widget('AquaLuxe_Recent_Posts_Widget');
        register_widget('AquaLuxe_Product_Categories_Widget');
        register_widget('AquaLuxe_Social_Links_Widget');
    }
}
add_action('widgets_init', 'aqualuxe_register_widgets');