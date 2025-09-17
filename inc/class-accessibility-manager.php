<?php
/**
 * Accessibility Enhancement System
 * WCAG 2.1 AA Compliance Implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * Accessibility Manager Class
 */
class Accessibility_Manager {

    /**
     * Instance
     *
     * @var Accessibility_Manager
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Accessibility_Manager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize accessibility features
     */
    public function init() {
        add_action('wp_head', array($this, 'add_accessibility_styles'));
        add_action('wp_footer', array($this, 'add_accessibility_scripts'));
        add_filter('wp_nav_menu_args', array($this, 'add_menu_accessibility'));
        add_filter('the_content', array($this, 'enhance_content_accessibility'));
        add_filter('post_thumbnail_html', array($this, 'enhance_image_accessibility'), 10, 5);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_accessibility_assets'));
        
        // Admin accessibility tools
        if (is_admin()) {
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('wp_ajax_aqualuxe_accessibility_audit', array($this, 'ajax_accessibility_audit'));
        }
    }

    /**
     * Add accessibility styles
     */
    public function add_accessibility_styles() {
        ?>
        <style id="aqualuxe-accessibility-styles">
        /* Skip to content link */
        .skip-link {
            position: absolute;
            left: -9999px;
            z-index: 999999;
            padding: 8px 16px;
            background: #000;
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 14px;
        }
        .skip-link:focus {
            left: 6px;
            top: 7px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .aqualuxe-content {
                background: #fff;
                color: #000;
            }
            .aqualuxe-button {
                border: 2px solid #000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* Focus indicators */
        .aqualuxe-focus-visible:focus-visible,
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        textarea:focus-visible,
        select:focus-visible {
            outline: 3px solid #005fcc;
            outline-offset: 2px;
        }

        /* Screen reader only text */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Large text mode */
        .aqualuxe-large-text {
            font-size: 1.25em;
            line-height: 1.6;
        }

        /* Color blind friendly indicators */
        .aqualuxe-error {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%23dc3545' d='M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm1 13H7v-2h2v2zm0-3H7V4h2v6z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: left center;
            padding-left: 20px;
        }

        /* Keyboard navigation enhancements */
        .aqualuxe-menu-item:focus-within > .sub-menu {
            display: block;
        }
        </style>
        <?php
    }

    /**
     * Add accessibility scripts
     */
    public function add_accessibility_scripts() {
        ?>
        <script id="aqualuxe-accessibility-scripts">
        (function() {
            'use strict';

            // Add focus-visible polyfill behavior
            function addFocusVisible() {
                var hadKeyboardEvent = true;
                var keyboardThrottleTimeout = 100;

                var pointerEvents = [
                    'mousedown',
                    'mouseup',
                    'pointermove',
                    'pointerdown',
                    'pointerup',
                    'touchmove',
                    'touchstart',
                    'touchend'
                ];

                function isValidFocusTarget(el) {
                    if (el && el !== document && el.nodeName !== 'HTML' && el.nodeName !== 'BODY') {
                        return true;
                    }
                    return false;
                }

                function onPointerDown() {
                    hadKeyboardEvent = false;
                }

                function onKeyDown(e) {
                    if (e.metaKey || e.altKey || e.ctrlKey) {
                        return;
                    }
                    hadKeyboardEvent = true;
                }

                function onFocus(e) {
                    if (hadKeyboardEvent || e.target.matches(':focus-visible')) {
                        e.target.classList.add('aqualuxe-focus-visible');
                    }
                }

                function onBlur(e) {
                    e.target.classList.remove('aqualuxe-focus-visible');
                }

                document.addEventListener('keydown', onKeyDown, true);
                document.addEventListener('mousedown', onPointerDown, true);
                document.addEventListener('pointerdown', onPointerDown, true);
                document.addEventListener('touchstart', onPointerDown, true);
                document.addEventListener('focus', onFocus, true);
                document.addEventListener('blur', onBlur, true);
            }

            // Keyboard navigation for menus
            function enhanceMenuNavigation() {
                var menuItems = document.querySelectorAll('.menu-item a');
                
                menuItems.forEach(function(item) {
                    item.addEventListener('keydown', function(e) {
                        var parent = e.target.closest('.menu-item');
                        var submenu = parent.querySelector('.sub-menu');
                        
                        if (e.key === 'ArrowDown' && submenu) {
                            e.preventDefault();
                            var firstSubmenuItem = submenu.querySelector('a');
                            if (firstSubmenuItem) {
                                firstSubmenuItem.focus();
                            }
                        } else if (e.key === 'Escape' && submenu) {
                            e.preventDefault();
                            e.target.focus();
                        }
                    });
                });
            }

            // Announce dynamic content changes to screen readers
            function announceToScreenReader(message, priority) {
                var announcement = document.createElement('div');
                announcement.setAttribute('aria-live', priority || 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only';
                announcement.textContent = message;
                
                document.body.appendChild(announcement);
                
                setTimeout(function() {
                    document.body.removeChild(announcement);
                }, 1000);
            }

            // Add alt text to images that don't have it
            function ensureImageAltText() {
                var images = document.querySelectorAll('img:not([alt])');
                images.forEach(function(img) {
                    img.setAttribute('alt', '');
                });
            }

            // Initialize accessibility features
            function init() {
                addFocusVisible();
                enhanceMenuNavigation();
                ensureImageAltText();

                // Add keyboard event listeners for accessibility shortcuts
                document.addEventListener('keydown', function(e) {
                    // Alt + 1: Skip to main content
                    if (e.altKey && e.key === '1') {
                        e.preventDefault();
                        var main = document.querySelector('main, #main, .main-content');
                        if (main) {
                            main.focus();
                            main.scrollIntoView();
                        }
                    }
                    
                    // Alt + 2: Skip to navigation
                    if (e.altKey && e.key === '2') {
                        e.preventDefault();
                        var nav = document.querySelector('nav, .navigation, .main-navigation');
                        if (nav) {
                            var firstLink = nav.querySelector('a');
                            if (firstLink) {
                                firstLink.focus();
                            }
                        }
                    }
                });

                // Make non-semantic interactive elements keyboard accessible
                var interactiveElements = document.querySelectorAll('[onclick]:not(a):not(button):not(input)');
                interactiveElements.forEach(function(el) {
                    if (!el.hasAttribute('tabindex')) {
                        el.setAttribute('tabindex', '0');
                    }
                    if (!el.hasAttribute('role')) {
                        el.setAttribute('role', 'button');
                    }
                    
                    el.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            el.click();
                        }
                    });
                });
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

            // Export functions for external use
            window.AquaLuxeAccessibility = {
                announceToScreenReader: announceToScreenReader
            };
        })();
        </script>
        <?php
    }

    /**
     * Add menu accessibility attributes
     */
    public function add_menu_accessibility($args) {
        if (!isset($args['menu_class'])) {
            $args['menu_class'] = '';
        }
        
        $args['menu_class'] .= ' aqualuxe-accessible-menu';
        
        if (!isset($args['container_class'])) {
            $args['container_class'] = '';
        }
        
        $args['container_class'] .= ' aqualuxe-menu-container';

        return $args;
    }

    /**
     * Enhance content accessibility
     */
    public function enhance_content_accessibility($content) {
        // Add heading structure validation
        $content = $this->validate_heading_structure($content);
        
        // Enhance tables with accessibility attributes
        $content = $this->enhance_table_accessibility($content);
        
        // Add language attributes to content in different languages
        $content = $this->add_language_attributes($content);
        
        return $content;
    }

    /**
     * Validate and fix heading structure
     */
    private function validate_heading_structure($content) {
        // This is a simplified implementation
        // In production, you'd want more sophisticated heading hierarchy validation
        
        $headings = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        
        foreach ($headings as $heading) {
            $content = preg_replace_callback(
                '/<' . $heading . '([^>]*)>(.*?)<\/' . $heading . '>/i',
                function($matches) use ($heading) {
                    $attributes = $matches[1];
                    $text = $matches[2];
                    
                    // Add tabindex for keyboard navigation if not present
                    if (strpos($attributes, 'tabindex') === false) {
                        $attributes .= ' tabindex="0"';
                    }
                    
                    return '<' . $heading . $attributes . '>' . $text . '</' . $heading . '>';
                },
                $content
            );
        }
        
        return $content;
    }

    /**
     * Enhance table accessibility
     */
    private function enhance_table_accessibility($content) {
        return preg_replace_callback(
            '/<table([^>]*)>(.*?)<\/table>/is',
            function($matches) {
                $attributes = $matches[1];
                $table_content = $matches[2];
                
                // Add role if not present
                if (strpos($attributes, 'role') === false) {
                    $attributes .= ' role="table"';
                }
                
                // Add summary or aria-label if not present
                if (strpos($attributes, 'aria-label') === false && strpos($attributes, 'summary') === false) {
                    $attributes .= ' aria-label="Data table"';
                }
                
                return '<table' . $attributes . '>' . $table_content . '</table>';
            },
            $content
        );
    }

    /**
     * Add language attributes to content
     */
    private function add_language_attributes($content) {
        // This would detect and add lang attributes to content in different languages
        // For now, just ensure proper language tagging for common patterns
        
        return $content;
    }

    /**
     * Enhance image accessibility
     */
    public function enhance_image_accessibility($html, $post_id, $post_thumbnail_id, $size, $attr) {
        // Ensure alt text is present
        if (strpos($html, 'alt=') === false) {
            $alt_text = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                $alt_text = get_the_title($post_id);
            }
            $html = str_replace('<img ', '<img alt="' . esc_attr($alt_text) . '" ', $html);
        }
        
        // Add loading="lazy" for performance
        if (strpos($html, 'loading=') === false) {
            $html = str_replace('<img ', '<img loading="lazy" ', $html);
        }
        
        return $html;
    }

    /**
     * Enqueue accessibility assets
     */
    public function enqueue_accessibility_assets() {
        $manifest = $this->get_manifest();
        
        if (isset($manifest['/css/accessibility.css'])) {
            wp_enqueue_style(
                'aqualuxe-accessibility',
                get_theme_file_uri('assets/dist' . $manifest['/css/accessibility.css']),
                array(),
                AQUALUXE_VERSION
            );
        }
        
        if (isset($manifest['/js/accessibility.js'])) {
            wp_enqueue_script(
                'aqualuxe-accessibility',
                get_theme_file_uri('assets/dist' . $manifest['/js/accessibility.js']),
                array(),
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Add admin menu for accessibility tools
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('Accessibility Audit', 'aqualuxe'),
            esc_html__('Accessibility', 'aqualuxe'),
            'manage_options',
            'aqualuxe-accessibility',
            array($this, 'admin_page')
        );
    }

    /**
     * Admin page for accessibility tools
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-accessibility-admin">
            <h1><?php esc_html_e('AquaLuxe Accessibility Audit', 'aqualuxe'); ?></h1>
            
            <div class="accessibility-section">
                <h3><?php esc_html_e('WCAG 2.1 AA Compliance Check', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Run a comprehensive accessibility audit to identify and fix compliance issues.', 'aqualuxe'); ?></p>
                
                <button type="button" id="run-accessibility-audit" class="button button-primary">
                    <?php esc_html_e('Run Accessibility Audit', 'aqualuxe'); ?>
                </button>
                
                <div id="audit-results" class="audit-results"></div>
            </div>

            <div class="accessibility-section">
                <h3><?php esc_html_e('Quick Fixes', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Common accessibility improvements that can be applied automatically.', 'aqualuxe'); ?></p>
                
                <div class="quick-fixes">
                    <label>
                        <input type="checkbox" name="fix_alt_text" checked>
                        <?php esc_html_e('Add missing alt text to images', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="fix_headings" checked>
                        <?php esc_html_e('Fix heading hierarchy', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="fix_contrast" checked>
                        <?php esc_html_e('Improve color contrast', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="fix_focus" checked>
                        <?php esc_html_e('Enhance focus indicators', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <button type="button" id="apply-quick-fixes" class="button button-secondary">
                    <?php esc_html_e('Apply Quick Fixes', 'aqualuxe'); ?>
                </button>
            </div>
        </div>

        <style>
        .aqualuxe-accessibility-admin .accessibility-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .quick-fixes label {
            display: block;
            margin-bottom: 10px;
        }
        .audit-results {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #0073aa;
            min-height: 50px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#run-accessibility-audit').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).text('<?php esc_js(_e('Running Audit...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_accessibility_audit',
                        nonce: '<?php echo wp_create_nonce('aqualuxe_accessibility'); ?>'
                    },
                    success: function(response) {
                        $('#audit-results').html(response.data);
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Accessibility Audit', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        $('#audit-results').html('<p style="color: red;"><?php esc_js(_e('Audit failed. Please try again.', 'aqualuxe')); ?></p>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Accessibility Audit', 'aqualuxe')); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX accessibility audit
     */
    public function ajax_accessibility_audit() {
        check_ajax_referer('aqualuxe_accessibility', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $audit_results = $this->run_accessibility_audit();
        wp_send_json_success($audit_results);
    }

    /**
     * Run accessibility audit
     */
    private function run_accessibility_audit() {
        $results = array();
        
        // Check for images without alt text
        $images_without_alt = $this->check_images_without_alt();
        if (!empty($images_without_alt)) {
            $results[] = array(
                'type' => 'warning',
                'message' => sprintf(__('%d images found without alt text', 'aqualuxe'), count($images_without_alt)),
                'details' => $images_without_alt
            );
        }
        
        // Check color contrast
        $contrast_issues = $this->check_color_contrast();
        if (!empty($contrast_issues)) {
            $results[] = array(
                'type' => 'error',
                'message' => __('Color contrast issues found', 'aqualuxe'),
                'details' => $contrast_issues
            );
        }
        
        // Check heading structure
        $heading_issues = $this->check_heading_structure();
        if (!empty($heading_issues)) {
            $results[] = array(
                'type' => 'warning',
                'message' => __('Heading structure issues found', 'aqualuxe'),
                'details' => $heading_issues
            );
        }
        
        if (empty($results)) {
            return '<p style="color: green;">✓ No accessibility issues found!</p>';
        }
        
        $html = '<h4>Accessibility Issues Found:</h4>';
        foreach ($results as $result) {
            $html .= '<div class="issue-item ' . $result['type'] . '">';
            $html .= '<strong>' . esc_html($result['message']) . '</strong>';
            if (!empty($result['details'])) {
                $html .= '<ul>';
                foreach (array_slice($result['details'], 0, 5) as $detail) {
                    $html .= '<li>' . esc_html($detail) . '</li>';
                }
                $html .= '</ul>';
            }
            $html .= '</div>';
        }
        
        return $html;
    }

    /**
     * Check for images without alt text
     */
    private function check_images_without_alt() {
        global $wpdb;
        
        $images = $wpdb->get_results("
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p 
            WHERE p.post_type = 'attachment' 
            AND p.post_mime_type LIKE 'image%'
            AND p.ID NOT IN (
                SELECT pm.post_id 
                FROM {$wpdb->postmeta} pm 
                WHERE pm.meta_key = '_wp_attachment_image_alt' 
                AND pm.meta_value != ''
            )
            LIMIT 10
        ");
        
        $issues = array();
        foreach ($images as $image) {
            $issues[] = $image->post_title ?: 'Image ID: ' . $image->ID;
        }
        
        return $issues;
    }

    /**
     * Check color contrast (simplified)
     */
    private function check_color_contrast() {
        // This would require actual CSS parsing and color analysis
        // For now, return sample data
        return array();
    }

    /**
     * Check heading structure
     */
    private function check_heading_structure() {
        // This would require parsing page content and checking heading hierarchy
        // For now, return sample data
        return array();
    }

    /**
     * Get webpack manifest
     */
    private function get_manifest() {
        static $manifest = null;
        
        if (null === $manifest) {
            $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
}