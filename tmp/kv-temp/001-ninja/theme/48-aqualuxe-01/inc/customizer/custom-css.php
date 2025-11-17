<?php
/**
 * AquaLuxe Theme Custom CSS
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Add custom CSS option to the customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_custom_css($wp_customize) {
    // Custom CSS section
    $wp_customize->add_section('aqualuxe_custom_css', array(
        'title'    => __('Custom CSS', 'aqualuxe'),
        'priority' => 200,
    ));

    // Custom CSS setting
    $wp_customize->add_setting('aqualuxe_options[custom_css]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'postMessage',
    ));

    // Custom CSS control
    $wp_customize->add_control('custom_css', array(
        'label'       => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add your custom CSS here. It will be included in the head section of your site.', 'aqualuxe'),
        'section'     => 'aqualuxe_custom_css',
        'settings'    => 'aqualuxe_options[custom_css]',
        'type'        => 'textarea',
        'input_attrs' => array(
            'class' => 'code',
            'rows'  => 15,
        ),
    ));

    // Custom CSS preview
    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('aqualuxe_options[custom_css]', array(
            'selector'            => '#aqualuxe-custom-css',
            'container_inclusive' => true,
            'render_callback'     => 'aqualuxe_custom_css_output',
        ));
    }
}
add_action('customize_register', 'aqualuxe_customize_custom_css');

/**
 * Output custom CSS
 */
function aqualuxe_custom_css_output() {
    $custom_css = aqualuxe_get_option('custom_css', '');
    
    if (!empty($custom_css)) {
        echo '<style id="aqualuxe-custom-css">' . wp_strip_all_tags($custom_css) . '</style>';
    }
}
add_action('wp_head', 'aqualuxe_custom_css_output', 999);

/**
 * Add custom CSS to the customizer preview
 */
function aqualuxe_customize_preview_custom_css() {
    wp_enqueue_script(
        'aqualuxe-customizer-custom-css',
        AQUALUXE_ASSETS_URI . '/js/customizer-custom-css.js',
        array('customize-preview', 'jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_custom_css');

/**
 * Create the customizer custom CSS JavaScript file
 */
function aqualuxe_create_customizer_custom_css_js() {
    // Create the directory if it doesn't exist
    $js_dir = AQUALUXE_DIR . '/assets/dist/js';
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }

    // Create the JavaScript file
    $js_file = $js_dir . '/customizer-custom-css.js';
    $js_content = <<<EOT
/**
 * AquaLuxe Theme Customizer Custom CSS
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Custom CSS
    wp.customize('aqualuxe_options[custom_css]', function(value) {
        value.bind(function(newval) {
            // Check if the custom CSS style tag exists
            var style = $('#aqualuxe-custom-css');
            
            if (style.length) {
                // Update existing style tag
                style.html(newval);
            } else {
                // Create new style tag
                $('head').append('<style id="aqualuxe-custom-css">' + newval + '</style>');
            }
        });
    });

})(jQuery);
EOT;

    // Write the file
    file_put_contents($js_file, $js_content);
}
add_action('after_setup_theme', 'aqualuxe_create_customizer_custom_css_js');

/**
 * Add CodeMirror for the custom CSS textarea
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    wp_enqueue_code_editor(array('type' => 'text/css'));
    wp_enqueue_script(
        'aqualuxe-customizer-controls',
        AQUALUXE_ASSETS_URI . '/js/customizer-controls.js',
        array('customize-controls', 'code-editor', 'jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Create the customizer controls JavaScript file
 */
function aqualuxe_create_customizer_controls_js() {
    // Create the directory if it doesn't exist
    $js_dir = AQUALUXE_DIR . '/assets/dist/js';
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }

    // Create the JavaScript file
    $js_file = $js_dir . '/customizer-controls.js';
    $js_content = <<<EOT
/**
 * AquaLuxe Theme Customizer Controls
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    wp.customize.bind('ready', function() {
        // Initialize CodeMirror for custom CSS
        var customCSSControl = $('#customize-control-custom_css textarea');
        
        if (customCSSControl.length) {
            var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
            editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
                indentUnit: 2,
                tabSize: 2,
                mode: 'css'
            });
            
            var editor = wp.codeEditor.initialize(customCSSControl, editorSettings);
            
            // Sync changes back to the textarea
            editor.codemirror.on('change', function() {
                editor.codemirror.save();
                customCSSControl.trigger('change');
            });
        }
    });

})(jQuery);
EOT;

    // Write the file
    file_put_contents($js_file, $js_content);
}
add_action('after_setup_theme', 'aqualuxe_create_customizer_controls_js');