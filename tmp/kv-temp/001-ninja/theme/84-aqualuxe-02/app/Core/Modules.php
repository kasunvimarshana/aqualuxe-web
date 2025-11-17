<?php
namespace Aqualuxe\Core;

defined('ABSPATH') || exit;

/**
 * Modules loader to keep features decoupled and easily toggleable.
 */
final class Modules {
    /** @var array<string,class-string> */
    private static array $registry = [
        // Core
        'assets'   => \Aqualuxe\Modules\Assets\AssetsModule::class,
        'seo'      => \Aqualuxe\Modules\SEO\SEOModule::class,
        'security' => \Aqualuxe\Modules\Security\SecurityModule::class,
        'access'   => \Aqualuxe\Modules\Access\AccessModule::class,
    'cpt'      => \Aqualuxe\Modules\Content\CPTModule::class,
    'ajax'     => \Aqualuxe\Modules\Ajax\AjaxModule::class,
    'ui_skins' => \Aqualuxe\Modules\UI\SkinsModule::class,
    'shortcodes' => \Aqualuxe\Modules\Shortcodes\ShortcodesModule::class,
    'currency' => \Aqualuxe\Modules\Commerce\CurrencyModule::class,
    'customizer' => \Aqualuxe\Modules\Customizer\CustomizerModule::class,
    'performance' => \Aqualuxe\Modules\Performance\PerformanceModule::class,
    ];

    public static function boot(): void {
        $enabled = \apply_filters( 'aqlx/modules/enabled', array_keys( self::$registry ) );
        foreach ( $enabled as $key ) {
            $class = self::$registry[ $key ] ?? null;
            if ( $class && class_exists( $class ) && method_exists( $class, 'register' ) ) {
                $class::register();
            }
        }
    }
}
