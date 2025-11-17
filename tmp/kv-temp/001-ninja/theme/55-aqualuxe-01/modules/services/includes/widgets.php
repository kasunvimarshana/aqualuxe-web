<?php
/**
 * Services Widgets
 *
 * Widgets for the services module.
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register services widgets
 */
function aqualuxe_register_services_widgets() {
    register_widget('AquaLuxe_Services_Widget');
    register_widget('AquaLuxe_Service_Categories_Widget');
    register_widget('AquaLuxe_Featured_Service_Widget');
    register_widget('AquaLuxe_Service_Search_Widget');
}
add_action('widgets_init', 'aqualuxe_register_services_widgets');

/**
 * Services Widget
 */
class AquaLuxe_Services_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_services_widget',
            __('AquaLuxe Services', 'aqualuxe'),
            [
                'description' => __('Display a list of services.', 'aqualuxe'),
                'classname' => 'aqualuxe-services-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;

        // Output widget
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get services
        $query_args = [
            'post_type' => 'service',
            'post_status' => 'publish',
            'posts_per_page' => $number,
            'orderby' => $orderby,
            'order' => $order,
        ];

        // Add category if specified
        if (!empty($category)) {
            $query_args['tax_query'] = [
                [
                    'taxonomy' => 'service_category',
                    'field' => 'term_id',
                    'terms' => $category,
                ],
            ];
        }

        $services = new WP_Query($query_args);

        if ($services->have_posts()) {
            echo '<ul class="aqualuxe-services-widget-list">';

            while ($services->have_posts()) {
                $services->the_post();

                echo '<li class="aqualuxe-services-widget-item">';

                // Show image
                if ($show_image && has_post_thumbnail()) {
                    echo '<div class="aqualuxe-services-widget-image">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="aqualuxe-services-widget-content">';

                // Show title
                echo '<h4 class="aqualuxe-services-widget-title">';
                echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
                echo '</h4>';

                // Show price
                $price = get_post_meta(get_the_ID(), '_service_price', true);
                $sale_price = get_post_meta(get_the_ID(), '_service_sale_price', true);

                if ($price) {
                    echo '<div class="aqualuxe-services-widget-price">';
                    if ($sale_price) {
                        echo '<del>' . esc_html(aqualuxe_format_price($price)) . '</del> ';
                        echo '<ins>' . esc_html(aqualuxe_format_price($sale_price)) . '</ins>';
                    } else {
                        echo esc_html(aqualuxe_format_price($price));
                    }
                    echo '</div>';
                }

                // Show excerpt
                if ($show_excerpt) {
                    echo '<div class="aqualuxe-services-widget-excerpt">';
                    the_excerpt();
                    echo '</div>';
                }

                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';

            // Reset post data
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No services found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = !empty($instance['order']) ? $instance['order'] : 'DESC';
        $category = !empty($instance['category']) ? $instance['category'] : '';
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;

        // Get service categories
        $categories = get_terms([
            'taxonomy' => 'service_category',
            'hide_empty' => false,
        ]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of services to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
                <option value="menu_order" <?php selected($orderby, 'menu_order'); ?>><?php esc_html_e('Menu Order', 'aqualuxe'); ?></option>
                <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
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
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value="" <?php selected($category, ''); ?>><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                <?php
                if (!empty($categories) && !is_wp_error($categories)) {
                    foreach ($categories as $cat) {
                        echo '<option value="' . esc_attr($cat->term_id) . '" ' . selected($category, $cat->term_id, false) . '>' . esc_html($cat->name) . '</option>';
                    }
                }
                ?>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image); ?> id="<?php echo esc_attr($this->get_field_id('show_image')); ?>" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e('Show featured image', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Show excerpt', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $instance['orderby'] = sanitize_text_field($new_instance['orderby']);
        $instance['order'] = sanitize_text_field($new_instance['order']);
        $instance['category'] = sanitize_text_field($new_instance['category']);
        $instance['show_image'] = isset($new_instance['show_image']) ? (bool) $new_instance['show_image'] : false;
        $instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
        return $instance;
    }
}

/**
 * Service Categories Widget
 */
class AquaLuxe_Service_Categories_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_service_categories_widget',
            __('AquaLuxe Service Categories', 'aqualuxe'),
            [
                'description' => __('Display a list of service categories.', 'aqualuxe'),
                'classname' => 'aqualuxe-service-categories-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
        $order = !empty($instance['order']) ? $instance['order'] : 'ASC';
        $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : true;
        $hierarchical = isset($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
        $dropdown = isset($instance['dropdown']) ? (bool) $instance['dropdown'] : false;

        // Output widget
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get categories
        $cat_args = [
            'taxonomy' => 'service_category',
            'orderby' => $orderby,
            'order' => $order,
            'show_count' => $show_count,
            'hierarchical' => $hierarchical,
            'title_li' => '',
        ];

        if ($dropdown) {
            $dropdown_id = 'service-category-' . $this->id;
            $cat_args['show_option_none'] = __('Select Category', 'aqualuxe');
            $cat_args['id'] = $dropdown_id;
            $cat_args['value_field'] = 'slug';

            wp_dropdown_categories($cat_args);
            ?>
            <script type="text/javascript">
                (function() {
                    var dropdown = document.getElementById('<?php echo esc_js($dropdown_id); ?>');
                    function onCatChange() {
                        if (dropdown.options[dropdown.selectedIndex].value !== '') {
                            location.href = '<?php echo esc_url(home_url('/')); ?>?service_category=' + dropdown.options[dropdown.selectedIndex].value;
                        }
                    }
                    dropdown.onchange = onCatChange;
                })();
            </script>
            <?php
        } else {
            echo '<ul class="aqualuxe-service-categories-widget-list">';
            wp_list_categories($cat_args);
            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $orderby = !empty($instance['orderby']) ? $instance['orderby'] : 'name';
        $order = !empty($instance['order']) ? $instance['order'] : 'ASC';
        $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : true;
        $hierarchical = isset($instance['hierarchical']) ? (bool) $instance['hierarchical'] : true;
        $dropdown = isset($instance['dropdown']) ? (bool) $instance['dropdown'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option value="name" <?php selected($orderby, 'name'); ?>><?php esc_html_e('Name', 'aqualuxe'); ?></option>
                <option value="id" <?php selected($orderby, 'id'); ?>><?php esc_html_e('ID', 'aqualuxe'); ?></option>
                <option value="count" <?php selected($orderby, 'count'); ?>><?php esc_html_e('Count', 'aqualuxe'); ?></option>
                <option value="slug" <?php selected($orderby, 'slug'); ?>><?php esc_html_e('Slug', 'aqualuxe'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option value="ASC" <?php selected($order, 'ASC'); ?>><?php esc_html_e('Ascending', 'aqualuxe'); ?></option>
                <option value="DESC" <?php selected($order, 'DESC'); ?>><?php esc_html_e('Descending', 'aqualuxe'); ?></option>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($dropdown); ?> id="<?php echo esc_attr($this->get_field_id('dropdown')); ?>" name="<?php echo esc_attr($this->get_field_name('dropdown')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('dropdown')); ?>"><?php esc_html_e('Display as dropdown', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_count); ?> id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show service counts', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($hierarchical); ?> id="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>" name="<?php echo esc_attr($this->get_field_name('hierarchical')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('hierarchical')); ?>"><?php esc_html_e('Show hierarchy', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['orderby'] = sanitize_text_field($new_instance['orderby']);
        $instance['order'] = sanitize_text_field($new_instance['order']);
        $instance['show_count'] = isset($new_instance['show_count']) ? (bool) $new_instance['show_count'] : false;
        $instance['hierarchical'] = isset($new_instance['hierarchical']) ? (bool) $new_instance['hierarchical'] : false;
        $instance['dropdown'] = isset($new_instance['dropdown']) ? (bool) $new_instance['dropdown'] : false;
        return $instance;
    }
}

/**
 * Featured Service Widget
 */
class AquaLuxe_Featured_Service_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_featured_service_widget',
            __('AquaLuxe Featured Service', 'aqualuxe'),
            [
                'description' => __('Display a featured service.', 'aqualuxe'),
                'classname' => 'aqualuxe-featured-service-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $service_id = !empty($instance['service_id']) ? absint($instance['service_id']) : 0;
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : true;
        $show_button = isset($instance['show_button']) ? (bool) $instance['show_button'] : true;
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('View Details', 'aqualuxe');

        // Output widget
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get service
        $service = get_post($service_id);

        // Check if service exists
        if ($service && $service->post_type === 'service' && $service->post_status === 'publish') {
            // Get service meta
            $price = get_post_meta($service->ID, '_service_price', true);
            $sale_price = get_post_meta($service->ID, '_service_sale_price', true);
            $price_type = get_post_meta($service->ID, '_service_price_type', true);
            $duration = get_post_meta($service->ID, '_service_duration', true);
            $location = get_post_meta($service->ID, '_service_location', true);

            // Output service
            echo '<div class="aqualuxe-featured-service">';

            // Show image
            if ($show_image && has_post_thumbnail($service->ID)) {
                echo '<div class="aqualuxe-featured-service-image">';
                echo '<a href="' . esc_url(get_permalink($service->ID)) . '">';
                echo get_the_post_thumbnail($service->ID, 'medium');
                echo '</a>';
                echo '</div>';
            }

            echo '<div class="aqualuxe-featured-service-content">';

            // Show title
            echo '<h4 class="aqualuxe-featured-service-title">';
            echo '<a href="' . esc_url(get_permalink($service->ID)) . '">' . esc_html($service->post_title) . '</a>';
            echo '</h4>';

            // Show meta
            echo '<div class="aqualuxe-featured-service-meta">';

            // Show price
            if ($price) {
                echo '<div class="aqualuxe-featured-service-price">';
                if ($sale_price) {
                    echo '<del>' . esc_html(aqualuxe_format_price($price)) . '</del> ';
                    echo '<ins>' . esc_html(aqualuxe_format_price($sale_price)) . '</ins>';
                } else {
                    echo esc_html(aqualuxe_format_price($price));
                }

                if ($price_type) {
                    echo ' <span class="aqualuxe-featured-service-price-type">' . esc_html($price_type) . '</span>';
                }
                echo '</div>';
            }

            // Show duration
            if ($duration) {
                echo '<div class="aqualuxe-featured-service-duration">';
                echo '<span class="aqualuxe-featured-service-duration-icon"></span>';
                echo '<span class="aqualuxe-featured-service-duration-text">' . esc_html($duration) . '</span>';
                echo '</div>';
            }

            // Show location
            if ($location) {
                echo '<div class="aqualuxe-featured-service-location">';
                echo '<span class="aqualuxe-featured-service-location-icon"></span>';
                echo '<span class="aqualuxe-featured-service-location-text">' . esc_html($location) . '</span>';
                echo '</div>';
            }

            echo '</div>';

            // Show excerpt
            if ($show_excerpt) {
                echo '<div class="aqualuxe-featured-service-excerpt">';
                echo wpautop(get_the_excerpt($service->ID));
                echo '</div>';
            }

            // Show button
            if ($show_button) {
                echo '<div class="aqualuxe-featured-service-button">';
                echo '<a href="' . esc_url(get_permalink($service->ID)) . '" class="button">' . esc_html($button_text) . '</a>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('No featured service selected.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $service_id = !empty($instance['service_id']) ? absint($instance['service_id']) : 0;
        $show_image = isset($instance['show_image']) ? (bool) $instance['show_image'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : true;
        $show_button = isset($instance['show_button']) ? (bool) $instance['show_button'] : true;
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('View Details', 'aqualuxe');

        // Get services
        $services = get_posts([
            'post_type' => 'service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('service_id')); ?>"><?php esc_html_e('Service:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('service_id')); ?>" name="<?php echo esc_attr($this->get_field_name('service_id')); ?>">
                <option value="0" <?php selected($service_id, 0); ?>><?php esc_html_e('Select Service', 'aqualuxe'); ?></option>
                <?php
                if (!empty($services)) {
                    foreach ($services as $service) {
                        echo '<option value="' . esc_attr($service->ID) . '" ' . selected($service_id, $service->ID, false) . '>' . esc_html($service->post_title) . '</option>';
                    }
                }
                ?>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_image); ?> id="<?php echo esc_attr($this->get_field_id('show_image')); ?>" name="<?php echo esc_attr($this->get_field_name('show_image')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e('Show featured image', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>"><?php esc_html_e('Show excerpt', 'aqualuxe'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_button); ?> id="<?php echo esc_attr($this->get_field_id('show_button')); ?>" name="<?php echo esc_attr($this->get_field_name('show_button')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_button')); ?>"><?php esc_html_e('Show button', 'aqualuxe'); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['service_id'] = absint($new_instance['service_id']);
        $instance['show_image'] = isset($new_instance['show_image']) ? (bool) $new_instance['show_image'] : false;
        $instance['show_excerpt'] = isset($new_instance['show_excerpt']) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['show_button'] = isset($new_instance['show_button']) ? (bool) $new_instance['show_button'] : false;
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        return $instance;
    }
}

/**
 * Service Search Widget
 */
class AquaLuxe_Service_Search_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_service_search_widget',
            __('AquaLuxe Service Search', 'aqualuxe'),
            [
                'description' => __('Display a service search form.', 'aqualuxe'),
                'classname' => 'aqualuxe-service-search-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Search services...', 'aqualuxe');
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Search', 'aqualuxe');
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;

        // Output widget
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Output search form
        echo '<div class="aqualuxe-service-search-form">';
        echo '<form role="search" method="get" action="' . esc_url(home_url('/')) . '">';
        echo '<input type="hidden" name="post_type" value="service" />';

        // Show categories
        if ($show_categories) {
            $categories = get_terms([
                'taxonomy' => 'service_category',
                'hide_empty' => true,
            ]);

            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<select name="service_category" class="aqualuxe-service-search-category">';
                echo '<option value="">' . esc_html__('All Categories', 'aqualuxe') . '</option>';

                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                }

                echo '</select>';
            }
        }

        echo '<div class="aqualuxe-service-search-input-wrapper">';
        echo '<input type="search" class="aqualuxe-service-search-input" placeholder="' . esc_attr($placeholder) . '" value="' . esc_attr(get_search_query()) . '" name="s" />';
        echo '<button type="submit" class="aqualuxe-service-search-button">' . esc_html($button_text) . '</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance) {
        // Get widget settings
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $placeholder = !empty($instance['placeholder']) ? $instance['placeholder'] : __('Search services...', 'aqualuxe');
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Search', 'aqualuxe');
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('placeholder')); ?>"><?php esc_html_e('Placeholder:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('placeholder')); ?>" name="<?php echo esc_attr($this->get_field_name('placeholder')); ?>" type="text" value="<?php echo esc_attr($placeholder); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories dropdown', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['placeholder'] = sanitize_text_field($new_instance['placeholder']);
        $instance['button_text'] = sanitize_text_field($new_instance['button_text']);
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        return $instance;
    }
}