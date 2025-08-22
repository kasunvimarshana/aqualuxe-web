<?php
/**
 * SEO Module
 *
 * @package AquaLuxe
 * @subpackage Modules\SEO
 */

namespace AquaLuxe\Modules\SEO;

use AquaLuxe\Core\Module_Base;

/**
 * SEO Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'seo';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'SEO';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds SEO features to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = [];

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Register hooks
        add_action('wp_head', [$this, 'add_meta_tags'], 1);
        add_action('customize_register', [$this, 'customize_register']);
        add_filter('document_title_parts', [$this, 'title_parts']);
        add_filter('document_title_separator', [$this, 'title_separator']);
        add_filter('get_the_archive_title', [$this, 'archive_title']);
        add_filter('body_class', [$this, 'body_classes']);
        add_filter('post_class', [$this, 'post_classes']);
        add_action('wp_footer', [$this, 'add_schema_markup']);
        add_action('admin_menu', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        
        // Initialize SEO features
        $this->init_seo_features();
    }

    /**
     * Initialize SEO features
     *
     * @return void
     */
    private function init_seo_features() {
        // Add theme support for title tag
        add_theme_support('title-tag');
        
        // Add excerpts to pages
        add_post_type_support('page', 'excerpt');
        
        // Add image dimensions
        add_filter('wp_get_attachment_image_attributes', function ($attr) {
            if (isset($attr['src']) && !isset($attr['width']) && !isset($attr['height'])) {
                $image_id = attachment_url_to_postid($attr['src']);
                if ($image_id) {
                    $image_data = wp_get_attachment_image_src($image_id, 'full');
                    if ($image_data) {
                        $attr['width'] = $image_data[1];
                        $attr['height'] = $image_data[2];
                    }
                }
            }
            return $attr;
        });
    }

    /**
     * Add meta tags
     *
     * @return void
     */
    public function add_meta_tags() {
        // Add meta description
        $description = $this->get_meta_description();
        if ($description) {
            echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        }
        
        // Add meta keywords
        $keywords = $this->get_meta_keywords();
        if ($keywords && get_theme_mod('enable_meta_keywords', false)) {
            echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
        }
        
        // Add canonical URL
        $canonical = $this->get_canonical_url();
        if ($canonical) {
            echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
        }
        
        // Add Open Graph tags
        if (get_theme_mod('enable_open_graph', true)) {
            $this->add_open_graph_tags();
        }
        
        // Add Twitter Card tags
        if (get_theme_mod('enable_twitter_cards', true)) {
            $this->add_twitter_card_tags();
        }
        
        // Add robots meta tag
        $robots = $this->get_robots_meta();
        if ($robots) {
            echo '<meta name="robots" content="' . esc_attr($robots) . '">' . "\n";
        }
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // SEO Section
        $wp_customize->add_section(
            'seo_section',
            [
                'title' => __('SEO', 'aqualuxe'),
                'priority' => 55,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Title Separator
        $wp_customize->add_setting(
            'title_separator',
            [
                'default' => '-',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'title_separator',
            [
                'label' => __('Title Separator', 'aqualuxe'),
                'description' => __('Character to use as title separator.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'select',
                'choices' => [
                    '-' => '-',
                    '|' => '|',
                    '»' => '»',
                    '·' => '·',
                    '/' => '/',
                    '•' => '•',
                ],
            ]
        );

        // Homepage Meta Description
        $wp_customize->add_setting(
            'homepage_meta_description',
            [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'homepage_meta_description',
            [
                'label' => __('Homepage Meta Description', 'aqualuxe'),
                'description' => __('Meta description for the homepage.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'textarea',
            ]
        );

        // Homepage Meta Keywords
        $wp_customize->add_setting(
            'homepage_meta_keywords',
            [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'homepage_meta_keywords',
            [
                'label' => __('Homepage Meta Keywords', 'aqualuxe'),
                'description' => __('Meta keywords for the homepage (comma separated).', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'text',
            ]
        );

        // Enable Meta Keywords
        $wp_customize->add_setting(
            'enable_meta_keywords',
            [
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_meta_keywords',
            [
                'label' => __('Enable Meta Keywords', 'aqualuxe'),
                'description' => __('Add meta keywords tag (not recommended for modern SEO).', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'checkbox',
            ]
        );

        // Enable Open Graph
        $wp_customize->add_setting(
            'enable_open_graph',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_open_graph',
            [
                'label' => __('Enable Open Graph', 'aqualuxe'),
                'description' => __('Add Open Graph meta tags for social sharing.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'checkbox',
            ]
        );

        // Enable Twitter Cards
        $wp_customize->add_setting(
            'enable_twitter_cards',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_twitter_cards',
            [
                'label' => __('Enable Twitter Cards', 'aqualuxe'),
                'description' => __('Add Twitter Card meta tags for Twitter sharing.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'checkbox',
            ]
        );

        // Twitter Username
        $wp_customize->add_setting(
            'twitter_username',
            [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'twitter_username',
            [
                'label' => __('Twitter Username', 'aqualuxe'),
                'description' => __('Your Twitter username (without @).', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'text',
            ]
        );

        // Facebook App ID
        $wp_customize->add_setting(
            'facebook_app_id',
            [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'facebook_app_id',
            [
                'label' => __('Facebook App ID', 'aqualuxe'),
                'description' => __('Your Facebook App ID.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'text',
            ]
        );

        // Default Social Image
        $wp_customize->add_setting(
            'default_social_image',
            [
                'default' => '',
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Media_Control(
                $wp_customize,
                'default_social_image',
                [
                    'label' => __('Default Social Image', 'aqualuxe'),
                    'description' => __('Default image for social sharing when no featured image is available.', 'aqualuxe'),
                    'section' => 'seo_section',
                    'mime_type' => 'image',
                ]
            )
        );

        // Enable Schema Markup
        $wp_customize->add_setting(
            'enable_schema_markup',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'enable_schema_markup',
            [
                'label' => __('Enable Schema Markup', 'aqualuxe'),
                'description' => __('Add schema.org structured data markup.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'checkbox',
            ]
        );

        // Organization Name
        $wp_customize->add_setting(
            'organization_name',
            [
                'default' => get_bloginfo('name'),
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'organization_name',
            [
                'label' => __('Organization Name', 'aqualuxe'),
                'description' => __('Name of your organization for schema markup.', 'aqualuxe'),
                'section' => 'seo_section',
                'type' => 'text',
            ]
        );

        // Organization Logo
        $wp_customize->add_setting(
            'organization_logo',
            [
                'default' => '',
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Media_Control(
                $wp_customize,
                'organization_logo',
                [
                    'label' => __('Organization Logo', 'aqualuxe'),
                    'description' => __('Logo of your organization for schema markup.', 'aqualuxe'),
                    'section' => 'seo_section',
                    'mime_type' => 'image',
                ]
            )
        );
    }

    /**
     * Filter title parts
     *
     * @param array $title Title parts
     * @return array
     */
    public function title_parts($title) {
        // Customize title parts if needed
        return $title;
    }

    /**
     * Filter title separator
     *
     * @param string $sep Title separator
     * @return string
     */
    public function title_separator($sep) {
        $separator = get_theme_mod('title_separator', '-');
        return $separator;
    }

    /**
     * Filter archive title
     *
     * @param string $title Archive title
     * @return string
     */
    public function archive_title($title) {
        // Remove "Category:", "Tag:", "Author:", etc. from the archive title
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = get_the_author();
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        } elseif (is_tax()) {
            $title = single_term_title('', false);
        }
        
        return $title;
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        // Add page slug
        if (is_singular()) {
            global $post;
            $classes[] = 'page-' . $post->post_name;
        }
        
        return $classes;
    }

    /**
     * Add post classes
     *
     * @param array $classes Post classes
     * @return array
     */
    public function post_classes($classes) {
        // Add custom post classes if needed
        return $classes;
    }

    /**
     * Add schema markup
     *
     * @return void
     */
    public function add_schema_markup() {
        if (!get_theme_mod('enable_schema_markup', true)) {
            return;
        }
        
        // Website schema
        $this->add_website_schema();
        
        // Organization schema
        $this->add_organization_schema();
        
        // Breadcrumb schema
        $this->add_breadcrumb_schema();
        
        // Content schema
        if (is_singular()) {
            $this->add_content_schema();
        }
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        $post_types = ['post', 'page', 'product'];
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'aqualuxe_seo_meta_box',
                __('SEO Settings', 'aqualuxe'),
                [$this, 'render_meta_box'],
                $post_type,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render meta box
     *
     * @param WP_Post $post Post object
     * @return void
     */
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_seo_meta_box', 'aqualuxe_seo_meta_box_nonce');
        
        // Get saved values
        $meta_title = get_post_meta($post->ID, '_aqualuxe_meta_title', true);
        $meta_description = get_post_meta($post->ID, '_aqualuxe_meta_description', true);
        $meta_keywords = get_post_meta($post->ID, '_aqualuxe_meta_keywords', true);
        $robots_noindex = get_post_meta($post->ID, '_aqualuxe_robots_noindex', true);
        $robots_nofollow = get_post_meta($post->ID, '_aqualuxe_robots_nofollow', true);
        $canonical_url = get_post_meta($post->ID, '_aqualuxe_canonical_url', true);
        $social_image_id = get_post_meta($post->ID, '_aqualuxe_social_image_id', true);
        
        // Get social image URL
        $social_image_url = '';
        if ($social_image_id) {
            $social_image_url = wp_get_attachment_image_url($social_image_id, 'full');
        }
        
        ?>
        <div class="aqualuxe-seo-meta-box">
            <p>
                <label for="aqualuxe_meta_title"><?php esc_html_e('Meta Title', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_meta_title" name="aqualuxe_meta_title" value="<?php echo esc_attr($meta_title); ?>" class="widefat">
                <span class="description"><?php esc_html_e('Custom meta title for this page. Leave blank to use the default title.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_meta_description"><?php esc_html_e('Meta Description', 'aqualuxe'); ?></label>
                <textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3" class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
                <span class="description"><?php esc_html_e('Custom meta description for this page. Leave blank to use the excerpt or auto-generated description.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_meta_keywords"><?php esc_html_e('Meta Keywords', 'aqualuxe'); ?></label>
                <input type="text" id="aqualuxe_meta_keywords" name="aqualuxe_meta_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="widefat">
                <span class="description"><?php esc_html_e('Custom meta keywords for this page (comma separated). Leave blank to use the default keywords.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="aqualuxe_canonical_url"><?php esc_html_e('Canonical URL', 'aqualuxe'); ?></label>
                <input type="url" id="aqualuxe_canonical_url" name="aqualuxe_canonical_url" value="<?php echo esc_url($canonical_url); ?>" class="widefat">
                <span class="description"><?php esc_html_e('Custom canonical URL for this page. Leave blank to use the default canonical URL.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label><?php esc_html_e('Robots Meta', 'aqualuxe'); ?></label>
                <br>
                <label>
                    <input type="checkbox" name="aqualuxe_robots_noindex" value="1" <?php checked($robots_noindex, '1'); ?>>
                    <?php esc_html_e('noindex', 'aqualuxe'); ?>
                </label>
                <label>
                    <input type="checkbox" name="aqualuxe_robots_nofollow" value="1" <?php checked($robots_nofollow, '1'); ?>>
                    <?php esc_html_e('nofollow', 'aqualuxe'); ?>
                </label>
                <br>
                <span class="description"><?php esc_html_e('Check to prevent search engines from indexing or following links on this page.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label><?php esc_html_e('Social Image', 'aqualuxe'); ?></label>
                <div class="aqualuxe-social-image-container">
                    <div class="aqualuxe-social-image-preview">
                        <?php if ($social_image_url) : ?>
                            <img src="<?php echo esc_url($social_image_url); ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="aqualuxe_social_image_id" id="aqualuxe_social_image_id" value="<?php echo esc_attr($social_image_id); ?>">
                    <button type="button" class="button aqualuxe-social-image-upload"><?php esc_html_e('Select Image', 'aqualuxe'); ?></button>
                    <button type="button" class="button aqualuxe-social-image-remove" <?php echo $social_image_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e('Remove Image', 'aqualuxe'); ?></button>
                    <br>
                    <span class="description"><?php esc_html_e('Custom image for social sharing. Leave blank to use the featured image.', 'aqualuxe'); ?></span>
                </div>
            </p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Social image upload
                $('.aqualuxe-social-image-upload').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.aqualuxe-social-image-container');
                    var preview = container.find('.aqualuxe-social-image-preview');
                    var input = container.find('#aqualuxe_social_image_id');
                    var removeButton = container.find('.aqualuxe-social-image-remove');
                    
                    var frame = wp.media({
                        title: '<?php esc_html_e('Select Social Image', 'aqualuxe'); ?>',
                        multiple: false,
                        library: {
                            type: 'image'
                        },
                        button: {
                            text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                        }
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        preview.html('<img src="' + attachment.url + '" alt="">');
                        input.val(attachment.id);
                        removeButton.show();
                    });
                    
                    frame.open();
                });
                
                // Social image remove
                $('.aqualuxe-social-image-remove').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.aqualuxe-social-image-container');
                    var preview = container.find('.aqualuxe-social-image-preview');
                    var input = container.find('#aqualuxe_social_image_id');
                    
                    preview.html('');
                    input.val('');
                    button.hide();
                });
            });
        </script>
        
        <style>
            .aqualuxe-seo-meta-box p {
                margin: 1em 0;
            }
            
            .aqualuxe-seo-meta-box label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .aqualuxe-seo-meta-box .description {
                display: block;
                color: #666;
                font-style: italic;
                margin-top: 5px;
            }
            
            .aqualuxe-seo-meta-box input[type="checkbox"] {
                margin-right: 5px;
            }
            
            .aqualuxe-seo-meta-box input[type="checkbox"] + label {
                display: inline-block;
                margin-right: 15px;
            }
            
            .aqualuxe-social-image-preview {
                margin-bottom: 10px;
                min-height: 10px;
            }
            
            .aqualuxe-social-image-preview img {
                max-width: 300px;
                height: auto;
                border: 1px solid #ddd;
            }
            
            .aqualuxe-social-image-remove {
                margin-left: 5px;
            }
        </style>
        <?php
    }

    /**
     * Save meta boxes
     *
     * @param int $post_id Post ID
     * @return void
     */
    public function save_meta_boxes($post_id) {
        // Check if nonce is set
        if (!isset($_POST['aqualuxe_seo_meta_box_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['aqualuxe_seo_meta_box_nonce'], 'aqualuxe_seo_meta_box')) {
            return;
        }
        
        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if ('page' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save meta title
        if (isset($_POST['aqualuxe_meta_title'])) {
            update_post_meta($post_id, '_aqualuxe_meta_title', sanitize_text_field($_POST['aqualuxe_meta_title']));
        }
        
        // Save meta description
        if (isset($_POST['aqualuxe_meta_description'])) {
            update_post_meta($post_id, '_aqualuxe_meta_description', sanitize_textarea_field($_POST['aqualuxe_meta_description']));
        }
        
        // Save meta keywords
        if (isset($_POST['aqualuxe_meta_keywords'])) {
            update_post_meta($post_id, '_aqualuxe_meta_keywords', sanitize_text_field($_POST['aqualuxe_meta_keywords']));
        }
        
        // Save robots meta
        update_post_meta($post_id, '_aqualuxe_robots_noindex', isset($_POST['aqualuxe_robots_noindex']) ? '1' : '');
        update_post_meta($post_id, '_aqualuxe_robots_nofollow', isset($_POST['aqualuxe_robots_nofollow']) ? '1' : '');
        
        // Save canonical URL
        if (isset($_POST['aqualuxe_canonical_url'])) {
            update_post_meta($post_id, '_aqualuxe_canonical_url', esc_url_raw($_POST['aqualuxe_canonical_url']));
        }
        
        // Save social image ID
        if (isset($_POST['aqualuxe_social_image_id'])) {
            update_post_meta($post_id, '_aqualuxe_social_image_id', absint($_POST['aqualuxe_social_image_id']));
        }
    }

    /**
     * Get meta description
     *
     * @return string
     */
    private function get_meta_description() {
        $description = '';
        
        if (is_singular()) {
            // Get custom meta description
            $description = get_post_meta(get_the_ID(), '_aqualuxe_meta_description', true);
            
            // Fallback to excerpt
            if (!$description) {
                $description = get_the_excerpt();
            }
            
            // Fallback to content
            if (!$description) {
                $description = wp_trim_words(strip_shortcodes(get_the_content()), 30, '...');
            }
        } elseif (is_home() || is_front_page()) {
            // Get homepage meta description
            $description = get_theme_mod('homepage_meta_description', '');
            
            // Fallback to site description
            if (!$description) {
                $description = get_bloginfo('description');
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            // Get term description
            $description = term_description();
        } elseif (is_author()) {
            // Get author description
            $description = get_the_author_meta('description');
        }
        
        return wp_strip_all_tags($description);
    }

    /**
     * Get meta keywords
     *
     * @return string
     */
    private function get_meta_keywords() {
        $keywords = '';
        
        if (is_singular()) {
            // Get custom meta keywords
            $keywords = get_post_meta(get_the_ID(), '_aqualuxe_meta_keywords', true);
            
            // Fallback to tags
            if (!$keywords && is_singular('post')) {
                $tags = get_the_tags();
                if ($tags) {
                    $tag_names = [];
                    foreach ($tags as $tag) {
                        $tag_names[] = $tag->name;
                    }
                    $keywords = implode(', ', $tag_names);
                }
            }
        } elseif (is_home() || is_front_page()) {
            // Get homepage meta keywords
            $keywords = get_theme_mod('homepage_meta_keywords', '');
        }
        
        return $keywords;
    }

    /**
     * Get canonical URL
     *
     * @return string
     */
    private function get_canonical_url() {
        $canonical = '';
        
        if (is_singular()) {
            // Get custom canonical URL
            $canonical = get_post_meta(get_the_ID(), '_aqualuxe_canonical_url', true);
            
            // Fallback to permalink
            if (!$canonical) {
                $canonical = get_permalink();
            }
        } elseif (is_home()) {
            $canonical = get_permalink(get_option('page_for_posts'));
        } elseif (is_front_page()) {
            $canonical = home_url('/');
        } elseif (is_category() || is_tag() || is_tax()) {
            $canonical = get_term_link(get_queried_object());
        } elseif (is_author()) {
            $canonical = get_author_posts_url(get_queried_object_id());
        } elseif (is_year()) {
            $canonical = get_year_link(get_query_var('year'));
        } elseif (is_month()) {
            $canonical = get_month_link(get_query_var('year'), get_query_var('monthnum'));
        } elseif (is_day()) {
            $canonical = get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day'));
        } elseif (is_post_type_archive()) {
            $canonical = get_post_type_archive_link(get_post_type());
        }
        
        return $canonical;
    }

    /**
     * Get robots meta
     *
     * @return string
     */
    private function get_robots_meta() {
        $robots = [];
        
        // Check if site is set to noindex
        if ('0' === get_option('blog_public')) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
            return implode(',', $robots);
        }
        
        if (is_singular()) {
            // Check custom robots meta
            $noindex = get_post_meta(get_the_ID(), '_aqualuxe_robots_noindex', true);
            $nofollow = get_post_meta(get_the_ID(), '_aqualuxe_robots_nofollow', true);
            
            if ($noindex) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            
            if ($nofollow) {
                $robots[] = 'nofollow';
            } else {
                $robots[] = 'follow';
            }
        } elseif (is_search() || is_404()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } elseif (is_archive()) {
            // Check if archives should be indexed
            if (get_theme_mod('noindex_archives', false)) {
                $robots[] = 'noindex';
            } else {
                $robots[] = 'index';
            }
            
            $robots[] = 'follow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
        }
        
        return implode(',', $robots);
    }

    /**
     * Add Open Graph tags
     *
     * @return void
     */
    private function add_open_graph_tags() {
        // Basic Open Graph tags
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($this->get_og_type()) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($this->get_canonical_url()) . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        
        // Facebook App ID
        $fb_app_id = get_theme_mod('facebook_app_id', '');
        if ($fb_app_id) {
            echo '<meta property="fb:app_id" content="' . esc_attr($fb_app_id) . '">' . "\n";
        }
        
        // Open Graph image
        $og_image = $this->get_og_image();
        if ($og_image) {
            echo '<meta property="og:image" content="' . esc_url($og_image['url']) . '">' . "\n";
            
            if (isset($og_image['width']) && isset($og_image['height'])) {
                echo '<meta property="og:image:width" content="' . esc_attr($og_image['width']) . '">' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($og_image['height']) . '">' . "\n";
            }
        }
        
        // Article specific tags
        if (is_singular('post')) {
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
            
            // Article author
            $author_url = get_author_posts_url(get_the_author_meta('ID'));
            echo '<meta property="article:author" content="' . esc_url($author_url) . '">' . "\n";
            
            // Article section (primary category)
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
            }
            
            // Article tags
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                }
            }
        }
    }

    /**
     * Add Twitter Card tags
     *
     * @return void
     */
    private function add_twitter_card_tags() {
        // Get Twitter username
        $twitter_username = get_theme_mod('twitter_username', '');
        
        // Determine card type
        $card_type = 'summary_large_image';
        $og_image = $this->get_og_image();
        if (!$og_image) {
            $card_type = 'summary';
        }
        
        // Add Twitter Card tags
        echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '">' . "\n";
        
        if ($twitter_username) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '">' . "\n";
            
            if (is_singular()) {
                echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_username) . '">' . "\n";
            }
        }
        
        echo '<meta name="twitter:title" content="' . esc_attr($this->get_og_title()) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($this->get_meta_description()) . '">' . "\n";
        
        if ($og_image) {
            echo '<meta name="twitter:image" content="' . esc_url($og_image['url']) . '">' . "\n";
        }
    }

    /**
     * Get Open Graph type
     *
     * @return string
     */
    private function get_og_type() {
        if (is_singular('post')) {
            return 'article';
        } elseif (is_singular('product') && class_exists('WooCommerce')) {
            return 'product';
        } elseif (is_author()) {
            return 'profile';
        }
        
        return 'website';
    }

    /**
     * Get Open Graph title
     *
     * @return string
     */
    private function get_og_title() {
        $title = '';
        
        if (is_singular()) {
            // Get custom meta title
            $title = get_post_meta(get_the_ID(), '_aqualuxe_meta_title', true);
            
            // Fallback to post title
            if (!$title) {
                $title = get_the_title();
            }
        } elseif (is_home() || is_front_page()) {
            $title = get_bloginfo('name');
        } elseif (is_category() || is_tag() || is_tax()) {
            $title = single_term_title('', false);
        } elseif (is_author()) {
            $title = get_the_author_meta('display_name');
        } elseif (is_search()) {
            $title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
        } elseif (is_404()) {
            $title = __('Page Not Found', 'aqualuxe');
        } else {
            $title = wp_get_document_title();
        }
        
        return $title;
    }

    /**
     * Get Open Graph image
     *
     * @return array|false
     */
    private function get_og_image() {
        $image = false;
        
        if (is_singular()) {
            // Get custom social image
            $social_image_id = get_post_meta(get_the_ID(), '_aqualuxe_social_image_id', true);
            
            if ($social_image_id) {
                $image_data = wp_get_attachment_image_src($social_image_id, 'full');
                
                if ($image_data) {
                    $image = [
                        'url' => $image_data[0],
                        'width' => $image_data[1],
                        'height' => $image_data[2],
                    ];
                }
            }
            
            // Fallback to featured image
            if (!$image && has_post_thumbnail()) {
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                
                if ($image_data) {
                    $image = [
                        'url' => $image_data[0],
                        'width' => $image_data[1],
                        'height' => $image_data[2],
                    ];
                }
            }
        }
        
        // Fallback to default social image
        if (!$image) {
            $default_image_id = get_theme_mod('default_social_image', '');
            
            if ($default_image_id) {
                $image_data = wp_get_attachment_image_src($default_image_id, 'full');
                
                if ($image_data) {
                    $image = [
                        'url' => $image_data[0],
                        'width' => $image_data[1],
                        'height' => $image_data[2],
                    ];
                }
            }
        }
        
        // Fallback to site logo
        if (!$image) {
            $logo_id = get_theme_mod('custom_logo');
            
            if ($logo_id) {
                $image_data = wp_get_attachment_image_src($logo_id, 'full');
                
                if ($image_data) {
                    $image = [
                        'url' => $image_data[0],
                        'width' => $image_data[1],
                        'height' => $image_data[2],
                    ];
                }
            }
        }
        
        return $image;
    }

    /**
     * Add website schema
     *
     * @return void
     */
    private function add_website_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => home_url('/'),
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add organization schema
     *
     * @return void
     */
    private function add_organization_schema() {
        $organization_name = get_theme_mod('organization_name', get_bloginfo('name'));
        $organization_logo_id = get_theme_mod('organization_logo', '');
        $organization_logo_url = '';
        
        if ($organization_logo_id) {
            $organization_logo_url = wp_get_attachment_image_url($organization_logo_id, 'full');
        } else {
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $organization_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
            }
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'url' => home_url('/'),
            'name' => $organization_name,
        ];
        
        if ($organization_logo_url) {
            $schema['logo'] = $organization_logo_url;
        }
        
        // Add social profiles
        $social_profiles = [];
        
        $facebook_url = get_theme_mod('facebook_url', '');
        if ($facebook_url) {
            $social_profiles[] = $facebook_url;
        }
        
        $twitter_username = get_theme_mod('twitter_username', '');
        if ($twitter_username) {
            $social_profiles[] = 'https://twitter.com/' . $twitter_username;
        }
        
        $instagram_url = get_theme_mod('instagram_url', '');
        if ($instagram_url) {
            $social_profiles[] = $instagram_url;
        }
        
        $linkedin_url = get_theme_mod('linkedin_url', '');
        if ($linkedin_url) {
            $social_profiles[] = $linkedin_url;
        }
        
        $youtube_url = get_theme_mod('youtube_url', '');
        if ($youtube_url) {
            $social_profiles[] = $youtube_url;
        }
        
        if (!empty($social_profiles)) {
            $schema['sameAs'] = $social_profiles;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add breadcrumb schema
     *
     * @return void
     */
    private function add_breadcrumb_schema() {
        if (is_front_page() || is_home()) {
            return;
        }
        
        $breadcrumbs = [];
        $position = 1;
        
        // Add home
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'item' => [
                '@id' => home_url('/'),
                'name' => __('Home', 'aqualuxe'),
            ],
        ];
        
        if (is_singular('post')) {
            // Add blog
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(get_option('page_for_posts')),
                    'name' => __('Blog', 'aqualuxe'),
                ],
            ];
            
            // Add categories
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $position++;
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'item' => [
                        '@id' => get_category_link($category->term_id),
                        'name' => $category->name,
                    ],
                ];
            }
            
            // Add post
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_singular('page')) {
            // Add ancestors
            $ancestors = get_post_ancestors(get_the_ID());
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    $position++;
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'item' => [
                            '@id' => get_permalink($ancestor),
                            'name' => get_the_title($ancestor),
                        ],
                    ];
                }
            }
            
            // Add page
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_permalink(),
                    'name' => get_the_title(),
                ],
            ];
        } elseif (is_category() || is_tag() || is_tax()) {
            // Add term
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_term_link(get_queried_object()),
                    'name' => single_term_title('', false),
                ],
            ];
        } elseif (is_author()) {
            // Add author
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_author_posts_url(get_queried_object_id()),
                    'name' => get_the_author_meta('display_name', get_queried_object_id()),
                ],
            ];
        } elseif (is_search()) {
            // Add search
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => get_search_link(),
                    'name' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
                ],
            ];
        } elseif (is_404()) {
            // Add 404
            $position++;
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'item' => [
                    '@id' => home_url(add_query_arg([])),
                    'name' => __('Page Not Found', 'aqualuxe'),
                ],
            ];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ];
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add content schema
     *
     * @return void
     */
    private function add_content_schema() {
        if (is_singular('post')) {
            $this->add_article_schema();
        } elseif (is_singular('product') && class_exists('WooCommerce')) {
            $this->add_product_schema();
        }
    }

    /**
     * Add article schema
     *
     * @return void
     */
    private function add_article_schema() {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ],
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID')),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => '',
                    'width' => 600,
                    'height' => 60,
                ],
            ],
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            
            if ($image_data) {
                $schema['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $image_data[0],
                    'width' => $image_data[1],
                    'height' => $image_data[2],
                ];
            }
        }
        
        // Add publisher logo
        $organization_logo_id = get_theme_mod('organization_logo', '');
        $organization_logo_url = '';
        
        if ($organization_logo_id) {
            $organization_logo_url = wp_get_attachment_image_url($organization_logo_id, 'full');
        } else {
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $organization_logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
            }
        }
        
        if ($organization_logo_url) {
            $schema['publisher']['logo']['url'] = $organization_logo_url;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }

    /**
     * Add product schema
     *
     * @return void
     */
    private function add_product_schema() {
        if (!function_exists('wc_get_product')) {
            return;
        }
        
        $product = wc_get_product(get_the_ID());
        
        if (!$product) {
            return;
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => $product->get_sku(),
            'brand' => [
                '@type' => 'Brand',
                'name' => get_bloginfo('name'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
                'priceValidUntil' => date('Y-12-31', strtotime('+1 year')),
            ],
        ];
        
        // Add product image
        if (has_post_thumbnail()) {
            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            
            if ($image_data) {
                $schema['image'] = $image_data[0];
            }
        }
        
        // Add product rating
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            ];
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}

// Initialize the module
new Module();