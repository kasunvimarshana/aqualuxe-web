<?php
/**
 * Enhanced Recent Posts Widget for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!class_exists('AquaLuxe_Recent_Posts_Widget_Enhanced')) {
    /**
     * Enhanced Recent Posts Widget Class
     */
    class AquaLuxe_Recent_Posts_Widget_Enhanced extends WP_Widget {
        /**
         * Cache instance
         *
         * @var array
         */
        protected $cache = array();

        /**
         * Cache expiration time in seconds
         *
         * @var int
         */
        protected $cache_time = 3600; // 1 hour

        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_recent_posts',
                __('AquaLuxe Recent Posts', 'aqualuxe'),
                array(
                    'description' => __('Displays recent posts with thumbnails and advanced options.', 'aqualuxe'),
                    'classname'   => 'aqualuxe-recent-posts',
                )
            );

            // Clear cache when posts are saved/updated
            add_action('save_post', array($this, 'flush_widget_cache'));
            add_action('deleted_post', array($this, 'flush_widget_cache'));
            add_action('switch_theme', array($this, 'flush_widget_cache'));
        }

        /**
         * Widget Front End
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            // Get cached widget
            $cache_key = 'aqualuxe_recent_posts_' . md5(serialize($args) . serialize($instance));
            $cached_widget = $this->get_cached_widget($cache_key);

            if ($cached_widget) {
                echo $cached_widget;
                return;
            }

            // Start output buffering for caching
            ob_start();

            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Recent Posts', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 5;
            $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
            $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
            $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
            $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
            $category = !empty($instance['category']) ? absint($instance['category']) : 0;
            $post_type = !empty($instance['post_type']) ? sanitize_text_field($instance['post_type']) : 'post';
            $orderby = !empty($instance['orderby']) ? sanitize_text_field($instance['orderby']) : 'date';
            $order = !empty($instance['order']) ? sanitize_text_field($instance['order']) : 'DESC';
            $show_author = isset($instance['show_author']) ? (bool) $instance['show_author'] : false;
            $show_comments = isset($instance['show_comments']) ? (bool) $instance['show_comments'] : false;
            $show_readmore = isset($instance['show_readmore']) ? (bool) $instance['show_readmore'] : false;
            $readmore_text = !empty($instance['readmore_text']) ? sanitize_text_field($instance['readmore_text']) : __('Read More', 'aqualuxe');
            $layout_style = !empty($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'list';
            $thumbnail_size = !empty($instance['thumbnail_size']) ? sanitize_text_field($instance['thumbnail_size']) : 'thumbnail';

            $query_args = array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
                'post_type'           => $post_type,
                'orderby'             => $orderby,
                'order'               => $order,
            );

            if ($category && $post_type === 'post') {
                $query_args['cat'] = $category;
            }

            // Add taxonomy query for custom post types
            if ($category && $post_type !== 'post') {
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => $post_type . '_category',
                        'field'    => 'term_id',
                        'terms'    => $category,
                    ),
                );
            }

            // Apply filters to query args
            $query_args = apply_filters('aqualuxe_recent_posts_widget_query_args', $query_args, $instance);

            $recent_posts = new WP_Query($query_args);

            echo $args['before_widget'];

            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if ($recent_posts->have_posts()) :
                // Add layout class
                $layout_class = 'aqualuxe-recent-posts-' . $layout_style;
                ?>
                <ul class="aqualuxe-recent-posts-list <?php echo esc_attr($layout_class); ?>">
                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <li class="aqualuxe-recent-post-item">
                            <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
                                <div class="aqualuxe-recent-post-thumbnail">
                                    <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                        <?php the_post_thumbnail($thumbnail_size); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="aqualuxe-recent-post-content">
                                <h4 class="aqualuxe-recent-post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                
                                <?php if ($show_date || $show_author || $show_comments) : ?>
                                    <div class="aqualuxe-recent-post-meta">
                                        <?php if ($show_date) : ?>
                                            <span class="aqualuxe-recent-post-date">
                                                <i class="aqualuxe-icon aqualuxe-icon-calendar"></i>
                                                <?php echo esc_html(get_the_date()); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($show_author) : ?>
                                            <span class="aqualuxe-recent-post-author">
                                                <i class="aqualuxe-icon aqualuxe-icon-user"></i>
                                                <?php the_author(); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($show_comments) : ?>
                                            <span class="aqualuxe-recent-post-comments">
                                                <i class="aqualuxe-icon aqualuxe-icon-comment"></i>
                                                <?php comments_number('0', '1', '%'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($show_excerpt) : ?>
                                    <div class="aqualuxe-recent-post-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), $excerpt_length, '...'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($show_readmore) : ?>
                                    <div class="aqualuxe-recent-post-readmore">
                                        <a href="<?php the_permalink(); ?>" class="aqualuxe-readmore-link">
                                            <?php echo esc_html($readmore_text); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php
                wp_reset_postdata();
            else :
                ?>
                <p><?php esc_html_e('No recent posts found.', 'aqualuxe'); ?></p>
                <?php
            endif;

            echo $args['after_widget'];

            // Get the buffered content and cache it
            $widget_content = ob_get_clean();
            $this->cache_widget($cache_key, $widget_content);
            
            echo $widget_content;
        }

        /**
         * Widget Backend
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'aqualuxe');
            $number = !empty($instance['number']) ? absint($instance['number']) : 5;
            $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
            $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
            $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
            $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
            $category = !empty($instance['category']) ? absint($instance['category']) : 0;
            $post_type = !empty($instance['post_type']) ? $instance['post_type'] : 'post';
            $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
            $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
            $show_author = isset($instance['show_author']) ? (bool) $instance['show_author'] : false;
            $show_comments = isset($instance['show_comments']) ? (bool) $instance['show_comments'] : false;
            $show_readmore = isset($instance['show_readmore']) ? (bool) $instance['show_readmore'] : false;
            $readmore_text = !empty($instance['readmore_text']) ? $instance['readmore_text'] : __('Read More', 'aqualuxe');
            $layout_style = !empty($instance['layout_style']) ? $instance['layout_style'] : 'list';
            $thumbnail_size = !empty($instance['thumbnail_size']) ? $instance['thumbnail_size'] : 'thumbnail';
            
            // Get available post types
            $post_types = get_post_types(array('public' => true), 'objects');
            
            // Get available thumbnail sizes
            $thumbnail_sizes = get_intermediate_image_sizes();
            $thumbnail_sizes[] = 'full';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php esc_html_e('Post Type:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>">
                    <?php foreach ($post_types as $type => $object) : ?>
                        <option value="<?php echo esc_attr($type); ?>" <?php selected($post_type, $type); ?>>
                            <?php echo esc_html($object->labels->singular_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'aqualuxe'); ?></label>
                <?php
                wp_dropdown_categories(array(
                    'show_option_all' => __('All Categories', 'aqualuxe'),
                    'name'            => $this->get_field_name('category'),
                    'selected'        => $category,
                    'hierarchical'    => true,
                    'class'           => 'widefat',
                ));
                ?>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order By:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                    <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                    <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
                    <option value="comment_count" <?php selected($orderby, 'comment_count'); ?>><?php esc_html_e('Comment Count', 'aqualuxe'); ?></option>
                    <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
                    <option value="modified" <?php selected($orderby, 'modified'); ?>><?php esc_html_e('Last Modified', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                    <option value="DESC" <?php selected($order, 'DESC'); ?>><?php esc_html_e('Descending', 'aqualuxe'); ?></option>
                    <option value="ASC" <?php selected($order, 'ASC'); ?>><?php esc_html_e('Ascending', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                    <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                    <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                    <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
                    <option value="featured" <?php selected($layout_style, 'featured'); ?>><?php esc_html_e('Featured', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display post thumbnail?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>"><?php esc_html_e('Thumbnail Size:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>" name="<?php echo esc_attr($this->get_field_name('thumbnail_size')); ?>">
                    <?php foreach ($thumbnail_sizes as $size) : ?>
                        <option value="<?php echo esc_attr($size); ?>" <?php selected($thumbnail_size, $size); ?>>
                            <?php echo esc_html($size); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_author); ?> id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>"><?php esc_html_e('Display post author?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_comments); ?> id="<?php echo esc_attr($this->get_field_id('show_comments')); ?>" name="<?php echo esc_attr($this->get_field_name('show_comments')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_comments')); ?>"><?php esc_html_e('Display comment count?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Display post excerpt?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt Length:', 'aqualuxe'); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($excerpt_length); ?>" size="3">
            </p>
            
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_readmore); ?> id="<?php echo esc_attr($this->get_field_id('show_readmore')); ?>" name="<?php echo esc_attr($this->get_field_name('show_readmore')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_readmore')); ?>"><?php esc_html_e('Display read more link?', 'aqualuxe'); ?></label>
            </p>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('readmore_text')); ?>"><?php esc_html_e('Read More Text:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('readmore_text')); ?>" name="<?php echo esc_attr($this->get_field_name('readmore_text')); ?>" type="text" value="<?php echo esc_attr($readmore_text); ?>">
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
            $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
            $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? (bool) $new_instance['show_thumbnail'] : true;
            $instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
            $instance['excerpt_length'] = (!empty($new_instance['excerpt_length'])) ? absint($new_instance['excerpt_length']) : 20;
            $instance['category'] = (!empty($new_instance['category'])) ? absint($new_instance['category']) : 0;
            $instance['post_type'] = (!empty($new_instance['post_type'])) ? sanitize_text_field($new_instance['post_type']) : 'post';
            $instance['orderby'] = (!empty($new_instance['orderby'])) ? sanitize_text_field($new_instance['orderby']) : 'date';
            $instance['order'] = (!empty($new_instance['order'])) ? sanitize_text_field($new_instance['order']) : 'DESC';
            $instance['show_author'] = isset($new_instance['show_author']) ? (bool) $new_instance['show_author'] : false;
            $instance['show_comments'] = isset($new_instance['show_comments']) ? (bool) $new_instance['show_comments'] : false;
            $instance['show_readmore'] = isset($new_instance['show_readmore']) ? (bool) $new_instance['show_readmore'] : false;
            $instance['readmore_text'] = (!empty($new_instance['readmore_text'])) ? sanitize_text_field($new_instance['readmore_text']) : __('Read More', 'aqualuxe');
            $instance['layout_style'] = (!empty($new_instance['layout_style'])) ? sanitize_text_field($new_instance['layout_style']) : 'list';
            $instance['thumbnail_size'] = (!empty($new_instance['thumbnail_size'])) ? sanitize_text_field($new_instance['thumbnail_size']) : 'thumbnail';

            // Clear the widget cache
            $this->flush_widget_cache();

            return $instance;
        }

        /**
         * Get cached widget
         *
         * @param string $cache_key Cache key
         * @return string|false Cached widget or false if not cached
         */
        protected function get_cached_widget($cache_key) {
            // Check if we have a cached version
            $cached = get_transient($cache_key);
            
            if ($cached) {
                return $cached;
            }
            
            return false;
        }

        /**
         * Cache widget
         *
         * @param string $cache_key Cache key
         * @param string $content Widget content to cache
         */
        protected function cache_widget($cache_key, $content) {
            // Cache the widget for the specified time
            set_transient($cache_key, $content, $this->cache_time);
        }

        /**
         * Flush widget cache
         */
        public function flush_widget_cache() {
            // Get all transients
            global $wpdb;
            
            $transients = $wpdb->get_col(
                "SELECT option_name FROM $wpdb->options 
                WHERE option_name LIKE '_transient_aqualuxe_recent_posts_%'"
            );
            
            // Delete all our transients
            foreach ($transients as $transient) {
                $transient_name = str_replace('_transient_', '', $transient);
                delete_transient($transient_name);
            }
        }
    }
}

/**
 * Register the Enhanced Recent Posts Widget
 */
function aqualuxe_register_recent_posts_widget_enhanced() {
    register_widget('AquaLuxe_Recent_Posts_Widget_Enhanced');
}
add_action('widgets_init', 'aqualuxe_register_recent_posts_widget_enhanced');

/**
 * Add widget styles
 */
function aqualuxe_recent_posts_widget_styles() {
    wp_enqueue_style(
        'aqualuxe-recent-posts-widget-style',
        AQUALUXE_URI . '/assets/css/widgets/recent-posts.css',
        array(),
        AQUALUXE_VERSION
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_recent_posts_widget_styles');