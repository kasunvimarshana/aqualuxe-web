<?php
/**
 * Dark Mode Widget Class
 *
 * @package AquaLuxe\Modules\DarkMode
 */

namespace AquaLuxe\Modules\DarkMode;

/**
 * Dark Mode Widget Class
 */
class Widget extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_dark_mode',
            __('AquaLuxe Dark Mode Toggle', 'aqualuxe'),
            [
                'description' => __('Displays a dark mode toggle switch.', 'aqualuxe'),
                'classname' => 'widget_aqualuxe_dark_mode',
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
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $style = !empty($instance['style']) ? $instance['style'] : 'switch';
        $show_icon = isset($instance['show_icon']) ? (bool) $instance['show_icon'] : true;
        $show_text = isset($instance['show_text']) ? (bool) $instance['show_text'] : true;

        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['dark-mode'] ?? null;

        if (!$module) {
            return;
        }

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        // Render dark mode toggle
        $module->get_template_part('toggle', $style, [
            'show_icon' => $show_icon,
            'show_text' => $show_text,
        ]);

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $style = !empty($instance['style']) ? $instance['style'] : 'switch';
        $show_icon = isset($instance['show_icon']) ? (bool) $instance['show_icon'] : true;
        $show_text = isset($instance['show_text']) ? (bool) $instance['show_text'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="switch" <?php selected($style, 'switch'); ?>><?php esc_html_e('Switch', 'aqualuxe'); ?></option>
                <option value="button" <?php selected($style, 'button'); ?>><?php esc_html_e('Button', 'aqualuxe'); ?></option>
                <option value="icon" <?php selected($style, 'icon'); ?>><?php esc_html_e('Icon Only', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('show_icon')); ?>" <?php checked($show_icon); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_icon')); ?>"><?php esc_html_e('Show icon', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_text')); ?>" name="<?php echo esc_attr($this->get_field_name('show_text')); ?>" <?php checked($show_text); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_text')); ?>"><?php esc_html_e('Show text', 'aqualuxe'); ?></label>
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
        $instance = [];
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['style'] = !empty($new_instance['style']) ? sanitize_key($new_instance['style']) : 'switch';
        $instance['show_icon'] = isset($new_instance['show_icon']) ? (bool) $new_instance['show_icon'] : false;
        $instance['show_text'] = isset($new_instance['show_text']) ? (bool) $new_instance['show_text'] : false;

        return $instance;
    }
}