<?php
namespace AquaLuxe\Modules\Multilingual;

class Module {
    public static function init(): void {
        // Minimal i18n readiness when translation plugins present
        add_filter('locale', [__CLASS__, 'preferred_locale']);
    }

    public static function preferred_locale($locale) {
        // Respect URL ?lang=xx (safe, not persistent)
        if (!empty($_GET['lang'])) {
            $lang = preg_replace('/[^a-zA-Z_\-]/','',$_GET['lang']);
            if ($lang) return $lang;
        }
        return $locale;
    }
}
