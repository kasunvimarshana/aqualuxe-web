<?php
/**
 * Accessibility Enhancement Module
 *
 * Comprehensive WCAG 2.1 AA compliance features and accessibility improvements
 *
 * @package AquaLuxe\Core\Accessibility
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Accessibility Enhancement Class
 */
class AquaLuxe_Accessibility_Enhancements {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_head', array($this, 'add_accessibility_meta'), 5);
        add_action('wp_head', array($this, 'add_accessibility_styles'), 15);
        add_action('wp_footer', array($this, 'add_accessibility_scripts'), 5);
        
        // Enhanced navigation
        add_filter('walker_nav_menu_start_el', array($this, 'enhance_menu_accessibility'), 10, 4);
        add_filter('wp_nav_menu_args', array($this, 'enhance_menu_args'));
        
        // Form enhancements
        add_filter('comment_form_defaults', array($this, 'enhance_comment_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_accessibility_scripts'));
        
        // Content enhancements
        add_filter('the_content', array($this, 'enhance_content_accessibility'));
        add_filter('wp_get_attachment_image_attributes', array($this, 'enhance_image_accessibility'), 10, 3);
        
        // Admin enhancements
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_accessibility_scripts'));
        add_action('customize_register', array($this, 'add_accessibility_customizer_settings'));
    }

    /**
     * Add accessibility meta tags
     */
    public function add_accessibility_meta() {
        // Ensure proper zoom functionality
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, maximum-scale=5">' . "\n";
        
        // Add prefers-reduced-motion support
        ?>
        <meta name="color-scheme" content="light dark">
        <style>
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
        </style>
        <?php
    }

    /**
     * Add accessibility-specific styles
     */
    public function add_accessibility_styles() {
        ?>
        <style id="aqualuxe-accessibility-styles">
        /* Enhanced focus styles */
        *:focus {
            outline: 2px solid #005f99;
            outline-offset: 2px;
        }
        
        .skip-links {
            position: absolute;
            top: -40px;
            left: 6px;
            z-index: 999999;
        }
        
        .skip-links a {
            position: absolute;
            left: -10000px;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }
        
        .skip-links a:focus {
            position: static;
            width: auto;
            height: auto;
            overflow: visible;
            background: #000;
            color: #fff;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 3px;
            font-weight: bold;
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            :root {
                --color-text: #000000;
                --color-background: #ffffff;
                --color-primary: #0000ff;
                --color-border: #000000;
            }
            
            .dark {
                --color-text: #ffffff;
                --color-background: #000000;
                --color-primary: #00ffff;
                --color-border: #ffffff;
            }
        }
        
        /* Reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .carousel, .slider {
                scroll-behavior: auto;
            }
            
            .parallax {
                transform: none !important;
            }
        }
        
        /* Large text mode */
        .large-text {
            font-size: 1.25em;
            line-height: 1.6;
        }
        
        /* Screen reader only text */
        .sr-only {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
        
        /* Focus-visible polyfill */
        .js-focus-visible :focus:not(.focus-visible) {
            outline: none;
        }
        
        /* Enhanced button accessibility */
        button:disabled, input[type="submit"]:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Table accessibility */
        table {
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid var(--color-border, #ccc);
            padding: 8px;
        }
        
        th {
            background-color: var(--color-primary-light, #f0f0f0);
            font-weight: bold;
        }
        
        /* Form accessibility */
        label {
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        input[type="text"]:invalid,
        input[type="email"]:invalid,
        textarea:invalid {
            border-color: #d63384;
        }
        
        .error-message {
            color: #d63384;
            font-size: 0.875em;
            margin-top: 4px;
        }
        
        /* Loading states */
        .loading-indicator {
            position: relative;
        }
        
        .loading-indicator::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .loading-indicator::after {
                animation: none;
                border: 2px solid currentColor;
            }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        </style>
        <?php
    }

    /**
     * Add accessibility JavaScript
     */
    public function add_accessibility_scripts() {
        ?>
        <script>
        (function() {
            'use strict';
            
            // Focus-visible polyfill for better focus management
            function initFocusVisible() {
                var hadKeyboardEvent = true;
                var keyboardThrottleTimeout = 100;
                
                var pointerInputs = ['mouse', 'pointer', 'touch'];
                var keyboardInputs = ['keyboard'];
                
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
                        e.target.classList.add('focus-visible');
                    }
                }
                
                function onBlur(e) {
                    e.target.classList.remove('focus-visible');
                }
                
                document.addEventListener('keydown', onKeyDown, true);
                document.addEventListener('mousedown', onPointerDown, true);
                document.addEventListener('pointerdown', onPointerDown, true);
                document.addEventListener('touchstart', onPointerDown, true);
                document.addEventListener('focus', onFocus, true);
                document.addEventListener('blur', onBlur, true);
                
                document.body.classList.add('js-focus-visible');
            }
            
            // Announcement region for dynamic content
            function createAnnouncementRegion() {
                var announcer = document.createElement('div');
                announcer.id = 'aqualuxe-announcer';
                announcer.setAttribute('aria-live', 'polite');
                announcer.setAttribute('aria-atomic', 'true');
                announcer.className = 'sr-only';
                document.body.appendChild(announcer);
                
                window.aqualuxeAnnounce = function(message, priority) {
                    announcer.setAttribute('aria-live', priority || 'polite');
                    announcer.textContent = message;
                    
                    setTimeout(function() {
                        announcer.textContent = '';
                    }, 1000);
                };
            }
            
            // Enhanced keyboard navigation
            function enhanceKeyboardNavigation() {
                // Skip to main content
                var skipLink = document.createElement('a');
                skipLink.href = '#main';
                skipLink.className = 'skip-link sr-only';
                skipLink.textContent = '<?php esc_html_e('Skip to main content', 'aqualuxe'); ?>';
                skipLink.addEventListener('focus', function() {
                    this.classList.remove('sr-only');
                });
                skipLink.addEventListener('blur', function() {
                    this.classList.add('sr-only');
                });
                
                document.body.insertBefore(skipLink, document.body.firstChild);
                
                // Trap focus in modals
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        var activeModal = document.querySelector('.modal.active, .dialog[open]');
                        if (activeModal) {
                            closeModal(activeModal);
                        }
                    }
                    
                    if (e.key === 'Tab') {
                        var activeModal = document.querySelector('.modal.active, .dialog[open]');
                        if (activeModal) {
                            trapFocus(activeModal, e);
                        }
                    }
                });
            }
            
            function trapFocus(element, event) {
                var focusableElements = element.querySelectorAll(
                    'a[href], button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'
                );
                var firstElement = focusableElements[0];
                var lastElement = focusableElements[focusableElements.length - 1];
                
                if (event.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        event.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        event.preventDefault();
                    }
                }
            }
            
            function closeModal(modal) {
                modal.classList.remove('active');
                modal.removeAttribute('open');
                var trigger = document.querySelector('[data-modal-trigger="' + modal.id + '"]');
                if (trigger) {
                    trigger.focus();
                }
            }
            
            // Enhanced form validation
            function enhanceFormValidation() {
                var forms = document.querySelectorAll('form');
                
                forms.forEach(function(form) {
                    var inputs = form.querySelectorAll('input, textarea, select');
                    
                    inputs.forEach(function(input) {
                        input.addEventListener('invalid', function(e) {
                            e.preventDefault();
                            
                            var errorMsg = input.nextElementSibling;
                            if (!errorMsg || !errorMsg.classList.contains('error-message')) {
                                errorMsg = document.createElement('div');
                                errorMsg.className = 'error-message';
                                errorMsg.setAttribute('role', 'alert');
                                input.parentNode.insertBefore(errorMsg, input.nextSibling);
                            }
                            
                            errorMsg.textContent = input.validationMessage;
                            input.setAttribute('aria-describedby', input.id + '-error');
                            errorMsg.id = input.id + '-error';
                        });
                        
                        input.addEventListener('input', function() {
                            if (input.validity.valid) {
                                var errorMsg = document.getElementById(input.id + '-error');
                                if (errorMsg) {
                                    errorMsg.remove();
                                    input.removeAttribute('aria-describedby');
                                }
                            }
                        });
                    });
                });
            }
            
            // Initialize accessibility features
            document.addEventListener('DOMContentLoaded', function() {
                initFocusVisible();
                createAnnouncementRegion();
                enhanceKeyboardNavigation();
                enhanceFormValidation();
                
                // Announce page loads for screen readers
                if (window.aqualuxeAnnounce) {
                    window.aqualuxeAnnounce('<?php esc_html_e('Page loaded', 'aqualuxe'); ?>');
                }
            });
            
        })();
        </script>
        <?php
    }

    /**
     * Enhance navigation menu accessibility
     */
    public function enhance_menu_accessibility($item_output, $item, $depth, $args) {
        if (isset($args->theme_location)) {
            // Add ARIA attributes for dropdowns
            if (in_array('menu-item-has-children', $item->classes)) {
                $item_output = str_replace('<a ', '<a aria-haspopup="true" aria-expanded="false" ', $item_output);
            }
            
            // Add current page indicators
            if (in_array('current-menu-item', $item->classes)) {
                $item_output = str_replace('<a ', '<a aria-current="page" ', $item_output);
            }
        }
        
        return $item_output;
    }

    /**
     * Enhance menu arguments
     */
    public function enhance_menu_args($args) {
        if (!isset($args['menu_id'])) {
            $args['menu_id'] = 'menu-' . $args['theme_location'];
        }
        
        return $args;
    }

    /**
     * Enhance comment form accessibility
     */
    public function enhance_comment_form($defaults) {
        $defaults['title_reply'] = '<span id="reply-title" class="comment-reply-title">' . $defaults['title_reply'] . '</span>';
        
        $defaults['fields']['author'] = str_replace(
            'name="author"',
            'name="author" aria-required="true" aria-describedby="author-help"',
            $defaults['fields']['author']
        );
        
        $defaults['fields']['email'] = str_replace(
            'name="email"',
            'name="email" aria-required="true" aria-describedby="email-help"',
            $defaults['fields']['email']
        );
        
        return $defaults;
    }

    /**
     * Enhance content accessibility
     */
    public function enhance_content_accessibility($content) {
        // Add proper heading structure validation
        $content = preg_replace_callback(
            '/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/i',
            array($this, 'validate_heading_structure'),
            $content
        );
        
        // Enhance table accessibility
        $content = preg_replace(
            '/<table(?![^>]*role=)/i',
            '<table role="table"',
            $content
        );
        
        // Add language attributes to foreign text
        $content = $this->add_language_attributes($content);
        
        return $content;
    }

    /**
     * Validate heading structure
     */
    private function validate_heading_structure($matches) {
        $level = intval($matches[1]);
        $content = $matches[2];
        
        // Add ID if missing for anchor links
        if (strpos($matches[0], 'id=') === false) {
            $id = sanitize_title($content);
            return sprintf('<h%d id="%s">%s</h%d>', $level, $id, $content, $level);
        }
        
        return $matches[0];
    }

    /**
     * Add language attributes to foreign text
     */
    private function add_language_attributes($content) {
        // This is a simplified implementation
        // In a real scenario, you'd use a language detection library
        $foreign_phrases = array(
            'café' => 'fr',
            'piña' => 'es',
            'naïve' => 'fr',
        );
        
        foreach ($foreign_phrases as $phrase => $lang) {
            $content = str_replace(
                $phrase,
                '<span lang="' . $lang . '">' . $phrase . '</span>',
                $content
            );
        }
        
        return $content;
    }

    /**
     * Enhance image accessibility
     */
    public function enhance_image_accessibility($attr, $attachment, $size) {
        // Ensure alt text is present
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                // If still no alt text, use title or filename as last resort
                $attr['alt'] = $attachment->post_title ?: pathinfo($attachment->guid, PATHINFO_FILENAME);
            } else {
                $attr['alt'] = $alt_text;
            }
        }
        
        // Add loading and decoding attributes
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
        
        return $attr;
    }

    /**
     * Enqueue accessibility scripts
     */
    public function enqueue_accessibility_scripts() {
        wp_enqueue_script(
            'aqualuxe-accessibility',
            AQUALUXE_ASSETS_URI . '/js/accessibility.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-accessibility', 'aqualuxeA11y', array(
            'strings' => array(
                'skipToContent' => __('Skip to content', 'aqualuxe'),
                'closeDialog' => __('Close dialog', 'aqualuxe'),
                'openMenu' => __('Open menu', 'aqualuxe'),
                'closeMenu' => __('Close menu', 'aqualuxe'),
                'nextSlide' => __('Next slide', 'aqualuxe'),
                'previousSlide' => __('Previous slide', 'aqualuxe'),
                'pageLoaded' => __('Page loaded', 'aqualuxe'),
                'formValidationError' => __('Please correct the errors in the form', 'aqualuxe'),
            )
        ));
    }

    /**
     * Enqueue admin accessibility scripts
     */
    public function enqueue_admin_accessibility_scripts() {
        wp_enqueue_script(
            'aqualuxe-admin-accessibility',
            AQUALUXE_ASSETS_URI . '/js/admin/accessibility.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add accessibility settings to customizer
     */
    public function add_accessibility_customizer_settings($wp_customize) {
        // Accessibility section
        $wp_customize->add_section('aqualuxe_accessibility', array(
            'title' => __('Accessibility', 'aqualuxe'),
            'priority' => 60,
            'description' => __('Configure accessibility features for your site.', 'aqualuxe'),
        ));
        
        // High contrast mode
        $wp_customize->add_setting('aqualuxe_high_contrast', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_high_contrast', array(
            'type' => 'checkbox',
            'section' => 'aqualuxe_accessibility',
            'label' => __('Enable High Contrast Mode', 'aqualuxe'),
            'description' => __('Provides higher contrast colors for better readability.', 'aqualuxe'),
        ));
        
        // Large text mode
        $wp_customize->add_setting('aqualuxe_large_text', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_large_text', array(
            'type' => 'checkbox',
            'section' => 'aqualuxe_accessibility',
            'label' => __('Enable Large Text Mode', 'aqualuxe'),
            'description' => __('Increases base font size for better readability.', 'aqualuxe'),
        ));
        
        // Reduced motion
        $wp_customize->add_setting('aqualuxe_reduced_motion', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_reduced_motion', array(
            'type' => 'checkbox',
            'section' => 'aqualuxe_accessibility',
            'label' => __('Reduce Motion by Default', 'aqualuxe'),
            'description' => __('Reduces animations and transitions for users with motion sensitivity.', 'aqualuxe'),
        ));
        
        // Focus indicators
        $wp_customize->add_setting('aqualuxe_enhanced_focus', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        
        $wp_customize->add_control('aqualuxe_enhanced_focus', array(
            'type' => 'checkbox',
            'section' => 'aqualuxe_accessibility',
            'label' => __('Enhanced Focus Indicators', 'aqualuxe'),
            'description' => __('Provides clearer focus indicators for keyboard navigation.', 'aqualuxe'),
        ));
    }
}

// Initialize accessibility enhancements
new AquaLuxe_Accessibility_Enhancements();