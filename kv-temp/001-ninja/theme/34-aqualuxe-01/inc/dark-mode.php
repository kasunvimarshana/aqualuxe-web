<?php
/**
 * Dark Mode functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Enqueue dark mode scripts
 */
function aqualuxe_dark_mode_scripts() {
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        AQUALUXE_ASSETS_URI . 'js/dark-mode.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );

    // Localize script for JavaScript variables
    wp_localize_script(
        'aqualuxe-dark-mode',
        'aqualuxeDarkMode',
        array(
            'cookieName' => 'aqualuxe_dark_mode',
            'cookieExpiry' => 30, // Days
            'toggleSelector' => '#dark-mode-toggle',
            'darkModeClass' => 'dark-mode',
        )
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts');

/**
 * Add dark mode toggle to header
 */
function aqualuxe_dark_mode_toggle() {
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
        <span class="dark-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </span>
        <span class="light-icon hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </span>
    </button>
    <?php
}

/**
 * Add dark mode class to body if enabled
 *
 * @param array $classes Array of body classes.
 * @return array Modified array of body classes.
 */
function aqualuxe_dark_mode_body_class($classes) {
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_dark_mode_body_class');

/**
 * Add dark mode prefers-color-scheme meta tag
 */
function aqualuxe_dark_mode_meta_tag() {
    ?>
    <meta name="color-scheme" content="light dark">
    <?php
}
add_action('wp_head', 'aqualuxe_dark_mode_meta_tag');

/**
 * Add dark mode styles
 */
function aqualuxe_dark_mode_styles() {
    $dark_mode_bg = get_theme_mod('aqualuxe_dark_mode_bg', '#121212');
    $dark_mode_text = get_theme_mod('aqualuxe_dark_mode_text', '#f5f5f5');
    
    $css = "
        /* Dark Mode Styles */
        :root {
            --dark-mode-bg: {$dark_mode_bg};
            --dark-mode-text: {$dark_mode_text};
            --dark-mode-bg-secondary: #1e1e1e;
            --dark-mode-border: #2c2c2c;
            --dark-mode-input-bg: #252525;
            --dark-mode-input-border: #3a3a3a;
        }
        
        @media (prefers-color-scheme: dark) {
            :root {
                --dark-mode-auto: true;
            }
        }
        
        .dark-mode {
            background-color: var(--dark-mode-bg);
            color: var(--dark-mode-text);
        }
        
        .dark-mode .site-header,
        .dark-mode .site-footer,
        .dark-mode .bg-white {
            background-color: var(--dark-mode-bg-secondary);
        }
        
        .dark-mode .text-gray-600 {
            color: #b0b0b0;
        }
        
        .dark-mode .text-gray-900 {
            color: var(--dark-mode-text);
        }
        
        .dark-mode .border,
        .dark-mode .border-gray-200,
        .dark-mode .border-gray-300 {
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode .bg-gray-50,
        .dark-mode .bg-gray-100 {
            background-color: #252525;
        }
        
        .dark-mode input,
        .dark-mode textarea,
        .dark-mode select {
            background-color: var(--dark-mode-input-bg);
            border-color: var(--dark-mode-input-border);
            color: var(--dark-mode-text);
        }
        
        .dark-mode .card,
        .dark-mode .shadow,
        .dark-mode .shadow-sm,
        .dark-mode .shadow-md {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.4), 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        }
        
        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .dark-mode-toggle:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .dark-mode .dark-mode-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .dark-mode .dark-icon {
            display: none;
        }
        
        .dark-mode .light-icon {
            display: block;
        }
        
        /* WooCommerce Dark Mode */
        .dark-mode.woocommerce-page .woocommerce-breadcrumb {
            color: #b0b0b0;
        }
        
        .dark-mode.woocommerce-page .woocommerce-breadcrumb a {
            color: var(--primary-color);
        }
        
        .dark-mode.woocommerce-page .woocommerce-ordering select {
            background-color: var(--dark-mode-input-bg);
            border-color: var(--dark-mode-input-border);
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page .woocommerce-result-count {
            color: #b0b0b0;
        }
        
        .dark-mode.woocommerce-page .price {
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page .star-rating {
            color: #f8e825;
        }
        
        .dark-mode.woocommerce-page .woocommerce-tabs .panel {
            background-color: var(--dark-mode-bg-secondary);
        }
        
        .dark-mode.woocommerce-page .woocommerce-tabs ul.tabs li {
            background-color: var(--dark-mode-input-bg);
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page .woocommerce-tabs ul.tabs li.active {
            background-color: var(--dark-mode-bg-secondary);
            border-bottom-color: var(--dark-mode-bg-secondary);
        }
        
        .dark-mode.woocommerce-page .woocommerce-tabs ul.tabs li a {
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page .woocommerce-message,
        .dark-mode.woocommerce-page .woocommerce-info,
        .dark-mode.woocommerce-page .woocommerce-error {
            background-color: var(--dark-mode-bg-secondary);
            color: var(--dark-mode-text);
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page .woocommerce-message::before,
        .dark-mode.woocommerce-page .woocommerce-info::before {
            color: var(--primary-color);
        }
        
        .dark-mode.woocommerce-page .woocommerce-error::before {
            color: #e74c3c;
        }
        
        .dark-mode.woocommerce-page table.shop_table {
            background-color: var(--dark-mode-bg-secondary);
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page table.shop_table th,
        .dark-mode.woocommerce-page table.shop_table td {
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page #payment {
            background-color: var(--dark-mode-bg-secondary);
            border-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page #payment div.payment_box {
            background-color: var(--dark-mode-input-bg);
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page #payment div.payment_box::before {
            border-bottom-color: var(--dark-mode-input-bg);
        }
        
        .dark-mode.woocommerce-page .woocommerce-checkout-review-order-table {
            background-color: var(--dark-mode-bg-secondary);
        }
        
        .dark-mode.woocommerce-page .select2-container--default .select2-selection--single {
            background-color: var(--dark-mode-input-bg);
            border-color: var(--dark-mode-input-border);
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--dark-mode-text);
        }
        
        .dark-mode.woocommerce-page .select2-dropdown {
            background-color: var(--dark-mode-input-bg);
            border-color: var(--dark-mode-input-border);
        }
        
        .dark-mode.woocommerce-page .select2-container--default .select2-results__option[data-selected=true] {
            background-color: var(--dark-mode-border);
        }
        
        .dark-mode.woocommerce-page .select2-container--default .select2-results__option--highlighted[data-selected] {
            background-color: var(--primary-color);
        }
    ";
    
    echo '<style id="aqualuxe-dark-mode-styles">' . $css . '</style>';
}
add_action('wp_head', 'aqualuxe_dark_mode_styles');

/**
 * Add dark mode toggle script
 */
function aqualuxe_dark_mode_inline_script() {
    ?>
    <script>
    // Initialize dark mode on page load before DOM is ready to prevent flash
    (function() {
        var darkMode = localStorage.getItem('aqualuxe_dark_mode') === 'true' || 
                      (localStorage.getItem('aqualuxe_dark_mode') === null && 
                       window.matchMedia('(prefers-color-scheme: dark)').matches);
        
        if (darkMode) {
            document.documentElement.classList.add('dark-mode');
            document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=' + (30 * 24 * 60 * 60);
        } else {
            document.documentElement.classList.remove('dark-mode');
            document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=' + (30 * 24 * 60 * 60);
        }
    })();
    </script>
    <?php
}
add_action('wp_head', 'aqualuxe_dark_mode_inline_script', 0);

/**
 * Add dark mode toggle to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_dark_mode_customizer($wp_customize) {
    // Add Dark Mode Section
    $wp_customize->add_section(
        'aqualuxe_dark_mode_section',
        array(
            'title'       => __('Dark Mode', 'aqualuxe'),
            'description' => __('Customize dark mode settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 35,
        )
    );

    // Add Dark Mode Toggle Option
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_toggle',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_toggle',
        array(
            'label'       => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'description' => __('Show dark mode toggle button in header', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode_section',
            'type'        => 'checkbox',
        )
    );

    // Add Auto Dark Mode Option
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_auto',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_dark_mode_auto',
        array(
            'label'       => __('Enable Auto Dark Mode', 'aqualuxe'),
            'description' => __('Automatically switch to dark mode based on user system preferences', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode_section',
            'type'        => 'checkbox',
        )
    );

    // Add Dark Mode Background Color
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_bg',
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_bg',
            array(
                'label'       => __('Dark Mode Background', 'aqualuxe'),
                'description' => __('Background color for dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_dark_mode_section',
            )
        )
    );

    // Add Dark Mode Text Color
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_text',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_mode_text',
            array(
                'label'       => __('Dark Mode Text', 'aqualuxe'),
                'description' => __('Text color for dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_dark_mode_section',
            )
        )
    );
}
add_action('customize_register', 'aqualuxe_dark_mode_customizer');

/**
 * Add dark mode toggle to admin bar
 */
function aqualuxe_dark_mode_admin_bar($wp_admin_bar) {
    if (!is_admin() && is_admin_bar_showing()) {
        $wp_admin_bar->add_node(
            array(
                'id'    => 'aqualuxe-dark-mode',
                'title' => '<span class="ab-icon dashicons dashicons-admin-appearance"></span>' . __('Toggle Dark Mode', 'aqualuxe'),
                'href'  => '#',
                'meta'  => array(
                    'class' => 'aqualuxe-dark-mode-toggle',
                    'title' => __('Toggle Dark Mode', 'aqualuxe'),
                ),
            )
        );
    }
}
add_action('admin_bar_menu', 'aqualuxe_dark_mode_admin_bar', 100);

/**
 * Add dark mode admin bar script
 */
function aqualuxe_dark_mode_admin_bar_script() {
    if (!is_admin() && is_admin_bar_showing()) {
        ?>
        <script>
        (function($) {
            $(document).ready(function() {
                $('#wp-admin-bar-aqualuxe-dark-mode').on('click', function(e) {
                    e.preventDefault();
                    
                    var isDarkMode = $('body').hasClass('dark-mode');
                    
                    if (isDarkMode) {
                        $('body').removeClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'false');
                        document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=' + (30 * 24 * 60 * 60);
                    } else {
                        $('body').addClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'true');
                        document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=' + (30 * 24 * 60 * 60);
                    }
                    
                    // Update toggle button state
                    if ($('#dark-mode-toggle').length) {
                        if (isDarkMode) {
                            $('#dark-mode-toggle').find('.dark-icon').removeClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').addClass('hidden');
                        } else {
                            $('#dark-mode-toggle').find('.dark-icon').addClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').removeClass('hidden');
                        }
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_dark_mode_admin_bar_script');

/**
 * Add dark mode toggle to footer
 */
function aqualuxe_dark_mode_footer_toggle() {
    ?>
    <div class="dark-mode-footer-toggle fixed bottom-4 right-4 z-50">
        <button id="dark-mode-footer-toggle" class="dark-mode-toggle bg-white dark:bg-gray-800 shadow-lg rounded-full p-2" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <span class="dark-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </span>
            <span class="light-icon hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </span>
        </button>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Initialize footer toggle button
            var $footerToggle = $('#dark-mode-footer-toggle');
            
            if ($footerToggle.length) {
                // Update initial state
                if ($('body').hasClass('dark-mode')) {
                    $footerToggle.find('.dark-icon').addClass('hidden');
                    $footerToggle.find('.light-icon').removeClass('hidden');
                }
                
                // Handle click event
                $footerToggle.on('click', function() {
                    var isDarkMode = $('body').hasClass('dark-mode');
                    
                    if (isDarkMode) {
                        $('body').removeClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'false');
                        document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=' + (30 * 24 * 60 * 60);
                        
                        $footerToggle.find('.dark-icon').removeClass('hidden');
                        $footerToggle.find('.light-icon').addClass('hidden');
                        
                        // Update header toggle if exists
                        if ($('#dark-mode-toggle').length) {
                            $('#dark-mode-toggle').find('.dark-icon').removeClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').addClass('hidden');
                        }
                    } else {
                        $('body').addClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'true');
                        document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=' + (30 * 24 * 60 * 60);
                        
                        $footerToggle.find('.dark-icon').addClass('hidden');
                        $footerToggle.find('.light-icon').removeClass('hidden');
                        
                        // Update header toggle if exists
                        if ($('#dark-mode-toggle').length) {
                            $('#dark-mode-toggle').find('.dark-icon').addClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').removeClass('hidden');
                        }
                    }
                });
            }
        });
    })(jQuery);
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_dark_mode_footer_toggle');

/**
 * Add dark mode toggle to mobile menu
 */
function aqualuxe_dark_mode_mobile_toggle() {
    ?>
    <div class="dark-mode-mobile-toggle py-2 border-t border-gray-200 dark:border-gray-700 mt-4">
        <button id="dark-mode-mobile-toggle" class="flex items-center w-full px-4 py-2 text-left" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <span class="dark-icon mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </span>
            <span class="light-icon mr-2 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </span>
            <span class="dark-mode-text"><?php esc_html_e('Dark Mode', 'aqualuxe'); ?></span>
            <span class="light-mode-text hidden"><?php esc_html_e('Light Mode', 'aqualuxe'); ?></span>
        </button>
    </div>
    
    <script>
    (function($) {
        $(document).ready(function() {
            // Initialize mobile toggle button
            var $mobileToggle = $('#dark-mode-mobile-toggle');
            
            if ($mobileToggle.length) {
                // Update initial state
                if ($('body').hasClass('dark-mode')) {
                    $mobileToggle.find('.dark-icon').addClass('hidden');
                    $mobileToggle.find('.light-icon').removeClass('hidden');
                    $mobileToggle.find('.dark-mode-text').addClass('hidden');
                    $mobileToggle.find('.light-mode-text').removeClass('hidden');
                }
                
                // Handle click event
                $mobileToggle.on('click', function() {
                    var isDarkMode = $('body').hasClass('dark-mode');
                    
                    if (isDarkMode) {
                        $('body').removeClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'false');
                        document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=' + (30 * 24 * 60 * 60);
                        
                        $mobileToggle.find('.dark-icon').removeClass('hidden');
                        $mobileToggle.find('.light-icon').addClass('hidden');
                        $mobileToggle.find('.dark-mode-text').removeClass('hidden');
                        $mobileToggle.find('.light-mode-text').addClass('hidden');
                        
                        // Update other toggles if they exist
                        updateOtherToggles(false);
                    } else {
                        $('body').addClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'true');
                        document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=' + (30 * 24 * 60 * 60);
                        
                        $mobileToggle.find('.dark-icon').addClass('hidden');
                        $mobileToggle.find('.light-icon').removeClass('hidden');
                        $mobileToggle.find('.dark-mode-text').addClass('hidden');
                        $mobileToggle.find('.light-mode-text').removeClass('hidden');
                        
                        // Update other toggles if they exist
                        updateOtherToggles(true);
                    }
                });
                
                // Helper function to update other toggles
                function updateOtherToggles(isDarkMode) {
                    // Update header toggle
                    if ($('#dark-mode-toggle').length) {
                        if (isDarkMode) {
                            $('#dark-mode-toggle').find('.dark-icon').addClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').removeClass('hidden');
                        } else {
                            $('#dark-mode-toggle').find('.dark-icon').removeClass('hidden');
                            $('#dark-mode-toggle').find('.light-icon').addClass('hidden');
                        }
                    }
                    
                    // Update footer toggle
                    if ($('#dark-mode-footer-toggle').length) {
                        if (isDarkMode) {
                            $('#dark-mode-footer-toggle').find('.dark-icon').addClass('hidden');
                            $('#dark-mode-footer-toggle').find('.light-icon').removeClass('hidden');
                        } else {
                            $('#dark-mode-footer-toggle').find('.dark-icon').removeClass('hidden');
                            $('#dark-mode-footer-toggle').find('.light-icon').addClass('hidden');
                        }
                    }
                }
            }
        });
    })(jQuery);
    </script>
    <?php
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_dark_mode_mobile_toggle');

/**
 * Add dark mode toggle to user profile
 */
function aqualuxe_dark_mode_user_profile($user) {
    ?>
    <h2><?php esc_html_e('Display Preferences', 'aqualuxe'); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_dark_mode"><?php esc_html_e('Dark Mode', 'aqualuxe'); ?></label></th>
            <td>
                <label for="aqualuxe_dark_mode">
                    <input type="checkbox" name="aqualuxe_dark_mode" id="aqualuxe_dark_mode" value="1" <?php checked(get_user_meta($user->ID, 'aqualuxe_dark_mode', true), '1'); ?> />
                    <?php esc_html_e('Enable dark mode by default', 'aqualuxe'); ?>
                </label>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'aqualuxe_dark_mode_user_profile');
add_action('edit_user_profile', 'aqualuxe_dark_mode_user_profile');

/**
 * Save dark mode user profile field
 */
function aqualuxe_dark_mode_save_user_profile($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    update_user_meta($user_id, 'aqualuxe_dark_mode', isset($_POST['aqualuxe_dark_mode']) ? '1' : '0');
}
add_action('personal_options_update', 'aqualuxe_dark_mode_save_user_profile');
add_action('edit_user_profile_update', 'aqualuxe_dark_mode_save_user_profile');

/**
 * Set dark mode cookie for logged-in users
 */
function aqualuxe_dark_mode_user_cookie() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $dark_mode = get_user_meta($user_id, 'aqualuxe_dark_mode', true);
        
        if ($dark_mode === '1' && !isset($_COOKIE['aqualuxe_dark_mode'])) {
            ?>
            <script>
            document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=<?php echo 30 * 24 * 60 * 60; ?>';
            if (!document.body.classList.contains('dark-mode')) {
                document.body.classList.add('dark-mode');
            }
            </script>
            <?php
        }
    }
}
add_action('wp_footer', 'aqualuxe_dark_mode_user_cookie');

/**
 * Add dark mode toggle keyboard shortcut
 */
function aqualuxe_dark_mode_keyboard_shortcut() {
    ?>
    <script>
    (function($) {
        $(document).ready(function() {
            // Add keyboard shortcut (Shift + D) for dark mode toggle
            $(document).on('keydown', function(e) {
                // Check if Shift + D is pressed
                if (e.shiftKey && e.which === 68) {
                    // Prevent default action
                    e.preventDefault();
                    
                    // Toggle dark mode
                    var isDarkMode = $('body').hasClass('dark-mode');
                    
                    if (isDarkMode) {
                        $('body').removeClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'false');
                        document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=' + (30 * 24 * 60 * 60);
                    } else {
                        $('body').addClass('dark-mode');
                        localStorage.setItem('aqualuxe_dark_mode', 'true');
                        document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=' + (30 * 24 * 60 * 60);
                    }
                    
                    // Update all toggle buttons
                    updateAllToggles(!isDarkMode);
                }
            });
            
            // Helper function to update all toggle buttons
            function updateAllToggles(isDarkMode) {
                // Update header toggle
                if ($('#dark-mode-toggle').length) {
                    if (isDarkMode) {
                        $('#dark-mode-toggle').find('.dark-icon').addClass('hidden');
                        $('#dark-mode-toggle').find('.light-icon').removeClass('hidden');
                    } else {
                        $('#dark-mode-toggle').find('.dark-icon').removeClass('hidden');
                        $('#dark-mode-toggle').find('.light-icon').addClass('hidden');
                    }
                }
                
                // Update footer toggle
                if ($('#dark-mode-footer-toggle').length) {
                    if (isDarkMode) {
                        $('#dark-mode-footer-toggle').find('.dark-icon').addClass('hidden');
                        $('#dark-mode-footer-toggle').find('.light-icon').removeClass('hidden');
                    } else {
                        $('#dark-mode-footer-toggle').find('.dark-icon').removeClass('hidden');
                        $('#dark-mode-footer-toggle').find('.light-icon').addClass('hidden');
                    }
                }
                
                // Update mobile toggle
                if ($('#dark-mode-mobile-toggle').length) {
                    if (isDarkMode) {
                        $('#dark-mode-mobile-toggle').find('.dark-icon').addClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.light-icon').removeClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.dark-mode-text').addClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.light-mode-text').removeClass('hidden');
                    } else {
                        $('#dark-mode-mobile-toggle').find('.dark-icon').removeClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.light-icon').addClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.dark-mode-text').removeClass('hidden');
                        $('#dark-mode-mobile-toggle').find('.light-mode-text').addClass('hidden');
                    }
                }
            }
        });
    })(jQuery);
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_dark_mode_keyboard_shortcut');