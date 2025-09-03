<?php
// Simple multicurrency readiness: filter to switch display currency symbol
\add_filter('aqualuxe/currency/symbol', function($symbol){
    $wanted = isset($_GET['currency']) ? strtoupper(sanitize_text_field($_GET['currency'])) : '';
    return in_array($wanted, ['USD','EUR','GBP'], true) ? $wanted : ($symbol ?: 'USD');
});
