<?php
/**
 * Social Media Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Sortable_Control;
use AquaLuxe\Customizer\Controls\Color_Alpha_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Social Media Customizer Section Class
 */
class Social_Media {

	/**
	 * Social networks
	 *
	 * @var array
	 */
	private $social_networks = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define social networks.
		$this->social_networks = array(
			'facebook'   => array(
				'label' => esc_html__( 'Facebook', 'aqualuxe' ),
				'icon'  => 'fab fa-facebook-f',
			),
			'twitter'    => array(
				'label' => esc_html__( 'Twitter', 'aqualuxe' ),
				'icon'  => 'fab fa-twitter',
			),
			'instagram'  => array(
				'label' => esc_html__( 'Instagram', 'aqualuxe' ),
				'icon'  => 'fab fa-instagram',
			),
			'linkedin'   => array(
				'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
				'icon'  => 'fab fa-linkedin-in',
			),
			'pinterest'  => array(
				'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
				'icon'  => 'fab fa-pinterest-p',
			),
			'youtube'    => array(
				'label' => esc_html__( 'YouTube', 'aqualuxe' ),
				'icon'  => 'fab fa-youtube',
			),
			'tiktok'     => array(
				'label' => esc_html__( 'TikTok', 'aqualuxe' ),
				'icon'  => 'fab fa-tiktok',
			),
			'snapchat'   => array(
				'label' => esc_html__( 'Snapchat', 'aqualuxe' ),
				'icon'  => 'fab fa-snapchat-ghost',
			),
			'tumblr'     => array(
				'label' => esc_html__( 'Tumblr', 'aqualuxe' ),
				'icon'  => 'fab fa-tumblr',
			),
			'reddit'     => array(
				'label' => esc_html__( 'Reddit', 'aqualuxe' ),
				'icon'  => 'fab fa-reddit-alien',
			),
			'vimeo'      => array(
				'label' => esc_html__( 'Vimeo', 'aqualuxe' ),
				'icon'  => 'fab fa-vimeo-v',
			),
			'dribbble'   => array(
				'label' => esc_html__( 'Dribbble', 'aqualuxe' ),
				'icon'  => 'fab fa-dribbble',
			),
			'behance'    => array(
				'label' => esc_html__( 'Behance', 'aqualuxe' ),
				'icon'  => 'fab fa-behance',
			),
			'github'     => array(
				'label' => esc_html__( 'GitHub', 'aqualuxe' ),
				'icon'  => 'fab fa-github',
			),
			'whatsapp'   => array(
				'label' => esc_html__( 'WhatsApp', 'aqualuxe' ),
				'icon'  => 'fab fa-whatsapp',
			),
			'telegram'   => array(
				'label' => esc_html__( 'Telegram', 'aqualuxe' ),
				'icon'  => 'fab fa-telegram-plane',
			),
			'discord'    => array(
				'label' => esc_html__( 'Discord', 'aqualuxe' ),
				'icon'  => 'fab fa-discord',
			),
			'twitch'     => array(
				'label' => esc_html__( 'Twitch', 'aqualuxe' ),
				'icon'  => 'fab fa-twitch',
			),
			'spotify'    => array(
				'label' => esc_html__( 'Spotify', 'aqualuxe' ),
				'icon'  => 'fab fa-spotify',
			),
			'soundcloud' => array(
				'label' => esc_html__( 'SoundCloud', 'aqualuxe' ),
				'icon'  => 'fab fa-soundcloud',
			),
			'email'      => array(
				'label' => esc_html__( 'Email', 'aqualuxe' ),
				'icon'  => 'fas fa-envelope',
			),
			'phone'      => array(
				'label' => esc_html__( 'Phone', 'aqualuxe' ),
				'icon'  => 'fas fa-phone',
			),
		);

		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Add Social Media section.
		$wp_customize->add_section(
			'aqualuxe_social_media',
			array(
				'title'    => esc_html__( 'Social Media', 'aqualuxe' ),
				'priority' => 100,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Social Media Profiles.
		$wp_customize->add_setting(
			'aqualuxe_social_media_profiles_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_social_media_profiles_heading',
				array(
					'label'    => esc_html__( 'Social Media Profiles', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => 10,
				)
			)
		);

		// Add settings for each social network.
		$priority = 20;
		foreach ( $this->social_networks as $network => $data ) {
			$wp_customize->add_setting(
				'aqualuxe_social_' . $network,
				array(
					'default'           => '',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'aqualuxe_social_' . $network,
				array(
					'label'       => $data['label'],
					'description' => sprintf(
						/* translators: %s: Social network name */
						esc_html__( 'Enter your %s profile URL', 'aqualuxe' ),
						$data['label']
					),
					'section'     => 'aqualuxe_social_media',
					'type'        => 'url',
					'priority'    => $priority,
					'input_attrs' => array(
						'placeholder' => sprintf(
							/* translators: %s: Social network name */
							esc_html__( 'https://example.com/%s', 'aqualuxe' ),
							strtolower( $network )
						),
					),
				)
			);

			$priority += 10;
		}

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_social_media_display_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_social_media_display_divider',
				array(
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Display Settings.
		$wp_customize->add_setting(
			'aqualuxe_social_media_display_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_social_media_display_heading',
				array(
					'label'    => esc_html__( 'Display Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Show in Header.
		$wp_customize->add_setting(
			'aqualuxe_social_show_header',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_social_show_header',
				array(
					'label'       => esc_html__( 'Show in Header', 'aqualuxe' ),
					'description' => esc_html__( 'Display social media icons in the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
				)
			)
		);

		$priority += 10;

		// Show in Footer.
		$wp_customize->add_setting(
			'aqualuxe_social_show_footer',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_social_show_footer',
				array(
					'label'       => esc_html__( 'Show in Footer', 'aqualuxe' ),
					'description' => esc_html__( 'Display social media icons in the footer.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
				)
			)
		);

		$priority += 10;

		// Social Networks Order.
		$social_choices = array();
		foreach ( $this->social_networks as $network => $data ) {
			$social_choices[ $network ] = $data['label'];
		}

		$wp_customize->add_setting(
			'aqualuxe_social_networks_order',
			array(
				'default'           => array_keys( $this->social_networks ),
				'sanitize_callback' => array( $this, 'sanitize_multi_choices' ),
			)
		);

		$wp_customize->add_control(
			new Sortable_Control(
				$wp_customize,
				'aqualuxe_social_networks_order',
				array(
					'label'    => esc_html__( 'Social Networks Order', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
					'choices'  => $social_choices,
				)
			)
		);

		$priority += 10;

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_social_media_style_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_social_media_style_divider',
				array(
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Style Settings.
		$wp_customize->add_setting(
			'aqualuxe_social_media_style_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_social_media_style_heading',
				array(
					'label'    => esc_html__( 'Style Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Icon Style.
		$wp_customize->add_setting(
			'aqualuxe_social_icon_style',
			array(
				'default'           => 'rounded',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_icon_style',
			array(
				'label'    => esc_html__( 'Icon Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'simple'  => esc_html__( 'Simple', 'aqualuxe' ),
					'rounded' => esc_html__( 'Rounded', 'aqualuxe' ),
					'square'  => esc_html__( 'Square', 'aqualuxe' ),
					'circle'  => esc_html__( 'Circle', 'aqualuxe' ),
				),
			)
		);

		$priority += 10;

		// Icon Size.
		$wp_customize->add_setting(
			'aqualuxe_social_icon_size',
			array(
				'default'           => 'medium',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_icon_size',
			array(
				'label'    => esc_html__( 'Icon Size', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'small'  => esc_html__( 'Small', 'aqualuxe' ),
					'medium' => esc_html__( 'Medium', 'aqualuxe' ),
					'large'  => esc_html__( 'Large', 'aqualuxe' ),
				),
			)
		);

		$priority += 10;

		// Icon Color.
		$wp_customize->add_setting(
			'aqualuxe_social_icon_color',
			array(
				'default'           => 'brand',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_icon_color',
			array(
				'label'    => esc_html__( 'Icon Color', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'brand'   => esc_html__( 'Brand Colors', 'aqualuxe' ),
					'custom'  => esc_html__( 'Custom Colors', 'aqualuxe' ),
					'monochrome' => esc_html__( 'Monochrome', 'aqualuxe' ),
				),
			)
		);

		$priority += 10;

		// Custom Icon Color.
		$wp_customize->add_setting(
			'aqualuxe_social_custom_color',
			array(
				'default'           => '#0073aa',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_custom_color' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_social_custom_color',
				array(
					'label'    => esc_html__( 'Custom Icon Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
					'active_callback' => array( $this, 'is_custom_color' ),
				)
			)
		);

		$priority += 10;

		// Custom Icon Background.
		$wp_customize->add_setting(
			'aqualuxe_social_custom_bg',
			array(
				'default'           => '#f5f5f5',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_custom_color_with_bg' ),
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_social_custom_bg',
				array(
					'label'    => esc_html__( 'Custom Icon Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
					'active_callback' => array( $this, 'is_custom_color_with_bg' ),
				)
			)
		);

		$priority += 10;

		// Hover Effect.
		$wp_customize->add_setting(
			'aqualuxe_social_hover_effect',
			array(
				'default'           => 'fade',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_social_hover_effect',
			array(
				'label'    => esc_html__( 'Hover Effect', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'none'   => esc_html__( 'None', 'aqualuxe' ),
					'fade'   => esc_html__( 'Fade', 'aqualuxe' ),
					'scale'  => esc_html__( 'Scale', 'aqualuxe' ),
					'bounce' => esc_html__( 'Bounce', 'aqualuxe' ),
					'rotate' => esc_html__( 'Rotate', 'aqualuxe' ),
				),
			)
		);

		$priority += 10;

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_social_media_sharing_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_social_media_sharing_divider',
				array(
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Social Sharing.
		$wp_customize->add_setting(
			'aqualuxe_social_media_sharing_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_social_media_sharing_heading',
				array(
					'label'    => esc_html__( 'Social Sharing', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
				)
			)
		);

		$priority += 10;

		// Enable Social Sharing.
		$wp_customize->add_setting(
			'aqualuxe_enable_social_sharing',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_enable_social_sharing',
				array(
					'label'       => esc_html__( 'Enable Social Sharing', 'aqualuxe' ),
					'description' => esc_html__( 'Display social sharing buttons on posts and pages.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
				)
			)
		);

		$priority += 10;

		// Show on Posts.
		$wp_customize->add_setting(
			'aqualuxe_sharing_on_posts',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_sharing_on_posts',
				array(
					'label'       => esc_html__( 'Show on Posts', 'aqualuxe' ),
					'description' => esc_html__( 'Display social sharing buttons on single posts.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
					'active_callback' => array( $this, 'is_social_sharing_enabled' ),
				)
			)
		);

		$priority += 10;

		// Show on Pages.
		$wp_customize->add_setting(
			'aqualuxe_sharing_on_pages',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_sharing_on_pages',
				array(
					'label'       => esc_html__( 'Show on Pages', 'aqualuxe' ),
					'description' => esc_html__( 'Display social sharing buttons on pages.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
					'active_callback' => array( $this, 'is_social_sharing_enabled' ),
				)
			)
		);

		$priority += 10;

		// Show on Products.
		$wp_customize->add_setting(
			'aqualuxe_sharing_on_products',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_sharing_on_products',
				array(
					'label'       => esc_html__( 'Show on Products', 'aqualuxe' ),
					'description' => esc_html__( 'Display social sharing buttons on WooCommerce products.', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_media',
					'priority'    => $priority,
					'active_callback' => array( $this, 'is_social_sharing_enabled' ),
				)
			)
		);

		$priority += 10;

		// Sharing Position.
		$wp_customize->add_setting(
			'aqualuxe_sharing_position',
			array(
				'default'           => 'after',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sharing_position',
			array(
				'label'    => esc_html__( 'Sharing Position', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'before' => esc_html__( 'Before Content', 'aqualuxe' ),
					'after'  => esc_html__( 'After Content', 'aqualuxe' ),
					'both'   => esc_html__( 'Before and After Content', 'aqualuxe' ),
					'float'  => esc_html__( 'Floating Sidebar', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$priority += 10;

		// Sharing Networks.
		$sharing_networks = array(
			'facebook'  => esc_html__( 'Facebook', 'aqualuxe' ),
			'twitter'   => esc_html__( 'Twitter', 'aqualuxe' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'aqualuxe' ),
			'pinterest' => esc_html__( 'Pinterest', 'aqualuxe' ),
			'reddit'    => esc_html__( 'Reddit', 'aqualuxe' ),
			'tumblr'    => esc_html__( 'Tumblr', 'aqualuxe' ),
			'whatsapp'  => esc_html__( 'WhatsApp', 'aqualuxe' ),
			'telegram'  => esc_html__( 'Telegram', 'aqualuxe' ),
			'email'     => esc_html__( 'Email', 'aqualuxe' ),
		);

		$wp_customize->add_setting(
			'aqualuxe_sharing_networks',
			array(
				'default'           => array( 'facebook', 'twitter', 'linkedin', 'pinterest', 'email' ),
				'sanitize_callback' => array( $this, 'sanitize_multi_choices' ),
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Sortable_Control(
				$wp_customize,
				'aqualuxe_sharing_networks',
				array(
					'label'    => esc_html__( 'Sharing Networks', 'aqualuxe' ),
					'section'  => 'aqualuxe_social_media',
					'priority' => $priority,
					'choices'  => $sharing_networks,
					'active_callback' => array( $this, 'is_social_sharing_enabled' ),
				)
			)
		);

		$priority += 10;

		// Sharing Style.
		$wp_customize->add_setting(
			'aqualuxe_sharing_style',
			array(
				'default'           => 'colored',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_social_sharing_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sharing_style',
			array(
				'label'    => esc_html__( 'Sharing Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_social_media',
				'type'     => 'select',
				'priority' => $priority,
				'choices'  => array(
					'colored'    => esc_html__( 'Colored', 'aqualuxe' ),
					'minimal'    => esc_html__( 'Minimal', 'aqualuxe' ),
					'monochrome' => esc_html__( 'Monochrome', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_social_sharing_enabled' ),
			)
		);
	}

	/**
	 * Check if custom color is selected
	 *
	 * @return bool
	 */
	public function is_custom_color() {
		return 'custom' === get_theme_mod( 'aqualuxe_social_icon_color', 'brand' );
	}

	/**
	 * Check if custom color with background is selected
	 *
	 * @return bool
	 */
	public function is_custom_color_with_bg() {
		$icon_color = get_theme_mod( 'aqualuxe_social_icon_color', 'brand' );
		$icon_style = get_theme_mod( 'aqualuxe_social_icon_style', 'rounded' );

		return 'custom' === $icon_color && 'simple' !== $icon_style;
	}

	/**
	 * Check if social sharing is enabled
	 *
	 * @return bool
	 */
	public function is_social_sharing_enabled() {
		return get_theme_mod( 'aqualuxe_enable_social_sharing', true );
	}

	/**
	 * Sanitize choices
	 *
	 * @param string $input Value to sanitize.
	 * @param object $setting Setting object.
	 * @return string Sanitized value.
	 */
	public function sanitize_choices( $input, $setting ) {
		// Get the list of possible choices.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize multi choices
	 *
	 * @param array $input Value to sanitize.
	 * @return array Sanitized value.
	 */
	public function sanitize_multi_choices( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$valid_input = array();
		foreach ( $input as $value ) {
			if ( ! empty( $value ) ) {
				$valid_input[] = sanitize_text_field( $value );
			}
		}

		return $valid_input;
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get social media settings.
		$icon_style      = get_theme_mod( 'aqualuxe_social_icon_style', 'rounded' );
		$icon_size       = get_theme_mod( 'aqualuxe_social_icon_size', 'medium' );
		$icon_color      = get_theme_mod( 'aqualuxe_social_icon_color', 'brand' );
		$custom_color    = get_theme_mod( 'aqualuxe_social_custom_color', '#0073aa' );
		$custom_bg       = get_theme_mod( 'aqualuxe_social_custom_bg', '#f5f5f5' );
		$hover_effect    = get_theme_mod( 'aqualuxe_social_hover_effect', 'fade' );

		// Generate inline styles.
		$css = '';

		// Icon size.
		$size_px = 16;
		switch ( $icon_size ) {
			case 'small':
				$size_px = 14;
				break;
			case 'medium':
				$size_px = 16;
				break;
			case 'large':
				$size_px = 20;
				break;
		}

		$css .= '.aqualuxe-social-icons a {';
		$css .= 'font-size: ' . absint( $size_px ) . 'px;';
		$css .= 'width: ' . absint( $size_px * 2.5 ) . 'px;';
		$css .= 'height: ' . absint( $size_px * 2.5 ) . 'px;';
		$css .= 'line-height: ' . absint( $size_px * 2.5 ) . 'px;';
		$css .= 'margin: 0 5px;';
		$css .= 'text-align: center;';
		$css .= 'display: inline-block;';
		$css .= 'transition: all 0.3s ease;';
		$css .= '}';

		// Icon style.
		switch ( $icon_style ) {
			case 'simple':
				$css .= '.aqualuxe-social-icons a {';
				$css .= 'background: transparent;';
				$css .= '}';
				break;
			case 'rounded':
				$css .= '.aqualuxe-social-icons a {';
				$css .= 'border-radius: 5px;';
				$css .= '}';
				break;
			case 'square':
				$css .= '.aqualuxe-social-icons a {';
				$css .= 'border-radius: 0;';
				$css .= '}';
				break;
			case 'circle':
				$css .= '.aqualuxe-social-icons a {';
				$css .= 'border-radius: 50%;';
				$css .= '}';
				break;
		}

		// Icon color.
		if ( 'custom' === $icon_color ) {
			$css .= '.aqualuxe-social-icons a {';
			$css .= 'color: ' . esc_attr( $custom_color ) . ';';
			
			if ( 'simple' !== $icon_style ) {
				$css .= 'background-color: ' . esc_attr( $custom_bg ) . ';';
			}
			
			$css .= '}';
			
			$css .= '.aqualuxe-social-icons a:hover {';
			$css .= 'color: ' . esc_attr( $custom_bg ) . ';';
			$css .= 'background-color: ' . esc_attr( $custom_color ) . ';';
			$css .= '}';
		} elseif ( 'monochrome' === $icon_color ) {
			$css .= '.aqualuxe-social-icons a {';
			$css .= 'color: #333333;';
			
			if ( 'simple' !== $icon_style ) {
				$css .= 'background-color: #f5f5f5;';
			}
			
			$css .= '}';
			
			$css .= '.aqualuxe-social-icons a:hover {';
			$css .= 'color: #ffffff;';
			$css .= 'background-color: #333333;';
			$css .= '}';
		} else {
			// Brand colors.
			$brand_colors = array(
				'facebook'   => '#3b5998',
				'twitter'    => '#1da1f2',
				'instagram'  => '#e4405f',
				'linkedin'   => '#0077b5',
				'pinterest'  => '#bd081c',
				'youtube'    => '#ff0000',
				'tiktok'     => '#000000',
				'snapchat'   => '#fffc00',
				'tumblr'     => '#35465c',
				'reddit'     => '#ff4500',
				'vimeo'      => '#1ab7ea',
				'dribbble'   => '#ea4c89',
				'behance'    => '#1769ff',
				'github'     => '#333333',
				'whatsapp'   => '#25d366',
				'telegram'   => '#0088cc',
				'discord'    => '#7289da',
				'twitch'     => '#6441a5',
				'spotify'    => '#1db954',
				'soundcloud' => '#ff8800',
				'email'      => '#333333',
				'phone'      => '#333333',
			);

			foreach ( $brand_colors as $network => $color ) {
				$css .= '.aqualuxe-social-icons a.aqualuxe-social-' . esc_attr( $network ) . ' {';
				
				if ( 'simple' === $icon_style ) {
					$css .= 'color: ' . esc_attr( $color ) . ';';
				} else {
					$css .= 'color: #ffffff;';
					$css .= 'background-color: ' . esc_attr( $color ) . ';';
				}
				
				$css .= '}';
				
				$css .= '.aqualuxe-social-icons a.aqualuxe-social-' . esc_attr( $network ) . ':hover {';
				
				if ( 'simple' === $icon_style ) {
					$css .= 'color: ' . esc_attr( $this->adjust_brightness( $color, -20 ) ) . ';';
				} else {
					$css .= 'background-color: ' . esc_attr( $this->adjust_brightness( $color, -20 ) ) . ';';
				}
				
				$css .= '}';
			}
		}

		// Hover effect.
		switch ( $hover_effect ) {
			case 'fade':
				$css .= '.aqualuxe-social-icons a:hover {';
				$css .= 'opacity: 0.8;';
				$css .= '}';
				break;
			case 'scale':
				$css .= '.aqualuxe-social-icons a:hover {';
				$css .= 'transform: scale(1.2);';
				$css .= '}';
				break;
			case 'bounce':
				$css .= '.aqualuxe-social-icons a:hover {';
				$css .= 'animation: aqualuxe-social-bounce 0.4s ease;';
				$css .= '}';
				$css .= '@keyframes aqualuxe-social-bounce {';
				$css .= '0%, 100% { transform: translateY(0); }';
				$css .= '50% { transform: translateY(-5px); }';
				$css .= '}';
				break;
			case 'rotate':
				$css .= '.aqualuxe-social-icons a:hover {';
				$css .= 'transform: rotate(360deg);';
				$css .= 'transition: all 0.5s ease;';
				$css .= '}';
				break;
		}

		// Social sharing styles.
		if ( get_theme_mod( 'aqualuxe_enable_social_sharing', true ) ) {
			$sharing_style = get_theme_mod( 'aqualuxe_sharing_style', 'colored' );
			
			$css .= '.aqualuxe-social-share {';
			$css .= 'margin: 20px 0;';
			$css .= 'display: flex;';
			$css .= 'flex-wrap: wrap;';
			$css .= 'align-items: center;';
			$css .= '}';
			
			$css .= '.aqualuxe-social-share-title {';
			$css .= 'margin-right: 15px;';
			$css .= 'font-weight: bold;';
			$css .= '}';
			
			$css .= '.aqualuxe-social-share-buttons {';
			$css .= 'display: flex;';
			$css .= 'flex-wrap: wrap;';
			$css .= '}';
			
			$css .= '.aqualuxe-social-share-button {';
			$css .= 'margin: 5px;';
			$css .= 'padding: 8px 15px;';
			$css .= 'border-radius: 3px;';
			$css .= 'display: flex;';
			$css .= 'align-items: center;';
			$css .= 'text-decoration: none;';
			$css .= 'transition: all 0.3s ease;';
			$css .= '}';
			
			$css .= '.aqualuxe-social-share-button i {';
			$css .= 'margin-right: 5px;';
			$css .= '}';
			
			if ( 'colored' === $sharing_style ) {
				$sharing_colors = array(
					'facebook'  => '#3b5998',
					'twitter'   => '#1da1f2',
					'linkedin'  => '#0077b5',
					'pinterest' => '#bd081c',
					'reddit'    => '#ff4500',
					'tumblr'    => '#35465c',
					'whatsapp'  => '#25d366',
					'telegram'  => '#0088cc',
					'email'     => '#333333',
				);
				
				foreach ( $sharing_colors as $network => $color ) {
					$css .= '.aqualuxe-social-share-button.' . esc_attr( $network ) . ' {';
					$css .= 'background-color: ' . esc_attr( $color ) . ';';
					$css .= 'color: #ffffff;';
					$css .= '}';
					
					$css .= '.aqualuxe-social-share-button.' . esc_attr( $network ) . ':hover {';
					$css .= 'background-color: ' . esc_attr( $this->adjust_brightness( $color, -20 ) ) . ';';
					$css .= '}';
				}
			} elseif ( 'minimal' === $sharing_style ) {
				$css .= '.aqualuxe-social-share-button {';
				$css .= 'background-color: transparent;';
				$css .= 'border: 1px solid #ddd;';
				$css .= 'color: #333333;';
				$css .= '}';
				
				$css .= '.aqualuxe-social-share-button:hover {';
				$css .= 'background-color: #f5f5f5;';
				$css .= '}';
			} else { // monochrome
				$css .= '.aqualuxe-social-share-button {';
				$css .= 'background-color: #333333;';
				$css .= 'color: #ffffff;';
				$css .= '}';
				
				$css .= '.aqualuxe-social-share-button:hover {';
				$css .= 'background-color: #555555;';
				$css .= '}';
			}
			
			// Floating share buttons.
			if ( 'float' === get_theme_mod( 'aqualuxe_sharing_position', 'after' ) ) {
				$css .= '.aqualuxe-social-share-floating {';
				$css .= 'position: fixed;';
				$css .= 'left: 20px;';
				$css .= 'top: 50%;';
				$css .= 'transform: translateY(-50%);';
				$css .= 'display: flex;';
				$css .= 'flex-direction: column;';
				$css .= 'z-index: 999;';
				$css .= '}';
				
				$css .= '.aqualuxe-social-share-floating .aqualuxe-social-share-button {';
				$css .= 'margin: 5px 0;';
				$css .= '}';
				
				// Responsive styles.
				$css .= '@media (max-width: 768px) {';
				$css .= '.aqualuxe-social-share-floating {';
				$css .= 'position: static;';
				$css .= 'transform: none;';
				$css .= 'flex-direction: row;';
				$css .= 'justify-content: center;';
				$css .= 'margin: 20px 0;';
				$css .= '}';
				$css .= '}';
			}
		}

		// Add inline style.
		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'aqualuxe-style', $css );
		}
	}

	/**
	 * Adjust color brightness
	 *
	 * @param string $hex_color Hex color code.
	 * @param int    $percent Percentage to adjust.
	 * @return string Adjusted hex color.
	 */
	public function adjust_brightness( $hex_color, $percent ) {
		// Remove # if present.
		$hex_color = ltrim( $hex_color, '#' );

		// Convert to RGB.
		$r = hexdec( substr( $hex_color, 0, 2 ) );
		$g = hexdec( substr( $hex_color, 2, 2 ) );
		$b = hexdec( substr( $hex_color, 4, 2 ) );

		// Adjust brightness.
		$r = max( 0, min( 255, $r + $percent ) );
		$g = max( 0, min( 255, $g + $percent ) );
		$b = max( 0, min( 255, $b + $percent ) );

		// Convert back to hex.
		return '#' . sprintf( '%02x%02x%02x', $r, $g, $b );
	}
}

// Initialize the class.
new Social_Media();