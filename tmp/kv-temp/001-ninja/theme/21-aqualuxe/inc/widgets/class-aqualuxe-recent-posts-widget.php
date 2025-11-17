<?php
/**
 * AquaLuxe Recent Posts Widget
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * AquaLuxe Recent Posts Widget Class
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts', // Base ID
            esc_html__('AquaLuxe Recent Posts', 'aqualuxe'), // Name
            array(
                'description' => esc_html__('Display recent posts with thumbnails.', 'aqualuxe'),
                'classname'   => 'aqualuxe-recent-posts',
            ) // Args
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
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $post_count = !empty($instance['post_count']) ? absint($instance['post_count']) : 3;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
        $post_type = !empty($instance['post_type']) ? sanitize_text_field($instance['post_type']) : 'post';
        $category = !empty($instance['category']) ? absint($instance['category']) : 0;

        $query_args = array(
            'post_type'           => $post_type,
            'posts_per_page'      => $post_count,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );

        // Add category filter for posts
        if ($post_type === 'post' && $category > 0) {
            $query_args['cat'] = $category;
        }

        $recent_posts = new WP_Query($query_args);

        if ($recent_posts->have_posts()) :
            ?>
            <div class="aqualuxe-recent-posts-widget">
                <ul class="recent-posts-list space-y-4">
                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <li class="recent-post-item flex <?php echo $show_thumbnail ? 'has-thumbnail' : ''; ?>">
                            <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="post-thumbnail mr-3 flex-shrink-0">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'w-16 h-16 rounded object-cover')); ?>
                                </a>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <h4 class="post-title text-sm font-medium mb-1">
                                    <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($show_date) : ?>
                                    <div class="post-meta text-xs text-gray-600 dark:text-gray-400 mb-1">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($show_excerpt) : ?>
                                    <div class="post-excerpt text-xs text-gray-700 dark:text-gray-300">
                                        <?php echo wp_trim_words(get_the_excerpt(), $excerpt_length, '...'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php
            wp_reset_postdata();
        endif;

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
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Posts', 'aqualuxe');
        $post_count = !empty($instance['post_count']) ? absint($instance['post_count']) : 3;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
        $post_type = !empty($instance['post_type']) ? sanitize_text_field($instance['post_type']) : 'post';
        $category = !empty($instance['category']) ? absint($instance['category']) : 0;
        
        // Get all public post types
        $post_types = get_post_types(array('public' => true), 'objects');
        
        // Get all categories
        $categories = get_categories(array('hide_empty' => false));
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php esc_html_e('Post Type:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('post_type')); ?>" name="<?php echo esc_attr($this->get_field_name('post_type')); ?>">
                <?php foreach ($post_types as $type) : ?>
                    <option value="<?php echo esc_attr($type->name); ?>" <?php selected($post_type, $type->name); ?>>
                        <?php echo esc_html($type->labels->singular_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category (for Posts only):', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value="0" <?php selected($category, 0); ?>><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?php echo esc_attr($cat->term_id); ?>" <?php selected($category, $cat->term_id); ?>>
                        <?php echo esc_html($cat->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('post_count')); ?>"><?php esc_html_e('Number of posts to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('post_count')); ?>" name="<?php echo esc_attr($this->get_field_name('post_count')); ?>" type="number" min="1" max="10" value="<?php echo esc_attr($post_count); ?>">
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
            <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt length (words):', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" type="number" min="5" max="100" value="<?php echo esc_attr($excerpt_length); ?>">
        </p>
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
        $instance['post_count'] = (!empty($new_instance['post_count'])) ? absint($new_instance['post_count']) : 3;
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? (bool) $new_instance['show_thumbnail'] : true;
        $instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['excerpt_length'] = (!empty($new_instance['excerpt_length'])) ? absint($new_instance['excerpt_length']) : 20;
        $instance['post_type'] = (!empty($new_instance['post_type'])) ? sanitize_text_field($new_instance['post_type']) : 'post';
        $instance['category'] = (!empty($new_instance['category'])) ? absint($new_instance['category']) : 0;
        
        return $instance;
    }
}

/**
 * Register the widget
 */
function aqualuxe_register_recent_posts_widget() {
    register_widget('AquaLuxe_Recent_Posts_Widget');
}
add_action('widgets_init', 'aqualuxe_register_recent_posts_widget');