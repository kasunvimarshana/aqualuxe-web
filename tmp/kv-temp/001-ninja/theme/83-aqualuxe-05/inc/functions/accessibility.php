<?php
/**
 * Accessibility Functions
 * 
 * WCAG 2.1 AA compliance and accessibility features
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Accessibility Manager Class
 * WCAG 2.1 AA compliance implementation
 */
class KV_Accessibility_Manager {
    
    /**
     * Initialize accessibility features
     * 
     * @return void
     */
    public static function init() {
        // Add accessibility toolbar
        add_action('wp_footer', [__CLASS__, 'add_accessibility_toolbar']);
        
        // Enhance navigation accessibility
        add_filter('nav_menu_link_attributes', [__CLASS__, 'enhance_menu_accessibility'], 10, 4);
        
        // Add skip links
        add_action('wp_body_open', [__CLASS__, 'add_skip_links']);
        
        // Enhance form accessibility
        add_filter('comment_form_default_fields', [__CLASS__, 'enhance_comment_form_accessibility']);
        add_filter('comment_form_defaults', [__CLASS__, 'enhance_comment_form_defaults']);
        
        // Add ARIA landmarks
        add_action('wp_head', [__CLASS__, 'add_aria_landmarks_script']);
        
        // Enhance image accessibility
        add_filter('wp_get_attachment_image_attributes', [__CLASS__, 'enhance_image_accessibility'], 10, 3);
        
        // Focus management
        add_action('wp_footer', [__CLASS__, 'add_focus_management_script']);
        
        // High contrast mode support
        add_action('wp_head', [__CLASS__, 'add_high_contrast_css']);
        
        // Screen reader support
        add_action('wp_head', [__CLASS__, 'add_screen_reader_styles']);
        
        // Keyboard navigation
        add_action('wp_footer', [__CLASS__, 'add_keyboard_navigation_script']);
        
        // Color contrast validation
        add_action('wp_head', [__CLASS__, 'validate_color_contrast']);
        
        // Font size controls
        add_action('wp_footer', [__CLASS__, 'add_font_size_controls']);
        
        // Animation controls
        add_action('wp_head', [__CLASS__, 'add_animation_controls']);
    }
    
    /**
     * Add accessibility toolbar
     * 
     * @return void
     */
    public static function add_accessibility_toolbar() {
        $toolbar_enabled = kv_get_theme_option('accessibility_toolbar', true);
        
        if (!$toolbar_enabled) {
            return;
        }
        ?>
        <div id="accessibility-toolbar" class="accessibility-toolbar" role="toolbar" aria-label="<?php esc_attr_e('Accessibility Options', KV_THEME_TEXTDOMAIN); ?>">
            <button type="button" class="accessibility-toggle" aria-expanded="false" aria-controls="accessibility-options">
                <span class="sr-only"><?php esc_html_e('Accessibility Options', KV_THEME_TEXTDOMAIN); ?></span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 5.5V5.5C14.8 5.3 14.6 5.1 14.3 4.9L13.5 4.5C13.1 4.3 12.6 4.2 12 4.2S10.9 4.3 10.5 4.5L9.7 4.9C9.4 5.1 9.2 5.3 9 5.5V5.5L3 7V9L9 7.5V10.5C9 11.8 10.2 13 11.5 13H12.5C13.8 13 15 11.8 15 10.5V7.5L21 9ZM6 17C6 15.9 6.9 15 8 15C9.1 15 10 15.9 10 17C10 18.1 9.1 19 8 19C6.9 19 6 18.1 6 17ZM14 17C14 15.9 14.9 15 16 15C17.1 15 18 15.9 18 17C18 18.1 17.1 19 16 19C14.9 19 14 18.1 14 17Z"/>
                </svg>
            </button>
            
            <div id="accessibility-options" class="accessibility-options" hidden>
                <div class="accessibility-option">
                    <button type="button" id="toggle-high-contrast" class="accessibility-btn">
                        <?php esc_html_e('High Contrast', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                </div>
                
                <div class="accessibility-option">
                    <label for="font-size-slider"><?php esc_html_e('Font Size', KV_THEME_TEXTDOMAIN); ?></label>
                    <input type="range" id="font-size-slider" min="80" max="150" value="100" step="10" aria-describedby="font-size-value">
                    <span id="font-size-value">100%</span>
                </div>
                
                <div class="accessibility-option">
                    <button type="button" id="toggle-animations" class="accessibility-btn">
                        <?php esc_html_e('Disable Animations', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                </div>
                
                <div class="accessibility-option">
                    <button type="button" id="toggle-focus-outline" class="accessibility-btn">
                        <?php esc_html_e('Enhanced Focus', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                </div>
                
                <div class="accessibility-option">
                    <button type="button" id="reset-accessibility" class="accessibility-btn accessibility-btn--reset">
                        <?php esc_html_e('Reset Settings', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <style>
        .accessibility-toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: #fff;
            border: 2px solid #0073aa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .accessibility-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .accessibility-toggle:hover,
        .accessibility-toggle:focus {
            background: #005a87;
        }
        
        .accessibility-options {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            padding: 16px;
            background: white;
            border: 2px solid #0073aa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            min-width: 200px;
        }
        
        .accessibility-option {
            margin-bottom: 12px;
        }
        
        .accessibility-option:last-child {
            margin-bottom: 0;
        }
        
        .accessibility-btn {
            width: 100%;
            padding: 8px 12px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .accessibility-btn:hover,
        .accessibility-btn:focus {
            background: #e0e0e0;
        }
        
        .accessibility-btn.active {
            background: #0073aa;
            color: white;
        }
        
        .accessibility-btn--reset {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }
        
        .accessibility-btn--reset:hover,
        .accessibility-btn--reset:focus {
            background: #c82333;
        }
        
        #font-size-slider {
            width: 100%;
            margin: 8px 0;
        }
        
        /* High contrast mode */
        body.high-contrast {
            background: #000 !important;
            color: #fff !important;
        }
        
        body.high-contrast a {
            color: #ffff00 !important;
        }
        
        body.high-contrast button {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #fff !important;
        }
        
        /* Enhanced focus */
        body.enhanced-focus *:focus {
            outline: 3px solid #ff0 !important;
            outline-offset: 2px !important;
        }
        
        /* Disable animations */
        body.no-animations *,
        body.no-animations *::before,
        body.no-animations *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
        
        @media (max-width: 768px) {
            .accessibility-toolbar {
                top: 10px;
                right: 10px;
            }
            
            .accessibility-options {
                position: fixed;
                top: 60px;
                right: 10px;
                left: 10px;
                width: auto;
            }
        }
        </style>
        <?php
    }
    
    /**
     * Enhance menu accessibility
     * 
     * @param array    $atts  Link attributes
     * @param WP_Post  $item  Menu item
     * @param stdClass $args  Menu arguments
     * @param int      $depth Menu depth
     * @return array Enhanced attributes
     */
    public static function enhance_menu_accessibility($atts, $item, $args, $depth) {
        // Add ARIA attributes for dropdown menus
        if (in_array('menu-item-has-children', $item->classes)) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }
        
        // Add descriptive ARIA labels
        if (!empty($item->description)) {
            $atts['aria-describedby'] = 'menu-item-description-' . $item->ID;
        }
        
        return $atts;
    }
    
    /**
     * Add skip links
     * 
     * @return void
     */
    public static function add_skip_links() {
        ?>
        <div class="skip-links">
            <a class="skip-link" href="#main"><?php esc_html_e('Skip to main content', KV_THEME_TEXTDOMAIN); ?></a>
            <a class="skip-link" href="#navigation"><?php esc_html_e('Skip to navigation', KV_THEME_TEXTDOMAIN); ?></a>
            <a class="skip-link" href="#footer"><?php esc_html_e('Skip to footer', KV_THEME_TEXTDOMAIN); ?></a>
        </div>
        <?php
    }
    
    /**
     * Enhance comment form accessibility
     * 
     * @param array $fields Form fields
     * @return array Enhanced fields
     */
    public static function enhance_comment_form_accessibility($fields) {
        // Add proper labels and ARIA attributes
        $fields['author'] = str_replace(
            'placeholder="Name *"',
            'placeholder="Name *" aria-required="true" aria-describedby="name-description"',
            $fields['author']
        );
        
        $fields['email'] = str_replace(
            'placeholder="Email *"',
            'placeholder="Email *" aria-required="true" aria-describedby="email-description"',
            $fields['email']
        );
        
        $fields['url'] = str_replace(
            'placeholder="Website"',
            'placeholder="Website" aria-describedby="url-description"',
            $fields['url']
        );
        
        return $fields;
    }
    
    /**
     * Enhance comment form defaults
     * 
     * @param array $defaults Form defaults
     * @return array Enhanced defaults
     */
    public static function enhance_comment_form_defaults($defaults) {
        // Add ARIA attributes to comment field
        $defaults['comment_field'] = str_replace(
            '<textarea',
            '<textarea aria-required="true" aria-describedby="comment-description"',
            $defaults['comment_field']
        );
        
        // Add field descriptions
        $defaults['fields']['author'] .= '<div id="name-description" class="field-description">' . esc_html__('Your name is required.', KV_THEME_TEXTDOMAIN) . '</div>';
        $defaults['fields']['email'] .= '<div id="email-description" class="field-description">' . esc_html__('Your email address will not be published.', KV_THEME_TEXTDOMAIN) . '</div>';
        $defaults['fields']['url'] .= '<div id="url-description" class="field-description">' . esc_html__('Optional website URL.', KV_THEME_TEXTDOMAIN) . '</div>';
        
        $defaults['comment_field'] .= '<div id="comment-description" class="field-description">' . esc_html__('Your comment is required.', KV_THEME_TEXTDOMAIN) . '</div>';
        
        return $defaults;
    }
    
    /**
     * Add ARIA landmarks script
     * 
     * @return void
     */
    public static function add_aria_landmarks_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add ARIA landmarks to common elements
            var header = document.querySelector('header');
            if (header && !header.getAttribute('role')) {
                header.setAttribute('role', 'banner');
            }
            
            var nav = document.querySelector('nav');
            if (nav && !nav.getAttribute('role')) {
                nav.setAttribute('role', 'navigation');
            }
            
            var main = document.querySelector('main');
            if (main && !main.getAttribute('role')) {
                main.setAttribute('role', 'main');
            }
            
            var footer = document.querySelector('footer');
            if (footer && !footer.getAttribute('role')) {
                footer.setAttribute('role', 'contentinfo');
            }
            
            var aside = document.querySelector('aside');
            if (aside && !aside.getAttribute('role')) {
                aside.setAttribute('role', 'complementary');
            }
        });
        </script>
        <?php
    }
    
    /**
     * Enhance image accessibility
     * 
     * @param array   $attr       Image attributes
     * @param WP_Post $attachment Attachment object
     * @param string  $size       Image size
     * @return array Enhanced attributes
     */
    public static function enhance_image_accessibility($attr, $attachment, $size) {
        // Ensure alt text is present
        if (empty($attr['alt'])) {
            $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
            if (empty($alt_text)) {
                $attr['alt'] = $attachment->post_title;
            }
        }
        
        // Add longdesc for complex images
        $long_description = get_post_meta($attachment->ID, '_wp_attachment_long_description', true);
        if (!empty($long_description)) {
            $attr['longdesc'] = $long_description;
        }
        
        return $attr;
    }
    
    /**
     * Add focus management script
     * 
     * @return void
     */
    public static function add_focus_management_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus management for modal dialogs
            var modals = document.querySelectorAll('[role="dialog"]');
            modals.forEach(function(modal) {
                modal.addEventListener('show', function() {
                    var focusableElements = modal.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                    if (focusableElements.length > 0) {
                        focusableElements[0].focus();
                    }
                });
            });
            
            // Trap focus in modal dialogs
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    var activeModal = document.querySelector('[role="dialog"]:not([hidden])');
                    if (activeModal) {
                        var focusableElements = activeModal.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
                        var firstElement = focusableElements[0];
                        var lastElement = focusableElements[focusableElements.length - 1];
                        
                        if (e.shiftKey) {
                            if (document.activeElement === firstElement) {
                                e.preventDefault();
                                lastElement.focus();
                            }
                        } else {
                            if (document.activeElement === lastElement) {
                                e.preventDefault();
                                firstElement.focus();
                            }
                        }
                    }
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Add high contrast CSS
     * 
     * @return void
     */
    public static function add_high_contrast_css() {
        ?>
        <style id="high-contrast-css" disabled>
        /* High contrast styles */
        body.high-contrast,
        body.high-contrast * {
            background: #000 !important;
            color: #fff !important;
            border-color: #fff !important;
        }
        
        body.high-contrast a,
        body.high-contrast a:visited {
            color: #ffff00 !important;
            text-decoration: underline !important;
        }
        
        body.high-contrast a:hover,
        body.high-contrast a:focus {
            color: #ffffff !important;
            background: #ffff00 !important;
        }
        
        body.high-contrast button,
        body.high-contrast input[type="submit"],
        body.high-contrast input[type="button"] {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #fff !important;
        }
        
        body.high-contrast input,
        body.high-contrast textarea,
        body.high-contrast select {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #fff !important;
        }
        
        body.high-contrast img {
            opacity: 0.8 !important;
            filter: contrast(150%) !important;
        }
        </style>
        <?php
    }
    
    /**
     * Add screen reader styles
     * 
     * @return void
     */
    public static function add_screen_reader_styles() {
        ?>
        <style>
        /* Screen reader only content */
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
        
        .sr-only:focus {
            position: static !important;
            width: auto !important;
            height: auto !important;
            padding: inherit !important;
            margin: inherit !important;
            overflow: visible !important;
            clip: auto !important;
            white-space: inherit !important;
        }
        
        /* Field descriptions */
        .field-description {
            font-size: 0.875em;
            color: #666;
            margin-top: 4px;
        }
        
        /* Focus indicators */
        *:focus {
            outline: 2px solid #0073aa;
            outline-offset: 2px;
        }
        
        /* Skip links */
        .skip-links {
            position: absolute;
            top: -40px;
            left: 6px;
            z-index: 999999;
        }
        
        .skip-link {
            position: absolute;
            left: -9999px;
            z-index: 999999;
            padding: 8px 16px;
            background: #0073aa;
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        
        .skip-link:focus {
            left: 6px;
            top: 7px;
        }
        </style>
        <?php
    }
    
    /**
     * Add keyboard navigation script
     * 
     * @return void
     */
    public static function add_keyboard_navigation_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhance keyboard navigation for menus
            var menuItems = document.querySelectorAll('.menu-item-has-children > a');
            
            menuItems.forEach(function(item) {
                item.addEventListener('keydown', function(e) {
                    var submenu = this.nextElementSibling;
                    
                    if (e.key === 'ArrowDown' || e.key === 'Space') {
                        e.preventDefault();
                        if (submenu) {
                            submenu.style.display = 'block';
                            this.setAttribute('aria-expanded', 'true');
                            var firstSubmenuItem = submenu.querySelector('a');
                            if (firstSubmenuItem) {
                                firstSubmenuItem.focus();
                            }
                        }
                    }
                    
                    if (e.key === 'Escape') {
                        if (submenu) {
                            submenu.style.display = 'none';
                            this.setAttribute('aria-expanded', 'false');
                            this.focus();
                        }
                    }
                });
            });
            
            // Close menus on escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    var openMenus = document.querySelectorAll('.menu-item-has-children [aria-expanded="true"]');
                    openMenus.forEach(function(menu) {
                        menu.setAttribute('aria-expanded', 'false');
                        var submenu = menu.nextElementSibling;
                        if (submenu) {
                            submenu.style.display = 'none';
                        }
                    });
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Validate color contrast
     * 
     * @return void
     */
    public static function validate_color_contrast() {
        if (!WP_DEBUG) {
            return;
        }
        
        ?>
        <script>
        // Color contrast validation for development
        function checkColorContrast() {
            var elements = document.querySelectorAll('*');
            
            elements.forEach(function(element) {
                var style = window.getComputedStyle(element);
                var bgColor = style.backgroundColor;
                var textColor = style.color;
                
                // Skip elements with transparent backgrounds
                if (bgColor === 'rgba(0, 0, 0, 0)' || bgColor === 'transparent') {
                    return;
                }
                
                var contrast = calculateContrast(bgColor, textColor);
                
                if (contrast < 4.5) {
                    console.warn('Low contrast detected:', element, 'Contrast ratio:', contrast.toFixed(2));
                }
            });
        }
        
        function calculateContrast(bg, text) {
            // Simplified contrast calculation
            var bgLum = getLuminance(bg);
            var textLum = getLuminance(text);
            
            var brightest = Math.max(bgLum, textLum);
            var darkest = Math.min(bgLum, textLum);
            
            return (brightest + 0.05) / (darkest + 0.05);
        }
        
        function getLuminance(color) {
            // Simplified luminance calculation
            var rgb = color.match(/\d+/g);
            if (!rgb) return 0;
            
            var r = parseInt(rgb[0]) / 255;
            var g = parseInt(rgb[1]) / 255;
            var b = parseInt(rgb[2]) / 255;
            
            return 0.2126 * r + 0.7152 * g + 0.0722 * b;
        }
        
        // Check contrast after page load
        window.addEventListener('load', checkColorContrast);
        </script>
        <?php
    }
    
    /**
     * Add font size controls
     * 
     * @return void
     */
    public static function add_font_size_controls() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var fontSizeSlider = document.getElementById('font-size-slider');
            var fontSizeValue = document.getElementById('font-size-value');
            
            if (fontSizeSlider && fontSizeValue) {
                // Load saved font size
                var savedFontSize = localStorage.getItem('kv-font-size') || '100';
                fontSizeSlider.value = savedFontSize;
                fontSizeValue.textContent = savedFontSize + '%';
                document.documentElement.style.fontSize = savedFontSize + '%';
                
                fontSizeSlider.addEventListener('input', function() {
                    var fontSize = this.value;
                    fontSizeValue.textContent = fontSize + '%';
                    document.documentElement.style.fontSize = fontSize + '%';
                    localStorage.setItem('kv-font-size', fontSize);
                });
            }
        });
        </script>
        <?php
    }
    
    /**
     * Add animation controls
     * 
     * @return void
     */
    public static function add_animation_controls() {
        ?>
        <style>
        /* Respect user's motion preferences */
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
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleAnimations = document.getElementById('toggle-animations');
            var toggleHighContrast = document.getElementById('toggle-high-contrast');
            var toggleFocusOutline = document.getElementById('toggle-focus-outline');
            var resetAccessibility = document.getElementById('reset-accessibility');
            
            // Load saved preferences
            if (localStorage.getItem('kv-no-animations') === 'true') {
                document.body.classList.add('no-animations');
                if (toggleAnimations) toggleAnimations.classList.add('active');
            }
            
            if (localStorage.getItem('kv-high-contrast') === 'true') {
                document.body.classList.add('high-contrast');
                if (toggleHighContrast) toggleHighContrast.classList.add('active');
            }
            
            if (localStorage.getItem('kv-enhanced-focus') === 'true') {
                document.body.classList.add('enhanced-focus');
                if (toggleFocusOutline) toggleFocusOutline.classList.add('active');
            }
            
            // Toggle animations
            if (toggleAnimations) {
                toggleAnimations.addEventListener('click', function() {
                    document.body.classList.toggle('no-animations');
                    this.classList.toggle('active');
                    localStorage.setItem('kv-no-animations', document.body.classList.contains('no-animations'));
                });
            }
            
            // Toggle high contrast
            if (toggleHighContrast) {
                toggleHighContrast.addEventListener('click', function() {
                    document.body.classList.toggle('high-contrast');
                    this.classList.toggle('active');
                    localStorage.setItem('kv-high-contrast', document.body.classList.contains('high-contrast'));
                });
            }
            
            // Toggle enhanced focus
            if (toggleFocusOutline) {
                toggleFocusOutline.addEventListener('click', function() {
                    document.body.classList.toggle('enhanced-focus');
                    this.classList.toggle('active');
                    localStorage.setItem('kv-enhanced-focus', document.body.classList.contains('enhanced-focus'));
                });
            }
            
            // Reset all accessibility settings
            if (resetAccessibility) {
                resetAccessibility.addEventListener('click', function() {
                    // Remove all classes
                    document.body.classList.remove('no-animations', 'high-contrast', 'enhanced-focus');
                    
                    // Reset font size
                    document.documentElement.style.fontSize = '100%';
                    var fontSizeSlider = document.getElementById('font-size-slider');
                    var fontSizeValue = document.getElementById('font-size-value');
                    if (fontSizeSlider) fontSizeSlider.value = '100';
                    if (fontSizeValue) fontSizeValue.textContent = '100%';
                    
                    // Clear local storage
                    localStorage.removeItem('kv-no-animations');
                    localStorage.removeItem('kv-high-contrast');
                    localStorage.removeItem('kv-enhanced-focus');
                    localStorage.removeItem('kv-font-size');
                    
                    // Update button states
                    if (toggleAnimations) toggleAnimations.classList.remove('active');
                    if (toggleHighContrast) toggleHighContrast.classList.remove('active');
                    if (toggleFocusOutline) toggleFocusOutline.classList.remove('active');
                });
            }
            
            // Accessibility toolbar toggle
            var accessibilityToggle = document.querySelector('.accessibility-toggle');
            var accessibilityOptions = document.getElementById('accessibility-options');
            
            if (accessibilityToggle && accessibilityOptions) {
                accessibilityToggle.addEventListener('click', function() {
                    var isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    
                    if (isExpanded) {
                        accessibilityOptions.hidden = true;
                    } else {
                        accessibilityOptions.hidden = false;
                    }
                });
                
                // Close on escape or click outside
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !accessibilityOptions.hidden) {
                        accessibilityOptions.hidden = true;
                        accessibilityToggle.setAttribute('aria-expanded', 'false');
                        accessibilityToggle.focus();
                    }
                });
                
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.accessibility-toolbar') && !accessibilityOptions.hidden) {
                        accessibilityOptions.hidden = true;
                        accessibilityToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });
        </script>
        <?php
    }
}

// Initialize accessibility features
add_action('init', ['KV_Accessibility_Manager', 'init']);
