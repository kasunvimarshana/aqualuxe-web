<?php
use PHPUnit\Framework\TestCase;
use AquaLuxe\Core\Modules;

class ModulesTest extends TestCase
{
    /**
     * @dataProvider studlyCaseProvider
     */
    public function testStudlyConversion(string $slug, string $expected): void
    {
        // Use reflection to test the private static method
        $reflection = new \ReflectionClass(Modules::class);
        $method = $reflection->getMethod('studly');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke(null, $slug));
    }

    public static function studlyCaseProvider(): array
    {
        return [
            'simple' => ['example', 'Example'],
            'kebab-case' => ['dark-mode', 'DarkMode'],
            'snake_case' => ['wc_filters', 'WcFilters'],
            'already-studly' => ['Seo', 'Seo'],
            'uppercase' => ['SEO', 'Seo'],
            'with-numbers' => ['version-1-2', 'Version12'],
        ];
    }
}
