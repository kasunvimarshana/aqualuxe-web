<?php

// Provide a minimal PHPUnit TestCase stub for editors when PHPUnit isn't installed.
namespace PHPUnit\Framework {
    if (! class_exists('PHPUnit\\Framework\\TestCase')) {
        abstract class TestCase {
            public static function assertIsString($actual, string $message = ''): void {}
            public static function assertStringContainsString(string $needle, string $haystack, string $message = ''): void {}
            public static function assertSame($expected, $actual, string $message = ''): void {}
        }
    }
}

namespace {
use PHPUnit\Framework\TestCase;
use function Aqualuxe\Inc\mix;

final class MixHelperTest extends TestCase
{
    public function testMixReturnsStringForKnownAsset(): void
    {
        // Arrange: create a temporary mix-manifest.
        $themeRoot = dirname(dirname(__DIR__));
        $manifestPath = $themeRoot . '/assets/dist/mix-manifest.json';
        $distDir = dirname($manifestPath);
        if (! is_dir($distDir)) {
            mkdir($distDir, 0777, true);
        }
        file_put_contents($manifestPath, json_encode([
            '/css/app.css' => '/css/app.css?id=123',
            '/js/app.js' => '/js/app.js?id=456',
        ], JSON_THROW_ON_ERROR));

        // Act
        if (! function_exists('Aqualuxe\\Inc\\mix')) {
            require_once $themeRoot . '/inc/enqueue.php';
        }
        if (! defined('AQUALUXE_DIR')) { define('AQUALUXE_DIR', rtrim($themeRoot, '/\\') . '/'); }
        if (! defined('AQUALUXE_URI')) { define('AQUALUXE_URI', '/'); }
        $uri = mix('/css/app.css');

        // Assert
        $this->assertIsString($uri);
        $this->assertStringContainsString('id=123', $uri);

        // Cleanup
        @unlink($manifestPath);
    }

    public function testMixFallsBackWhenManifestMissing(): void
    {
        $themeRoot = dirname(dirname(__DIR__));
        $manifestPath = $themeRoot . '/assets/dist/mix-manifest.json';
        if (file_exists($manifestPath)) {
            @unlink($manifestPath);
        }
        if (! function_exists('Aqualuxe\\Inc\\mix')) {
            require_once $themeRoot . '/inc/enqueue.php';
        }
        if (! defined('AQUALUXE_DIR')) { define('AQUALUXE_DIR', rtrim($themeRoot, '/\\') . '/'); }
        if (! defined('AQUALUXE_URI')) { define('AQUALUXE_URI', '/'); }
        $uri = mix('/css/app.css');
        $this->assertSame('/assets/dist/css/app.css', $uri);
    }
}
