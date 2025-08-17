<?php
/**
 * Internationalization setup for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Internationalization Class
 */
class AquaLuxe_I18n {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'load_theme_textdomain' ) );
	}

	/**
	 * Load theme text domain
	 */
	public function load_theme_textdomain() {
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );
	}

	/**
	 * Get translatable string with context
	 *
	 * @param string $text String to translate.
	 * @param string $context Context information for translators.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_string_with_context( $text, $context, $domain = 'aqualuxe' ) {
		return _x( $text, $context, $domain );
	}

	/**
	 * Get escaped translatable string
	 *
	 * @param string $text String to translate.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_string( $text, $domain = 'aqualuxe' ) {
		return esc_html__( $text, $domain );
	}

	/**
	 * Get escaped translatable string with context
	 *
	 * @param string $text String to translate.
	 * @param string $context Context information for translators.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_string_with_context( $text, $context, $domain = 'aqualuxe' ) {
		return esc_html_x( $text, $context, $domain );
	}

	/**
	 * Get escaped attribute string
	 *
	 * @param string $text String to translate.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_attribute( $text, $domain = 'aqualuxe' ) {
		return esc_attr__( $text, $domain );
	}

	/**
	 * Get escaped attribute string with context
	 *
	 * @param string $text String to translate.
	 * @param string $context Context information for translators.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_attribute_with_context( $text, $context, $domain = 'aqualuxe' ) {
		return esc_attr_x( $text, $context, $domain );
	}

	/**
	 * Get translatable string with number
	 *
	 * @param string $single Singular text.
	 * @param string $plural Plural text.
	 * @param int    $number Number to determine form.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_string_with_number( $single, $plural, $number, $domain = 'aqualuxe' ) {
		return sprintf( _n( $single, $plural, $number, $domain ), $number );
	}

	/**
	 * Get escaped translatable string with number
	 *
	 * @param string $single Singular text.
	 * @param string $plural Plural text.
	 * @param int    $number Number to determine form.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_string_with_number( $single, $plural, $number, $domain = 'aqualuxe' ) {
		return esc_html( sprintf( _n( $single, $plural, $number, $domain ), $number ) );
	}

	/**
	 * Get translatable string with number and context
	 *
	 * @param string $single Singular text.
	 * @param string $plural Plural text.
	 * @param int    $number Number to determine form.
	 * @param string $context Context information for translators.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_string_with_number_and_context( $single, $plural, $number, $context, $domain = 'aqualuxe' ) {
		return sprintf( _nx( $single, $plural, $number, $context, $domain ), $number );
	}

	/**
	 * Get escaped translatable string with number and context
	 *
	 * @param string $single Singular text.
	 * @param string $plural Plural text.
	 * @param int    $number Number to determine form.
	 * @param string $context Context information for translators.
	 * @param string $domain Text domain.
	 * @return string
	 */
	public static function get_escaped_string_with_number_and_context( $single, $plural, $number, $context, $domain = 'aqualuxe' ) {
		return esc_html( sprintf( _nx( $single, $plural, $number, $context, $domain ), $number ) );
	}

	/**
	 * Get date in localized format
	 *
	 * @param string $format Date format.
	 * @param int    $timestamp Optional. Timestamp.
	 * @return string
	 */
	public static function get_localized_date( $format = '', $timestamp = null ) {
		if ( empty( $format ) ) {
			$format = get_option( 'date_format' );
		}

		if ( null === $timestamp ) {
			$timestamp = current_time( 'timestamp' );
		}

		return date_i18n( $format, $timestamp );
	}

	/**
	 * Get time in localized format
	 *
	 * @param string $format Time format.
	 * @param int    $timestamp Optional. Timestamp.
	 * @return string
	 */
	public static function get_localized_time( $format = '', $timestamp = null ) {
		if ( empty( $format ) ) {
			$format = get_option( 'time_format' );
		}

		if ( null === $timestamp ) {
			$timestamp = current_time( 'timestamp' );
		}

		return date_i18n( $format, $timestamp );
	}

	/**
	 * Get date and time in localized format
	 *
	 * @param string $date_format Date format.
	 * @param string $time_format Time format.
	 * @param int    $timestamp Optional. Timestamp.
	 * @return string
	 */
	public static function get_localized_date_time( $date_format = '', $time_format = '', $timestamp = null ) {
		if ( empty( $date_format ) ) {
			$date_format = get_option( 'date_format' );
		}

		if ( empty( $time_format ) ) {
			$time_format = get_option( 'time_format' );
		}

		if ( null === $timestamp ) {
			$timestamp = current_time( 'timestamp' );
		}

		return date_i18n( $date_format . ' ' . $time_format, $timestamp );
	}

	/**
	 * Get currency symbol
	 *
	 * @param string $currency Currency code.
	 * @return string
	 */
	public static function get_currency_symbol( $currency = '' ) {
		if ( empty( $currency ) && function_exists( 'get_woocommerce_currency' ) ) {
			$currency = get_woocommerce_currency();
		}

		$symbols = array(
			'AED' => 'د.إ',
			'AFN' => '؋',
			'ALL' => 'L',
			'AMD' => '֏',
			'ANG' => 'ƒ',
			'AOA' => 'Kz',
			'ARS' => '$',
			'AUD' => '$',
			'AWG' => 'ƒ',
			'AZN' => '₼',
			'BAM' => 'KM',
			'BBD' => '$',
			'BDT' => '৳',
			'BGN' => 'лв',
			'BHD' => '.د.ب',
			'BIF' => 'Fr',
			'BMD' => '$',
			'BND' => '$',
			'BOB' => 'Bs.',
			'BRL' => 'R$',
			'BSD' => '$',
			'BTC' => '₿',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYN' => 'Br',
			'BYR' => 'Br',
			'BZD' => '$',
			'CAD' => '$',
			'CDF' => 'Fr',
			'CHF' => 'Fr',
			'CLP' => '$',
			'CNY' => '¥',
			'COP' => '$',
			'CRC' => '₡',
			'CUC' => '$',
			'CUP' => '$',
			'CVE' => '$',
			'CZK' => 'Kč',
			'DJF' => 'Fr',
			'DKK' => 'kr',
			'DOP' => 'RD$',
			'DZD' => 'د.ج',
			'EGP' => 'E£',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '€',
			'FJD' => '$',
			'FKP' => '£',
			'GBP' => '£',
			'GEL' => '₾',
			'GGP' => '£',
			'GHS' => '₵',
			'GIP' => '£',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '$',
			'HKD' => '$',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => 'Ft',
			'IDR' => 'Rp',
			'ILS' => '₪',
			'IMP' => '£',
			'INR' => '₹',
			'IQD' => 'ع.د',
			'IRR' => '﷼',
			'ISK' => 'kr',
			'JEP' => '£',
			'JMD' => '$',
			'JOD' => 'د.ا',
			'JPY' => '¥',
			'KES' => 'Sh',
			'KGS' => 'с',
			'KHR' => '៛',
			'KMF' => 'Fr',
			'KPW' => '₩',
			'KRW' => '₩',
			'KWD' => 'د.ك',
			'KYD' => '$',
			'KZT' => '₸',
			'LAK' => '₭',
			'LBP' => 'ل.ل',
			'LKR' => 'Rs',
			'LRD' => '$',
			'LSL' => 'L',
			'LYD' => 'ل.د',
			'MAD' => 'د.م.',
			'MDL' => 'L',
			'MGA' => 'Ar',
			'MKD' => 'ден',
			'MMK' => 'Ks',
			'MNT' => '₮',
			'MOP' => 'P',
			'MRO' => 'UM',
			'MUR' => '₨',
			'MVR' => '.ރ',
			'MWK' => 'MK',
			'MXN' => '$',
			'MYR' => 'RM',
			'MZN' => 'MT',
			'NAD' => '$',
			'NGN' => '₦',
			'NIO' => 'C$',
			'NOK' => 'kr',
			'NPR' => '₨',
			'NZD' => '$',
			'OMR' => 'ر.ع.',
			'PAB' => 'B/.',
			'PEN' => 'S/.',
			'PGK' => 'K',
			'PHP' => '₱',
			'PKR' => '₨',
			'PLN' => 'zł',
			'PRB' => 'р.',
			'PYG' => '₲',
			'QAR' => 'ر.ق',
			'RON' => 'lei',
			'RSD' => 'дин',
			'RUB' => '₽',
			'RWF' => 'Fr',
			'SAR' => 'ر.س',
			'SBD' => '$',
			'SCR' => '₨',
			'SDG' => 'ج.س.',
			'SEK' => 'kr',
			'SGD' => '$',
			'SHP' => '£',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '$',
			'SSP' => '£',
			'STD' => 'Db',
			'SYP' => 'ل.س',
			'SZL' => 'L',
			'THB' => '฿',
			'TJS' => 'ЅМ',
			'TMT' => 'm',
			'TND' => 'د.ت',
			'TOP' => 'T$',
			'TRY' => '₺',
			'TTD' => '$',
			'TVD' => '$',
			'TWD' => 'NT$',
			'TZS' => 'Sh',
			'UAH' => '₴',
			'UGX' => 'Sh',
			'USD' => '$',
			'UYU' => '$',
			'UZS' => 'so\'m',
			'VEF' => 'Bs',
			'VND' => '₫',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'Fr',
			'XCD' => '$',
			'XOF' => 'Fr',
			'XPF' => 'Fr',
			'YER' => '﷼',
			'ZAR' => 'R',
			'ZMW' => 'ZK',
		);

		return isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
	}

	/**
	 * Format price with currency symbol
	 *
	 * @param float  $price Price.
	 * @param string $currency Currency code.
	 * @return string
	 */
	public static function format_price( $price, $currency = '' ) {
		if ( function_exists( 'wc_price' ) ) {
			return wc_price( $price );
		}

		$currency_symbol = self::get_currency_symbol( $currency );
		$formatted_price = number_format_i18n( $price, 2 );

		return $currency_symbol . $formatted_price;
	}

	/**
	 * Get RTL status
	 *
	 * @return bool
	 */
	public static function is_rtl() {
		return is_rtl();
	}

	/**
	 * Get current locale
	 *
	 * @return string
	 */
	public static function get_locale() {
		return get_locale();
	}

	/**
	 * Get language attributes for HTML tag
	 *
	 * @return string
	 */
	public static function get_language_attributes() {
		return get_language_attributes();
	}
}

// Initialize internationalization.
new AquaLuxe_I18n();