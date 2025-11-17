<?php
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    public function test_allowed_currencies_contains_usd(): void
    {
        if (!defined('ABSPATH')) { define('ABSPATH', __DIR__); }
        // Include module file
        require_once dirname(__DIR__) . '/../modules/currency/bootstrap.php';
        $this->assertTrue(function_exists('aqualuxe_allowed_currencies'));
        $allowed = aqualuxe_allowed_currencies();
        $this->assertIsArray($allowed);
        $this->assertContains('USD', $allowed);
    }
}
