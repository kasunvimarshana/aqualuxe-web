<?php
/**
 * Demo content importer for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register demo import menu
 */
function aqualuxe_register_demo_import_menu() {
    add_theme_page(
        __('Import Demo Content', 'aqualuxe'),
        __('Import Demo Content', 'aqualuxe'),
        'manage_options',
        'aqualuxe-demo-import',
        'aqualuxe_demo_import_page'
    );
}
add_action('admin_menu', 'aqualuxe_register_demo_import_menu');

/**
 * Demo import page
 */
function aqualuxe_demo_import_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get available demos
    $demos = aqualuxe_get_available_demos();
    
    // Handle import
    if (isset($_POST['aqualuxe_import_demo']) && isset($_POST['aqualuxe_demo_nonce']) && wp_verify_nonce($_POST['aqualuxe_demo_nonce'], 'aqualuxe_import_demo')) {
        $demo_id = sanitize_text_field($_POST['aqualuxe_import_demo']);
        
        if (isset($demos[$demo_id])) {
            $import_options = array(
                'content' => isset($_POST['import_content']),
                'widgets' => isset($_POST['import_widgets']),
                'customizer' => isset($_POST['import_customizer']),
                'options' => isset($_POST['import_options']),
            );
            
            $result = aqualuxe_import_demo($demo_id, $import_options);
            
            if (is_wp_error($result)) {
                echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
            } else {
                echo '<div class="notice notice-success"><p>' . esc_html__('Demo content imported successfully!', 'aqualuxe') . '</p></div>';
            }
        }
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="aqualuxe-demo-import-notice">
            <p><?php esc_html_e('Importing demo content will help you get started with your website. It will import posts, pages, images, widgets, menus, and other theme settings.', 'aqualuxe'); ?></p>
            <p><strong><?php esc_html_e('WARNING:', 'aqualuxe'); ?></strong> <?php esc_html_e('Importing demo content will overwrite your current theme settings and content. It is recommended to use this feature on a fresh WordPress installation.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-demo-import-grid">
            <?php foreach ($demos as $demo_id => $demo) : ?>
                <div class="aqualuxe-demo-import-item">
                    <div class="aqualuxe-demo-import-preview">
                        <?php if (isset($demo['preview_image'])) : ?>
                            <img src="<?php echo esc_url($demo['preview_image']); ?>" alt="<?php echo esc_attr($demo['name']); ?>">
                        <?php else : ?>
                            <div class="aqualuxe-demo-import-no-preview">
                                <span><?php echo esc_html($demo['name']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="aqualuxe-demo-import-content">
                        <h3><?php echo esc_html($demo['name']); ?></h3>
                        
                        <?php if (isset($demo['description'])) : ?>
                            <p><?php echo esc_html($demo['description']); ?></p>
                        <?php endif; ?>
                        
                        <form method="post" action="">
                            <?php wp_nonce_field('aqualuxe_import_demo', 'aqualuxe_demo_nonce'); ?>
                            <input type="hidden" name="aqualuxe_import_demo" value="<?php echo esc_attr($demo_id); ?>">
                            
                            <div class="aqualuxe-demo-import-options">
                                <label>
                                    <input type="checkbox" name="import_content" checked>
                                    <?php esc_html_e('Import Content', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_widgets" checked>
                                    <?php esc_html_e('Import Widgets', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_customizer" checked>
                                    <?php esc_html_e('Import Customizer Settings', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_options" checked>
                                    <?php esc_html_e('Import Theme Options', 'aqualuxe'); ?>
                                </label>
                            </div>
                            
                            <button type="submit" class="button button-primary"><?php esc_html_e('Import Demo', 'aqualuxe'); ?></button>
                            
                            <?php if (isset($demo['preview_url'])) : ?>
                                <a href="<?php echo esc_url($demo['preview_url']); ?>" target="_blank" class="button"><?php esc_html_e('Preview', 'aqualuxe'); ?></a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
        .aqualuxe-demo-import-notice {
            background-color: #fff;
            border-left: 4px solid #00a0d2;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 10px 12px;
        }
        
        .aqualuxe-demo-import-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            grid-gap: 20px;
            margin-top: 20px;
        }
        
        .aqualuxe-demo-import-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .aqualuxe-demo-import-preview {
            height: 200px;
            overflow: hidden;
        }
        
        .aqualuxe-demo-import-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .aqualuxe-demo-import-no-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #f5f5f5;
            color: #999;
            font-size: 18px;
        }
        
        .aqualuxe-demo-import-content {
            padding: 15px;
        }
        
        .aqualuxe-demo-import-options {
            margin: 15px 0;
        }
        
        .aqualuxe-demo-import-options label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
    <?php
}

/**
 * Get available demos
 *
 * @return array
 */
function aqualuxe_get_available_demos() {
    $demos = array(
        'default' => array(
            'name' => __('Default Demo', 'aqualuxe'),
            'description' => __('The default AquaLuxe demo with all features.', 'aqualuxe'),
            'preview_image' => get_template_directory_uri() . '/assets/dist/images/demo/default-preview.jpg',
            'preview_url' => 'https://aqualuxe.demo.com',
            'files' => array(
                'content' => get_template_directory() . '/demo/default/content.xml',
                'widgets' => get_template_directory() . '/demo/default/widgets.json',
                'customizer' => get_template_directory() . '/demo/default/customizer.dat',
                'options' => get_template_directory() . '/demo/default/options.json',
            ),
        ),
        'minimal' => array(
            'name' => __('Minimal Demo', 'aqualuxe'),
            'description' => __('A minimal version of AquaLuxe with essential features.', 'aqualuxe'),
            'preview_image' => get_template_directory_uri() . '/assets/dist/images/demo/minimal-preview.jpg',
            'preview_url' => 'https://aqualuxe.demo.com/minimal',
            'files' => array(
                'content' => get_template_directory() . '/demo/minimal/content.xml',
                'widgets' => get_template_directory() . '/demo/minimal/widgets.json',
                'customizer' => get_template_directory() . '/demo/minimal/customizer.dat',
                'options' => get_template_directory() . '/demo/minimal/options.json',
            ),
        ),
        'wholesale' => array(
            'name' => __('Wholesale Demo', 'aqualuxe'),
            'description' => __('AquaLuxe demo focused on wholesale and B2B features.', 'aqualuxe'),
            'preview_image' => get_template_directory_uri() . '/assets/dist/images/demo/wholesale-preview.jpg',
            'preview_url' => 'https://aqualuxe.demo.com/wholesale',
            'files' => array(
                'content' => get_template_directory() . '/demo/wholesale/content.xml',
                'widgets' => get_template_directory() . '/demo/wholesale/widgets.json',
                'customizer' => get_template_directory() . '/demo/wholesale/customizer.dat',
                'options' => get_template_directory() . '/demo/wholesale/options.json',
            ),
        ),
    );
    
    return apply_filters('aqualuxe_available_demos', $demos);
}

/**
 * Import demo content
 *
 * @param string $demo_id Demo ID.
 * @param array $options Import options.
 * @return bool|WP_Error
 */
function aqualuxe_import_demo($demo_id, $options = array()) {
    $demos = aqualuxe_get_available_demos();
    
    if (!isset($demos[$demo_id])) {
        return new WP_Error('invalid_demo', __('Invalid demo ID.', 'aqualuxe'));
    }
    
    $demo = $demos[$demo_id];
    
    // Default options
    $default_options = array(
        'content' => true,
        'widgets' => true,
        'customizer' => true,
        'options' => true,
    );
    
    $options = wp_parse_args($options, $default_options);
    
    // Import content
    if ($options['content'] && isset($demo['files']['content']) && file_exists($demo['files']['content'])) {
        $result = aqualuxe_import_content($demo['files']['content']);
        
        if (is_wp_error($result)) {
            return $result;
        }
    }
    
    // Import widgets
    if ($options['widgets'] && isset($demo['files']['widgets']) && file_exists($demo['files']['widgets'])) {
        $result = aqualuxe_import_widgets($demo['files']['widgets']);
        
        if (is_wp_error($result)) {
            return $result;
        }
    }
    
    // Import customizer settings
    if ($options['customizer'] && isset($demo['files']['customizer']) && file_exists($demo['files']['customizer'])) {
        $result = aqualuxe_import_customizer($demo['files']['customizer']);
        
        if (is_wp_error($result)) {
            return $result;
        }
    }
    
    // Import theme options
    if ($options['options'] && isset($demo['files']['options']) && file_exists($demo['files']['options'])) {
        $result = aqualuxe_import_options($demo['files']['options']);
        
        if (is_wp_error($result)) {
            return $result;
        }
    }
    
    // Update home and blog page
    $front_page = get_page_by_title('Home');
    $blog_page = get_page_by_title('Blog');
    
    if ($front_page) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front_page->ID);
    }
    
    if ($blog_page) {
        update_option('page_for_posts', $blog_page->ID);
    }
    
    // Update permalinks
    flush_rewrite_rules();
    
    return true;
}

/**
 * Import content from WXR file
 *
 * @param string $file File path.
 * @return bool|WP_Error
 */
function aqualuxe_import_content($file) {
    // Check if WordPress Importer plugin is active
    if (!class_exists('WP_Importer')) {
        include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
    }
    
    if (!class_exists('WP_Import')) {
        include get_template_directory() . '/inc/core/wordpress-importer.php';
    }
    
    if (!class_exists('WP_Import')) {
        return new WP_Error('importer_not_found', __('WordPress Importer not found.', 'aqualuxe'));
    }
    
    // Import content
    $importer = new WP_Import();
    $importer->fetch_attachments = true;
    
    ob_start();
    $importer->import($file);
    ob_end_clean();
    
    return true;
}

/**
 * Import widgets
 *
 * @param string $file File path.
 * @return bool|WP_Error
 */
function aqualuxe_import_widgets($file) {
    // Get file contents
    $data = file_get_contents($file);
    
    if (!$data) {
        return new WP_Error('file_read_error', __('Could not read widgets file.', 'aqualuxe'));
    }
    
    $data = json_decode($data, true);
    
    if (empty($data) || !is_array($data)) {
        return new WP_Error('invalid_widgets_data', __('Invalid widgets data.', 'aqualuxe'));
    }
    
    // Get all available widgets
    global $wp_registered_sidebars, $wp_registered_widget_controls;
    
    // Clear all widgets
    $sidebars_widgets = get_option('sidebars_widgets');
    
    // Remove inactive widgets
    if (isset($sidebars_widgets['wp_inactive_widgets'])) {
        $sidebars_widgets['wp_inactive_widgets'] = array();
    }
    
    // Clear all sidebars
    foreach ($wp_registered_sidebars as $sidebar_id => $sidebar) {
        $sidebars_widgets[$sidebar_id] = array();
    }
    
    update_option('sidebars_widgets', $sidebars_widgets);
    
    // Import widgets
    foreach ($data as $sidebar_id => $widgets) {
        if (!isset($wp_registered_sidebars[$sidebar_id])) {
            continue;
        }
        
        foreach ($widgets as $widget_id => $widget_data) {
            // Get widget base ID
            $id_base = preg_replace('/-[0-9]+$/', '', $widget_id);
            
            // Get widget instance ID
            $instance_id = str_replace($id_base . '-', '', $widget_id);
            
            // Get widget instances
            $instances = get_option('widget_' . $id_base);
            
            if (!is_array($instances)) {
                $instances = array();
            }
            
            // Add widget instance
            $instances[$instance_id] = $widget_data;
            
            // Update widget instances
            update_option('widget_' . $id_base, $instances);
            
            // Add widget to sidebar
            $sidebars_widgets[$sidebar_id][] = $widget_id;
        }
    }
    
    // Update sidebars widgets
    update_option('sidebars_widgets', $sidebars_widgets);
    
    return true;
}

/**
 * Import customizer settings
 *
 * @param string $file File path.
 * @return bool|WP_Error
 */
function aqualuxe_import_customizer($file) {
    // Get file contents
    $data = file_get_contents($file);
    
    if (!$data) {
        return new WP_Error('file_read_error', __('Could not read customizer file.', 'aqualuxe'));
    }
    
    $data = unserialize(base64_decode($data));
    
    if (empty($data) || !is_array($data)) {
        return new WP_Error('invalid_customizer_data', __('Invalid customizer data.', 'aqualuxe'));
    }
    
    // Import customizer settings
    foreach ($data as $key => $value) {
        set_theme_mod($key, $value);
    }
    
    return true;
}

/**
 * Import theme options
 *
 * @param string $file File path.
 * @return bool|WP_Error
 */
function aqualuxe_import_options($file) {
    // Get file contents
    $data = file_get_contents($file);
    
    if (!$data) {
        return new WP_Error('file_read_error', __('Could not read options file.', 'aqualuxe'));
    }
    
    $data = json_decode($data, true);
    
    if (empty($data) || !is_array($data)) {
        return new WP_Error('invalid_options_data', __('Invalid options data.', 'aqualuxe'));
    }
    
    // Import theme options
    foreach ($data as $key => $value) {
        update_option($key, $value);
    }
    
    return true;
}

/**
 * Add demo import admin styles
 */
function aqualuxe_demo_import_admin_styles() {
    $screen = get_current_screen();
    
    if ($screen && $screen->id === 'appearance_page_aqualuxe-demo-import') {
        wp_enqueue_style(
            'aqualuxe-demo-import',
            get_template_directory_uri() . '/assets/dist/css/demo-import.css',
            array(),
            AQUALUXE_VERSION
        );
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_demo_import_admin_styles');

/**
 * Create demo content directory structure
 */
function aqualuxe_create_demo_content_structure() {
    $demo_dir = get_template_directory() . '/demo';
    
    // Create demo directory
    if (!file_exists($demo_dir)) {
        mkdir($demo_dir, 0755, true);
    }
    
    // Create demo subdirectories
    $demos = array('default', 'minimal', 'wholesale');
    
    foreach ($demos as $demo) {
        $dir = $demo_dir . '/' . $demo;
        
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_create_demo_content_structure');

/**
 * Generate demo content
 */
function aqualuxe_generate_demo_content() {
    // Check if demo content exists
    $default_content_file = get_template_directory() . '/demo/default/content.xml';
    
    if (file_exists($default_content_file)) {
        return;
    }
    
    // Create demo content directory structure
    aqualuxe_create_demo_content_structure();
    
    // Generate default demo content
    $default_demo_dir = get_template_directory() . '/demo/default';
    
    // Generate content.xml
    $content = aqualuxe_generate_demo_content_xml();
    file_put_contents($default_demo_dir . '/content.xml', $content);
    
    // Generate widgets.json
    $widgets = aqualuxe_generate_demo_widgets_json();
    file_put_contents($default_demo_dir . '/widgets.json', $widgets);
    
    // Generate customizer.dat
    $customizer = aqualuxe_generate_demo_customizer_dat();
    file_put_contents($default_demo_dir . '/customizer.dat', $customizer);
    
    // Generate options.json
    $options = aqualuxe_generate_demo_options_json();
    file_put_contents($default_demo_dir . '/options.json', $options);
    
    // Generate minimal demo content
    $minimal_demo_dir = get_template_directory() . '/demo/minimal';
    
    // Generate content.xml
    $content = aqualuxe_generate_demo_content_xml('minimal');
    file_put_contents($minimal_demo_dir . '/content.xml', $content);
    
    // Generate widgets.json
    $widgets = aqualuxe_generate_demo_widgets_json('minimal');
    file_put_contents($minimal_demo_dir . '/widgets.json', $widgets);
    
    // Generate customizer.dat
    $customizer = aqualuxe_generate_demo_customizer_dat('minimal');
    file_put_contents($minimal_demo_dir . '/customizer.dat', $customizer);
    
    // Generate options.json
    $options = aqualuxe_generate_demo_options_json('minimal');
    file_put_contents($minimal_demo_dir . '/options.json', $options);
    
    // Generate wholesale demo content
    $wholesale_demo_dir = get_template_directory() . '/demo/wholesale';
    
    // Generate content.xml
    $content = aqualuxe_generate_demo_content_xml('wholesale');
    file_put_contents($wholesale_demo_dir . '/content.xml', $content);
    
    // Generate widgets.json
    $widgets = aqualuxe_generate_demo_widgets_json('wholesale');
    file_put_contents($wholesale_demo_dir . '/widgets.json', $widgets);
    
    // Generate customizer.dat
    $customizer = aqualuxe_generate_demo_customizer_dat('wholesale');
    file_put_contents($wholesale_demo_dir . '/customizer.dat', $customizer);
    
    // Generate options.json
    $options = aqualuxe_generate_demo_options_json('wholesale');
    file_put_contents($wholesale_demo_dir . '/options.json', $options);
}
add_action('after_switch_theme', 'aqualuxe_generate_demo_content');

/**
 * Generate demo content XML
 *
 * @param string $demo Demo type.
 * @return string
 */
function aqualuxe_generate_demo_content_xml($demo = 'default') {
    // This is a placeholder function
    // In a real theme, this would generate a WXR file with demo content
    
    $content = '<?xml version="1.0" encoding="UTF-8" ?>';
    $content .= '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">';
    $content .= '<channel>';
    $content .= '<title>AquaLuxe Demo</title>';
    $content .= '<link>https://aqualuxe.demo.com</link>';
    $content .= '<description>AquaLuxe Demo Content</description>';
    $content .= '<pubDate>Mon, 01 Jan 2023 00:00:00 +0000</pubDate>';
    $content .= '<language>en-US</language>';
    $content .= '<wp:wxr_version>1.2</wp:wxr_version>';
    $content .= '<wp:base_site_url>https://aqualuxe.demo.com</wp:base_site_url>';
    $content .= '<wp:base_blog_url>https://aqualuxe.demo.com</wp:base_blog_url>';
    
    // Add demo content based on demo type
    switch ($demo) {
        case 'minimal':
            // Add minimal demo content
            $content .= aqualuxe_generate_minimal_demo_content();
            break;
            
        case 'wholesale':
            // Add wholesale demo content
            $content .= aqualuxe_generate_wholesale_demo_content();
            break;
            
        default:
            // Add default demo content
            $content .= aqualuxe_generate_default_demo_content();
            break;
    }
    
    $content .= '</channel>';
    $content .= '</rss>';
    
    return $content;
}

/**
 * Generate default demo content
 *
 * @return string
 */
function aqualuxe_generate_default_demo_content() {
    // This is a placeholder function
    // In a real theme, this would generate demo content for the default demo
    
    $content = '';
    
    // Add pages
    $content .= '<item>';
    $content .= '<title>Home</title>';
    $content .= '<link>https://aqualuxe.demo.com/</link>';
    $content .= '<pubDate>Mon, 01 Jan 2023 00:00:00 +0000</pubDate>';
    $content .= '<dc:creator><![CDATA[admin]]></dc:creator>';
    $content .= '<guid isPermaLink="false">https://aqualuxe.demo.com/?page_id=1</guid>';
    $content .= '<description></description>';
    $content .= '<content:encoded><![CDATA[<!-- wp:paragraph -->
<p>Welcome to AquaLuxe, your premier destination for premium ornamental aquatic solutions.</p>
<!-- /wp:paragraph -->]]></content:encoded>';
    $content .= '<excerpt:encoded><![CDATA[]]></excerpt:encoded>';
    $content .= '<wp:post_id>1</wp:post_id>';
    $content .= '<wp:post_date>2023-01-01 00:00:00</wp:post_date>';
    $content .= '<wp:post_date_gmt>2023-01-01 00:00:00</wp:post_date_gmt>';
    $content .= '<wp:comment_status>closed</wp:comment_status>';
    $content .= '<wp:ping_status>closed</wp:ping_status>';
    $content .= '<wp:post_name>home</wp:post_name>';
    $content .= '<wp:status>publish</wp:status>';
    $content .= '<wp:post_parent>0</wp:post_parent>';
    $content .= '<wp:menu_order>0</wp:menu_order>';
    $content .= '<wp:post_type>page</wp:post_type>';
    $content .= '<wp:post_password></wp:post_password>';
    $content .= '<wp:is_sticky>0</wp:is_sticky>';
    $content .= '</item>';
    
    // Add more pages, posts, products, etc.
    
    return $content;
}

/**
 * Generate minimal demo content
 *
 * @return string
 */
function aqualuxe_generate_minimal_demo_content() {
    // This is a placeholder function
    // In a real theme, this would generate demo content for the minimal demo
    
    $content = '';
    
    // Add pages
    $content .= '<item>';
    $content .= '<title>Home</title>';
    $content .= '<link>https://aqualuxe.demo.com/</link>';
    $content .= '<pubDate>Mon, 01 Jan 2023 00:00:00 +0000</pubDate>';
    $content .= '<dc:creator><![CDATA[admin]]></dc:creator>';
    $content .= '<guid isPermaLink="false">https://aqualuxe.demo.com/?page_id=1</guid>';
    $content .= '<description></description>';
    $content .= '<content:encoded><![CDATA[<!-- wp:paragraph -->
<p>Welcome to AquaLuxe Minimal, a streamlined version of our premium ornamental aquatic solutions.</p>
<!-- /wp:paragraph -->]]></content:encoded>';
    $content .= '<excerpt:encoded><![CDATA[]]></excerpt:encoded>';
    $content .= '<wp:post_id>1</wp:post_id>';
    $content .= '<wp:post_date>2023-01-01 00:00:00</wp:post_date>';
    $content .= '<wp:post_date_gmt>2023-01-01 00:00:00</wp:post_date_gmt>';
    $content .= '<wp:comment_status>closed</wp:comment_status>';
    $content .= '<wp:ping_status>closed</wp:ping_status>';
    $content .= '<wp:post_name>home</wp:post_name>';
    $content .= '<wp:status>publish</wp:status>';
    $content .= '<wp:post_parent>0</wp:post_parent>';
    $content .= '<wp:menu_order>0</wp:menu_order>';
    $content .= '<wp:post_type>page</wp:post_type>';
    $content .= '<wp:post_password></wp:post_password>';
    $content .= '<wp:is_sticky>0</wp:is_sticky>';
    $content .= '</item>';
    
    // Add more pages, posts, products, etc.
    
    return $content;
}

/**
 * Generate wholesale demo content
 *
 * @return string
 */
function aqualuxe_generate_wholesale_demo_content() {
    // This is a placeholder function
    // In a real theme, this would generate demo content for the wholesale demo
    
    $content = '';
    
    // Add pages
    $content .= '<item>';
    $content .= '<title>Home</title>';
    $content .= '<link>https://aqualuxe.demo.com/</link>';
    $content .= '<pubDate>Mon, 01 Jan 2023 00:00:00 +0000</pubDate>';
    $content .= '<dc:creator><![CDATA[admin]]></dc:creator>';
    $content .= '<guid isPermaLink="false">https://aqualuxe.demo.com/?page_id=1</guid>';
    $content .= '<description></description>';
    $content .= '<content:encoded><![CDATA[<!-- wp:paragraph -->
<p>Welcome to AquaLuxe Wholesale, your B2B partner for premium ornamental aquatic solutions.</p>
<!-- /wp:paragraph -->]]></content:encoded>';
    $content .= '<excerpt:encoded><![CDATA[]]></excerpt:encoded>';
    $content .= '<wp:post_id>1</wp:post_id>';
    $content .= '<wp:post_date>2023-01-01 00:00:00</wp:post_date>';
    $content .= '<wp:post_date_gmt>2023-01-01 00:00:00</wp:post_date_gmt>';
    $content .= '<wp:comment_status>closed</wp:comment_status>';
    $content .= '<wp:ping_status>closed</wp:ping_status>';
    $content .= '<wp:post_name>home</wp:post_name>';
    $content .= '<wp:status>publish</wp:status>';
    $content .= '<wp:post_parent>0</wp:post_parent>';
    $content .= '<wp:menu_order>0</wp:menu_order>';
    $content .= '<wp:post_type>page</wp:post_type>';
    $content .= '<wp:post_password></wp:post_password>';
    $content .= '<wp:is_sticky>0</wp:is_sticky>';
    $content .= '</item>';
    
    // Add more pages, posts, products, etc.
    
    return $content;
}

/**
 * Generate demo widgets JSON
 *
 * @param string $demo Demo type.
 * @return string
 */
function aqualuxe_generate_demo_widgets_json($demo = 'default') {
    // This is a placeholder function
    // In a real theme, this would generate a JSON file with demo widgets
    
    $widgets = array();
    
    // Add widgets based on demo type
    switch ($demo) {
        case 'minimal':
            // Add minimal demo widgets
            $widgets = array(
                'sidebar-1' => array(
                    'search-1' => array(
                        'title' => 'Search',
                    ),
                    'recent-posts-1' => array(
                        'title' => 'Recent Posts',
                        'number' => 5,
                        'show_date' => false,
                    ),
                ),
                'footer-1' => array(
                    'text-1' => array(
                        'title' => 'About Us',
                        'text' => 'AquaLuxe is a premium ornamental aquatic solutions provider.',
                    ),
                ),
                'footer-2' => array(
                    'nav_menu-1' => array(
                        'title' => 'Quick Links',
                        'nav_menu' => 'footer',
                    ),
                ),
            );
            break;
            
        case 'wholesale':
            // Add wholesale demo widgets
            $widgets = array(
                'sidebar-1' => array(
                    'search-1' => array(
                        'title' => 'Search',
                    ),
                    'recent-posts-1' => array(
                        'title' => 'Recent Posts',
                        'number' => 5,
                        'show_date' => false,
                    ),
                ),
                'footer-1' => array(
                    'text-1' => array(
                        'title' => 'About Us',
                        'text' => 'AquaLuxe Wholesale is a B2B provider of premium ornamental aquatic solutions.',
                    ),
                ),
                'footer-2' => array(
                    'nav_menu-1' => array(
                        'title' => 'Quick Links',
                        'nav_menu' => 'footer',
                    ),
                ),
                'shop-sidebar' => array(
                    'woocommerce_product_categories-1' => array(
                        'title' => 'Product Categories',
                        'orderby' => 'name',
                        'dropdown' => 0,
                        'count' => 1,
                        'hierarchical' => 1,
                        'show_children_only' => 0,
                        'hide_empty' => 1,
                    ),
                    'woocommerce_price_filter-1' => array(
                        'title' => 'Filter by Price',
                    ),
                ),
            );
            break;
            
        default:
            // Add default demo widgets
            $widgets = array(
                'sidebar-1' => array(
                    'search-1' => array(
                        'title' => 'Search',
                    ),
                    'recent-posts-1' => array(
                        'title' => 'Recent Posts',
                        'number' => 5,
                        'show_date' => false,
                    ),
                    'categories-1' => array(
                        'title' => 'Categories',
                        'count' => 1,
                        'hierarchical' => 1,
                        'dropdown' => 0,
                    ),
                ),
                'footer-1' => array(
                    'text-1' => array(
                        'title' => 'About Us',
                        'text' => 'AquaLuxe is a premium ornamental aquatic solutions provider.',
                    ),
                ),
                'footer-2' => array(
                    'nav_menu-1' => array(
                        'title' => 'Quick Links',
                        'nav_menu' => 'footer',
                    ),
                ),
                'footer-3' => array(
                    'aqualuxe_contact_info-1' => array(
                        'title' => 'Contact Us',
                        'address' => '123 Aquarium Street, Fishville, FL 12345',
                        'phone' => '+1 (555) 123-4567',
                        'email' => 'info@aqualuxe.com',
                        'hours' => 'Mon-Fri: 9am-5pm, Sat: 10am-4pm',
                    ),
                ),
                'footer-4' => array(
                    'aqualuxe_social_links-1' => array(
                        'title' => 'Follow Us',
                        'facebook' => 'https://facebook.com/aqualuxe',
                        'twitter' => 'https://twitter.com/aqualuxe',
                        'instagram' => 'https://instagram.com/aqualuxe',
                    ),
                ),
                'shop-sidebar' => array(
                    'woocommerce_product_categories-1' => array(
                        'title' => 'Product Categories',
                        'orderby' => 'name',
                        'dropdown' => 0,
                        'count' => 1,
                        'hierarchical' => 1,
                        'show_children_only' => 0,
                        'hide_empty' => 1,
                    ),
                    'woocommerce_price_filter-1' => array(
                        'title' => 'Filter by Price',
                    ),
                    'woocommerce_layered_nav-1' => array(
                        'title' => 'Filter by',
                        'attribute' => 'color',
                        'display_type' => 'list',
                        'query_type' => 'and',
                    ),
                ),
            );
            break;
    }
    
    return json_encode($widgets, JSON_PRETTY_PRINT);
}

/**
 * Generate demo customizer data
 *
 * @param string $demo Demo type.
 * @return string
 */
function aqualuxe_generate_demo_customizer_dat($demo = 'default') {
    // This is a placeholder function
    // In a real theme, this would generate a DAT file with demo customizer settings
    
    $customizer = array();
    
    // Add customizer settings based on demo type
    switch ($demo) {
        case 'minimal':
            // Add minimal demo customizer settings
            $customizer = array(
                'aqualuxe_primary_color' => '#0077b6',
                'aqualuxe_secondary_color' => '#00b4d8',
                'aqualuxe_accent_color' => '#90e0ef',
                'aqualuxe_header_layout' => 'minimal',
                'aqualuxe_footer_layout' => 'one-column',
                'aqualuxe_blog_layout' => 'no-sidebar',
                'aqualuxe_shop_layout' => 'no-sidebar',
                'aqualuxe_product_layout' => 'no-sidebar',
                'aqualuxe_enable_top_bar' => false,
                'aqualuxe_enable_breadcrumbs' => true,
                'aqualuxe_enable_related_posts' => false,
                'aqualuxe_enable_post_share' => false,
                'aqualuxe_enable_author_bio' => false,
                'aqualuxe_enable_quick_view' => false,
                'aqualuxe_enable_wishlist' => false,
                'aqualuxe_enable_compare' => false,
            );
            break;
            
        case 'wholesale':
            // Add wholesale demo customizer settings
            $customizer = array(
                'aqualuxe_primary_color' => '#1a535c',
                'aqualuxe_secondary_color' => '#4ecdc4',
                'aqualuxe_accent_color' => '#f7fff7',
                'aqualuxe_header_layout' => 'default',
                'aqualuxe_footer_layout' => 'default',
                'aqualuxe_blog_layout' => 'right-sidebar',
                'aqualuxe_shop_layout' => 'left-sidebar',
                'aqualuxe_product_layout' => 'no-sidebar',
                'aqualuxe_enable_top_bar' => true,
                'aqualuxe_enable_breadcrumbs' => true,
                'aqualuxe_enable_related_posts' => true,
                'aqualuxe_enable_post_share' => true,
                'aqualuxe_enable_author_bio' => true,
                'aqualuxe_enable_quick_view' => true,
                'aqualuxe_enable_wishlist' => true,
                'aqualuxe_enable_compare' => true,
                'aqualuxe_products_per_row' => '4',
                'aqualuxe_products_per_page' => '24',
            );
            break;
            
        default:
            // Add default demo customizer settings
            $customizer = array(
                'aqualuxe_primary_color' => '#0077b6',
                'aqualuxe_secondary_color' => '#00b4d8',
                'aqualuxe_accent_color' => '#90e0ef',
                'aqualuxe_header_layout' => 'default',
                'aqualuxe_footer_layout' => 'default',
                'aqualuxe_blog_layout' => 'right-sidebar',
                'aqualuxe_shop_layout' => 'right-sidebar',
                'aqualuxe_product_layout' => 'no-sidebar',
                'aqualuxe_enable_top_bar' => true,
                'aqualuxe_enable_breadcrumbs' => true,
                'aqualuxe_enable_related_posts' => true,
                'aqualuxe_enable_post_share' => true,
                'aqualuxe_enable_author_bio' => true,
                'aqualuxe_enable_quick_view' => true,
                'aqualuxe_enable_wishlist' => true,
                'aqualuxe_enable_compare' => true,
                'aqualuxe_products_per_row' => '3',
                'aqualuxe_products_per_page' => '12',
            );
            break;
    }
    
    return base64_encode(serialize($customizer));
}

/**
 * Generate demo options JSON
 *
 * @param string $demo Demo type.
 * @return string
 */
function aqualuxe_generate_demo_options_json($demo = 'default') {
    // This is a placeholder function
    // In a real theme, this would generate a JSON file with demo options
    
    $options = array();
    
    // Add options based on demo type
    switch ($demo) {
        case 'minimal':
            // Add minimal demo options
            $options = array(
                'aqualuxe_active_modules' => array(
                    'dark-mode' => true,
                    'multilingual' => true,
                    'subscriptions' => false,
                    'auctions' => false,
                    'bookings' => false,
                    'events' => false,
                    'wholesale' => false,
                    'trade-ins' => false,
                    'services' => false,
                    'franchise' => false,
                    'sustainability' => false,
                    'affiliate' => false,
                ),
            );
            break;
            
        case 'wholesale':
            // Add wholesale demo options
            $options = array(
                'aqualuxe_active_modules' => array(
                    'dark-mode' => true,
                    'multilingual' => true,
                    'subscriptions' => true,
                    'auctions' => false,
                    'bookings' => false,
                    'events' => false,
                    'wholesale' => true,
                    'trade-ins' => false,
                    'services' => true,
                    'franchise' => true,
                    'sustainability' => false,
                    'affiliate' => true,
                ),
            );
            break;
            
        default:
            // Add default demo options
            $options = array(
                'aqualuxe_active_modules' => array(
                    'dark-mode' => true,
                    'multilingual' => true,
                    'subscriptions' => false,
                    'auctions' => false,
                    'bookings' => false,
                    'events' => false,
                    'wholesale' => true,
                    'trade-ins' => false,
                    'services' => true,
                    'franchise' => false,
                    'sustainability' => false,
                    'affiliate' => false,
                ),
            );
            break;
    }
    
    return json_encode($options, JSON_PRETTY_PRINT);
}