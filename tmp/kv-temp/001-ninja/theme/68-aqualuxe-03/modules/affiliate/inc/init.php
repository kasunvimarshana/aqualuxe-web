<?php
// Affiliate module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-affiliate.php';

global $aqualuxe_affiliate_module;
$aqualuxe_affiliate_module = new AquaLuxe_Affiliate_Module();
