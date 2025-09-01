<?php
use PHPUnit\Framework\TestCase;

final class BreadcrumbsTest extends TestCase
{
    public function test_style_contains_breadcrumb_class(): void
    {
        $style = dirname(__DIR__) . '/../style.css';
    $this->assertTrue(file_exists($style), 'style.css should exist');
    $css = file_get_contents($style);
        $this->assertStringContainsString('.ax-breadcrumb', $css);
    }
}
