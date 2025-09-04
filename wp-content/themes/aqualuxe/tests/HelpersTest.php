<?php
use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function testMixReturnsEmptyWhenNoManifest(): void
    {
        $uri = \AquaLuxe\mix('css/app.css');
        $this->assertIsString($uri);
    }

    public function testMixReadsManifestWhenPresent(): void
    {
    $manifestDir = AQUALUXE_ASSETS_DIST;
    if (!is_dir($manifestDir)) { mkdir($manifestDir, 0777, true); } // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
        $path = $manifestDir . '/mix-manifest.json';
        file_put_contents($path, json_encode(["/css/app.css" => "/css/app.css?id=abc123"]));
        $uri = \AquaLuxe\mix('css/app.css');
        $this->assertStringContainsString('/css/app.css?id=abc123', $uri);
        if (file_exists($path)) {
            unlink($path); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
        }
    }
}
