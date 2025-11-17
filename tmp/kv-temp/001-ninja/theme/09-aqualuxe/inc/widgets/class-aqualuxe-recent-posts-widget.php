<?php
/**
 * Recent Posts Widget with Thumbnails
 *
 * @package AquaLuxe
 */

/**
 * Recent Posts Widget with Thumbnails
 */
class Aqualuxe_Recent_Posts_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts',
            __('AquaLuxe Recent Posts', 'aqualuxe'),
            array(
                'description' => __('Display recent posts with thumbnails', 'aqualuxe'),
                'customize_selective_refresh' => true,
            )
        );
    }

    /**
     * Front-end display of widget
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $category = !empty($instance['category']) ? absint($instance['category']) : 0;

        $query_args = array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );

        if ($category) {
            $query_args['cat'] = $category;
        }

        $recent_posts = new WP_Query($query_args);

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if ($recent_posts->have_posts()) :
            ?>
            <ul class="aqualuxe-recent-posts">
                <?php
                while ($recent_posts->have_posts()) :
                    $recent_posts->the_post();
                    ?>
                    <li class="recent-post-item">
                        <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'rounded')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <h4 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            
                            <?php if ($show_date) : ?>
                                <div class="post-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($show_excerpt) : ?>
                                <div class="post-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), $excerpt_length, '...'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php
            // Reset the global $post variable
            wp_reset_postdata();
        else :
            echo '<p>' . esc_html__('No posts found.', 'aqualuxe') . '</p>';
        endif;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'aqualuxe');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = !empty($instance['excerpt_length']) ? absint($instance['excerpt_length']) : 20;
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $category = !empty($instance['category']) ? absint($instance['category']) : 0;
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
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'aqualuxe'); ?></label>
            <?php
            wp_dropdown_categories(array(
                'show_option_all' => __('All Categories', 'aqualuxe'),
                'name' => $this->get_field_name('category'),
                'selected' => $category,
                'hierarchical' => true,
                'class' => 'widefat',
            ));
            ?>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>"><?php esc_html_e('Display post thumbnail?', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Display post date?', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Display post excerpt?', 'aqualuxe'); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt length (words):', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($excerpt_length); ?>" size="3">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
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
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? (bool) $new_instance['show_date'] : false;
        $instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['excerpt_length'] = (!empty($new_instance['excerpt_length'])) ? absint($new_instance['excerpt_length']) : 20;
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['category'] = (!empty($new_instance['category'])) ? absint($new_instance['category']) : 0;

        return $instance;
    }
}

/**
 * Register the widget
 */
function aqualuxe_register_recent_posts_widget() {
    register_widget('Aqualuxe_Recent_Posts_Widget');
}
add_action('widgets_init', 'aqualuxe_register_recent_posts_widget');