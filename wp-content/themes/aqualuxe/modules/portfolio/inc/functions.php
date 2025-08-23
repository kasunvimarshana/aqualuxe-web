<?php
// Portfolio module helper functions
if ( ! defined( 'ABSPATH' ) ) exit;

function aqualuxe_portfolio_is_enabled() {
    $config = include __DIR__ . '/config.php';
    return ! empty( $config['enabled'] );
}
