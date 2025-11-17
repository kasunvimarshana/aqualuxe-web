<?php
namespace AquaLuxe\Core;

class Module_Loader {
    public static function init(): void {
        $base = get_template_directory() . '/modules';
        if (!is_dir($base)) return;
        $dirs = glob($base . '/*', GLOB_ONLYDIR);
        foreach (($dirs ?: []) as $dir) {
            $cfg = $dir . '/module.json';
            $php = $dir . '/module.php';
            if (!file_exists($cfg) || !file_exists($php)) continue;
            $json = json_decode(file_get_contents($cfg), true);
            if (isset($json['enabled']) && $json['enabled']) {
                require_once $php;
                $class = $json['class'] ?? null;
                if ($class && class_exists($class)) {
                    if (method_exists($class, 'init')) {
                        $class::init();
                    }
                }
            }
        }
    }
}
