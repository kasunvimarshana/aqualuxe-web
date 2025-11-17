<?php
/**
 * Multilingual module hooks
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add language switcher to header actions
 */
function aqualuxe_multilingual_add_language_switcher() {
    // Check if language switcher is enabled
    if (!aqualuxe_get_module_option('multilingual', 'show_language_switcher', true)) {
        return;
    }
    
    // Get language switcher position
    $position = aqualuxe_get_module_option('multilingual', 'language_switcher_position', 'header');
    
    if ($position !== 'header') {
        return;
    }
    
    // Get language switcher settings
    $show_flags = aqualuxe_get_module_option('multilingual', 'show_flags', true);
    $show_names = aqualuxe_get_module_option('multilingual', 'show_names', true);
    $dropdown = aqualuxe_get_module_option('multilingual', 'dropdown', true);
    
    // Display language switcher
    aqualuxe_multilingual_language_switcher(array(
        'show_flags' => $show_flags,
        'show_names' => $show_names,
        'dropdown' => $dropdown,
    ));
}
add_action('aqualuxe_language_switcher', 'aqualuxe_multilingual_add_language_switcher');

/**
 * Add language switcher to header
 */
function aqualuxe_multilingual_add_language_switcher_to_header() {
    do_action('aqualuxe_language_switcher');
}
add_action('aqualuxe_header_actions', 'aqualuxe_multilingual_add_language_switcher_to_header', 50);

/**
 * Add language switcher to footer
 */
function aqualuxe_multilingual_add_language_switcher_to_footer() {
    // Check if language switcher is enabled
    if (!aqualuxe_get_module_option('multilingual', 'show_language_switcher', true)) {
        return;
    }
    
    // Get language switcher position
    $position = aqualuxe_get_module_option('multilingual', 'language_switcher_position', 'header');
    
    if ($position !== 'footer') {
        return;
    }
    
    // Get language switcher settings
    $show_flags = aqualuxe_get_module_option('multilingual', 'show_flags', true);
    $show_names = aqualuxe_get_module_option('multilingual', 'show_names', true);
    $dropdown = aqualuxe_get_module_option('multilingual', 'dropdown', true);
    
    // Display language switcher
    echo '<div class="footer-language-switcher">';
    aqualuxe_multilingual_language_switcher(array(
        'show_flags' => $show_flags,
        'show_names' => $show_names,
        'dropdown' => $dropdown,
    ));
    echo '</div>';
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_multilingual_add_language_switcher_to_footer', 25);

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_multilingual_add_language_switcher_to_mobile_menu() {
    // Check if language switcher is enabled
    if (!aqualuxe_get_module_option('multilingual', 'show_language_switcher', true)) {
        return;
    }
    
    // Get language switcher position
    $position = aqualuxe_get_module_option('multilingual', 'language_switcher_position', 'header');
    
    if ($position !== 'mobile_menu') {
        return;
    }
    
    // Get language switcher settings
    $show_flags = aqualuxe_get_module_option('multilingual', 'show_flags', true);
    $show_names = aqualuxe_get_module_option('multilingual', 'show_names', true);
    
    // Display language switcher
    echo '<div class="mobile-language-switcher">';
    aqualuxe_multilingual_language_switcher(array(
        'show_flags' => $show_flags,
        'show_names' => $show_names,
        'dropdown' => false,
    ));
    echo '</div>';
}
add_action('aqualuxe_mobile_menu_after', 'aqualuxe_multilingual_add_language_switcher_to_mobile_menu');

/**
 * Add language switcher widget
 */
function aqualuxe_multilingual_register_widgets() {
    // Register language switcher widget
    register_widget('AquaLuxe_Language_Switcher_Widget');
}
add_action('widgets_init', 'aqualuxe_multilingual_register_widgets');

/**
 * Add language class to body
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_multilingual_body_class($classes) {
    $language = aqualuxe_multilingual_get_current_language();
    $classes[] = 'lang-' . $language;
    
    // Add RTL class if needed
    if (aqualuxe_multilingual_is_rtl()) {
        $classes[] = 'rtl';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_multilingual_body_class');

/**
 * Filter language attributes
 *
 * @param string $output Language attributes
 * @return string Modified language attributes
 */
function aqualuxe_multilingual_language_attributes($output) {
    $language = aqualuxe_multilingual_get_current_language();
    $direction = aqualuxe_multilingual_get_language_direction($language);
    
    return 'lang="' . esc_attr($language) . '" dir="' . esc_attr($direction) . '"';
}
add_filter('language_attributes', 'aqualuxe_multilingual_language_attributes');

/**
 * Register strings for translation
 */
function aqualuxe_multilingual_register_strings() {
    // Register theme strings
    aqualuxe_multilingual_register_string(get_bloginfo('name'), 'site_name');
    aqualuxe_multilingual_register_string(get_bloginfo('description'), 'site_description');
    
    // Register footer text
    $footer_text = get_theme_mod('aqualuxe_footer_text', '');
    if ($footer_text) {
        aqualuxe_multilingual_register_string($footer_text, 'footer_text');
    }
    
    // Register social network labels
    $networks = array('facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'pinterest');
    foreach ($networks as $network) {
        aqualuxe_multilingual_register_string(ucfirst($network), 'social_' . $network);
    }
    
    // Register button labels
    aqualuxe_multilingual_register_string(__('Read More', 'aqualuxe'), 'read_more');
    aqualuxe_multilingual_register_string(__('Add to Cart', 'aqualuxe'), 'add_to_cart');
    aqualuxe_multilingual_register_string(__('View Details', 'aqualuxe'), 'view_details');
    aqualuxe_multilingual_register_string(__('Quick View', 'aqualuxe'), 'quick_view');
    
    // Register form labels
    aqualuxe_multilingual_register_string(__('Name', 'aqualuxe'), 'form_name');
    aqualuxe_multilingual_register_string(__('Email', 'aqualuxe'), 'form_email');
    aqualuxe_multilingual_register_string(__('Subject', 'aqualuxe'), 'form_subject');
    aqualuxe_multilingual_register_string(__('Message', 'aqualuxe'), 'form_message');
    aqualuxe_multilingual_register_string(__('Submit', 'aqualuxe'), 'form_submit');
}
add_action('init', 'aqualuxe_multilingual_register_strings');

/**
 * Filter translated strings
 *
 * @param string $translation Translated text
 * @param string $text Text to translate
 * @param string $domain Text domain
 * @return string Filtered translation
 */
function aqualuxe_multilingual_gettext($translation, $text, $domain) {
    // Only filter our theme domain
    if ($domain !== 'aqualuxe') {
        return $translation;
    }
    
    // Get custom translation
    $custom_translation = aqualuxe_multilingual_translate($text, $domain);
    
    if ($custom_translation !== $text) {
        return $custom_translation;
    }
    
    return $translation;
}
add_filter('gettext', 'aqualuxe_multilingual_gettext', 10, 3);

/**
 * Add hreflang links to head
 */
function aqualuxe_multilingual_add_hreflang() {
    $languages = aqualuxe_multilingual_get_languages();
    
    foreach ($languages as $code => $language) {
        echo '<link rel="alternate" hreflang="' . esc_attr($code) . '" href="' . esc_url($language['url']) . '" />' . "\n";
    }
    
    // Add x-default hreflang
    $default_language = aqualuxe_multilingual_get_default_language();
    if (isset($languages[$default_language])) {
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($languages[$default_language]['url']) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_multilingual_add_hreflang');

/**
 * Add language information to AJAX requests
 *
 * @param array $data AJAX data
 * @return array Modified AJAX data
 */
function aqualuxe_multilingual_ajax_data($data) {
    $data['language'] = aqualuxe_multilingual_get_current_language();
    return $data;
}
add_filter('aqualuxe_ajax_data', 'aqualuxe_multilingual_ajax_data');

/**
 * Filter WooCommerce strings
 *
 * @param string $translation Translated text
 * @param string $text Text to translate
 * @param string $domain Text domain
 * @return string Filtered translation
 */
function aqualuxe_multilingual_woocommerce_gettext($translation, $text, $domain) {
    // Only filter WooCommerce domain
    if ($domain !== 'woocommerce') {
        return $translation;
    }
    
    // Get custom translation
    $custom_translation = aqualuxe_multilingual_translate($text, $domain);
    
    if ($custom_translation !== $text) {
        return $custom_translation;
    }
    
    return $translation;
}
add_filter('gettext', 'aqualuxe_multilingual_woocommerce_gettext', 10, 3);

/**
 * Language switcher widget class
 */
class AquaLuxe_Language_Switcher_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_language_switcher',
            __('AquaLuxe Language Switcher', 'aqualuxe'),
            array(
                'description' => __('Displays a language switcher for multilingual sites.', 'aqualuxe'),
                'classname' => 'widget_language_switcher',
            )
        );
    }
    
    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        aqualuxe_multilingual_language_switcher(array(
            'show_flags' => !empty($instance['show_flags']),
            'show_names' => !empty($instance['show_names']),
            'dropdown' => !empty($instance['dropdown']),
        ));
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $show_flags = isset($instance['show_flags']) ? (bool) $instance['show_flags'] : true;
        $show_names = isset($instance['show_names']) ? (bool) $instance['show_names'] : true;
        $dropdown = isset($instance['dropdown']) ? (bool) $instance['dropdown'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_flags')); ?>" name="<?php echo esc_attr($this->get_field_name('show_flags')); ?>" <?php checked($show_flags); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_flags')); ?>"><?php esc_html_e('Show flags', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_names')); ?>" name="<?php echo esc_attr($this->get_field_name('show_names')); ?>" <?php checked($show_names); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_names')); ?>"><?php esc_html_e('Show language names', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input type="checkbox" id="<?php echo esc_attr($this->get_field_id('dropdown')); ?>" name="<?php echo esc_attr($this->get_field_name('dropdown')); ?>" <?php checked($dropdown); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('dropdown')); ?>"><?php esc_html_e('Display as dropdown', 'aqualuxe'); ?></label>
        </p>
        <?php
    }
    
    /**
     * Update widget instance
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array Updated instance
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_flags'] = !empty($new_instance['show_flags']);
        $instance['show_names'] = !empty($new_instance['show_names']);
        $instance['dropdown'] = !empty($new_instance['dropdown']);
        
        return $instance;
    }
}