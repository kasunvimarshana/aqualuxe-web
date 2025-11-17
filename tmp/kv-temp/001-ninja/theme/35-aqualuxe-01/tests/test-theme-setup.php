<?php
/**
 * Class TestThemeSetup
 *
 * @package AquaLuxe
 */

/**
 * Theme setup test case.
 */
class TestThemeSetup extends WP_UnitTestCase {

    /**
     * Test theme support features.
     */
    public function test_theme_support() {
        // Test if theme supports custom logo.
        $this->assertTrue( current_theme_supports( 'custom-logo' ) );

        // Test if theme supports post thumbnails.
        $this->assertTrue( current_theme_supports( 'post-thumbnails' ) );

        // Test if theme supports title tag.
        $this->assertTrue( current_theme_supports( 'title-tag' ) );

        // Test if theme supports HTML5.
        $this->assertTrue( current_theme_supports( 'html5' ) );

        // Test if theme supports automatic feed links.
        $this->assertTrue( current_theme_supports( 'automatic-feed-links' ) );

        // Test if theme supports custom background.
        $this->assertTrue( current_theme_supports( 'custom-background' ) );

        // Test if theme supports align wide.
        $this->assertTrue( current_theme_supports( 'align-wide' ) );

        // Test if theme supports responsive embeds.
        $this->assertTrue( current_theme_supports( 'responsive-embeds' ) );

        // Test if theme supports editor styles.
        $this->assertTrue( current_theme_supports( 'editor-styles' ) );
    }

    /**
     * Test navigation menus.
     */
    public function test_nav_menus() {
        // Get registered nav menus.
        $nav_menus = get_registered_nav_menus();

        // Test if primary menu is registered.
        $this->assertArrayHasKey( 'primary', $nav_menus );

        // Test if footer menu is registered.
        $this->assertArrayHasKey( 'footer', $nav_menus );

        // Test if social menu is registered.
        $this->assertArrayHasKey( 'social', $nav_menus );
    }

    /**
     * Test image sizes.
     */
    public function test_image_sizes() {
        // Test if featured image size is registered.
        $this->assertTrue( has_image_size( 'aqualuxe-featured' ) );

        // Test if medium image size is registered.
        $this->assertTrue( has_image_size( 'aqualuxe-medium' ) );

        // Test if small image size is registered.
        $this->assertTrue( has_image_size( 'aqualuxe-small' ) );
    }

    /**
     * Test widget areas.
     */
    public function test_widget_areas() {
        global $wp_registered_sidebars;

        // Test if sidebar is registered.
        $this->assertArrayHasKey( 'sidebar-1', $wp_registered_sidebars );

        // Test if footer widget areas are registered.
        $this->assertArrayHasKey( 'footer-1', $wp_registered_sidebars );
        $this->assertArrayHasKey( 'footer-2', $wp_registered_sidebars );
        $this->assertArrayHasKey( 'footer-3', $wp_registered_sidebars );
        $this->assertArrayHasKey( 'footer-4', $wp_registered_sidebars );
    }

    /**
     * Test content width.
     */
    public function test_content_width() {
        global $content_width;

        // Test if content width is set.
        $this->assertEquals( 1200, $content_width );
    }
}