<?php
namespace AquaLuxe\Core;

final class Roles {
    public static function init(): void {
        \add_action( 'after_switch_theme', [ __CLASS__, 'install' ] );
    }

    public static function install(): void {
        // Wholesale/B2B role
        \add_role( 'alx_wholesale', 'Wholesale Customer', [
            'read' => true,
        ] );
        // Partner/Franchise role
        \add_role( 'alx_partner', 'AquaLuxe Partner', [
            'read' => true,
            'upload_files' => true,
        ] );
    }
}
