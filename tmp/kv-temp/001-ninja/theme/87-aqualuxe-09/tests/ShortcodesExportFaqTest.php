<?php
use PHPUnit\Framework\TestCase;

final class ShortcodesExportFaqTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../tests/bootstrap.php';
    }

    public function testExportFaqShortcodeRegisters(): void
    {
        // include shortcodes file
        require_once AQUALUXE_INC . '/shortcodes.php';
    $this->assertSame(true, function_exists('shortcode_exists'));
    $this->assertSame(true, shortcode_exists('aqualuxe_export_faq'));
    $this->assertSame(true, shortcode_exists('aqualuxe_export_info'));
    }
}
