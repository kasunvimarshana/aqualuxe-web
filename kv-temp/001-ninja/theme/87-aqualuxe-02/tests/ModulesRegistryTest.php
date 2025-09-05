<?php
use PHPUnit\Framework\TestCase;

final class ModulesRegistryTest extends TestCase
{
    public function testRegistryDiscoversModulesFolder(): void
    {
        $mods = \AquaLuxe\Core\Modules::registry();
        $this->assertTrue(is_array($mods));
        // Expect at least one module present in repo
        $this->assertTrue(isset($mods['permissions']));
        $this->assertTrue(isset($mods['seo']));
        foreach ($mods as $slug => $meta) {
            $this->assertTrue(isset($meta['slug']));
            $this->assertSame($slug, $meta['slug']);
        }
    }
}
