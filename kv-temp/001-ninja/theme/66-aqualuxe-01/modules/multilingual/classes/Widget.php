<?php
/**
 * Language Switcher Widget
 *
 * @package AquaLuxe\Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

/**
 * Language Switcher Widget Class
 */
class Widget extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_language_switcher',
            __('AquaLuxe Language Switcher', 'aqualuxe'),
            [
                'description' => __('Displays a language switcher for your multilingual site.', 'aqualuxe'),
                'classname' => 'widget_aqualuxe_language_switcher',
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
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
        $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;

        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['multilingual'] ?? null;

        if (!$module) {
            return;
        }

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        // Create switcher
        $switcher = new Switcher($module->get_languages(), $module->get_current_language());
        $switcher->render($style, $show_flags, $show_names);

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
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
        $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="dropdown" <?php selected($style, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="buttons" <?php selected($style, 'buttons'); ?>><?php esc_html_e('Buttons', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_flags')); ?>" name="<?php echo esc_attr($this->get_field_name('show_flags')); ?>" <?php checked($show_flags); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_flags')); ?>"><?php esc_html_e('Show flags', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_names')); ?>" name="<?php echo esc_attr($this->get_field_name('show_names')); ?>" <?php checked($show_names); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_names')); ?>"><?php esc_html_e('Show language names', 'aqualuxe'); ?></label>
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
        $instance['style'] = !empty($new_instance['style']) ? sanitize_key($new_instance['style']) : 'dropdown';
        $instance['show_flags'] = isset($new_instance['show_flags']) ? (bool) $new_instance['show_flags'] : false;
        $instance['show_names'] = isset($new_instance['show_names']) ? (bool) $new_instance['show_names'] : false;

        return $instance;
    }
}