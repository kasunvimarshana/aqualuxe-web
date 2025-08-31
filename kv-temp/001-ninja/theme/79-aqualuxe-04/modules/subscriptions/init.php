<?php
declare(strict_types=1);

// Placeholder for subscriptions/memberships integration.
add_shortcode('aqlx_membership_cta', static function () {
    return '<div class="border p-4 rounded"><h3>'.esc_html__('Memberships', 'aqualuxe').'</h3><p>'.esc_html__('Join to access exclusive stock and benefits.', 'aqualuxe').'</p></div>';
});
