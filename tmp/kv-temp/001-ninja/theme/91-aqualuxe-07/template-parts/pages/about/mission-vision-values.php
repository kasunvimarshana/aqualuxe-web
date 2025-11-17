<?php
/**
 * Displays the mission, vision, and values section on the about page.
 *
 * @package AquaLuxe
 */

?>
<section class="mission-vision-values py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="mission text-center">
                <h3 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Our Mission', 'aqualuxe' ); ?></h3>
                <p><?php // Add mission text here ?></p>
            </div>
            <div class="vision text-center">
                <h3 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Our Vision', 'aqualuxe' ); ?></h3>
                <p><?php // Add vision text here ?></p>
            </div>
            <div class="values text-center">
                <h3 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Our Values', 'aqualuxe' ); ?></h3>
                <p><?php // Add values text here ?></p>
            </div>
        </div>
    </div>
</section>
