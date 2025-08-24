<?php
// Wholesale module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-wholesale.php';

global $aqualuxe_wholesale_module;
$aqualuxe_wholesale_module = new AquaLuxe_Wholesale_Module();
