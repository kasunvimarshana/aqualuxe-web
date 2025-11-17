<?php
/**
 * AquaLuxe Theme Customizer - Social Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add social settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_social($wp_customize) {
    // Add Social section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'priority' => 90,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Social Media Profiles
    $social_platforms = aqualuxe_get_social_platforms();
    
    foreach ($social_platforms as $platform => $data) {
        $wp_customize->add_setting('aqualuxe_social_' . $platform, array(
            'default'           => '',
            'transport'         => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control('aqualuxe_social_' . $platform, array(
            'label'       => $data['label'],
            'description' => sprintf(__('Enter your %s profile URL', 'aqualuxe'), $data['label']),
            'section'     => 'aqualuxe_social',
            'type'        => 'url',
        ));
    }
    
    // Social Icons Style
    $wp_customize->add_setting('aqualuxe_social_icons_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_icons_style', array(
        'label'       => __('Social Icons Style', 'aqualuxe'),
        'description' => __('Choose the style for social icons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'default'  => __('Default', 'aqualuxe'),
            'rounded'  => __('Rounded', 'aqualuxe'),
            'square'   => __('Square', 'aqualuxe'),
            'circle'   => __('Circle', 'aqualuxe'),
            'outline'  => __('Outline', 'aqualuxe'),
        ),
    ));
    
    // Social Icons Size
    $wp_customize->add_setting('aqualuxe_social_icons_size', array(
        'default'           => 'medium',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_icons_size', array(
        'label'       => __('Social Icons Size', 'aqualuxe'),
        'description' => __('Choose the size for social icons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'small'   => __('Small', 'aqualuxe'),
            'medium'  => __('Medium', 'aqualuxe'),
            'large'   => __('Large', 'aqualuxe'),
        ),
    ));
    
    // Social Icons Color Type
    $wp_customize->add_setting('aqualuxe_social_icons_color_type', array(
        'default'           => 'brand',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_icons_color_type', array(
        'label'       => __('Social Icons Color Type', 'aqualuxe'),
        'description' => __('Choose how to color the social icons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'brand'    => __('Brand Colors', 'aqualuxe'),
            'custom'   => __('Custom Color', 'aqualuxe'),
            'monochrome' => __('Monochrome', 'aqualuxe'),
        ),
    ));
    
    // Social Icons Custom Color
    $wp_customize->add_setting('aqualuxe_social_icons_color', array(
        'default'           => '#0073aa',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_social_icons_color', array(
        'label'       => __('Social Icons Color', 'aqualuxe'),
        'description' => __('Choose a custom color for social icons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_social_icons_color_type', 'brand') === 'custom';
        },
    )));
    
    // Social Icons Hover Effect
    $wp_customize->add_setting('aqualuxe_social_icons_hover_effect', array(
        'default'           => 'fade',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_icons_hover_effect', array(
        'label'       => __('Social Icons Hover Effect', 'aqualuxe'),
        'description' => __('Choose the hover effect for social icons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'none'    => __('None', 'aqualuxe'),
            'fade'    => __('Fade', 'aqualuxe'),
            'scale'   => __('Scale', 'aqualuxe'),
            'rotate'  => __('Rotate', 'aqualuxe'),
            'bounce'  => __('Bounce', 'aqualuxe'),
        ),
    ));
    
    // Show Social Icons in Header
    $wp_customize->add_setting('aqualuxe_show_social_header', array(
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_social_header', array(
        'label'       => __('Show Social Icons in Header', 'aqualuxe'),
        'description' => __('Display social icons in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
    ));
    
    // Show Social Icons in Footer
    $wp_customize->add_setting('aqualuxe_show_social_footer', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_social_footer', array(
        'label'       => __('Show Social Icons in Footer', 'aqualuxe'),
        'description' => __('Display social icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
    ));
    
    // Social Sharing
    $wp_customize->add_setting('aqualuxe_enable_social_sharing', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_enable_social_sharing', array(
        'label'       => __('Enable Social Sharing', 'aqualuxe'),
        'description' => __('Enable social sharing buttons on posts and products.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
    ));
    
    // Social Sharing Platforms
    $wp_customize->add_setting('aqualuxe_social_sharing_platforms', array(
        'default'           => array('facebook', 'twitter', 'linkedin', 'pinterest'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));
    
    $wp_customize->add_control(new AquaLuxe_Multi_Checkbox_Control($wp_customize, 'aqualuxe_social_sharing_platforms', array(
        'label'       => __('Social Sharing Platforms', 'aqualuxe'),
        'description' => __('Select which platforms to include in social sharing.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'choices'     => array(
            'facebook'   => __('Facebook', 'aqualuxe'),
            'twitter'    => __('Twitter', 'aqualuxe'),
            'linkedin'   => __('LinkedIn', 'aqualuxe'),
            'pinterest'  => __('Pinterest', 'aqualuxe'),
            'reddit'     => __('Reddit', 'aqualuxe'),
            'tumblr'     => __('Tumblr', 'aqualuxe'),
            'whatsapp'   => __('WhatsApp', 'aqualuxe'),
            'telegram'   => __('Telegram', 'aqualuxe'),
            'email'      => __('Email', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));
    
    // Social Sharing Position
    $wp_customize->add_setting('aqualuxe_social_sharing_position', array(
        'default'           => 'after',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_sharing_position', array(
        'label'       => __('Social Sharing Position', 'aqualuxe'),
        'description' => __('Choose where to display social sharing buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'before'  => __('Before Content', 'aqualuxe'),
            'after'   => __('After Content', 'aqualuxe'),
            'both'    => __('Before and After Content', 'aqualuxe'),
            'floating' => __('Floating Sidebar', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));
    
    // Social Sharing Style
    $wp_customize->add_setting('aqualuxe_social_sharing_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_sharing_style', array(
        'label'       => __('Social Sharing Style', 'aqualuxe'),
        'description' => __('Choose the style for social sharing buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'select',
        'choices'     => array(
            'default'  => __('Default', 'aqualuxe'),
            'rounded'  => __('Rounded', 'aqualuxe'),
            'square'   => __('Square', 'aqualuxe'),
            'circle'   => __('Circle', 'aqualuxe'),
            'minimal'  => __('Minimal', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));
    
    // Show Share Count
    $wp_customize->add_setting('aqualuxe_show_share_count', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_share_count', array(
        'label'       => __('Show Share Count', 'aqualuxe'),
        'description' => __('Display the number of shares for each platform.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));
    
    // Show Total Share Count
    $wp_customize->add_setting('aqualuxe_show_total_share_count', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_total_share_count', array(
        'label'       => __('Show Total Share Count', 'aqualuxe'),
        'description' => __('Display the total number of shares across all platforms.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true) && get_theme_mod('aqualuxe_show_share_count', true);
        },
    ));
    
    // Social Share Button Text
    $wp_customize->add_setting('aqualuxe_social_share_text', array(
        'default'           => __('Share this:', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_social_share_text', array(
        'label'       => __('Social Share Button Text', 'aqualuxe'),
        'description' => __('Text to display before social sharing buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    ));
}

// Add the social section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_social');

/**
 * Get social platforms.
 *
 * @return array Social platforms.
 */
function aqualuxe_get_social_platforms() {
    return array(
        'facebook' => array(
            'label' => __('Facebook', 'aqualuxe'),
            'icon'  => 'fab fa-facebook-f',
            'color' => '#3b5998',
        ),
        'twitter' => array(
            'label' => __('Twitter', 'aqualuxe'),
            'icon'  => 'fab fa-twitter',
            'color' => '#1da1f2',
        ),
        'instagram' => array(
            'label' => __('Instagram', 'aqualuxe'),
            'icon'  => 'fab fa-instagram',
            'color' => '#e1306c',
        ),
        'linkedin' => array(
            'label' => __('LinkedIn', 'aqualuxe'),
            'icon'  => 'fab fa-linkedin-in',
            'color' => '#0077b5',
        ),
        'youtube' => array(
            'label' => __('YouTube', 'aqualuxe'),
            'icon'  => 'fab fa-youtube',
            'color' => '#ff0000',
        ),
        'pinterest' => array(
            'label' => __('Pinterest', 'aqualuxe'),
            'icon'  => 'fab fa-pinterest-p',
            'color' => '#bd081c',
        ),
        'tiktok' => array(
            'label' => __('TikTok', 'aqualuxe'),
            'icon'  => 'fab fa-tiktok',
            'color' => '#000000',
        ),
        'snapchat' => array(
            'label' => __('Snapchat', 'aqualuxe'),
            'icon'  => 'fab fa-snapchat-ghost',
            'color' => '#fffc00',
        ),
        'tumblr' => array(
            'label' => __('Tumblr', 'aqualuxe'),
            'icon'  => 'fab fa-tumblr',
            'color' => '#35465c',
        ),
        'reddit' => array(
            'label' => __('Reddit', 'aqualuxe'),
            'icon'  => 'fab fa-reddit-alien',
            'color' => '#ff4500',
        ),
        'vimeo' => array(
            'label' => __('Vimeo', 'aqualuxe'),
            'icon'  => 'fab fa-vimeo-v',
            'color' => '#1ab7ea',
        ),
        'dribbble' => array(
            'label' => __('Dribbble', 'aqualuxe'),
            'icon'  => 'fab fa-dribbble',
            'color' => '#ea4c89',
        ),
        'github' => array(
            'label' => __('GitHub', 'aqualuxe'),
            'icon'  => 'fab fa-github',
            'color' => '#333333',
        ),
        'whatsapp' => array(
            'label' => __('WhatsApp', 'aqualuxe'),
            'icon'  => 'fab fa-whatsapp',
            'color' => '#25d366',
        ),
        'telegram' => array(
            'label' => __('Telegram', 'aqualuxe'),
            'icon'  => 'fab fa-telegram-plane',
            'color' => '#0088cc',
        ),
    );
}

/**
 * Sanitize multi-select values for social settings.
 *
 * @param array $input Multi-select values.
 * @return array Sanitized multi-select values.
 */
function aqualuxe_sanitize_social_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array('facebook', 'twitter', 'linkedin', 'pinterest', 'reddit', 'tumblr', 'whatsapp', 'telegram', 'email');
    
    return array_intersect($input, $valid_keys);
}

/**
 * Display social icons.
 */
function aqualuxe_social_icons() {
    $social_platforms = aqualuxe_get_social_platforms();
    $style = get_theme_mod('aqualuxe_social_icons_style', 'default');
    $size = get_theme_mod('aqualuxe_social_icons_size', 'medium');
    $color_type = get_theme_mod('aqualuxe_social_icons_color_type', 'brand');
    $custom_color = get_theme_mod('aqualuxe_social_icons_color', '#0073aa');
    $hover_effect = get_theme_mod('aqualuxe_social_icons_hover_effect', 'fade');
    
    $classes = array(
        'social-icons',
        'style-' . $style,
        'size-' . $size,
        'color-' . $color_type,
        'hover-' . $hover_effect,
    );
    
    echo '<div class="' . esc_attr(implode(' ', $classes)) . '">';
    
    foreach ($social_platforms as $platform => $data) {
        $url = get_theme_mod('aqualuxe_social_' . $platform, '');
        
        if (!empty($url)) {
            $style_attr = '';
            
            if ($color_type === 'brand') {
                $style_attr = ' style="--platform-color: ' . esc_attr($data['color']) . ';"';
            } elseif ($color_type === 'custom') {
                $style_attr = ' style="--platform-color: ' . esc_attr($custom_color) . ';"';
            }
            
            echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" class="social-icon ' . esc_attr($platform) . '"' . $style_attr . ' aria-label="' . esc_attr($data['label']) . '">';
            echo '<i class="' . esc_attr($data['icon']) . '"></i>';
            echo '</a>';
        }
    }
    
    echo '</div>';
}

/**
 * Display social sharing buttons.
 *
 * @param string $position Position of the sharing buttons.
 */
function aqualuxe_social_sharing($position = 'after') {
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return;
    }
    
    $sharing_position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    
    if ($sharing_position !== $position && $sharing_position !== 'both') {
        return;
    }
    
    $platforms = get_theme_mod('aqualuxe_social_sharing_platforms', array('facebook', 'twitter', 'linkedin', 'pinterest'));
    
    if (!is_array($platforms)) {
        $platforms = explode(',', $platforms);
    }
    
    if (empty($platforms)) {
        return;
    }
    
    $style = get_theme_mod('aqualuxe_social_sharing_style', 'default');
    $show_count = get_theme_mod('aqualuxe_show_share_count', true);
    $show_total = get_theme_mod('aqualuxe_show_total_share_count', true);
    $share_text = get_theme_mod('aqualuxe_social_share_text', __('Share this:', 'aqualuxe'));
    
    $post_url = get_permalink();
    $post_title = get_the_title();
    $post_thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
    
    $classes = array(
        'social-sharing',
        'style-' . $style,
        'position-' . $position,
    );
    
    if ($show_count) {
        $classes[] = 'show-count';
    }
    
    if ($show_total) {
        $classes[] = 'show-total';
    }
    
    echo '<div class="' . esc_attr(implode(' ', $classes)) . '">';
    
    if (!empty($share_text)) {
        echo '<span class="social-sharing-text">' . esc_html($share_text) . '</span>';
    }
    
    echo '<div class="social-sharing-buttons">';
    
    foreach ($platforms as $platform) {
        switch ($platform) {
            case 'facebook':
                echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($post_url) . '" target="_blank" rel="noopener noreferrer" class="social-share-button facebook" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
                echo '<i class="fab fa-facebook-f"></i>';
                echo '<span class="social-share-label">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'twitter':
                echo '<a href="https://twitter.com/intent/tweet?url=' . esc_url($post_url) . '&text=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button twitter" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '">';
                echo '<i class="fab fa-twitter"></i>';
                echo '<span class="social-share-label">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'linkedin':
                echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_url($post_url) . '&title=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button linkedin" aria-label="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
                echo '<i class="fab fa-linkedin-in"></i>';
                echo '<span class="social-share-label">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'pinterest':
                echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_url($post_url) . '&media=' . esc_url($post_thumbnail) . '&description=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button pinterest" aria-label="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
                echo '<i class="fab fa-pinterest-p"></i>';
                echo '<span class="social-share-label">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'reddit':
                echo '<a href="https://reddit.com/submit?url=' . esc_url($post_url) . '&title=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button reddit" aria-label="' . esc_attr__('Share on Reddit', 'aqualuxe') . '">';
                echo '<i class="fab fa-reddit-alien"></i>';
                echo '<span class="social-share-label">' . esc_html__('Reddit', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'tumblr':
                echo '<a href="https://www.tumblr.com/share/link?url=' . esc_url($post_url) . '&name=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button tumblr" aria-label="' . esc_attr__('Share on Tumblr', 'aqualuxe') . '">';
                echo '<i class="fab fa-tumblr"></i>';
                echo '<span class="social-share-label">' . esc_html__('Tumblr', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'whatsapp':
                echo '<a href="https://api.whatsapp.com/send?text=' . esc_attr($post_title) . ' ' . esc_url($post_url) . '" target="_blank" rel="noopener noreferrer" class="social-share-button whatsapp" aria-label="' . esc_attr__('Share on WhatsApp', 'aqualuxe') . '">';
                echo '<i class="fab fa-whatsapp"></i>';
                echo '<span class="social-share-label">' . esc_html__('WhatsApp', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'telegram':
                echo '<a href="https://t.me/share/url?url=' . esc_url($post_url) . '&text=' . esc_attr($post_title) . '" target="_blank" rel="noopener noreferrer" class="social-share-button telegram" aria-label="' . esc_attr__('Share on Telegram', 'aqualuxe') . '">';
                echo '<i class="fab fa-telegram-plane"></i>';
                echo '<span class="social-share-label">' . esc_html__('Telegram', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
                
            case 'email':
                echo '<a href="mailto:?subject=' . esc_attr($post_title) . '&body=' . esc_url($post_url) . '" class="social-share-button email" aria-label="' . esc_attr__('Share via Email', 'aqualuxe') . '">';
                echo '<i class="fas fa-envelope"></i>';
                echo '<span class="social-share-label">' . esc_html__('Email', 'aqualuxe') . '</span>';
                if ($show_count) {
                    echo '<span class="social-share-count">0</span>';
                }
                echo '</a>';
                break;
        }
    }
    
    echo '</div>';
    
    if ($show_total && $show_count) {
        echo '<div class="social-sharing-total">';
        echo '<span class="social-sharing-total-count">0</span>';
        echo '<span class="social-sharing-total-label">' . esc_html__('Shares', 'aqualuxe') . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Add social sharing to content.
 *
 * @param string $content Post content.
 * @return string Modified post content.
 */
function aqualuxe_add_social_sharing_to_content($content) {
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return $content;
    }
    
    if (!is_singular('post') && (!aqualuxe_is_woocommerce_active() || !is_singular('product'))) {
        return $content;
    }
    
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    $output = '';
    
    if ($position === 'before' || $position === 'both') {
        ob_start();
        aqualuxe_social_sharing('before');
        $output .= ob_get_clean();
    }
    
    $output .= $content;
    
    if ($position === 'after' || $position === 'both') {
        ob_start();
        aqualuxe_social_sharing('after');
        $output .= ob_get_clean();
    }
    
    return $output;
}
add_filter('the_content', 'aqualuxe_add_social_sharing_to_content');
add_filter('woocommerce_single_product_summary', 'aqualuxe_social_sharing', 45);

/**
 * Add floating social sharing.
 */
function aqualuxe_add_floating_social_sharing() {
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return;
    }
    
    if (!is_singular('post') && (!aqualuxe_is_woocommerce_active() || !is_singular('product'))) {
        return;
    }
    
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    
    if ($position === 'floating') {
        aqualuxe_social_sharing('floating');
    }
}
add_action('wp_footer', 'aqualuxe_add_floating_social_sharing');

/**
 * Add social CSS to the head.
 */
function aqualuxe_social_css() {
    $style = get_theme_mod('aqualuxe_social_icons_style', 'default');
    $size = get_theme_mod('aqualuxe_social_icons_size', 'medium');
    $color_type = get_theme_mod('aqualuxe_social_icons_color_type', 'brand');
    $custom_color = get_theme_mod('aqualuxe_social_icons_color', '#0073aa');
    $hover_effect = get_theme_mod('aqualuxe_social_icons_hover_effect', 'fade');
    $sharing_style = get_theme_mod('aqualuxe_social_sharing_style', 'default');
    
    ?>
    <style type="text/css">
        /* Social Icons */
        .social-icons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        /* Icon Sizes */
        .social-icons.size-small .social-icon {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }
        
        .social-icons.size-medium .social-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .social-icons.size-large .social-icon {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }
        
        /* Icon Styles */
        .social-icons.style-default .social-icon {
            color: #ffffff;
            background-color: var(--platform-color, #333333);
        }
        
        .social-icons.style-rounded .social-icon {
            color: #ffffff;
            background-color: var(--platform-color, #333333);
            border-radius: 5px;
        }
        
        .social-icons.style-square .social-icon {
            color: #ffffff;
            background-color: var(--platform-color, #333333);
            border-radius: 0;
        }
        
        .social-icons.style-circle .social-icon {
            color: #ffffff;
            background-color: var(--platform-color, #333333);
            border-radius: 50%;
        }
        
        .social-icons.style-outline .social-icon {
            color: var(--platform-color, #333333);
            background-color: transparent;
            border: 2px solid var(--platform-color, #333333);
        }
        
        /* Icon Colors */
        .social-icons.color-monochrome .social-icon {
            --platform-color: #333333;
        }
        
        .social-icons.color-custom .social-icon {
            --platform-color: <?php echo esc_attr($custom_color); ?>;
        }
        
        /* Hover Effects */
        .social-icons.hover-fade .social-icon:hover {
            opacity: 0.8;
        }
        
        .social-icons.hover-scale .social-icon:hover {
            transform: scale(1.1);
        }
        
        .social-icons.hover-rotate .social-icon:hover {
            transform: rotate(10deg);
        }
        
        .social-icons.hover-bounce .social-icon:hover {
            animation: socialBounce 0.4s ease;
        }
        
        @keyframes socialBounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }
        
        /* Social Sharing */
        .social-sharing {
            margin: 30px 0;
        }
        
        .social-sharing-text {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .social-sharing-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .social-share-button {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-share-button i {
            margin-right: 8px;
        }
        
        .social-share-label {
            margin-right: 5px;
        }
        
        .social-share-count {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
        
        .social-sharing-total {
            margin-top: 15px;
            display: flex;
            align-items: center;
        }
        
        .social-sharing-total-count {
            font-size: 1.5rem;
            font-weight: 700;
            margin-right: 5px;
        }
        
        .social-sharing-total-label {
            color: #666;
        }
        
        /* Sharing Button Styles */
        .social-sharing.style-default .social-share-button {
            color: #ffffff;
            border-radius: 3px;
        }
        
        .social-sharing.style-rounded .social-share-button {
            color: #ffffff;
            border-radius: 30px;
        }
        
        .social-sharing.style-square .social-share-button {
            color: #ffffff;
            border-radius: 0;
        }
        
        .social-sharing.style-circle .social-share-button {
            color: #ffffff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            justify-content: center;
            padding: 0;
        }
        
        .social-sharing.style-circle .social-share-label,
        .social-sharing.style-circle .social-share-count {
            display: none;
        }
        
        .social-sharing.style-minimal .social-share-button {
            background-color: transparent;
            color: #333333;
            padding: 5px;
        }
        
        .social-sharing.style-minimal .social-share-button:hover {
            color: var(--platform-color);
        }
        
        /* Sharing Button Colors */
        .social-share-button.facebook {
            background-color: #3b5998;
        }
        
        .social-share-button.twitter {
            background-color: #1da1f2;
        }
        
        .social-share-button.linkedin {
            background-color: #0077b5;
        }
        
        .social-share-button.pinterest {
            background-color: #bd081c;
        }
        
        .social-share-button.reddit {
            background-color: #ff4500;
        }
        
        .social-share-button.tumblr {
            background-color: #35465c;
        }
        
        .social-share-button.whatsapp {
            background-color: #25d366;
        }
        
        .social-share-button.telegram {
            background-color: #0088cc;
        }
        
        .social-share-button.email {
            background-color: #333333;
        }
        
        /* Floating Social Sharing */
        .social-sharing.position-floating {
            position: fixed;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            margin: 0;
            z-index: 999;
        }
        
        .social-sharing.position-floating .social-sharing-buttons {
            flex-direction: column;
        }
        
        .social-sharing.position-floating .social-sharing-text {
            display: none;
        }
        
        .social-sharing.position-floating .social-sharing-total {
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
        }
        
        @media (max-width: 767px) {
            .social-sharing.position-floating {
                display: none;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_social_css');

/**
 * Enqueue social scripts.
 */
function aqualuxe_enqueue_social_scripts() {
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true) || !get_theme_mod('aqualuxe_show_share_count', true)) {
        return;
    }
    
    if (!is_singular('post') && (!aqualuxe_is_woocommerce_active() || !is_singular('product'))) {
        return;
    }
    
    wp_enqueue_script('aqualuxe-social-sharing', AQUALUXE_ASSETS_URI . 'js/social-sharing.js', array('jquery'), AQUALUXE_VERSION, true);
    
    wp_localize_script('aqualuxe-social-sharing', 'aqualuxeSocialSharing', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'postId'  => get_the_ID(),
        'nonce'   => wp_create_nonce('aqualuxe-social-sharing'),
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_social_scripts');