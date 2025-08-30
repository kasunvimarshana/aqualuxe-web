<?php
// Portfolio module loader
if ( ! defined( 'ABSPATH' ) ) exit;

require_once __DIR__ . '/class-portfolio.php';

global $aqualuxe_portfolio_module;
$aqualuxe_portfolio_module = new AquaLuxe_Portfolio_Module();
