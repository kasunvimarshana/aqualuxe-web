<?php
use PHPUnit\Framework\TestCase;

/**
 * Tests to ensure language/currency switchers are owned by modules and not by inc/shortcodes.php.
 */
final class ShortcodesOwnershipTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset shortcode registry for tests
        $GLOBALS['__aqlx_shortcodes'] = [];

        if (!function_exists('add_shortcode')) {
            function add_shortcode($tag, $callback) {
                $GLOBALS['__aqlx_shortcodes'][$tag] = $callback;
            }
        }
        if (!function_exists('shortcode_exists')) {
            function shortcode_exists($tag) {
                return isset($GLOBALS['__aqlx_shortcodes'][$tag]);
            }
        }
    }

    public function testShortcodesNotRegisteredByInc(): void
    {
        // Include theme shortcodes file — should NOT register language/currency switchers
        require_once AQUALUXE_INC . '/shortcodes.php';
    $this->assertSame(false, shortcode_exists('aqualuxe_language_switcher'));
    $this->assertSame(false, shortcode_exists('aqualuxe_currency_switcher'));
    }

    public function testLanguageShortcodeRegisteredByModule(): void
    {
        // Ensure not present
    $this->assertSame(false, shortcode_exists('aqualuxe_language_switcher'));
        // Include module file
        require AQUALUXE_MODULES . '/multilingual/module.php';
    $this->assertSame(true, shortcode_exists('aqualuxe_language_switcher'));
    }

    public function testCurrencyShortcodeRegisteredByModule(): void
    {
        // Ensure not present
    $this->assertSame(false, shortcode_exists('aqualuxe_currency_switcher'));
        // Include module file (registration is guarded)
        require AQUALUXE_MODULES . '/multicurrency/module.php';
    $this->assertSame(true, shortcode_exists('aqualuxe_currency_switcher'));
    }
}
