<?php
use PHPUnit\Framework\TestCase;

final class SampleTest extends TestCase {
    public function testVersionConstant(): void {
        require_once __DIR__ . '/../../functions.php';
        $this->assertTrue(defined('AQUALUXE_VERSION'));
    }
}
