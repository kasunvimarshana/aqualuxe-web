<?php
// Franchise module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-franchise.php';

global $aqualuxe_franchise_module;
$aqualuxe_franchise_module = new AquaLuxe_Franchise_Module();
