<?php
// Auctions module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-auctions.php';

global $aqualuxe_auctions_module;
$aqualuxe_auctions_module = new AquaLuxe_Auctions_Module();
