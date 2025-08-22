<?php
/**
 * Multilingual module settings
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register module settings
 */
function aqualuxe_multilingual_register_settings() {
    // Register settings
    aqualuxe_register_module_settings('multilingual', array(
        'sections' => array(
            'general' => array(
                'title' => __('General Settings', 'aqualuxe'),
                'fields' => array(
                    'show_language_switcher' => array(
                        'title' => __('Show Language Switcher', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => true,
                        'description' => __('Enable or disable the language switcher.', 'aqualuxe'),
                    ),
                    'language_switcher_position' => array(
                        'title' => __('Language Switcher Position', 'aqualuxe'),
                        'type' => 'select',
                        'default' => 'header',
                        'options' => array(
                            'header' => __('Header', 'aqualuxe'),
                            'footer' => __('Footer', 'aqualuxe'),
                            'mobile_menu' => __('Mobile Menu', 'aqualuxe'),
                            'widget' => __('Widget Only', 'aqualuxe'),
                        ),
                        'description' => __('Choose where to display the language switcher.', 'aqualuxe'),
                    ),
                    'show_flags' => array(
                        'title' => __('Show Flags', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => true,
                        'description' => __('Show country flags in the language switcher.', 'aqualuxe'),
                    ),
                    'show_names' => array(
                        'title' => __('Show Language Names', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => true,
                        'description' => __('Show language names in the language switcher.', 'aqualuxe'),
                    ),
                    'dropdown' => array(
                        'title' => __('Display as Dropdown', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => true,
                        'description' => __('Display the language switcher as a dropdown menu.', 'aqualuxe'),
                    ),
                ),
            ),
            'integration' => array(
                'title' => __('Integration Settings', 'aqualuxe'),
                'fields' => array(
                    'integration_type' => array(
                        'title' => __('Integration Type', 'aqualuxe'),
                        'type' => 'select',
                        'default' => 'auto',
                        'options' => array(
                            'auto' => __('Automatic (WPML/Polylang)', 'aqualuxe'),
                            'custom' => __('Custom Implementation', 'aqualuxe'),
                        ),
                        'description' => __('Choose how to integrate multilingual functionality.', 'aqualuxe'),
                    ),
                    'default_language' => array(
                        'title' => __('Default Language', 'aqualuxe'),
                        'type' => 'select',
                        'default' => 'en',
                        'options' => aqualuxe_multilingual_get_available_languages_options(),
                        'description' => __('Choose the default language for your site.', 'aqualuxe'),
                    ),
                    'rtl_languages' => array(
                        'title' => __('RTL Languages', 'aqualuxe'),
                        'type' => 'text',
                        'default' => 'ar,he,fa,ur',
                        'description' => __('Comma-separated list of RTL language codes.', 'aqualuxe'),
                    ),
                ),
            ),
            'custom_languages' => array(
                'title' => __('Custom Languages', 'aqualuxe'),
                'callback' => 'aqualuxe_multilingual_custom_languages_section',
            ),
            'translations' => array(
                'title' => __('String Translations', 'aqualuxe'),
                'callback' => 'aqualuxe_multilingual_translations_section',
            ),
        ),
    ));
}

/**
 * Get available languages options
 *
 * @return array Language options
 */
function aqualuxe_multilingual_get_available_languages_options() {
    $languages = array(
        'en' => __('English', 'aqualuxe'),
        'es' => __('Spanish', 'aqualuxe'),
        'fr' => __('French', 'aqualuxe'),
        'de' => __('German', 'aqualuxe'),
        'it' => __('Italian', 'aqualuxe'),
        'pt' => __('Portuguese', 'aqualuxe'),
        'ru' => __('Russian', 'aqualuxe'),
        'ja' => __('Japanese', 'aqualuxe'),
        'zh' => __('Chinese', 'aqualuxe'),
        'ar' => __('Arabic', 'aqualuxe'),
        'hi' => __('Hindi', 'aqualuxe'),
        'nl' => __('Dutch', 'aqualuxe'),
        'sv' => __('Swedish', 'aqualuxe'),
        'fi' => __('Finnish', 'aqualuxe'),
        'da' => __('Danish', 'aqualuxe'),
        'no' => __('Norwegian', 'aqualuxe'),
        'pl' => __('Polish', 'aqualuxe'),
        'tr' => __('Turkish', 'aqualuxe'),
        'ko' => __('Korean', 'aqualuxe'),
        'he' => __('Hebrew', 'aqualuxe'),
    );
    
    // Check if WPML is active
    if (function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0');
        
        if (!empty($wpml_languages)) {
            $languages = array();
            
            foreach ($wpml_languages as $code => $language) {
                $languages[$code] = $language['translated_name'];
            }
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll_languages_list')) {
        $pll_languages = pll_languages_list(array('fields' => 'name'));
        $pll_codes = pll_languages_list(array('fields' => 'slug'));
        
        if (!empty($pll_languages) && !empty($pll_codes)) {
            $languages = array();
            
            foreach ($pll_codes as $i => $code) {
                $languages[$code] = $pll_languages[$i];
            }
        }
    }
    
    return $languages;
}

/**
 * Custom languages section
 */
function aqualuxe_multilingual_custom_languages_section() {
    // Get current languages
    $languages = aqualuxe_get_module_option('multilingual', 'languages', array());
    $integration_type = aqualuxe_get_module_option('multilingual', 'integration_type', 'auto');
    
    // Check if custom implementation is enabled
    if ($integration_type !== 'custom') {
        echo '<p>' . esc_html__('Custom languages are only available when using custom integration. Please change the integration type to "Custom Implementation" to manage custom languages.', 'aqualuxe') . '</p>';
        return;
    }
    
    // Display custom languages form
    ?>
    <div class="custom-languages-form">
        <p><?php esc_html_e('Add and manage custom languages for your site.', 'aqualuxe'); ?></p>
        
        <table class="form-table custom-languages-table" id="custom-languages-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Language Code', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Name', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Native Name', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Flag URL', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('URL', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($languages)) : ?>
                    <?php foreach ($languages as $code => $language) : ?>
                        <tr>
                            <td>
                                <input type="text" name="aqualuxe_module_multilingual_options[languages][<?php echo esc_attr($code); ?>][code]" value="<?php echo esc_attr($code); ?>" readonly>
                            </td>
                            <td>
                                <input type="text" name="aqualuxe_module_multilingual_options[languages][<?php echo esc_attr($code); ?>][name]" value="<?php echo esc_attr($language['name']); ?>">
                            </td>
                            <td>
                                <input type="text" name="aqualuxe_module_multilingual_options[languages][<?php echo esc_attr($code); ?>][native_name]" value="<?php echo esc_attr($language['native_name']); ?>">
                            </td>
                            <td>
                                <input type="text" name="aqualuxe_module_multilingual_options[languages][<?php echo esc_attr($code); ?>][flag]" value="<?php echo esc_attr($language['flag']); ?>">
                            </td>
                            <td>
                                <input type="text" name="aqualuxe_module_multilingual_options[languages][<?php echo esc_attr($code); ?>][url]" value="<?php echo esc_attr($language['url']); ?>">
                            </td>
                            <td>
                                <button type="button" class="button remove-language"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="add-language-form">
            <h4><?php esc_html_e('Add New Language', 'aqualuxe'); ?></h4>
            <div class="form-fields">
                <div class="form-field">
                    <label for="new-language-code"><?php esc_html_e('Language Code', 'aqualuxe'); ?></label>
                    <input type="text" id="new-language-code" placeholder="en">
                </div>
                <div class="form-field">
                    <label for="new-language-name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
                    <input type="text" id="new-language-name" placeholder="English">
                </div>
                <div class="form-field">
                    <label for="new-language-native-name"><?php esc_html_e('Native Name', 'aqualuxe'); ?></label>
                    <input type="text" id="new-language-native-name" placeholder="English">
                </div>
                <div class="form-field">
                    <label for="new-language-flag"><?php esc_html_e('Flag URL', 'aqualuxe'); ?></label>
                    <input type="text" id="new-language-flag" placeholder="https://example.com/flags/en.png">
                </div>
                <div class="form-field">
                    <label for="new-language-url"><?php esc_html_e('URL', 'aqualuxe'); ?></label>
                    <input type="text" id="new-language-url" placeholder="https://example.com/en/">
                </div>
                <div class="form-field">
                    <button type="button" class="button button-primary" id="add-language"><?php esc_html_e('Add Language', 'aqualuxe'); ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Add new language
        $('#add-language').on('click', function() {
            var code = $('#new-language-code').val();
            var name = $('#new-language-name').val();
            var nativeName = $('#new-language-native-name').val();
            var flag = $('#new-language-flag').val();
            var url = $('#new-language-url').val();
            
            if (!code || !name) {
                alert('<?php esc_html_e('Language code and name are required.', 'aqualuxe'); ?>');
                return;
            }
            
            var row = '<tr>' +
                '<td><input type="text" name="aqualuxe_module_multilingual_options[languages][' + code + '][code]" value="' + code + '" readonly></td>' +
                '<td><input type="text" name="aqualuxe_module_multilingual_options[languages][' + code + '][name]" value="' + name + '"></td>' +
                '<td><input type="text" name="aqualuxe_module_multilingual_options[languages][' + code + '][native_name]" value="' + nativeName + '"></td>' +
                '<td><input type="text" name="aqualuxe_module_multilingual_options[languages][' + code + '][flag]" value="' + flag + '"></td>' +
                '<td><input type="text" name="aqualuxe_module_multilingual_options[languages][' + code + '][url]" value="' + url + '"></td>' +
                '<td><button type="button" class="button remove-language"><?php esc_html_e('Remove', 'aqualuxe'); ?></button></td>' +
                '</tr>';
            
            $('#custom-languages-table tbody').append(row);
            
            // Clear form
            $('#new-language-code').val('');
            $('#new-language-name').val('');
            $('#new-language-native-name').val('');
            $('#new-language-flag').val('');
            $('#new-language-url').val('');
        });
        
        // Remove language
        $(document).on('click', '.remove-language', function() {
            $(this).closest('tr').remove();
        });
    });
    </script>
    
    <style>
    .custom-languages-table {
        margin-bottom: 20px;
    }
    .custom-languages-table input[type="text"] {
        width: 100%;
    }
    .add-language-form {
        background: #f9f9f9;
        padding: 15px;
        border: 1px solid #e5e5e5;
        margin-top: 20px;
    }
    .add-language-form h4 {
        margin-top: 0;
    }
    .form-fields {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .form-field {
        flex: 1;
        min-width: 150px;
    }
    .form-field label {
        display: block;
        margin-bottom: 5px;
    }
    </style>
    <?php
}

/**
 * Translations section
 */
function aqualuxe_multilingual_translations_section() {
    // Get registered strings
    $registered_strings = get_option('aqualuxe_multilingual_strings', array());
    
    // Get translations
    $translations = aqualuxe_get_module_option('multilingual', 'translations', array());
    
    // Get available languages
    $languages = aqualuxe_multilingual_get_languages();
    $current_language = aqualuxe_multilingual_get_current_language();
    $default_language = aqualuxe_multilingual_get_default_language();
    
    // Remove default language from languages list
    if (isset($languages[$default_language])) {
        unset($languages[$default_language]);
    }
    
    // Check if there are any registered strings
    if (empty($registered_strings)) {
        echo '<p>' . esc_html__('No strings have been registered for translation.', 'aqualuxe') . '</p>';
        return;
    }
    
    // Check if there are any languages to translate to
    if (empty($languages)) {
        echo '<p>' . esc_html__('No languages available for translation.', 'aqualuxe') . '</p>';
        return;
    }
    
    // Display translations form
    ?>
    <div class="translations-form">
        <p><?php esc_html_e('Translate registered strings for each language.', 'aqualuxe'); ?></p>
        
        <div class="translations-tabs">
            <div class="translations-tabs-nav">
                <?php foreach ($languages as $code => $language) : ?>
                    <a href="#" data-language="<?php echo esc_attr($code); ?>" class="tab-link <?php echo $code === $current_language ? 'active' : ''; ?>">
                        <?php if (!empty($language['flag'])) : ?>
                            <img src="<?php echo esc_url($language['flag']); ?>" alt="<?php echo esc_attr($language['name']); ?>" width="16" height="11">
                        <?php endif; ?>
                        <?php echo esc_html($language['name']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="translations-tabs-content">
                <?php foreach ($languages as $code => $language) : ?>
                    <div class="tab-content <?php echo $code === $current_language ? 'active' : ''; ?>" data-language="<?php echo esc_attr($code); ?>">
                        <h4><?php printf(esc_html__('Translations for %s', 'aqualuxe'), esc_html($language['name'])); ?></h4>
                        
                        <table class="form-table translations-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Domain', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Original String', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Translation', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registered_strings as $domain => $strings) : ?>
                                    <?php foreach ($strings as $name => $string) : ?>
                                        <tr>
                                            <td><?php echo esc_html($domain); ?></td>
                                            <td><?php echo esc_html($string); ?></td>
                                            <td>
                                                <input type="text" name="aqualuxe_module_multilingual_options[translations][<?php echo esc_attr($code); ?>][<?php echo esc_attr($domain); ?>][<?php echo esc_attr($name); ?>]" value="<?php echo isset($translations[$code][$domain][$name]) ? esc_attr($translations[$code][$domain][$name]) : ''; ?>" placeholder="<?php echo esc_attr($string); ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab navigation
        $('.translations-tabs-nav .tab-link').on('click', function(e) {
            e.preventDefault();
            
            var language = $(this).data('language');
            
            // Update active tab
            $('.translations-tabs-nav .tab-link').removeClass('active');
            $(this).addClass('active');
            
            // Show tab content
            $('.translations-tabs-content .tab-content').removeClass('active');
            $('.translations-tabs-content .tab-content[data-language="' + language + '"]').addClass('active');
        });
    });
    </script>
    
    <style>
    .translations-tabs {
        margin-top: 20px;
    }
    .translations-tabs-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 15px;
    }
    .translations-tabs-nav .tab-link {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        background: #f0f0f0;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
    }
    .translations-tabs-nav .tab-link.active {
        background: #fff;
        border-bottom-color: #fff;
        font-weight: bold;
    }
    .translations-tabs-content {
        border: 1px solid #ccc;
        padding: 15px;
        background: #fff;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .translations-table input[type="text"] {
        width: 100%;
    }
    </style>
    <?php
}

/**
 * Sanitize module options
 *
 * @param array $options Options to sanitize
 * @return array Sanitized options
 */
function aqualuxe_multilingual_sanitize_options($options) {
    // Sanitize languages
    if (isset($options['languages']) && is_array($options['languages'])) {
        foreach ($options['languages'] as $code => $language) {
            $options['languages'][$code]['code'] = sanitize_text_field($language['code']);
            $options['languages'][$code]['name'] = sanitize_text_field($language['name']);
            $options['languages'][$code]['native_name'] = sanitize_text_field($language['native_name']);
            $options['languages'][$code]['flag'] = esc_url_raw($language['flag']);
            $options['languages'][$code]['url'] = esc_url_raw($language['url']);
        }
    }
    
    // Sanitize translations
    if (isset($options['translations']) && is_array($options['translations'])) {
        foreach ($options['translations'] as $language => $domains) {
            foreach ($domains as $domain => $strings) {
                foreach ($strings as $name => $translation) {
                    $options['translations'][$language][$domain][$name] = sanitize_text_field($translation);
                }
            }
        }
    }
    
    return $options;
}
add_filter('aqualuxe_sanitize_module_options_multilingual', 'aqualuxe_multilingual_sanitize_options');