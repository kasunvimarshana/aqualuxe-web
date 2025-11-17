<?php
/**
 * Dark Mode Support for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Check if dark mode is enabled
 */
function aqualuxe_is_dark_mode_enabled() {
    return get_theme_mod( 'aqualuxe_enable_dark_mode', true );
}

/**
 * Get default color mode
 */
function aqualuxe_get_default_mode() {
    return get_theme_mod( 'aqualuxe_default_mode', 'light' );
}

/**
 * Add dark mode toggle button to header
 */
function aqualuxe_dark_mode_toggle() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle flex items-center justify-center w-10 h-10 rounded-full text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-300" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sun-icon hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 moon-icon block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
    <?php
}

/**
 * Add dark mode script to footer
 */
function aqualuxe_dark_mode_script() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    $default_mode = aqualuxe_get_default_mode();
    
    ?>
    <script>
        (function() {
            // Dark mode functionality
            function setDarkMode(isDark) {
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    document.cookie = 'aqualuxe_dark_mode=true; path=/; max-age=31536000'; // 1 year
                } else {
                    document.documentElement.classList.remove('dark');
                    document.cookie = 'aqualuxe_dark_mode=false; path=/; max-age=31536000'; // 1 year
                }
            }
            
            // Check for saved user preference, first in localStorage, then cookies
            const darkModeSaved = localStorage.getItem('darkMode');
            const darkModeCookie = document.cookie.split('; ').find(row => row.startsWith('aqualuxe_dark_mode='));
            
            // Check if dark mode preference exists
            if (darkModeSaved !== null) {
                setDarkMode(darkModeSaved === 'true');
            } else if (darkModeCookie) {
                setDarkMode(darkModeCookie.split('=')[1] === 'true');
            } else {
                // If no preference found, check default setting
                const defaultMode = '<?php echo esc_js( $default_mode ); ?>';
                
                if (defaultMode === 'dark') {
                    setDarkMode(true);
                } else if (defaultMode === 'light') {
                    setDarkMode(false);
                } else if (defaultMode === 'system') {
                    // Check system preference
                    const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    setDarkMode(prefersDarkMode);
                    
                    // Listen for changes in system preference
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                        setDarkMode(e.matches);
                    });
                }
            }
            
            // Toggle button functionality
            document.addEventListener('DOMContentLoaded', () => {
                const darkModeToggle = document.getElementById('dark-mode-toggle');
                
                if (darkModeToggle) {
                    darkModeToggle.addEventListener('click', () => {
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        setDarkMode(!isDarkMode);
                        
                        // Save preference to localStorage as well
                        localStorage.setItem('darkMode', !isDarkMode);
                        
                        // Send AJAX request to save preference in user meta if logged in
                        if (typeof aqualuxeData !== 'undefined') {
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', aqualuxeData.ajaxUrl, true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.send(
                                'action=aqualuxe_dark_mode_toggle' +
                                '&nonce=' + aqualuxeData.nonce +
                                '&mode=' + (!isDarkMode ? 'dark' : 'light')
                            );
                        }
                    });
                }
            });
        })();
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_dark_mode_script' );

/**
 * Add dark mode class to body
 */
function aqualuxe_dark_mode_body_class( $classes ) {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return $classes;
    }
    
    // Check cookie for dark mode preference
    if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) && 'true' === $_COOKIE['aqualuxe_dark_mode'] ) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_dark_mode_body_class' );

/**
 * Add dark mode CSS variables
 */
function aqualuxe_dark_mode_css() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    $dark_background = get_theme_mod( 'aqualuxe_dark_background', '#121212' );
    $dark_text_color = get_theme_mod( 'aqualuxe_dark_text_color', '#f5f5f5' );
    
    ?>
    <style>
        :root {
            --dark-background: <?php echo esc_attr( $dark_background ); ?>;
            --dark-text-color: <?php echo esc_attr( $dark_text_color ); ?>;
        }
        
        .dark {
            color-scheme: dark;
        }
        
        /* Base dark mode styles */
        .dark body {
            background-color: var(--dark-background);
            color: var(--dark-text-color);
        }
        
        /* Dark mode transitions */
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_dark_mode_css' );

/**
 * Add dark mode toggle to mobile menu
 */
function aqualuxe_mobile_dark_mode_toggle() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    ?>
    <div class="mobile-dark-mode-toggle py-2 mt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Appearance', 'aqualuxe' ); ?></div>
        <button id="mobile-dark-mode-toggle" class="flex items-center w-full px-4 py-2 text-base text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sun-icon hidden dark:block mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 moon-icon block dark:hidden mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <span class="light-text block dark:hidden"><?php esc_html_e( 'Dark Mode', 'aqualuxe' ); ?></span>
            <span class="dark-text hidden dark:block"><?php esc_html_e( 'Light Mode', 'aqualuxe' ); ?></span>
        </button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileDarkModeToggle = document.getElementById('mobile-dark-mode-toggle');
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            
            if (mobileDarkModeToggle && darkModeToggle) {
                mobileDarkModeToggle.addEventListener('click', () => {
                    // Trigger the main dark mode toggle button click
                    darkModeToggle.click();
                });
            }
        });
    </script>
    <?php
}

/**
 * Add dark mode admin styles
 */
function aqualuxe_dark_mode_admin_styles() {
    $screen = get_current_screen();
    
    // Only add on Customizer screen
    if ( ! $screen || 'customize' !== $screen->id ) {
        return;
    }
    
    ?>
    <style>
        /* Dark Mode Customizer Preview */
        .dark-mode-preview {
            background-color: #121212;
            color: #f5f5f5;
            padding: 20px;
            border-radius: 4px;
            margin-top: 10px;
        }
        
        .dark-mode-preview-header {
            background-color: #1e1e1e;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        
        .dark-mode-preview-content {
            background-color: #2d2d2d;
            padding: 10px;
            border-radius: 4px;
        }
        
        .dark-mode-preview-footer {
            background-color: #1e1e1e;
            padding: 10px;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'aqualuxe_dark_mode_admin_styles' );

/**
 * Add dark mode customizer preview
 */
function aqualuxe_dark_mode_customizer_preview() {
    ?>
    <script>
        (function($) {
            wp.customize('aqualuxe_dark_background', function(value) {
                value.bind(function(newval) {
                    $('.dark-mode-preview, .dark-mode-preview-header, .dark-mode-preview-content, .dark-mode-preview-footer').css('background-color', newval);
                });
            });
            
            wp.customize('aqualuxe_dark_text_color', function(value) {
                value.bind(function(newval) {
                    $('.dark-mode-preview, .dark-mode-preview-header, .dark-mode-preview-content, .dark-mode-preview-footer').css('color', newval);
                });
            });
        })(jQuery);
    </script>
    <?php
}
add_action( 'customize_preview_init', 'aqualuxe_dark_mode_customizer_preview' );

/**
 * Add dark mode toggle to admin bar
 */
function aqualuxe_admin_bar_dark_mode_toggle( $wp_admin_bar ) {
    if ( ! aqualuxe_is_dark_mode_enabled() || ! is_admin_bar_showing() ) {
        return;
    }
    
    $wp_admin_bar->add_node(
        array(
            'id'    => 'aqualuxe-dark-mode',
            'title' => '<span class="ab-icon dashicons dashicons-admin-appearance"></span><span class="ab-label">' . esc_html__( 'Toggle Dark Mode', 'aqualuxe' ) . '</span>',
            'href'  => '#',
            'meta'  => array(
                'class' => 'aqualuxe-admin-bar-dark-mode',
                'title' => esc_attr__( 'Toggle Dark Mode', 'aqualuxe' ),
            ),
        )
    );
    
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const adminBarToggle = document.querySelector('.aqualuxe-admin-bar-dark-mode');
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            
            if (adminBarToggle && darkModeToggle) {
                adminBarToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    darkModeToggle.click();
                });
            }
        });
    </script>
    <?php
}
add_action( 'admin_bar_menu', 'aqualuxe_admin_bar_dark_mode_toggle', 100 );

/**
 * Add dark mode meta box to user profile
 */
function aqualuxe_dark_mode_user_profile( $user ) {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    $user_dark_mode = get_user_meta( $user->ID, 'aqualuxe_dark_mode', true );
    
    if ( empty( $user_dark_mode ) ) {
        $user_dark_mode = 'default';
    }
    
    ?>
    <h2><?php esc_html_e( 'Display Settings', 'aqualuxe' ); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_dark_mode"><?php esc_html_e( 'Color Scheme', 'aqualuxe' ); ?></label></th>
            <td>
                <select name="aqualuxe_dark_mode" id="aqualuxe_dark_mode">
                    <option value="default" <?php selected( $user_dark_mode, 'default' ); ?>><?php esc_html_e( 'Site Default', 'aqualuxe' ); ?></option>
                    <option value="light" <?php selected( $user_dark_mode, 'light' ); ?>><?php esc_html_e( 'Light', 'aqualuxe' ); ?></option>
                    <option value="dark" <?php selected( $user_dark_mode, 'dark' ); ?>><?php esc_html_e( 'Dark', 'aqualuxe' ); ?></option>
                </select>
                <p class="description"><?php esc_html_e( 'Select your preferred color scheme for this site.', 'aqualuxe' ); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action( 'show_user_profile', 'aqualuxe_dark_mode_user_profile' );
add_action( 'edit_user_profile', 'aqualuxe_dark_mode_user_profile' );

/**
 * Save dark mode user profile setting
 */
function aqualuxe_save_dark_mode_user_profile( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    
    if ( isset( $_POST['aqualuxe_dark_mode'] ) ) {
        update_user_meta( $user_id, 'aqualuxe_dark_mode', sanitize_text_field( wp_unslash( $_POST['aqualuxe_dark_mode'] ) ) );
    }
}
add_action( 'personal_options_update', 'aqualuxe_save_dark_mode_user_profile' );
add_action( 'edit_user_profile_update', 'aqualuxe_save_dark_mode_user_profile' );

/**
 * Apply user dark mode preference
 */
function aqualuxe_apply_user_dark_mode() {
    if ( ! aqualuxe_is_dark_mode_enabled() || ! is_user_logged_in() ) {
        return;
    }
    
    $user_id = get_current_user_id();
    $user_dark_mode = get_user_meta( $user_id, 'aqualuxe_dark_mode', true );
    
    if ( empty( $user_dark_mode ) || 'default' === $user_dark_mode ) {
        return;
    }
    
    $is_dark = 'dark' === $user_dark_mode;
    
    ?>
    <script>
        (function() {
            // Apply user preference if no manual override exists
            if (!localStorage.getItem('darkMode') && !document.cookie.split('; ').find(row => row.startsWith('aqualuxe_dark_mode='))) {
                if (<?php echo $is_dark ? 'true' : 'false'; ?>) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        })();
    </script>
    <?php
}
add_action( 'wp_head', 'aqualuxe_apply_user_dark_mode', 0 );

/**
 * Add dark mode toggle keyboard shortcut
 */
function aqualuxe_dark_mode_keyboard_shortcut() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    ?>
    <script>
        document.addEventListener('keydown', function(e) {
            // Alt+Shift+D to toggle dark mode
            if (e.altKey && e.shiftKey && e.key === 'D') {
                const darkModeToggle = document.getElementById('dark-mode-toggle');
                if (darkModeToggle) {
                    darkModeToggle.click();
                }
            }
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_dark_mode_keyboard_shortcut' );

/**
 * Add dark mode toggle to floating button
 */
function aqualuxe_floating_dark_mode_toggle() {
    if ( ! aqualuxe_is_dark_mode_enabled() ) {
        return;
    }
    
    // Check if floating toggle is enabled
    $show_floating_toggle = get_theme_mod( 'aqualuxe_show_floating_dark_mode_toggle', false );
    
    if ( ! $show_floating_toggle ) {
        return;
    }
    
    ?>
    <button id="floating-dark-mode-toggle" class="fixed bottom-20 right-4 z-50 flex items-center justify-center w-12 h-12 rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 shadow-lg hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-300" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sun-icon hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 moon-icon block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const floatingDarkModeToggle = document.getElementById('floating-dark-mode-toggle');
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            
            if (floatingDarkModeToggle && darkModeToggle) {
                floatingDarkModeToggle.addEventListener('click', () => {
                    // Trigger the main dark mode toggle button click
                    darkModeToggle.click();
                });
            }
        });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_floating_dark_mode_toggle' );

/**
 * Add dark mode toggle to customizer
 */
function aqualuxe_customizer_dark_mode_toggle( $wp_customize ) {
    // Add Dark Mode section
    $wp_customize->add_section(
        'aqualuxe_dark_mode_section',
        array(
            'title'       => __( 'Dark Mode', 'aqualuxe' ),
            'description' => __( 'Configure dark mode settings.', 'aqualuxe' ),
            'priority'    => 30,
            'panel'       => 'aqualuxe_theme_options',
        )
    );
    
    // Enable Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_enable_dark_mode',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'aqualuxe_enable_dark_mode',
        array(
            'label'       => __( 'Enable Dark Mode', 'aqualuxe' ),
            'description' => __( 'Allow users to switch between light and dark mode.', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_section',
            'type'        => 'checkbox',
        )
    );
    
    // Default Mode
    $wp_customize->add_setting(
        'aqualuxe_default_mode',
        array(
            'default'           => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );
    
    $wp_customize->add_control(
        'aqualuxe_default_mode',
        array(
            'label'       => __( 'Default Mode', 'aqualuxe' ),
            'description' => __( 'Choose the default color mode.', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_section',
            'type'        => 'select',
            'choices'     => array(
                'light'  => __( 'Light', 'aqualuxe' ),
                'dark'   => __( 'Dark', 'aqualuxe' ),
                'system' => __( 'System Preference', 'aqualuxe' ),
            ),
        )
    );
    
    // Dark Mode Background
    $wp_customize->add_setting(
        'aqualuxe_dark_background',
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_background',
            array(
                'label'       => __( 'Dark Mode Background', 'aqualuxe' ),
                'description' => __( 'Choose the dark mode background color.', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_section',
            )
        )
    );
    
    // Dark Mode Text Color
    $wp_customize->add_setting(
        'aqualuxe_dark_text_color',
        array(
            'default'           => '#f5f5f5',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_dark_text_color',
            array(
                'label'       => __( 'Dark Mode Text Color', 'aqualuxe' ),
                'description' => __( 'Choose the dark mode text color.', 'aqualuxe' ),
                'section'     => 'aqualuxe_dark_mode_section',
            )
        )
    );
    
    // Show Floating Toggle
    $wp_customize->add_setting(
        'aqualuxe_show_floating_dark_mode_toggle',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        'aqualuxe_show_floating_dark_mode_toggle',
        array(
            'label'       => __( 'Show Floating Toggle', 'aqualuxe' ),
            'description' => __( 'Display a floating dark mode toggle button.', 'aqualuxe' ),
            'section'     => 'aqualuxe_dark_mode_section',
            'type'        => 'checkbox',
        )
    );
    
    // Dark Mode Preview
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_preview',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'aqualuxe_dark_mode_preview',
            array(
                'label'       => __( 'Dark Mode Preview', 'aqualuxe' ),
                'description' => '<div class="dark-mode-preview">
                                    <div class="dark-mode-preview-header">Header</div>
                                    <div class="dark-mode-preview-content">Content</div>
                                    <div class="dark-mode-preview-footer">Footer</div>
                                </div>',
                'section'     => 'aqualuxe_dark_mode_section',
                'type'        => 'hidden',
            )
        )
    );
}
add_action( 'customize_register', 'aqualuxe_customizer_dark_mode_toggle' );

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}