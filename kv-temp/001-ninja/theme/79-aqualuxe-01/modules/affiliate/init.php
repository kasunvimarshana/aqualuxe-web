<?php
declare(strict_types=1);

// Simple referral query param tracking
add_action('init', static function (): void {
    if (isset($_GET['ref'])) {
        $ref = sanitize_text_field((string) $_GET['ref']);
        setcookie('aqlx_ref', $ref, time() + 30*24*60*60, '/', '', is_ssl(), true);
    }
});
