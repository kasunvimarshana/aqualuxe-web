<?php
// Testimonials module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-testimonials.php';

global $aqualuxe_testimonials_module;
$aqualuxe_testimonials_module = new AquaLuxe_Testimonials_Module();
