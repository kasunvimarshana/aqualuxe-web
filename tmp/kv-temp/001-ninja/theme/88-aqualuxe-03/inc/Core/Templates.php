<?php
namespace AquaLuxe\Core;

class Templates {
    /** Locate a template part within theme hierarchy. */
    public static function locate( string $slug, string $name = '' ): string {
        $templates = [];
        if ( $name ) {
            $templates[] = "{$slug}-{$name}.php";
        }
        $templates[] = "{$slug}.php";
        foreach ( $templates as $template ) {
            $path = function_exists( 'locate_template' ) ? call_user_func( 'locate_template', $template ) : '';
            if ( $path ) {
                return $path;
            }
        }
        return '';
    }
}
