<?php
/**
 * Enterprise Theme Language Management Service
 * 
 * Comprehensive multilingual management system providing
 * language detection, translation management, RTL support,
 * and content localization
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Language Management Service Class
 * 
 * Implements:
 * - Multi-language support with 13+ languages
 * - RTL (Right-to-Left) language support
 * - Dynamic language switching
 * - Translation management and caching
 * - Content localization
 * - Language-specific URLs and routing
 * - Browser language detection
 * - Language fallback system
 */
class Enterprise_Theme_Language_Service {
    
    /**
     * Service configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * Database service instance
     * 
     * @var Enterprise_Theme_Database_Service
     */
    private Enterprise_Theme_Database_Service $database;
    
    /**
     * Cache service instance
     * 
     * @var Enterprise_Theme_Cache_Service
     */
    private Enterprise_Theme_Cache_Service $cache;
    
    /**
     * Tenant service instance
     * 
     * @var Enterprise_Theme_Tenant_Service
     */
    private Enterprise_Theme_Tenant_Service $tenant_service;
    
    /**
     * Current language
     * 
     * @var array|null
     */
    private ?array $current_language = null;
    
    /**
     * Supported languages
     * 
     * @var array
     */
    private array $supported_languages = [
        'en' => [
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'en_US',
            'is_default' => true,
        ],
        'es' => [
            'code' => 'es',
            'name' => 'Spanish',
            'native_name' => 'Español',
            'flag' => '🇪🇸',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'es_ES',
            'is_default' => false,
        ],
        'fr' => [
            'code' => 'fr',
            'name' => 'French',
            'native_name' => 'Français',
            'flag' => '🇫🇷',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'fr_FR',
            'is_default' => false,
        ],
        'de' => [
            'code' => 'de',
            'name' => 'German',
            'native_name' => 'Deutsch',
            'flag' => '🇩🇪',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'de_DE',
            'is_default' => false,
        ],
        'it' => [
            'code' => 'it',
            'name' => 'Italian',
            'native_name' => 'Italiano',
            'flag' => '🇮🇹',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'it_IT',
            'is_default' => false,
        ],
        'pt' => [
            'code' => 'pt',
            'name' => 'Portuguese',
            'native_name' => 'Português',
            'flag' => '🇵🇹',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'pt_PT',
            'is_default' => false,
        ],
        'ru' => [
            'code' => 'ru',
            'name' => 'Russian',
            'native_name' => 'Русский',
            'flag' => '🇷🇺',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'ru_RU',
            'is_default' => false,
        ],
        'zh' => [
            'code' => 'zh',
            'name' => 'Chinese',
            'native_name' => '中文',
            'flag' => '🇨🇳',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'zh_CN',
            'is_default' => false,
        ],
        'ja' => [
            'code' => 'ja',
            'name' => 'Japanese',
            'native_name' => '日本語',
            'flag' => '🇯🇵',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'ja_JP',
            'is_default' => false,
        ],
        'ko' => [
            'code' => 'ko',
            'name' => 'Korean',
            'native_name' => '한국어',
            'flag' => '🇰🇷',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'ko_KR',
            'is_default' => false,
        ],
        'ar' => [
            'code' => 'ar',
            'name' => 'Arabic',
            'native_name' => 'العربية',
            'flag' => '🇸🇦',
            'direction' => 'rtl',
            'charset' => 'UTF-8',
            'locale' => 'ar_SA',
            'is_default' => false,
        ],
        'he' => [
            'code' => 'he',
            'name' => 'Hebrew',
            'native_name' => 'עברית',
            'flag' => '🇮🇱',
            'direction' => 'rtl',
            'charset' => 'UTF-8',
            'locale' => 'he_IL',
            'is_default' => false,
        ],
        'hi' => [
            'code' => 'hi',
            'name' => 'Hindi',
            'native_name' => 'हिन्दी',
            'flag' => '🇮🇳',
            'direction' => 'ltr',
            'charset' => 'UTF-8',
            'locale' => 'hi_IN',
            'is_default' => false,
        ],
    ];
    
    /**
     * Translation cache
     * 
     * @var array
     */
    private array $translation_cache = [];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     * @param Enterprise_Theme_Database_Service $database Database service
     * @param Enterprise_Theme_Cache_Service $cache Cache service
     * @param Enterprise_Theme_Tenant_Service $tenant_service Tenant service
     */
    public function __construct(
        Enterprise_Theme_Config $config,
        Enterprise_Theme_Database_Service $database,
        Enterprise_Theme_Cache_Service $cache,
        Enterprise_Theme_Tenant_Service $tenant_service
    ) {
        $this->config = $config->get('multilingual');
        $this->database = $database;
        $this->cache = $cache;
        $this->tenant_service = $tenant_service;
        $this->init_language_management();
    }
    
    /**
     * Get current language
     * 
     * @return array Current language data
     */
    public function get_current_language(): array {
        if ($this->current_language === null) {
            $this->current_language = $this->resolve_current_language();
        }
        
        return $this->current_language;
    }
    
    /**
     * Get available languages for current tenant
     * 
     * @return array Available languages
     */
    public function get_available_languages(): array {
        $tenant = $this->tenant_service->get_current_tenant();
        $cache_key = 'available_languages_' . ($tenant['id'] ?? 0);
        
        return $this->cache->remember(
            $cache_key,
            function() use ($tenant) {
                if ($tenant) {
                    $enabled_languages = $this->tenant_service->get_tenant_setting(
                        'languages.enabled',
                        ['en'],
                        $tenant['id']
                    );
                } else {
                    $enabled_languages = $this->config['default_languages'] ?? ['en'];
                }
                
                $available = [];
                foreach ($enabled_languages as $code) {
                    if (isset($this->supported_languages[$code])) {
                        $available[$code] = $this->supported_languages[$code];
                    }
                }
                
                return $available;
            },
            3600,
            ['group' => 'languages']
        );
    }
    
    /**
     * Switch to language
     * 
     * @param string $language_code Language code
     * @return bool Success status
     */
    public function switch_language(string $language_code): bool {
        $available_languages = $this->get_available_languages();
        
        if (!isset($available_languages[$language_code])) {
            return false;
        }
        
        $this->current_language = $available_languages[$language_code];
        
        // Set WordPress locale
        add_filter('locale', function() use ($language_code) {
            return $this->supported_languages[$language_code]['locale'];
        });
        
        // Store in session/cookie for persistence
        $this->store_language_preference($language_code);
        
        // Trigger language switch hook
        do_action('enterprise_theme_language_switched', $language_code, $this->current_language);
        
        return true;
    }
    
    /**
     * Translate text
     * 
     * @param string $text Text to translate
     * @param string $context Translation context
     * @param string $language_code Target language (optional)
     * @return string Translated text
     */
    public function translate(string $text, string $context = 'default', string $language_code = null): string {
        if ($language_code === null) {
            $language_code = $this->get_current_language()['code'];
        }
        
        // If it's the default language, return as-is
        if ($language_code === 'en') {
            return $text;
        }
        
        $cache_key = "translation_{$language_code}_{$context}_" . md5($text);
        
        if (isset($this->translation_cache[$cache_key])) {
            return $this->translation_cache[$cache_key];
        }
        
        $translation = $this->cache->remember(
            $cache_key,
            function() use ($text, $context, $language_code) {
                return $this->get_translation_from_database($text, $context, $language_code);
            },
            7200,
            ['group' => 'translations']
        );
        
        $this->translation_cache[$cache_key] = $translation ?: $text;
        
        return $this->translation_cache[$cache_key];
    }
    
    /**
     * Add translation
     * 
     * @param string $text Original text
     * @param string $translation Translated text
     * @param string $language_code Target language
     * @param string $context Translation context
     * @return bool Success status
     */
    public function add_translation(string $text, string $translation, string $language_code, string $context = 'default'): bool {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $translation_data = [
            'tenant_id' => $tenant['id'] ?? 0,
            'original_text' => $text,
            'translated_text' => $translation,
            'language_code' => $language_code,
            'context' => $context,
            'status' => 'active',
        ];
        
        $result = $this->database->insert('translations', $translation_data);
        
        if ($result) {
            // Clear translation cache
            $this->cache->flush_group('translations');
            unset($this->translation_cache["translation_{$language_code}_{$context}_" . md5($text)]);
        }
        
        return $result !== false;
    }
    
    /**
     * Get localized URL
     * 
     * @param string $url URL to localize
     * @param string $language_code Language code (optional)
     * @return string Localized URL
     */
    public function get_localized_url(string $url, string $language_code = null): string {
        if ($language_code === null) {
            $language_code = $this->get_current_language()['code'];
        }
        
        // If it's the default language, return original URL
        if ($language_code === 'en') {
            return $url;
        }
        
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'] ?? '/';
        
        // Check if language code is already in URL
        if (preg_match('/^\/(' . implode('|', array_keys($this->get_available_languages())) . ')\//', $path)) {
            // Replace existing language code
            $path = preg_replace('/^\/[a-z]{2}\//', "/{$language_code}/", $path);
        } else {
            // Add language code
            $path = "/{$language_code}" . $path;
        }
        
        $localized_url = ($parsed_url['scheme'] ?? 'http') . '://' . 
                        ($parsed_url['host'] ?? $_SERVER['HTTP_HOST']) . 
                        $path;
        
        if (isset($parsed_url['query'])) {
            $localized_url .= '?' . $parsed_url['query'];
        }
        
        if (isset($parsed_url['fragment'])) {
            $localized_url .= '#' . $parsed_url['fragment'];
        }
        
        return $localized_url;
    }
    
    /**
     * Get language from URL
     * 
     * @param string $url URL to parse
     * @return string|null Language code or null
     */
    public function get_language_from_url(string $url): ?string {
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'] ?? '/';
        
        if (preg_match('/^\/([a-z]{2})\//', $path, $matches)) {
            $language_code = $matches[1];
            
            if (isset($this->get_available_languages()[$language_code])) {
                return $language_code;
            }
        }
        
        return null;
    }
    
    /**
     * Detect browser language
     * 
     * @return string Detected language code
     */
    public function detect_browser_language(): string {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return 'en';
        }
        
        $browser_languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $available_languages = array_keys($this->get_available_languages());
        
        // Parse Accept-Language header
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $browser_languages, $lang_parse);
        
        $langs = [];
        if (count($lang_parse[1])) {
            $langs = array_combine($lang_parse[1], $lang_parse[2]);
            
            foreach ($langs as $lang => $val) {
                if ($val === '') $val = 1;
                $langs[$lang] = (float) $val;
            }
            
            arsort($langs, SORT_NUMERIC);
        }
        
        foreach ($langs as $lang => $priority) {
            $lang = strtolower(substr($lang, 0, 2));
            
            if (in_array($lang, $available_languages)) {
                return $lang;
            }
        }
        
        return 'en';
    }
    
    /**
     * Check if language is RTL
     * 
     * @param string $language_code Language code (optional)
     * @return bool RTL status
     */
    public function is_rtl(string $language_code = null): bool {
        if ($language_code === null) {
            $language_code = $this->get_current_language()['code'];
        }
        
        return ($this->supported_languages[$language_code]['direction'] ?? 'ltr') === 'rtl';
    }
    
    /**
     * Get language direction
     * 
     * @param string $language_code Language code (optional)
     * @return string Language direction (ltr/rtl)
     */
    public function get_direction(string $language_code = null): string {
        if ($language_code === null) {
            $language_code = $this->get_current_language()['code'];
        }
        
        return $this->supported_languages[$language_code]['direction'] ?? 'ltr';
    }
    
    /**
     * Get language statistics
     * 
     * @return array Language statistics
     */
    public function get_language_statistics(): array {
        $tenant = $this->tenant_service->get_current_tenant();
        $tenant_id = $tenant['id'] ?? 0;
        
        return [
            'available_languages' => count($this->get_available_languages()),
            'total_translations' => $this->database->count('translations', ['tenant_id' => $tenant_id]),
            'active_translations' => $this->database->count('translations', [
                'tenant_id' => $tenant_id,
                'status' => 'active'
            ]),
            'rtl_languages' => count(array_filter($this->get_available_languages(), function($lang) {
                return $lang['direction'] === 'rtl';
            })),
        ];
    }
    
    /**
     * Export translations
     * 
     * @param string $language_code Language code
     * @param string $format Export format (json, csv, po)
     * @return string|false Exported data or false on failure
     */
    public function export_translations(string $language_code, string $format = 'json'): string|false {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $translations = $this->database->get('translations', [
            'where' => [
                'tenant_id' => $tenant['id'] ?? 0,
                'language_code' => $language_code,
                'status' => 'active'
            ]
        ]);
        
        switch ($format) {
            case 'json':
                return $this->export_translations_json($translations);
            case 'csv':
                return $this->export_translations_csv($translations);
            case 'po':
                return $this->export_translations_po($translations, $language_code);
            default:
                return false;
        }
    }
    
    /**
     * Import translations
     * 
     * @param string $data Translation data
     * @param string $language_code Language code
     * @param string $format Import format (json, csv, po)
     * @return bool Success status
     */
    public function import_translations(string $data, string $language_code, string $format = 'json'): bool {
        switch ($format) {
            case 'json':
                return $this->import_translations_json($data, $language_code);
            case 'csv':
                return $this->import_translations_csv($data, $language_code);
            case 'po':
                return $this->import_translations_po($data, $language_code);
            default:
                return false;
        }
    }
    
    /**
     * Initialize language management
     * 
     * @return void
     */
    private function init_language_management(): void {
        // Hook into WordPress init to detect language
        add_action('init', [$this, 'early_language_detection'], 1);
        
        // Hook into template redirect for language URLs
        add_action('template_redirect', [$this, 'handle_language_routing']);
        
        // Hook into locale filter
        add_filter('locale', [$this, 'filter_locale']);
        
        // Hook into body class for RTL support
        add_filter('body_class', [$this, 'add_language_body_classes']);
        
        // Initialize database tables
        $this->create_language_tables();
    }
    
    /**
     * Resolve current language
     * 
     * @return array Current language data
     */
    private function resolve_current_language(): array {
        $available_languages = $this->get_available_languages();
        
        // 1. Check URL for language code
        $url_language = $this->get_language_from_url($_SERVER['REQUEST_URI'] ?? '/');
        if ($url_language && isset($available_languages[$url_language])) {
            return $available_languages[$url_language];
        }
        
        // 2. Check stored preference (session/cookie)
        $stored_language = $this->get_stored_language_preference();
        if ($stored_language && isset($available_languages[$stored_language])) {
            return $available_languages[$stored_language];
        }
        
        // 3. Check browser language
        $browser_language = $this->detect_browser_language();
        if (isset($available_languages[$browser_language])) {
            return $available_languages[$browser_language];
        }
        
        // 4. Fall back to default language
        foreach ($available_languages as $lang) {
            if ($lang['is_default'] ?? false) {
                return $lang;
            }
        }
        
        // 5. Fall back to English
        return $this->supported_languages['en'];
    }
    
    /**
     * Get translation from database
     * 
     * @param string $text Original text
     * @param string $context Translation context
     * @param string $language_code Target language
     * @return string|null Translation or null
     */
    private function get_translation_from_database(string $text, string $context, string $language_code): ?string {
        $tenant = $this->tenant_service->get_current_tenant();
        
        $results = $this->database->get('translations', [
            'where' => [
                'tenant_id' => $tenant['id'] ?? 0,
                'original_text' => $text,
                'language_code' => $language_code,
                'context' => $context,
                'status' => 'active'
            ],
            'limit' => 1
        ]);
        
        return !empty($results) ? $results[0]['translated_text'] : null;
    }
    
    /**
     * Store language preference
     * 
     * @param string $language_code Language code
     * @return void
     */
    private function store_language_preference(string $language_code): void {
        // Store in cookie
        setcookie(
            'enterprise_theme_language',
            $language_code,
            time() + (86400 * 30), // 30 days
            '/',
            '',
            is_ssl(),
            true
        );
        
        // Store in session if available
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['enterprise_theme_language'] = $language_code;
        }
    }
    
    /**
     * Get stored language preference
     * 
     * @return string|null Stored language code or null
     */
    private function get_stored_language_preference(): ?string {
        // Check session first
        if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['enterprise_theme_language'])) {
            return $_SESSION['enterprise_theme_language'];
        }
        
        // Check cookie
        return $_COOKIE['enterprise_theme_language'] ?? null;
    }
    
    /**
     * Create language database tables
     * 
     * @return void
     */
    private function create_language_tables(): void {
        // Languages table
        $this->database->create_table('languages', [
            'columns' => [
                'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                'tenant_id' => ['type' => 'bigint'],
                'code' => ['type' => 'varchar', 'length' => 5],
                'name' => ['type' => 'varchar', 'length' => 100],
                'native_name' => ['type' => 'varchar', 'length' => 100],
                'flag' => ['type' => 'varchar', 'length' => 10],
                'direction' => ['type' => 'varchar', 'length' => 3, 'default' => 'ltr'],
                'locale' => ['type' => 'varchar', 'length' => 10],
                'is_default' => ['type' => 'boolean', 'default' => false],
                'is_active' => ['type' => 'boolean', 'default' => true],
                'sort_order' => ['type' => 'int', 'default' => 0],
                'created_at' => ['type' => 'datetime'],
                'updated_at' => ['type' => 'datetime'],
            ],
            'indexes' => [
                'tenant_code_unique' => ['type' => 'unique', 'columns' => ['tenant_id', 'code']],
                'tenant_index' => ['columns' => ['tenant_id']],
                'code_index' => ['columns' => ['code']],
            ],
        ]);
        
        // Translations table
        $this->database->create_table('translations', [
            'columns' => [
                'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                'tenant_id' => ['type' => 'bigint'],
                'original_text' => ['type' => 'text'],
                'translated_text' => ['type' => 'text'],
                'language_code' => ['type' => 'varchar', 'length' => 5],
                'context' => ['type' => 'varchar', 'length' => 100, 'default' => 'default'],
                'status' => ['type' => 'varchar', 'length' => 20, 'default' => 'active'],
                'created_at' => ['type' => 'datetime'],
                'updated_at' => ['type' => 'datetime'],
            ],
            'indexes' => [
                'translation_lookup' => ['columns' => ['tenant_id', 'language_code', 'context']],
                'original_text_index' => ['columns' => ['original_text(255)']],
                'tenant_index' => ['columns' => ['tenant_id']],
            ],
        ]);
    }
    
    /**
     * Export translations as JSON
     * 
     * @param array $translations Translations data
     * @return string JSON data
     */
    private function export_translations_json(array $translations): string {
        $export_data = [];
        
        foreach ($translations as $translation) {
            $export_data[$translation['original_text']] = [
                'translated' => $translation['translated_text'],
                'context' => $translation['context'],
            ];
        }
        
        return json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Export translations as CSV
     * 
     * @param array $translations Translations data
     * @return string CSV data
     */
    private function export_translations_csv(array $translations): string {
        $csv_data = "Original,Translation,Context\n";
        
        foreach ($translations as $translation) {
            $csv_data .= '"' . str_replace('"', '""', $translation['original_text']) . '",';
            $csv_data .= '"' . str_replace('"', '""', $translation['translated_text']) . '",';
            $csv_data .= '"' . str_replace('"', '""', $translation['context']) . '"' . "\n";
        }
        
        return $csv_data;
    }
    
    /**
     * Export translations as PO format
     * 
     * @param array $translations Translations data
     * @param string $language_code Language code
     * @return string PO data
     */
    private function export_translations_po(array $translations, string $language_code): string {
        $language = $this->supported_languages[$language_code] ?? [];
        
        $po_data = "# Translation file for Enterprise Theme\n";
        $po_data .= "# Language: {$language['name']} ({$language_code})\n";
        $po_data .= "# Generated: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($translations as $translation) {
            if ($translation['context'] !== 'default') {
                $po_data .= "msgctxt \"{$translation['context']}\"\n";
            }
            $po_data .= "msgid \"" . addslashes($translation['original_text']) . "\"\n";
            $po_data .= "msgstr \"" . addslashes($translation['translated_text']) . "\"\n\n";
        }
        
        return $po_data;
    }
    
    /**
     * Import translations from JSON
     * 
     * @param string $data JSON data
     * @param string $language_code Language code
     * @return bool Success status
     */
    private function import_translations_json(string $data, string $language_code): bool {
        $translations = json_decode($data, true);
        
        if (!$translations) {
            return false;
        }
        
        $success = true;
        
        foreach ($translations as $original => $translation_data) {
            $translated = is_array($translation_data) ? $translation_data['translated'] : $translation_data;
            $context = is_array($translation_data) ? ($translation_data['context'] ?? 'default') : 'default';
            
            if (!$this->add_translation($original, $translated, $language_code, $context)) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    /**
     * Import translations from CSV
     * 
     * @param string $data CSV data
     * @param string $language_code Language code
     * @return bool Success status
     */
    private function import_translations_csv(string $data, string $language_code): bool {
        $lines = str_getcsv($data, "\n");
        $success = true;
        
        // Skip header row
        array_shift($lines);
        
        foreach ($lines as $line) {
            $fields = str_getcsv($line);
            
            if (count($fields) >= 2) {
                $original = $fields[0];
                $translated = $fields[1];
                $context = $fields[2] ?? 'default';
                
                if (!$this->add_translation($original, $translated, $language_code, $context)) {
                    $success = false;
                }
            }
        }
        
        return $success;
    }
    
    /**
     * Import translations from PO format
     * 
     * @param string $data PO data
     * @param string $language_code Language code
     * @return bool Success status
     */
    private function import_translations_po(string $data, string $language_code): bool {
        $lines = explode("\n", $data);
        $success = true;
        $current_msgid = '';
        $current_msgstr = '';
        $current_context = 'default';
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (strpos($line, 'msgctxt') === 0) {
                $current_context = trim(substr($line, 7), ' "');
            } elseif (strpos($line, 'msgid') === 0) {
                $current_msgid = trim(substr($line, 5), ' "');
            } elseif (strpos($line, 'msgstr') === 0) {
                $current_msgstr = trim(substr($line, 6), ' "');
                
                if ($current_msgid && $current_msgstr) {
                    if (!$this->add_translation($current_msgid, $current_msgstr, $language_code, $current_context)) {
                        $success = false;
                    }
                }
                
                // Reset for next entry
                $current_msgid = '';
                $current_msgstr = '';
                $current_context = 'default';
            }
        }
        
        return $success;
    }
    
    /**
     * Early language detection
     * 
     * @return void
     */
    public function early_language_detection(): void {
        // Detect and set current language early
        $this->get_current_language();
    }
    
    /**
     * Handle language routing
     * 
     * @return void
     */
    public function handle_language_routing(): void {
        $language_code = $this->get_language_from_url($_SERVER['REQUEST_URI'] ?? '/');
        
        if ($language_code) {
            $this->switch_language($language_code);
        }
    }
    
    /**
     * Filter WordPress locale
     * 
     * @param string $locale Current locale
     * @return string Filtered locale
     */
    public function filter_locale(string $locale): string {
        $current_language = $this->get_current_language();
        return $current_language['locale'] ?? $locale;
    }
    
    /**
     * Add language-specific body classes
     * 
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function add_language_body_classes(array $classes): array {
        $current_language = $this->get_current_language();
        
        $classes[] = 'lang-' . $current_language['code'];
        $classes[] = 'dir-' . $current_language['direction'];
        
        if ($current_language['direction'] === 'rtl') {
            $classes[] = 'rtl';
        }
        
        return $classes;
    }
}
