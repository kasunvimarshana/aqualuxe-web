<?php
/**
 * Template part for displaying the header top bar
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="header-top-bar bg-primary text-white py-2">
    <div class="container mx-auto px-4">
        <div class="header-top-bar-inner flex justify-between items-center">
            <div class="header-top-left flex items-center space-x-4">
                <?php aqualuxe_contact_info(); ?>
            </div>
            <div class="header-top-right flex items-center space-x-4">
                <?php aqualuxe_language_switcher(); ?>
                <?php aqualuxe_currency_switcher(); ?>
                <?php aqualuxe_social_links(); ?>
            </div>
        </div>
    </div>
</div>