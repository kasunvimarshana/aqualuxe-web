<?php
/**
 * Open Graph functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Add Open Graph meta tags to the head
 */
function aqualuxe_open_graph_tags() {
    // Skip if Yoast SEO or Rank Math is active as they handle Open Graph tags
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    global $post;
    
    // Default values
    $og_title = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url = home_url('/');
    $og_image = '';
    $og_type = 'website';
    $og_locale = get_locale();
    
    // Get default image from customizer
    $default_image = get_theme_mod('aqualuxe_og_default_image');
    if ($default_image) {
        $og_image = $default_image;
    }
    
    // Specific values based on page type
    if (is_singular() && $post) {
        $og_title = get_the_title();
        $og_url = get_permalink();
        $og_type = 'article';
        
        // Get post excerpt or content for description
        $excerpt = get_the_excerpt();
        if (!empty($excerpt)) {
            $og_description = wp_strip_all_tags($excerpt);
        } else {
            $og_description = wp_strip_all_tags(get_the_content());
            $og_description = preg_replace('/\s+/', ' ', $og_description);
            $og_description = substr($og_description, 0, 300);
        }
        
        // Get featured image
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail = wp_get_attachment_image_src($thumbnail_id, 'large');
            if ($thumbnail) {
                $og_image = $thumbnail[0];
            }
        }
        
        // Additional article tags
        $article_published_time = get_the_date('c');
        $article_modified_time = get_the_modified_date('c');
        $article_author = get_the_author();
        
        // Get post categories
        $categories = get_the_category();
        $article_section = '';
        if (!empty($categories)) {
            $article_section = $categories[0]->name;
        }
        
        // Get post tags
        $tags = get_the_tags();
        $article_tags = array();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $article_tags[] = $tag->name;
            }
        }
    } elseif (is_author()) {
        $author = get_queried_object();
        $og_title = $author->display_name;
        $og_description = get_the_author_meta('description', $author->ID);
        $og_url = get_author_posts_url($author->ID);
        $og_type = 'profile';
        
        // Get author avatar
        $avatar = get_avatar_url($author->ID, array('size' => 200));
        if ($avatar) {
            $og_image = $avatar;
        }
        
        // Additional profile tags
        $profile_first_name = get_the_author_meta('first_name', $author->ID);
        $profile_last_name = get_the_author_meta('last_name', $author->ID);
        $profile_username = $author->user_login;
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $og_title = $term->name;
        $og_description = wp_strip_all_tags($term->description);
        $og_url = get_term_link($term);
    } elseif (is_post_type_archive()) {
        $post_type = get_queried_object();
        $og_title = $post_type->labels->name;
        $og_url = get_post_type_archive_link($post_type->name);
    } elseif (is_search()) {
        $og_title = sprintf(__('Search results for "%s"', 'aqualuxe'), get_search_query());
        $og_url = get_search_link();
    } elseif (is_404()) {
        $og_title = __('Page not found', 'aqualuxe');
    }
    
    // WooCommerce specific tags
    if (class_exists('WooCommerce')) {
        if (is_product()) {
            $product = wc_get_product($post->ID);
            $og_type = 'product';
            
            // Additional product tags
            $product_price = $product->get_price();
            $product_currency = get_woocommerce_currency();
            $product_availability = $product->is_in_stock() ? 'instock' : 'oos';
            
            // Get product gallery images
            $attachment_ids = $product->get_gallery_image_ids();
            $product_images = array();
            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $image = wp_get_attachment_image_src($attachment_id, 'large');
                    if ($image) {
                        $product_images[] = $image[0];
                    }
                }
            }
        } elseif (is_product_category()) {
            $term = get_queried_object();
            $og_title = $term->name;
            $og_description = wp_strip_all_tags($term->description);
            $og_url = get_term_link($term);
            
            // Get category thumbnail
            $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
            if ($thumbnail_id) {
                $image = wp_get_attachment_image_src($thumbnail_id, 'large');
                if ($image) {
                    $og_image = $image[0];
                }
            }
        } elseif (is_shop()) {
            $og_title = get_the_title(wc_get_page_id('shop'));
            $og_url = get_permalink(wc_get_page_id('shop'));
        }
    }
    
    // Trim description to 300 characters
    $og_description = substr($og_description, 0, 300);
    
    // Output Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:locale" content="' . esc_attr($og_locale) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    // Output image tag if available
    if (!empty($og_image)) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
        
        // Get image dimensions if possible
        $image_id = attachment_url_to_postid($og_image);
        if ($image_id) {
            $image_meta = wp_get_attachment_metadata($image_id);
            if (!empty($image_meta['width']) && !empty($image_meta['height'])) {
                echo '<meta property="og:image:width" content="' . esc_attr($image_meta['width']) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image_meta['height']) . '" />' . "\n";
            }
        }
    }
    
    // Additional article tags
    if ($og_type === 'article' && isset($article_published_time)) {
        echo '<meta property="article:published_time" content="' . esc_attr($article_published_time) . '" />' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr($article_modified_time) . '" />' . "\n";
        
        if (!empty($article_section)) {
            echo '<meta property="article:section" content="' . esc_attr($article_section) . '" />' . "\n";
        }
        
        if (!empty($article_tags)) {
            foreach ($article_tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag) . '" />' . "\n";
            }
        }
        
        echo '<meta property="article:author" content="' . esc_attr($article_author) . '" />' . "\n";
    }
    
    // Additional profile tags
    if ($og_type === 'profile' && isset($profile_first_name)) {
        echo '<meta property="profile:first_name" content="' . esc_attr($profile_first_name) . '" />' . "\n";
        echo '<meta property="profile:last_name" content="' . esc_attr($profile_last_name) . '" />' . "\n";
        echo '<meta property="profile:username" content="' . esc_attr($profile_username) . '" />' . "\n";
    }
    
    // Additional product tags
    if ($og_type === 'product' && isset($product_price)) {
        echo '<meta property="product:price:amount" content="' . esc_attr($product_price) . '" />' . "\n";
        echo '<meta property="product:price:currency" content="' . esc_attr($product_currency) . '" />' . "\n";
        echo '<meta property="product:availability" content="' . esc_attr($product_availability) . '" />' . "\n";
        
        if (!empty($product_images)) {
            foreach ($product_images as $image) {
                echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
            }
        }
    }
}
add_action('wp_head', 'aqualuxe_open_graph_tags', 5);

/**
 * Add Twitter Card meta tags to the head
 */
function aqualuxe_twitter_card_tags() {
    // Skip if Yoast SEO or Rank Math is active as they handle Twitter Card tags
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    global $post;
    
    // Default values
    $twitter_card = 'summary_large_image';
    $twitter_title = get_bloginfo('name');
    $twitter_description = get_bloginfo('description');
    $twitter_image = '';
    
    // Get Twitter username from customizer
    $twitter_site = get_theme_mod('aqualuxe_twitter_username');
    
    // Get default image from customizer
    $default_image = get_theme_mod('aqualuxe_og_default_image');
    if ($default_image) {
        $twitter_image = $default_image;
    }
    
    // Specific values based on page type
    if (is_singular() && $post) {
        $twitter_title = get_the_title();
        
        // Get post excerpt or content for description
        $excerpt = get_the_excerpt();
        if (!empty($excerpt)) {
            $twitter_description = wp_strip_all_tags($excerpt);
        } else {
            $twitter_description = wp_strip_all_tags(get_the_content());
            $twitter_description = preg_replace('/\s+/', ' ', $twitter_description);
            $twitter_description = substr($twitter_description, 0, 200);
        }
        
        // Get featured image
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail = wp_get_attachment_image_src($thumbnail_id, 'large');
            if ($thumbnail) {
                $twitter_image = $thumbnail[0];
            }
        }
        
        // Get author Twitter username
        $twitter_creator = get_the_author_meta('twitter', $post->post_author);
    } elseif (is_author()) {
        $author = get_queried_object();
        $twitter_title = $author->display_name;
        $twitter_description = get_the_author_meta('description', $author->ID);
        
        // Get author avatar
        $avatar = get_avatar_url($author->ID, array('size' => 200));
        if ($avatar) {
            $twitter_image = $avatar;
        }
        
        // Get author Twitter username
        $twitter_creator = get_the_author_meta('twitter', $author->ID);
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $twitter_title = $term->name;
        $twitter_description = wp_strip_all_tags($term->description);
    } elseif (is_post_type_archive()) {
        $post_type = get_queried_object();
        $twitter_title = $post_type->labels->name;
    } elseif (is_search()) {
        $twitter_title = sprintf(__('Search results for "%s"', 'aqualuxe'), get_search_query());
    } elseif (is_404()) {
        $twitter_title = __('Page not found', 'aqualuxe');
    }
    
    // WooCommerce specific tags
    if (class_exists('WooCommerce')) {
        if (is_product()) {
            $product = wc_get_product($post->ID);
            
            // Get product gallery images
            $attachment_ids = $product->get_gallery_image_ids();
            if ($attachment_ids) {
                $attachment_id = reset($attachment_ids);
                $image = wp_get_attachment_image_src($attachment_id, 'large');
                if ($image) {
                    $twitter_image = $image[0];
                }
            }
        } elseif (is_product_category()) {
            $term = get_queried_object();
            $twitter_title = $term->name;
            $twitter_description = wp_strip_all_tags($term->description);
            
            // Get category thumbnail
            $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
            if ($thumbnail_id) {
                $image = wp_get_attachment_image_src($thumbnail_id, 'large');
                if ($image) {
                    $twitter_image = $image[0];
                }
            }
        } elseif (is_shop()) {
            $twitter_title = get_the_title(wc_get_page_id('shop'));
        }
    }
    
    // Trim description to 200 characters
    $twitter_description = substr($twitter_description, 0, 200);
    
    // Output Twitter Card tags
    echo '<meta name="twitter:card" content="' . esc_attr($twitter_card) . '" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '" />' . "\n";
    
    // Output Twitter site username if available
    if (!empty($twitter_site)) {
        echo '<meta name="twitter:site" content="@' . esc_attr($twitter_site) . '" />' . "\n";
    }
    
    // Output Twitter creator username if available
    if (!empty($twitter_creator)) {
        echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_creator) . '" />' . "\n";
    }
    
    // Output image tag if available
    if (!empty($twitter_image)) {
        echo '<meta name="twitter:image" content="' . esc_url($twitter_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_twitter_card_tags', 6);

/**
 * Add Facebook App ID meta tag to the head
 */
function aqualuxe_facebook_app_id() {
    // Skip if Yoast SEO or Rank Math is active as they handle Facebook App ID
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    // Get Facebook App ID from customizer
    $facebook_app_id = get_theme_mod('aqualuxe_facebook_app_id');
    
    if (!empty($facebook_app_id)) {
        echo '<meta property="fb:app_id" content="' . esc_attr($facebook_app_id) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_facebook_app_id', 7);

/**
 * Add Open Graph customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_open_graph_customizer($wp_customize) {
    // Add Open Graph Section
    $wp_customize->add_section(
        'aqualuxe_open_graph_section',
        array(
            'title'       => __('Open Graph & Social', 'aqualuxe'),
            'description' => __('Customize Open Graph and social media settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 75,
        )
    );

    // Add Default Image Setting
    $wp_customize->add_setting(
        'aqualuxe_og_default_image',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'aqualuxe_og_default_image',
            array(
                'label'       => __('Default Social Image', 'aqualuxe'),
                'description' => __('Default image used for Open Graph and Twitter Card when no featured image is available', 'aqualuxe'),
                'section'     => 'aqualuxe_open_graph_section',
            )
        )
    );

    // Add Twitter Username Setting
    $wp_customize->add_setting(
        'aqualuxe_twitter_username',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_username',
        array(
            'label'       => __('Twitter Username', 'aqualuxe'),
            'description' => __('Enter your Twitter username without the @ symbol', 'aqualuxe'),
            'section'     => 'aqualuxe_open_graph_section',
            'type'        => 'text',
        )
    );

    // Add Facebook App ID Setting
    $wp_customize->add_setting(
        'aqualuxe_facebook_app_id',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_app_id',
        array(
            'label'       => __('Facebook App ID', 'aqualuxe'),
            'description' => __('Enter your Facebook App ID for domain verification', 'aqualuxe'),
            'section'     => 'aqualuxe_open_graph_section',
            'type'        => 'text',
        )
    );

    // Add Enable Open Graph Setting
    $wp_customize->add_setting(
        'aqualuxe_enable_open_graph',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_open_graph',
        array(
            'label'       => __('Enable Open Graph', 'aqualuxe'),
            'description' => __('Add Open Graph meta tags to your site', 'aqualuxe'),
            'section'     => 'aqualuxe_open_graph_section',
            'type'        => 'checkbox',
        )
    );

    // Add Enable Twitter Card Setting
    $wp_customize->add_setting(
        'aqualuxe_enable_twitter_card',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_twitter_card',
        array(
            'label'       => __('Enable Twitter Card', 'aqualuxe'),
            'description' => __('Add Twitter Card meta tags to your site', 'aqualuxe'),
            'section'     => 'aqualuxe_open_graph_section',
            'type'        => 'checkbox',
        )
    );
}
add_action('customize_register', 'aqualuxe_open_graph_customizer');

/**
 * Filter Open Graph and Twitter Card output based on customizer settings
 */
function aqualuxe_filter_social_tags() {
    // Check if Open Graph is disabled
    if (!get_theme_mod('aqualuxe_enable_open_graph', true)) {
        remove_action('wp_head', 'aqualuxe_open_graph_tags', 5);
        remove_action('wp_head', 'aqualuxe_facebook_app_id', 7);
    }
    
    // Check if Twitter Card is disabled
    if (!get_theme_mod('aqualuxe_enable_twitter_card', true)) {
        remove_action('wp_head', 'aqualuxe_twitter_card_tags', 6);
    }
}
add_action('wp_head', 'aqualuxe_filter_social_tags', 1);

/**
 * Add Open Graph namespace to html tag
 *
 * @param string $output The html tag output.
 * @return string Modified html tag output.
 */
function aqualuxe_add_opengraph_namespace($output) {
    // Skip if Open Graph is disabled
    if (!get_theme_mod('aqualuxe_enable_open_graph', true)) {
        return $output;
    }
    
    // Skip if Yoast SEO or Rank Math is active as they handle Open Graph namespace
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return $output;
    }
    
    return $output . ' prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# article: https://ogp.me/ns/article# product: https://ogp.me/ns/product#"';
}
add_filter('language_attributes', 'aqualuxe_add_opengraph_namespace');

/**
 * Add author Twitter username field to user profile
 *
 * @param WP_User $user User object.
 */
function aqualuxe_add_twitter_username_field($user) {
    ?>
    <h3><?php esc_html_e('Social Media', 'aqualuxe'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="twitter"><?php esc_html_e('Twitter Username', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="twitter" id="twitter" value="<?php echo esc_attr(get_user_meta($user->ID, 'twitter', true)); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Enter your Twitter username without the @ symbol', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'aqualuxe_add_twitter_username_field');
add_action('edit_user_profile', 'aqualuxe_add_twitter_username_field');

/**
 * Save author Twitter username field
 *
 * @param int $user_id User ID.
 * @return bool True on successful update, false on failure.
 */
function aqualuxe_save_twitter_username_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    if (isset($_POST['twitter'])) {
        update_user_meta($user_id, 'twitter', sanitize_text_field($_POST['twitter']));
    }
    
    return true;
}
add_action('personal_options_update', 'aqualuxe_save_twitter_username_field');
add_action('edit_user_profile_update', 'aqualuxe_save_twitter_username_field');

/**
 * Add Open Graph meta box to posts and pages
 */
function aqualuxe_add_open_graph_meta_box() {
    // Skip if Yoast SEO or Rank Math is active as they handle Open Graph meta boxes
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    $screens = array('post', 'page');
    
    // Add WooCommerce product if available
    if (class_exists('WooCommerce')) {
        $screens[] = 'product';
    }
    
    // Add custom post types
    $custom_post_types = get_post_types(array('public' => true, '_builtin' => false));
    $screens = array_merge($screens, $custom_post_types);
    
    add_meta_box(
        'aqualuxe_open_graph_meta_box',
        __('Social Media Preview', 'aqualuxe'),
        'aqualuxe_open_graph_meta_box_callback',
        $screens,
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_open_graph_meta_box');

/**
 * Open Graph meta box callback
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_open_graph_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_open_graph_meta_box', 'aqualuxe_open_graph_meta_box_nonce');
    
    // Get current values
    $og_title = get_post_meta($post->ID, '_aqualuxe_og_title', true);
    $og_description = get_post_meta($post->ID, '_aqualuxe_og_description', true);
    $og_image_id = get_post_meta($post->ID, '_aqualuxe_og_image_id', true);
    
    // Default values
    if (empty($og_title)) {
        $og_title = get_the_title($post->ID);
    }
    
    if (empty($og_description)) {
        $excerpt = get_the_excerpt($post->ID);
        if (!empty($excerpt)) {
            $og_description = wp_strip_all_tags($excerpt);
        } else {
            $og_description = wp_strip_all_tags(get_the_content('', false, $post->ID));
            $og_description = preg_replace('/\s+/', ' ', $og_description);
            $og_description = substr($og_description, 0, 300);
        }
    }
    
    // Get image URL
    $og_image_url = '';
    if (!empty($og_image_id)) {
        $og_image = wp_get_attachment_image_src($og_image_id, 'large');
        if ($og_image) {
            $og_image_url = $og_image[0];
        }
    } elseif (has_post_thumbnail($post->ID)) {
        $og_image_id = get_post_thumbnail_id($post->ID);
        $og_image = wp_get_attachment_image_src($og_image_id, 'large');
        if ($og_image) {
            $og_image_url = $og_image[0];
        }
    }
    
    ?>
    <p><?php esc_html_e('Customize how this page appears when shared on social media.', 'aqualuxe'); ?></p>
    
    <div class="social-preview-container" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
        <h4><?php esc_html_e('Social Media Preview', 'aqualuxe'); ?></h4>
        
        <div class="social-preview" style="border: 1px solid #ddd; max-width: 500px; font-family: Arial, sans-serif;">
            <?php if (!empty($og_image_url)) : ?>
                <div class="social-preview-image" style="height: 250px; background-image: url('<?php echo esc_url($og_image_url); ?>'); background-size: cover; background-position: center;"></div>
            <?php else : ?>
                <div class="social-preview-image" style="height: 250px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                    <span><?php esc_html_e('No image selected', 'aqualuxe'); ?></span>
                </div>
            <?php endif; ?>
            
            <div class="social-preview-content" style="padding: 10px;">
                <div class="social-preview-site" style="color: #606770; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">
                    <?php echo esc_html(parse_url(home_url(), PHP_URL_HOST)); ?>
                </div>
                <div class="social-preview-title" style="color: #1d2129; font-size: 16px; font-weight: bold; margin-bottom: 5px;">
                    <?php echo esc_html($og_title); ?>
                </div>
                <div class="social-preview-description" style="color: #606770; font-size: 14px; line-height: 1.4;">
                    <?php echo esc_html($og_description); ?>
                </div>
            </div>
        </div>
    </div>
    
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_og_title"><?php esc_html_e('Social Title', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="aqualuxe_og_title" id="aqualuxe_og_title" value="<?php echo esc_attr($og_title); ?>" class="large-text" />
                <p class="description"><?php esc_html_e('The title that will be displayed when shared on social media. Leave blank to use the post title.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_og_description"><?php esc_html_e('Social Description', 'aqualuxe'); ?></label></th>
            <td>
                <textarea name="aqualuxe_og_description" id="aqualuxe_og_description" rows="3" class="large-text"><?php echo esc_textarea($og_description); ?></textarea>
                <p class="description"><?php esc_html_e('The description that will be displayed when shared on social media. Leave blank to use the post excerpt or content.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_og_image"><?php esc_html_e('Social Image', 'aqualuxe'); ?></label></th>
            <td>
                <div class="aqualuxe-og-image-container">
                    <input type="hidden" name="aqualuxe_og_image_id" id="aqualuxe_og_image_id" value="<?php echo esc_attr($og_image_id); ?>" />
                    
                    <div class="aqualuxe-og-image-preview" style="margin-bottom: 10px;">
                        <?php if (!empty($og_image_url)) : ?>
                            <img src="<?php echo esc_url($og_image_url); ?>" alt="<?php esc_attr_e('Social Image Preview', 'aqualuxe'); ?>" style="max-width: 300px; height: auto;" />
                        <?php endif; ?>
                    </div>
                    
                    <input type="button" class="button aqualuxe-og-image-upload" value="<?php esc_attr_e('Select Image', 'aqualuxe'); ?>" />
                    <input type="button" class="button aqualuxe-og-image-remove" value="<?php esc_attr_e('Remove Image', 'aqualuxe'); ?>" <?php echo empty($og_image_url) ? 'style="display:none;"' : ''; ?> />
                    
                    <p class="description"><?php esc_html_e('The image that will be displayed when shared on social media. Recommended size: 1200x630 pixels. Leave blank to use the featured image.', 'aqualuxe'); ?></p>
                </div>
                
                <script>
                jQuery(document).ready(function($) {
                    // Update preview when title or description changes
                    $('#aqualuxe_og_title, #aqualuxe_og_description').on('input', function() {
                        var title = $('#aqualuxe_og_title').val();
                        var description = $('#aqualuxe_og_description').val();
                        
                        $('.social-preview-title').text(title);
                        $('.social-preview-description').text(description);
                    });
                    
                    // Image upload
                    $('.aqualuxe-og-image-upload').click(function(e) {
                        e.preventDefault();
                        
                        var image_frame;
                        
                        if (image_frame) {
                            image_frame.open();
                            return;
                        }
                        
                        image_frame = wp.media({
                            title: '<?php esc_html_e('Select or Upload Social Image', 'aqualuxe'); ?>',
                            button: {
                                text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                            },
                            multiple: false
                        });
                        
                        image_frame.on('select', function() {
                            var attachment = image_frame.state().get('selection').first().toJSON();
                            
                            $('#aqualuxe_og_image_id').val(attachment.id);
                            $('.aqualuxe-og-image-preview').html('<img src="' + attachment.url + '" alt="<?php esc_attr_e('Social Image Preview', 'aqualuxe'); ?>" style="max-width: 300px; height: auto;" />');
                            $('.aqualuxe-og-image-remove').show();
                            
                            // Update preview
                            $('.social-preview-image').css('background-image', 'url(' + attachment.url + ')');
                            $('.social-preview-image').html('');
                        });
                        
                        image_frame.open();
                    });
                    
                    // Image remove
                    $('.aqualuxe-og-image-remove').click(function(e) {
                        e.preventDefault();
                        
                        $('#aqualuxe_og_image_id').val('');
                        $('.aqualuxe-og-image-preview').html('');
                        $(this).hide();
                        
                        // Update preview
                        $('.social-preview-image').css('background-image', 'none');
                        $('.social-preview-image').html('<span><?php esc_html_e('No image selected', 'aqualuxe'); ?></span>');
                    });
                });
                </script>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Open Graph meta box data
 *
 * @param int $post_id Post ID.
 * @return int|bool Post ID on success, false on failure.
 */
function aqualuxe_save_open_graph_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_open_graph_meta_box_nonce'])) {
        return $post_id;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_open_graph_meta_box_nonce'], 'aqualuxe_open_graph_meta_box')) {
        return $post_id;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    
    // Check permissions
    if ('page' === $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }
    
    // Save meta data
    if (isset($_POST['aqualuxe_og_title'])) {
        update_post_meta($post_id, '_aqualuxe_og_title', sanitize_text_field($_POST['aqualuxe_og_title']));
    }
    
    if (isset($_POST['aqualuxe_og_description'])) {
        update_post_meta($post_id, '_aqualuxe_og_description', sanitize_textarea_field($_POST['aqualuxe_og_description']));
    }
    
    if (isset($_POST['aqualuxe_og_image_id'])) {
        update_post_meta($post_id, '_aqualuxe_og_image_id', absint($_POST['aqualuxe_og_image_id']));
    }
    
    return $post_id;
}
add_action('save_post', 'aqualuxe_save_open_graph_meta_box');

/**
 * Add Open Graph meta box to taxonomies
 */
function aqualuxe_add_taxonomy_open_graph_fields() {
    // Skip if Yoast SEO or Rank Math is active as they handle Open Graph meta boxes
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    // Get all public taxonomies
    $taxonomies = get_taxonomies(array('public' => true), 'names');
    
    foreach ($taxonomies as $taxonomy) {
        add_action($taxonomy . '_edit_form_fields', 'aqualuxe_taxonomy_open_graph_fields', 10, 2);
        add_action('edited_' . $taxonomy, 'aqualuxe_save_taxonomy_open_graph_fields', 10, 2);
    }
}
add_action('admin_init', 'aqualuxe_add_taxonomy_open_graph_fields');

/**
 * Add Open Graph fields to taxonomy edit form
 *
 * @param WP_Term $term Term object.
 * @param string $taxonomy Taxonomy slug.
 */
function aqualuxe_taxonomy_open_graph_fields($term, $taxonomy) {
    // Get current values
    $og_title = get_term_meta($term->term_id, '_aqualuxe_og_title', true);
    $og_description = get_term_meta($term->term_id, '_aqualuxe_og_description', true);
    $og_image_id = get_term_meta($term->term_id, '_aqualuxe_og_image_id', true);
    
    // Default values
    if (empty($og_title)) {
        $og_title = $term->name;
    }
    
    if (empty($og_description)) {
        $og_description = wp_strip_all_tags($term->description);
    }
    
    // Get image URL
    $og_image_url = '';
    if (!empty($og_image_id)) {
        $og_image = wp_get_attachment_image_src($og_image_id, 'large');
        if ($og_image) {
            $og_image_url = $og_image[0];
        }
    }
    
    // For WooCommerce product categories
    if ($taxonomy === 'product_cat' && function_exists('get_term_meta')) {
        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        if (!empty($thumbnail_id) && empty($og_image_id)) {
            $og_image_id = $thumbnail_id;
            $og_image = wp_get_attachment_image_src($og_image_id, 'large');
            if ($og_image) {
                $og_image_url = $og_image[0];
            }
        }
    }
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="aqualuxe_og_title"><?php esc_html_e('Social Title', 'aqualuxe'); ?></label></th>
        <td>
            <input type="text" name="aqualuxe_og_title" id="aqualuxe_og_title" value="<?php echo esc_attr($og_title); ?>" />
            <p class="description"><?php esc_html_e('The title that will be displayed when shared on social media. Leave blank to use the term name.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="aqualuxe_og_description"><?php esc_html_e('Social Description', 'aqualuxe'); ?></label></th>
        <td>
            <textarea name="aqualuxe_og_description" id="aqualuxe_og_description" rows="3"><?php echo esc_textarea($og_description); ?></textarea>
            <p class="description"><?php esc_html_e('The description that will be displayed when shared on social media. Leave blank to use the term description.', 'aqualuxe'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="aqualuxe_og_image"><?php esc_html_e('Social Image', 'aqualuxe'); ?></label></th>
        <td>
            <div class="aqualuxe-og-image-container">
                <input type="hidden" name="aqualuxe_og_image_id" id="aqualuxe_og_image_id" value="<?php echo esc_attr($og_image_id); ?>" />
                
                <div class="aqualuxe-og-image-preview" style="margin-bottom: 10px;">
                    <?php if (!empty($og_image_url)) : ?>
                        <img src="<?php echo esc_url($og_image_url); ?>" alt="<?php esc_attr_e('Social Image Preview', 'aqualuxe'); ?>" style="max-width: 300px; height: auto;" />
                    <?php endif; ?>
                </div>
                
                <input type="button" class="button aqualuxe-og-image-upload" value="<?php esc_attr_e('Select Image', 'aqualuxe'); ?>" />
                <input type="button" class="button aqualuxe-og-image-remove" value="<?php esc_attr_e('Remove Image', 'aqualuxe'); ?>" <?php echo empty($og_image_url) ? 'style="display:none;"' : ''; ?> />
                
                <p class="description"><?php esc_html_e('The image that will be displayed when shared on social media. Recommended size: 1200x630 pixels.', 'aqualuxe'); ?></p>
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                // Image upload
                $('.aqualuxe-og-image-upload').click(function(e) {
                    e.preventDefault();
                    
                    var image_frame;
                    
                    if (image_frame) {
                        image_frame.open();
                        return;
                    }
                    
                    image_frame = wp.media({
                        title: '<?php esc_html_e('Select or Upload Social Image', 'aqualuxe'); ?>',
                        button: {
                            text: '<?php esc_html_e('Use this image', 'aqualuxe'); ?>'
                        },
                        multiple: false
                    });
                    
                    image_frame.on('select', function() {
                        var attachment = image_frame.state().get('selection').first().toJSON();
                        
                        $('#aqualuxe_og_image_id').val(attachment.id);
                        $('.aqualuxe-og-image-preview').html('<img src="' + attachment.url + '" alt="<?php esc_attr_e('Social Image Preview', 'aqualuxe'); ?>" style="max-width: 300px; height: auto;" />');
                        $('.aqualuxe-og-image-remove').show();
                    });
                    
                    image_frame.open();
                });
                
                // Image remove
                $('.aqualuxe-og-image-remove').click(function(e) {
                    e.preventDefault();
                    
                    $('#aqualuxe_og_image_id').val('');
                    $('.aqualuxe-og-image-preview').html('');
                    $(this).hide();
                });
            });
            </script>
        </td>
    </tr>
    <?php
}

/**
 * Save Open Graph fields for taxonomies
 *
 * @param int $term_id Term ID.
 * @param int $tt_id Term taxonomy ID.
 */
function aqualuxe_save_taxonomy_open_graph_fields($term_id, $tt_id) {
    if (isset($_POST['aqualuxe_og_title'])) {
        update_term_meta($term_id, '_aqualuxe_og_title', sanitize_text_field($_POST['aqualuxe_og_title']));
    }
    
    if (isset($_POST['aqualuxe_og_description'])) {
        update_term_meta($term_id, '_aqualuxe_og_description', sanitize_textarea_field($_POST['aqualuxe_og_description']));
    }
    
    if (isset($_POST['aqualuxe_og_image_id'])) {
        update_term_meta($term_id, '_aqualuxe_og_image_id', absint($_POST['aqualuxe_og_image_id']));
    }
}

/**
 * Add Open Graph meta data to REST API
 */
function aqualuxe_register_open_graph_rest_fields() {
    // Register fields for posts
    register_rest_field(
        'post',
        'aqualuxe_open_graph',
        array(
            'get_callback' => 'aqualuxe_get_open_graph_rest_field',
            'schema'       => null,
        )
    );
    
    // Register fields for pages
    register_rest_field(
        'page',
        'aqualuxe_open_graph',
        array(
            'get_callback' => 'aqualuxe_get_open_graph_rest_field',
            'schema'       => null,
        )
    );
    
    // Register fields for WooCommerce products
    if (class_exists('WooCommerce')) {
        register_rest_field(
            'product',
            'aqualuxe_open_graph',
            array(
                'get_callback' => 'aqualuxe_get_open_graph_rest_field',
                'schema'       => null,
            )
        );
    }
    
    // Register fields for custom post types
    $custom_post_types = get_post_types(array('public' => true, '_builtin' => false));
    foreach ($custom_post_types as $post_type) {
        register_rest_field(
            $post_type,
            'aqualuxe_open_graph',
            array(
                'get_callback' => 'aqualuxe_get_open_graph_rest_field',
                'schema'       => null,
            )
        );
    }
}
add_action('rest_api_init', 'aqualuxe_register_open_graph_rest_fields');

/**
 * Get Open Graph data for REST API
 *
 * @param array $object Post object.
 * @return array Open Graph data.
 */
function aqualuxe_get_open_graph_rest_field($object) {
    $post_id = $object['id'];
    
    // Get Open Graph data
    $og_title = get_post_meta($post_id, '_aqualuxe_og_title', true);
    $og_description = get_post_meta($post_id, '_aqualuxe_og_description', true);
    $og_image_id = get_post_meta($post_id, '_aqualuxe_og_image_id', true);
    
    // Default values
    if (empty($og_title)) {
        $og_title = get_the_title($post_id);
    }
    
    if (empty($og_description)) {
        $excerpt = get_the_excerpt($post_id);
        if (!empty($excerpt)) {
            $og_description = wp_strip_all_tags($excerpt);
        } else {
            $og_description = wp_strip_all_tags(get_the_content('', false, $post_id));
            $og_description = preg_replace('/\s+/', ' ', $og_description);
            $og_description = substr($og_description, 0, 300);
        }
    }
    
    // Get image URL
    $og_image_url = '';
    if (!empty($og_image_id)) {
        $og_image = wp_get_attachment_image_src($og_image_id, 'large');
        if ($og_image) {
            $og_image_url = $og_image[0];
        }
    } elseif (has_post_thumbnail($post_id)) {
        $og_image_id = get_post_thumbnail_id($post_id);
        $og_image = wp_get_attachment_image_src($og_image_id, 'large');
        if ($og_image) {
            $og_image_url = $og_image[0];
        }
    }
    
    return array(
        'title'       => $og_title,
        'description' => $og_description,
        'image'       => $og_image_url,
        'image_id'    => $og_image_id,
    );
}

/**
 * Add Open Graph preview to Gutenberg editor
 */
function aqualuxe_open_graph_gutenberg_preview() {
    // Skip if Yoast SEO or Rank Math is active as they handle Open Graph preview
    if (defined('WPSEO_VERSION') || class_exists('RankMath')) {
        return;
    }
    
    // Skip if not in the editor
    $screen = get_current_screen();
    if (!$screen || !$screen->is_block_editor) {
        return;
    }
    
    // Enqueue script
    wp_enqueue_script(
        'aqualuxe-open-graph-preview',
        AQUALUXE_ASSETS_URI . 'js/open-graph-preview.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-data', 'wp-plugins', 'wp-edit-post'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-open-graph-preview',
        'aqualuxeOpenGraph',
        array(
            'site_name' => get_bloginfo('name'),
            'site_url'  => parse_url(home_url(), PHP_URL_HOST),
        )
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_open_graph_gutenberg_preview');

/**
 * Add social sharing buttons to single posts and pages
 */
function aqualuxe_social_sharing_buttons() {
    // Skip if not a single post or page
    if (!is_singular()) {
        return;
    }
    
    // Get current page URL
    $url = urlencode(get_permalink());
    
    // Get current page title
    $title = urlencode(get_the_title());
    
    // Get current page thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
    }
    
    // Construct sharing URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
    $email_url = 'mailto:?subject=' . $title . '&body=' . $url;
    
    // Output sharing buttons
    ?>
    <div class="social-sharing-buttons mt-6 pt-6 border-t border-gray-200">
        <h4 class="text-lg font-bold mb-4"><?php esc_html_e('Share This', 'aqualuxe'); ?></h4>
        
        <div class="flex space-x-2">
            <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-button facebook bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors">
                <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
            </a>
            
            <a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-button twitter bg-blue-400 hover:bg-blue-500 text-white p-2 rounded-full transition-colors">
                <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            
            <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-button linkedin bg-blue-700 hover:bg-blue-800 text-white p-2 rounded-full transition-colors">
                <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
            </a>
            
            <a href="<?php echo esc_url($pinterest_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-button pinterest bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors">
                <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
            </a>
            
            <a href="<?php echo esc_url($email_url); ?>" class="social-sharing-button email bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-full transition-colors">
                <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </a>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_after_content', 'aqualuxe_social_sharing_buttons');

/**
 * Add social sharing buttons to WooCommerce products
 */
function aqualuxe_woocommerce_social_sharing_buttons() {
    if (is_product()) {
        aqualuxe_social_sharing_buttons();
    }
}
add_action('woocommerce_share', 'aqualuxe_woocommerce_social_sharing_buttons');

/**
 * Add social sharing customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_social_sharing_customizer($wp_customize) {
    // Add Social Sharing Section
    $wp_customize->add_section(
        'aqualuxe_social_sharing_section',
        array(
            'title'       => __('Social Sharing', 'aqualuxe'),
            'description' => __('Customize social sharing buttons', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 76,
        )
    );

    // Add Enable Social Sharing Setting
    $wp_customize->add_setting(
        'aqualuxe_enable_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_social_sharing',
        array(
            'label'       => __('Enable Social Sharing', 'aqualuxe'),
            'description' => __('Show social sharing buttons on posts and pages', 'aqualuxe'),
            'section'     => 'aqualuxe_social_sharing_section',
            'type'        => 'checkbox',
        )
    );

    // Add Social Networks Setting
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_networks',
        array(
            'default'           => array('facebook', 'twitter', 'linkedin', 'pinterest', 'email'),
            'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'aqualuxe_social_sharing_networks',
            array(
                'label'       => __('Social Networks', 'aqualuxe'),
                'description' => __('Select which social networks to display', 'aqualuxe'),
                'section'     => 'aqualuxe_social_sharing_section',
                'type'        => 'select',
                'multiple'    => true,
                'choices'     => array(
                    'facebook'  => __('Facebook', 'aqualuxe'),
                    'twitter'   => __('Twitter', 'aqualuxe'),
                    'linkedin'  => __('LinkedIn', 'aqualuxe'),
                    'pinterest' => __('Pinterest', 'aqualuxe'),
                    'email'     => __('Email', 'aqualuxe'),
                ),
            )
        )
    );

    // Add Social Sharing Position Setting
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_position',
        array(
            'default'           => 'after_content',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_position',
        array(
            'label'       => __('Sharing Position', 'aqualuxe'),
            'description' => __('Choose where to display the sharing buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_social_sharing_section',
            'type'        => 'select',
            'choices'     => array(
                'before_content' => __('Before Content', 'aqualuxe'),
                'after_content'  => __('After Content', 'aqualuxe'),
                'both'           => __('Both Before and After Content', 'aqualuxe'),
                'floating'       => __('Floating Sidebar', 'aqualuxe'),
            ),
        )
    );

    // Add Social Sharing Style Setting
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_style',
        array(
            'default'           => 'icon',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_style',
        array(
            'label'       => __('Sharing Style', 'aqualuxe'),
            'description' => __('Choose the style of the sharing buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_social_sharing_section',
            'type'        => 'select',
            'choices'     => array(
                'icon'       => __('Icon Only', 'aqualuxe'),
                'text'       => __('Text Only', 'aqualuxe'),
                'icon_text'  => __('Icon and Text', 'aqualuxe'),
            ),
        )
    );

    // Add Social Sharing Shape Setting
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_shape',
        array(
            'default'           => 'circle',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_shape',
        array(
            'label'       => __('Button Shape', 'aqualuxe'),
            'description' => __('Choose the shape of the sharing buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_social_sharing_section',
            'type'        => 'select',
            'choices'     => array(
                'circle'    => __('Circle', 'aqualuxe'),
                'rounded'   => __('Rounded', 'aqualuxe'),
                'square'    => __('Square', 'aqualuxe'),
            ),
        )
    );

    // Add Social Sharing Size Setting
    $wp_customize->add_setting(
        'aqualuxe_social_sharing_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_social_sharing_size',
        array(
            'label'       => __('Button Size', 'aqualuxe'),
            'description' => __('Choose the size of the sharing buttons', 'aqualuxe'),
            'section'     => 'aqualuxe_social_sharing_section',
            'type'        => 'select',
            'choices'     => array(
                'small'     => __('Small', 'aqualuxe'),
                'medium'    => __('Medium', 'aqualuxe'),
                'large'     => __('Large', 'aqualuxe'),
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_social_sharing_customizer');

/**
 * Filter social sharing buttons based on customizer settings
 */
function aqualuxe_filter_social_sharing_buttons() {
    // Check if social sharing is disabled
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        remove_action('aqualuxe_after_content', 'aqualuxe_social_sharing_buttons');
        remove_action('woocommerce_share', 'aqualuxe_woocommerce_social_sharing_buttons');
        return;
    }
    
    // Check sharing position
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after_content');
    
    if ($position === 'before_content') {
        remove_action('aqualuxe_after_content', 'aqualuxe_social_sharing_buttons');
        add_action('aqualuxe_before_content', 'aqualuxe_social_sharing_buttons');
    } elseif ($position === 'both') {
        add_action('aqualuxe_before_content', 'aqualuxe_social_sharing_buttons');
    } elseif ($position === 'floating') {
        remove_action('aqualuxe_after_content', 'aqualuxe_social_sharing_buttons');
        add_action('wp_footer', 'aqualuxe_floating_social_sharing_buttons');
    }
}
add_action('wp', 'aqualuxe_filter_social_sharing_buttons');

/**
 * Add floating social sharing buttons
 */
function aqualuxe_floating_social_sharing_buttons() {
    // Skip if not a single post or page
    if (!is_singular()) {
        return;
    }
    
    // Get current page URL
    $url = urlencode(get_permalink());
    
    // Get current page title
    $title = urlencode(get_the_title());
    
    // Get current page thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
    }
    
    // Construct sharing URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
    $email_url = 'mailto:?subject=' . $title . '&body=' . $url;
    
    // Get selected networks
    $networks = get_theme_mod('aqualuxe_social_sharing_networks', array('facebook', 'twitter', 'linkedin', 'pinterest', 'email'));
    
    // Get button style
    $style = get_theme_mod('aqualuxe_social_sharing_style', 'icon');
    $shape = get_theme_mod('aqualuxe_social_sharing_shape', 'circle');
    $size = get_theme_mod('aqualuxe_social_sharing_size', 'medium');
    
    // Set button classes based on shape and size
    $button_classes = 'social-sharing-button text-white transition-colors';
    
    if ($shape === 'circle') {
        $button_classes .= ' rounded-full';
    } elseif ($shape === 'rounded') {
        $button_classes .= ' rounded';
    }
    
    if ($size === 'small') {
        $button_classes .= ' p-1';
        $icon_size = 'h-4 w-4';
    } elseif ($size === 'large') {
        $button_classes .= ' p-3';
        $icon_size = 'h-6 w-6';
    } else {
        $button_classes .= ' p-2';
        $icon_size = 'h-5 w-5';
    }
    
    // Output floating sharing buttons
    ?>
    <div class="social-sharing-floating fixed left-0 top-1/2 transform -translate-y-1/2 z-50">
        <div class="flex flex-col space-y-2 p-2">
            <?php if (in_array('facebook', $networks)) : ?>
                <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr($button_classes); ?> bg-blue-600 hover:bg-blue-700">
                    <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr($icon_size); ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    <?php if ($style === 'text' || $style === 'icon_text') : ?>
                        <span class="ml-2"><?php esc_html_e('Share', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <?php if (in_array('twitter', $networks)) : ?>
                <a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr($button_classes); ?> bg-blue-400 hover:bg-blue-500">
                    <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr($icon_size); ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    <?php if ($style === 'text' || $style === 'icon_text') : ?>
                        <span class="ml-2"><?php esc_html_e('Tweet', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <?php if (in_array('linkedin', $networks)) : ?>
                <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr($button_classes); ?> bg-blue-700 hover:bg-blue-800">
                    <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr($icon_size); ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>
                    <?php if ($style === 'text' || $style === 'icon_text') : ?>
                        <span class="ml-2"><?php esc_html_e('Share', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <?php if (in_array('pinterest', $networks)) : ?>
                <a href="<?php echo esc_url($pinterest_url); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr($button_classes); ?> bg-red-600 hover:bg-red-700">
                    <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr($icon_size); ?>" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                    <?php if ($style === 'text' || $style === 'icon_text') : ?>
                        <span class="ml-2"><?php esc_html_e('Pin', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <?php if (in_array('email', $networks)) : ?>
                <a href="<?php echo esc_url($email_url); ?>" class="<?php echo esc_attr($button_classes); ?> bg-gray-600 hover:bg-gray-700">
                    <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="<?php echo esc_attr($icon_size); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <?php if ($style === 'text' || $style === 'icon_text') : ?>
                        <span class="ml-2"><?php esc_html_e('Email', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Sanitize multi-select values
 *
 * @param mixed $input The value to sanitize.
 * @return array Sanitized value.
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array('facebook', 'twitter', 'linkedin', 'pinterest', 'email');
    
    return array_intersect($input, $valid_keys);
}