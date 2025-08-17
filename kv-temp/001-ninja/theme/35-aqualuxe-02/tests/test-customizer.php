<?php
/**
 * Class TestCustomizer
 *
 * @package AquaLuxe
 */

/**
 * Customizer test case.
 */
class TestCustomizer extends WP_UnitTestCase {

    /**
     * Test customizer sections.
     */
    public function test_customizer_sections() {
        // Create a mock WP_Customize_Manager.
        $wp_customize = new WP_Customize_Manager();

        // Call the customizer registration function.
        aqualuxe_customize_register( $wp_customize );

        // Test if theme options section is registered.
        $this->assertInstanceOf( 'WP_Customize_Section', $wp_customize->get_section( 'aqualuxe_theme_options' ) );

        // Test if header options section is registered.
        $this->assertInstanceOf( 'WP_Customize_Section', $wp_customize->get_section( 'aqualuxe_header_options' ) );

        // Test if footer options section is registered.
        $this->assertInstanceOf( 'WP_Customize_Section', $wp_customize->get_section( 'aqualuxe_footer_options' ) );

        // Test if blog options section is registered.
        $this->assertInstanceOf( 'WP_Customize_Section', $wp_customize->get_section( 'aqualuxe_blog_options' ) );

        // Test if WooCommerce options section is registered.
        $this->assertInstanceOf( 'WP_Customize_Section', $wp_customize->get_section( 'aqualuxe_woocommerce_options' ) );
    }

    /**
     * Test customizer settings.
     */
    public function test_customizer_settings() {
        // Create a mock WP_Customize_Manager.
        $wp_customize = new WP_Customize_Manager();

        // Call the customizer registration function.
        aqualuxe_customize_register( $wp_customize );

        // Test if primary color setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_primary_color' ) );

        // Test if secondary color setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_secondary_color' ) );

        // Test if header layout setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_header_layout' ) );

        // Test if show search setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_show_search' ) );

        // Test if sticky header setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_sticky_header' ) );

        // Test if footer widget columns setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_footer_widget_columns' ) );

        // Test if footer copyright setting is registered.
        $this->assertInstanceOf( 'WP_Customize_Setting', $wp_customize->get_setting( 'aqualuxe_footer_copyright' ) );
    }

    /**
     * Test customizer controls.
     */
    public function test_customizer_controls() {
        // Create a mock WP_Customize_Manager.
        $wp_customize = new WP_Customize_Manager();

        // Call the customizer registration function.
        aqualuxe_customize_register( $wp_customize );

        // Test if primary color control is registered.
        $this->assertInstanceOf( 'WP_Customize_Color_Control', $wp_customize->get_control( 'aqualuxe_primary_color' ) );

        // Test if secondary color control is registered.
        $this->assertInstanceOf( 'WP_Customize_Color_Control', $wp_customize->get_control( 'aqualuxe_secondary_color' ) );

        // Test if header layout control is registered.
        $this->assertInstanceOf( 'WP_Customize_Control', $wp_customize->get_control( 'aqualuxe_header_layout' ) );

        // Test if show search control is registered.
        $this->assertInstanceOf( 'WP_Customize_Control', $wp_customize->get_control( 'aqualuxe_show_search' ) );

        // Test if sticky header control is registered.
        $this->assertInstanceOf( 'WP_Customize_Control', $wp_customize->get_control( 'aqualuxe_sticky_header' ) );

        // Test if footer widget columns control is registered.
        $this->assertInstanceOf( 'WP_Customize_Control', $wp_customize->get_control( 'aqualuxe_footer_widget_columns' ) );

        // Test if footer copyright control is registered.
        $this->assertInstanceOf( 'WP_Customize_Control', $wp_customize->get_control( 'aqualuxe_footer_copyright' ) );
    }

    /**
     * Test sanitization functions.
     */
    public function test_sanitization_functions() {
        // Test checkbox sanitization.
        $this->assertTrue( aqualuxe_sanitize_checkbox( true ) );
        $this->assertFalse( aqualuxe_sanitize_checkbox( false ) );
        $this->assertFalse( aqualuxe_sanitize_checkbox( 'not a boolean' ) );

        // Test select sanitization.
        $setting = $this->getMockBuilder( 'WP_Customize_Setting' )
            ->disableOriginalConstructor()
            ->getMock();

        $setting->expects( $this->any() )
            ->method( 'manager' )
            ->willReturn( (object) array(
                'get_control' => function() {
                    return (object) array(
                        'choices' => array(
                            'option1' => 'Option 1',
                            'option2' => 'Option 2',
                            'option3' => 'Option 3',
                        ),
                    );
                },
            ) );

        $setting->default = 'option1';

        $this->assertEquals( 'option2', aqualuxe_sanitize_select( 'option2', $setting ) );
        $this->assertEquals( 'option1', aqualuxe_sanitize_select( 'invalid', $setting ) );

        // Test number sanitization.
        $setting = $this->getMockBuilder( 'WP_Customize_Setting' )
            ->disableOriginalConstructor()
            ->getMock();

        $setting->expects( $this->any() )
            ->method( 'manager' )
            ->willReturn( (object) array(
                'get_control' => function() {
                    return (object) array(
                        'input_attrs' => array(
                            'min' => 0,
                            'max' => 100,
                            'step' => 1,
                        ),
                    );
                },
            ) );

        $setting->default = 50;

        $this->assertEquals( 75, aqualuxe_sanitize_number( 75, $setting ) );
        $this->assertEquals( 50, aqualuxe_sanitize_number( 150, $setting ) );
        $this->assertEquals( 50, aqualuxe_sanitize_number( -10, $setting ) );
    }
}