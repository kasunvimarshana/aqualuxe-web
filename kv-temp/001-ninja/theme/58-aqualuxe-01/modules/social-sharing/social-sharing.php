<?php
/**
 * Social Sharing Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Social Sharing Module Class
 */
class AquaLuxe_Social_Sharing {
    /**
     * Constructor
     */
    public function __construct() {
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $enable_social_sharing = isset($options['enable_social_sharing']) ? $options['enable_social_sharing'] : true;
        
        // Only initialize if social sharing is enabled
        if ($enable_social_sharing) {
            $this->init();
        }
    }

    /**
     * Initialize the module
     */
    public function init() {
        // Add social sharing scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add social sharing functions
        add_action('aqualuxe_after_content', array($this, 'add_social_sharing'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue social sharing script
        wp_enqueue_script(
            'aqualuxe-social-sharing',
            get_template_directory_uri() . '/modules/social-sharing/js/social-sharing.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Enqueue social sharing styles
        wp_enqueue_style(
            'aqualuxe-social-sharing',
            get_template_directory_uri() . '/modules/social-sharing/css/social-sharing.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Add social sharing buttons
     */
    public function add_social_sharing() {
        // Only add on single posts and pages
        if (!is_singular()) {
            return;
        }
        
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $social_sharing_position = isset($options['social_sharing_position']) ? $options['social_sharing_position'] : 'after_content';
        $social_sharing_networks = isset($options['social_sharing_networks']) ? $options['social_sharing_networks'] : array('facebook', 'twitter', 'linkedin', 'pinterest');
        $social_sharing_style = isset($options['social_sharing_style']) ? $options['social_sharing_style'] : 'buttons';
        $show_share_count = isset($options['show_share_count']) ? $options['show_share_count'] : true;
        
        // Check if we should display sharing buttons here
        $current_position = current_filter();
        if ($current_position === 'aqualuxe_before_content' && $social_sharing_position !== 'before_content' && $social_sharing_position !== 'both') {
            return;
        }
        if ($current_position === 'aqualuxe_after_content' && $social_sharing_position !== 'after_content' && $social_sharing_position !== 'both') {
            return;
        }
        
        // Display social sharing buttons
        aqualuxe_social_sharing();
    }
}

// Initialize the module
new AquaLuxe_Social_Sharing();

/**
 * Display social sharing buttons
 */
function aqualuxe_social_sharing() {
    // Get theme options
    $options = get_option('aqualuxe_options', array());
    $social_sharing_networks = isset($options['social_sharing_networks']) ? $options['social_sharing_networks'] : array('facebook', 'twitter', 'linkedin', 'pinterest');
    $social_sharing_style = isset($options['social_sharing_style']) ? $options['social_sharing_style'] : 'buttons';
    $show_share_count = isset($options['show_share_count']) ? $options['show_share_count'] : true;
    
    // Get post data
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large')) : '';
    
    // Set sharing class
    $sharing_class = 'social-sharing social-sharing-' . $social_sharing_style;
    if ($show_share_count) {
        $sharing_class .= ' show-share-count';
    }
    
    // Start output
    $output = '<div class="' . esc_attr($sharing_class) . '">';
    $output .= '<h4 class="social-sharing-title">' . esc_html__('Share This', 'aqualuxe') . '</h4>';
    $output .= '<ul class="social-sharing-list">';
    
    // Facebook
    if (in_array('facebook', $social_sharing_networks)) {
        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
        $output .= '<li class="social-sharing-item social-facebook">';
        $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-facebook"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
        }
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Twitter/X
    if (in_array('twitter', $social_sharing_networks)) {
        $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
        $output .= '<li class="social-sharing-item social-twitter">';
        $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Twitter/X', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-twitter"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Twitter/X', 'aqualuxe') . '</span>';
        }
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // LinkedIn
    if (in_array('linkedin', $social_sharing_networks)) {
        $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
        $output .= '<li class="social-sharing-item social-linkedin">';
        $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-linkedin"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
        }
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Pinterest
    if (in_array('pinterest', $social_sharing_networks) && !empty($post_thumbnail)) {
        $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
        $output .= '<li class="social-sharing-item social-pinterest">';
        $output .= '<a href="' . esc_url($pinterest_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-pinterest"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
        }
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Reddit
    if (in_array('reddit', $social_sharing_networks)) {
        $reddit_url = 'https://www.reddit.com/submit?url=' . $post_url . '&title=' . $post_title;
        $output .= '<li class="social-sharing-item social-reddit">';
        $output .= '<a href="' . esc_url($reddit_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Reddit', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-reddit"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Reddit', 'aqualuxe') . '</span>';
        }
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Email
    if (in_array('email', $social_sharing_networks)) {
        $email_subject = get_the_title();
        $email_body = esc_html__('Check out this article: ', 'aqualuxe') . get_permalink();
        $email_url = 'mailto:?subject=' . rawurlencode($email_subject) . '&body=' . rawurlencode($email_body);
        $output .= '<li class="social-sharing-item social-email">';
        $output .= '<a href="' . esc_url($email_url) . '" class="social-sharing-link" title="' . esc_attr__('Share via Email', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-email"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Email', 'aqualuxe') . '</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // WhatsApp
    if (in_array('whatsapp', $social_sharing_networks)) {
        $whatsapp_text = get_the_title() . ' ' . get_permalink();
        $whatsapp_url = 'https://api.whatsapp.com/send?text=' . urlencode($whatsapp_text);
        $output .= '<li class="social-sharing-item social-whatsapp">';
        $output .= '<a href="' . esc_url($whatsapp_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on WhatsApp', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-whatsapp"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('WhatsApp', 'aqualuxe') . '</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Telegram
    if (in_array('telegram', $social_sharing_networks)) {
        $telegram_text = get_the_title() . ' ' . get_permalink();
        $telegram_url = 'https://t.me/share/url?url=' . urlencode(get_permalink()) . '&text=' . urlencode(get_the_title());
        $output .= '<li class="social-sharing-item social-telegram">';
        $output .= '<a href="' . esc_url($telegram_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Telegram', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-telegram"></span>';
        if ($social_sharing_style !== 'icons') {
            $output .= '<span class="social-label">' . esc_html__('Telegram', 'aqualuxe') . '</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    echo $output;
}

/**
 * Display floating social sharing buttons
 */
function aqualuxe_social_sharing_floating() {
    // Get theme options
    $options = get_option('aqualuxe_options', array());
    $social_sharing_networks = isset($options['social_sharing_networks']) ? $options['social_sharing_networks'] : array('facebook', 'twitter', 'linkedin', 'pinterest');
    $show_share_count = isset($options['show_share_count']) ? $options['show_share_count'] : true;
    
    // Get post data
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large')) : '';
    
    // Set sharing class
    $sharing_class = 'social-sharing social-sharing-floating social-sharing-icons';
    if ($show_share_count) {
        $sharing_class .= ' show-share-count';
    }
    
    // Start output
    $output = '<div class="' . esc_attr($sharing_class) . '">';
    $output .= '<ul class="social-sharing-list">';
    
    // Facebook
    if (in_array('facebook', $social_sharing_networks)) {
        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
        $output .= '<li class="social-sharing-item social-facebook">';
        $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-facebook"></span>';
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Twitter/X
    if (in_array('twitter', $social_sharing_networks)) {
        $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
        $output .= '<li class="social-sharing-item social-twitter">';
        $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Twitter/X', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-twitter"></span>';
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // LinkedIn
    if (in_array('linkedin', $social_sharing_networks)) {
        $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
        $output .= '<li class="social-sharing-item social-linkedin">';
        $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-linkedin"></span>';
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Pinterest
    if (in_array('pinterest', $social_sharing_networks) && !empty($post_thumbnail)) {
        $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
        $output .= '<li class="social-sharing-item social-pinterest">';
        $output .= '<a href="' . esc_url($pinterest_url) . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link" title="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
        $output .= '<span class="social-icon icon-pinterest"></span>';
        if ($show_share_count) {
            $output .= '<span class="share-count">0</span>';
        }
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    echo $output;
}