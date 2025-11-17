<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize multilingual functionality
 */
function aqualuxe_multilingual_init() {
    // Only proceed if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }

    // Add language switcher to header
    add_action('aqualuxe_after_header', 'aqualuxe_language_switcher_display');
    
    // Add language switcher to footer
    add_action('aqualuxe_before_footer', 'aqualuxe_language_switcher_display_footer');
    
    // Add hreflang links to head
    add_action('wp_head', 'aqualuxe_add_hreflang_links');
    
    // Filter body classes to add language-specific class
    add_filter('body_class', 'aqualuxe_language_body_class');
}
add_action('init', 'aqualuxe_multilingual_init');

/**
 * Display language switcher in header
 */
function aqualuxe_language_switcher_display() {
    // Only show if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if (!aqualuxe_is_multilingual_plugin_active()) {
        return;
    }
    
    aqualuxe_before_language_switcher();
    ?>
    <div class="language-switcher-wrapper">
        <?php aqualuxe_language_switcher(); ?>
    </div>
    <?php
    aqualuxe_after_language_switcher();
}

/**
 * Display language switcher in footer
 */
function aqualuxe_language_switcher_display_footer() {
    // Only show if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if (!aqualuxe_is_multilingual_plugin_active()) {
        return;
    }
    
    aqualuxe_before_language_switcher();
    ?>
    <div class="language-switcher-wrapper footer-language-switcher">
        <?php aqualuxe_language_switcher(); ?>
    </div>
    <?php
    aqualuxe_after_language_switcher();
}

/**
 * Check if a multilingual plugin is active
 *
 * @return bool True if WPML or Polylang is active, false otherwise
 */
function aqualuxe_is_multilingual_plugin_active() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return true;
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return true;
    }
    
    return false;
}

/**
 * Get current language code
 *
 * @return string Current language code
 */
function aqualuxe_get_current_language() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return apply_filters('wpml_current_language', '');
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default to WordPress locale
    return get_locale();
}

/**
 * Get current language name
 *
 * @return string Current language name
 */
function aqualuxe_get_current_language_name() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        $languages = $sitepress->get_active_languages();
        $current_lang = apply_filters('wpml_current_language', '');
        
        if (isset($languages[$current_lang]['display_name'])) {
            return $languages[$current_lang]['display_name'];
        }
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language('name');
    }
    
    // Default to WordPress locale name
    $locale = get_locale();
    $languages = aqualuxe_get_available_languages();
    
    foreach ($languages as $language) {
        if ($language['code'] === $locale) {
            return $language['name'];
        }
    }
    
    return __('English', 'aqualuxe');
}

/**
 * Get available languages
 *
 * @return array Array of available languages
 */
function aqualuxe_get_available_languages() {
    $languages = array();
    
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        $wpml_languages = $sitepress->get_active_languages();
        
        foreach ($wpml_languages as $code => $language) {
            $languages[] = array(
                'code' => $code,
                'name' => $language['display_name'],
                'url' => apply_filters('wpml_permalink', home_url(), $code),
                'flag' => $language['flag_url'],
                'active' => $code === apply_filters('wpml_current_language', ''),
            );
        }
        
        return $languages;
    }
    
    // Check for Polylang
    if (function_exists('pll_languages_list')) {
        $pll_languages = pll_languages_list(array('fields' => ''));
        
        foreach ($pll_languages as $language) {
            $languages[] = array(
                'code' => $language->slug,
                'name' => $language->name,
                'url' => $language->home_url,
                'flag' => $language->flag_url,
                'active' => $language->slug === pll_current_language(),
            );
        }
        
        return $languages;
    }
    
    // Default to WordPress locales
    $wp_languages = get_available_languages();
    $current_locale = get_locale();
    
    // Always include English
    $languages[] = array(
        'code' => 'en_US',
        'name' => __('English (United States)', 'aqualuxe'),
        'url' => home_url(),
        'flag' => '',
        'active' => $current_locale === 'en_US',
    );
    
    foreach ($wp_languages as $locale) {
        if ($locale !== 'en_US') {
            $languages[] = array(
                'code' => $locale,
                'name' => $locale,
                'url' => home_url(),
                'flag' => '',
                'active' => $locale === $current_locale,
            );
        }
    }
    
    return $languages;
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    $languages = aqualuxe_get_available_languages();
    $current_language = aqualuxe_get_current_language();
    
    if (empty($languages) || count($languages) <= 1) {
        return;
    }
    
    // Get current language data
    $current_language_data = array();
    foreach ($languages as $language) {
        if ($language['active']) {
            $current_language_data = $language;
            break;
        }
    }
    
    if (empty($current_language_data)) {
        return;
    }
    ?>
    <div class="language-switcher">
        <button class="language-switcher-toggle" aria-expanded="false" aria-controls="language-switcher-dropdown">
            <?php if (!empty($current_language_data['flag'])) : ?>
                <img src="<?php echo esc_url($current_language_data['flag']); ?>" alt="<?php echo esc_attr($current_language_data['name']); ?>" width="16" height="11" class="language-flag">
            <?php endif; ?>
            <span class="language-name"><?php echo esc_html($current_language_data['name']); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="language-switcher-arrow" width="16" height="16">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        <div id="language-switcher-dropdown" class="language-switcher-dropdown" aria-hidden="true">
            <ul class="language-list">
                <?php foreach ($languages as $language) : ?>
                    <li class="language-item<?php echo $language['active'] ? ' active' : ''; ?>">
                        <a href="<?php echo esc_url($language['url']); ?>" class="language-link" <?php echo $language['active'] ? ' aria-current="true"' : ''; ?>>
                            <?php if (!empty($language['flag'])) : ?>
                                <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" width="16" height="11" class="language-flag">
                            <?php endif; ?>
                            <span class="language-name"><?php echo esc_html($language['name']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    // Only add if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if (!aqualuxe_is_multilingual_plugin_active()) {
        return;
    }
    
    // WPML already adds hreflang links
    if (defined('ICL_SITEPRESS_VERSION')) {
        return;
    }
    
    // Check for Polylang
    if (function_exists('pll_languages_list')) {
        $languages = pll_languages_list(array('fields' => ''));
        
        foreach ($languages as $language) {
            $url = pll_get_post(get_the_ID(), $language->slug);
            if ($url) {
                echo '<link rel="alternate" hreflang="' . esc_attr($language->slug) . '" href="' . esc_url(get_permalink($url)) . '" />' . "\n";
            } else {
                echo '<link rel="alternate" hreflang="' . esc_attr($language->slug) . '" href="' . esc_url($language->home_url) . '" />' . "\n";
            }
        }
        
        // Add x-default hreflang
        $default_lang = pll_default_language();
        $url = pll_get_post(get_the_ID(), $default_lang);
        if ($url) {
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(get_permalink($url)) . '" />' . "\n";
        } else {
            foreach ($languages as $language) {
                if ($language->slug === $default_lang) {
                    echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($language->home_url) . '" />' . "\n";
                    break;
                }
            }
        }
    }
}

/**
 * Add language-specific class to body
 *
 * @param array $classes Array of body classes
 * @return array Modified array of body classes
 */
function aqualuxe_language_body_class($classes) {
    // Only add if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return $classes;
    }
    
    $current_language = aqualuxe_get_current_language();
    if ($current_language) {
        $classes[] = 'lang-' . sanitize_html_class($current_language);
    }
    
    return $classes;
}

/**
 * Get translated string
 *
 * @param string $string String to translate
 * @param string $domain Text domain
 * @return string Translated string
 */
function aqualuxe_translate($string, $domain = 'aqualuxe') {
    return __($string, $domain);
}

/**
 * Get translated string with context
 *
 * @param string $string String to translate
 * @param string $context Context information for translators
 * @param string $domain Text domain
 * @return string Translated string
 */
function aqualuxe_translate_with_context($string, $context, $domain = 'aqualuxe') {
    return _x($string, $context, $domain);
}

/**
 * Register theme for translation
 */
function aqualuxe_load_theme_textdomain() {
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'aqualuxe_load_theme_textdomain');

/**
 * Add language switcher script to footer
 */
function aqualuxe_language_switcher_script() {
    // Only add if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if (!aqualuxe_is_multilingual_plugin_active()) {
        return;
    }
    ?>
    <script>
    (function() {
        // Language switcher functionality
        function aqualuxeLanguageSwitcher() {
            const toggles = document.querySelectorAll('.language-switcher-toggle');
            
            if (!toggles.length) {
                return;
            }
            
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const dropdown = this.nextElementSibling;
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    
                    this.setAttribute('aria-expanded', !expanded);
                    dropdown.setAttribute('aria-hidden', expanded);
                    
                    if (!expanded) {
                        dropdown.classList.add('active');
                    } else {
                        dropdown.classList.remove('active');
                    }
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.language-switcher')) {
                    toggles.forEach(toggle => {
                        toggle.setAttribute('aria-expanded', 'false');
                        const dropdown = toggle.nextElementSibling;
                        dropdown.setAttribute('aria-hidden', 'true');
                        dropdown.classList.remove('active');
                    });
                }
            });
        }
        
        // Run when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', aqualuxeLanguageSwitcher);
        } else {
            aqualuxeLanguageSwitcher();
        }
    })();
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_language_switcher_script');

/**
 * Add language switcher styles to head
 */
function aqualuxe_language_switcher_styles() {
    // Only add if multilingual support is enabled in theme options
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if (!aqualuxe_is_multilingual_plugin_active()) {
        return;
    }
    ?>
    <style id="aqualuxe-language-switcher-styles">
        /* Language switcher styles */
        .language-switcher-wrapper {
            display: inline-flex;
            align-items: center;
            margin-left: 1rem;
            position: relative;
        }
        
        .language-switcher {
            position: relative;
        }
        
        .language-switcher-toggle {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            color: var(--color-text);
            transition: background-color 0.3s ease;
        }
        
        .language-switcher-toggle:hover {
            background-color: rgba(128, 128, 128, 0.1);
        }
        
        .language-switcher-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--color-primary);
        }
        
        .language-flag {
            margin-right: 0.5rem;
            border-radius: 2px;
        }
        
        .language-name {
            margin-right: 0.25rem;
        }
        
        .language-switcher-arrow {
            transition: transform 0.3s ease;
        }
        
        .language-switcher-toggle[aria-expanded="true"] .language-switcher-arrow {
            transform: rotate(180deg);
        }
        
        .language-switcher-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 100;
            min-width: 150px;
            background-color: var(--color-background);
            border-radius: 0.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
        }
        
        .language-switcher-dropdown.active,
        .language-switcher-toggle[aria-expanded="true"] + .language-switcher-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .language-list {
            list-style: none;
            padding: 0.5rem 0;
            margin: 0;
        }
        
        .language-item {
            padding: 0;
            margin: 0;
        }
        
        .language-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: var(--color-text);
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .language-link:hover {
            background-color: rgba(128, 128, 128, 0.1);
        }
        
        .language-item.active .language-link {
            background-color: rgba(var(--color-primary-rgb), 0.1);
            color: var(--color-primary);
        }
        
        .footer-language-switcher .language-switcher-dropdown {
            bottom: 100%;
            top: auto;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_language_switcher_styles');