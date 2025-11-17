<?php
use PHPUnit\Framework\TestCase;

final class AssetsTest extends TestCase
{
    public function test_mix_returns_path_when_manifest_missing(): void
    {
        if (!defined('AQUALUXE_DIR')) {
            define('AQUALUXE_DIR', __DIR__ . '/../');
        }
        if (!defined('AQUALUXE_ASSETS_URI')) {
            define('AQUALUXE_ASSETS_URI', 'https://example.com/assets/dist/');
        }
        require_once dirname(__DIR__) . '/../inc/assets.php';
        $url = aqualuxe_mix('/css/app.css');
        $this->assertStringContainsString('/css/app.css', $url);
    }
}
