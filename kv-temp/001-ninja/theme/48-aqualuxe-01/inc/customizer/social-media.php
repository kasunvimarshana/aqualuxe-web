<?php
/**
 * AquaLuxe Theme Social Media Integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Social Media Icons Component
 * 
 * Renders social media icons based on customizer settings
 *
 * @param array $args Optional. Arguments to customize the output.
 * @return string HTML output of social media icons
 */
function aqualuxe_social_icons($args = array()) {
    // Default arguments
    $defaults = array(
        'container'       => 'div',
        'container_class' => 'social-icons',
        'icons_class'     => 'social-icon',
        'show_labels'     => false,
        'target'          => '_blank',
        'rel'             => 'noopener noreferrer',
        'echo'            => true,
    );

    // Parse arguments
    $args = wp_parse_args($args, $defaults);

    // Get social media URLs from options
    $facebook_url  = aqualuxe_get_option('facebook_url', '');
    $twitter_url   = aqualuxe_get_option('twitter_url', '');
    $instagram_url = aqualuxe_get_option('instagram_url', '');
    $youtube_url   = aqualuxe_get_option('youtube_url', '');
    $linkedin_url  = aqualuxe_get_option('linkedin_url', '');
    $pinterest_url = aqualuxe_get_option('pinterest_url', '');
    $tiktok_url    = aqualuxe_get_option('tiktok_url', '');

    // Start output buffer
    ob_start();

    // Only output if we have at least one social URL
    if ($facebook_url || $twitter_url || $instagram_url || $youtube_url || $linkedin_url || $pinterest_url || $tiktok_url) {
        // Open container
        printf('<%1$s class="%2$s">', esc_attr($args['container']), esc_attr($args['container_class']));

        // Facebook
        if ($facebook_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-facebook" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($facebook_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Follow us on Facebook', 'aqualuxe'),
                '<i class="fab fa-facebook-f" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('Facebook', 'aqualuxe') . '</span>' : ''
            );
        }

        // Twitter
        if ($twitter_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-twitter" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($twitter_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Follow us on Twitter', 'aqualuxe'),
                '<i class="fab fa-twitter" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('Twitter', 'aqualuxe') . '</span>' : ''
            );
        }

        // Instagram
        if ($instagram_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-instagram" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($instagram_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Follow us on Instagram', 'aqualuxe'),
                '<i class="fab fa-instagram" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('Instagram', 'aqualuxe') . '</span>' : ''
            );
        }

        // YouTube
        if ($youtube_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-youtube" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($youtube_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Subscribe to our YouTube channel', 'aqualuxe'),
                '<i class="fab fa-youtube" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('YouTube', 'aqualuxe') . '</span>' : ''
            );
        }

        // LinkedIn
        if ($linkedin_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-linkedin" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($linkedin_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Connect with us on LinkedIn', 'aqualuxe'),
                '<i class="fab fa-linkedin-in" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>' : ''
            );
        }

        // Pinterest
        if ($pinterest_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-pinterest" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($pinterest_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Follow us on Pinterest', 'aqualuxe'),
                '<i class="fab fa-pinterest-p" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('Pinterest', 'aqualuxe') . '</span>' : ''
            );
        }

        // TikTok
        if ($tiktok_url) {
            printf(
                '<a href="%1$s" class="%2$s %2$s-tiktok" target="%3$s" rel="%4$s" aria-label="%5$s">%6$s%7$s</a>',
                esc_url($tiktok_url),
                esc_attr($args['icons_class']),
                esc_attr($args['target']),
                esc_attr($args['rel']),
                esc_attr__('Follow us on TikTok', 'aqualuxe'),
                '<i class="fab fa-tiktok" aria-hidden="true"></i>',
                $args['show_labels'] ? '<span class="social-label">' . esc_html__('TikTok', 'aqualuxe') . '</span>' : ''
            );
        }

        // Close container
        printf('</%s>', esc_attr($args['container']));
    }

    // Get output buffer
    $output = ob_get_clean();

    // Echo or return
    if ($args['echo']) {
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        return $output;
    }
}

/**
 * Social Sharing Buttons
 * 
 * Renders social sharing buttons for posts and products
 *
 * @param array $args Optional. Arguments to customize the output.
 * @return string HTML output of social sharing buttons
 */
function aqualuxe_social_sharing($args = array()) {
    // Default arguments
    $defaults = array(
        'container'       => 'div',
        'container_class' => 'social-sharing',
        'buttons_class'   => 'share-button',
        'show_labels'     => true,
        'title'           => esc_html__('Share this:', 'aqualuxe'),
        'echo'            => true,
        'networks'        => array('facebook', 'twitter', 'linkedin', 'pinterest', 'email'),
    );

    // Parse arguments
    $args = wp_parse_args($args, $defaults);

    // Get current page URL
    $url = urlencode(get_permalink());
    
    // Get current page title
    $title = urlencode(get_the_title());
    
    // Get current page thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'large', true);
        $thumbnail = urlencode($thumbnail_url[0]);
    }

    // Get enabled sharing options from customizer
    $share_facebook  = aqualuxe_get_option('share_facebook', true);
    $share_twitter   = aqualuxe_get_option('share_twitter', true);
    $share_linkedin  = aqualuxe_get_option('share_linkedin', true);
    $share_pinterest = aqualuxe_get_option('share_pinterest', true);
    $share_email     = aqualuxe_get_option('share_email', true);

    // Start output buffer
    ob_start();

    // Open container
    printf('<%1$s class="%2$s">', esc_attr($args['container']), esc_attr($args['container_class']));

    // Title
    if ($args['title']) {
        echo '<span class="sharing-title">' . esc_html($args['title']) . '</span>';
    }

    // Facebook
    if ($share_facebook && in_array('facebook', $args['networks'])) {
        printf(
            '<a href="%1$s" class="%2$s %2$s-facebook" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s%5$s</a>',
            esc_url('https://www.facebook.com/sharer/sharer.php?u=' . $url),
            esc_attr($args['buttons_class']),
            esc_attr__('Share on Facebook', 'aqualuxe'),
            '<i class="fab fa-facebook-f" aria-hidden="true"></i>',
            $args['show_labels'] ? '<span class="share-label">' . esc_html__('Facebook', 'aqualuxe') . '</span>' : ''
        );
    }

    // Twitter
    if ($share_twitter && in_array('twitter', $args['networks'])) {
        printf(
            '<a href="%1$s" class="%2$s %2$s-twitter" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s%5$s</a>',
            esc_url('https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url),
            esc_attr($args['buttons_class']),
            esc_attr__('Share on Twitter', 'aqualuxe'),
            '<i class="fab fa-twitter" aria-hidden="true"></i>',
            $args['show_labels'] ? '<span class="share-label">' . esc_html__('Twitter', 'aqualuxe') . '</span>' : ''
        );
    }

    // LinkedIn
    if ($share_linkedin && in_array('linkedin', $args['networks'])) {
        printf(
            '<a href="%1$s" class="%2$s %2$s-linkedin" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s%5$s</a>',
            esc_url('https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title),
            esc_attr($args['buttons_class']),
            esc_attr__('Share on LinkedIn', 'aqualuxe'),
            '<i class="fab fa-linkedin-in" aria-hidden="true"></i>',
            $args['show_labels'] ? '<span class="share-label">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>' : ''
        );
    }

    // Pinterest
    if ($share_pinterest && in_array('pinterest', $args['networks']) && $thumbnail) {
        printf(
            '<a href="%1$s" class="%2$s %2$s-pinterest" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s%5$s</a>',
            esc_url('https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title),
            esc_attr($args['buttons_class']),
            esc_attr__('Pin on Pinterest', 'aqualuxe'),
            '<i class="fab fa-pinterest-p" aria-hidden="true"></i>',
            $args['show_labels'] ? '<span class="share-label">' . esc_html__('Pinterest', 'aqualuxe') . '</span>' : ''
        );
    }

    // Email
    if ($share_email && in_array('email', $args['networks'])) {
        printf(
            '<a href="%1$s" class="%2$s %2$s-email" aria-label="%3$s">%4$s%5$s</a>',
            esc_url('mailto:?subject=' . $title . '&body=' . esc_url(get_permalink())),
            esc_attr($args['buttons_class']),
            esc_attr__('Share via Email', 'aqualuxe'),
            '<i class="fas fa-envelope" aria-hidden="true"></i>',
            $args['show_labels'] ? '<span class="share-label">' . esc_html__('Email', 'aqualuxe') . '</span>' : ''
        );
    }

    // Close container
    printf('</%s>', esc_attr($args['container']));

    // Get output buffer
    $output = ob_get_clean();

    // Echo or return
    if ($args['echo']) {
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        return $output;
    }
}

/**
 * Add social media icons to header
 */
function aqualuxe_header_social_icons() {
    if (aqualuxe_get_option('header_social', true)) {
        aqualuxe_social_icons(array(
            'container_class' => 'header-social-icons',
            'icons_class'     => 'header-social-icon',
            'show_labels'     => false,
        ));
    }
}
add_action('aqualuxe_header_top_bar', 'aqualuxe_header_social_icons', 20);

/**
 * Add social media icons to footer
 */
function aqualuxe_footer_social_icons() {
    if (aqualuxe_get_option('footer_social', true)) {
        aqualuxe_social_icons(array(
            'container_class' => 'footer-social-icons',
            'icons_class'     => 'footer-social-icon',
            'show_labels'     => false,
        ));
    }
}
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_social_icons', 20);

/**
 * Add social sharing buttons to single posts
 */
function aqualuxe_post_social_sharing() {
    if (is_singular('post') && aqualuxe_get_option('social_sharing', true)) {
        echo '<div class="post-sharing">';
        aqualuxe_social_sharing();
        echo '</div>';
    }
}
add_action('aqualuxe_after_post_content', 'aqualuxe_post_social_sharing', 20);

/**
 * Add social sharing buttons to products
 */
function aqualuxe_product_social_sharing() {
    if (is_singular('product') && aqualuxe_get_option('enable_product_sharing', true)) {
        echo '<div class="product-sharing">';
        aqualuxe_social_sharing(array(
            'title' => esc_html__('Share this product:', 'aqualuxe'),
        ));
        echo '</div>';
    }
}
add_action('woocommerce_share', 'aqualuxe_product_social_sharing', 10);