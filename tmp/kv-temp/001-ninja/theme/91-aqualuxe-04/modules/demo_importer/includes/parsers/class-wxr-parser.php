<?php
/**
 * WordPress eXtended RSS file parser implementations
 *
 * @package WordPress
 * @subpackage Importer
 */

/**
 * WordPress Importer class for managing parsing of WXR files.
 */
class WXR_Parser {
	public function parse( $file ) {
		// Allow forcing a specific parser via PREFERRED_WXR_PARSER: simplexml|xml|regex
		$parser = $this->get_parser( $file );
		if ( is_wp_error( $parser ) ) {
			return $parser;
		}

		$result = $parser->parse( $file );

		// If SimpleXML succeeds or this is an invalid WXR file then return the results
		if ( ! is_wp_error( $result ) || 'SimpleXML_parse_error' !== $result->get_error_code() ) {
			return $result;
		}

		// We have a malformed XML file, so display the error and fallthrough to regex
		if ( isset( $result ) && defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
			echo '<pre>';
			if ( 'SimpleXML_parse_error' === $result->get_error_code() ) {
				foreach ( $result->get_error_data() as $error ) {
					echo esc_html( $error->message ) . "\n";
				}
			} elseif ( 'XML_parse_error' === $result->get_error_code() ) {
				$error = $result->get_error_data();
				echo esc_html( $error[2] );
			}
			echo '</pre>';
			echo '<p><strong>' . esc_html__( 'There was an error when reading this WXR file', 'wordpress-importer' ) . '</strong><br />';
			echo esc_html__( 'Details are shown above. The importer will now try again with a different parser...', 'wordpress-importer' ) . '</p>';
		}

		// use regular expressions if nothing else available or this is bad XML
		$parser = new WXR_Parser_Regex();
		return $parser->parse( $file );
	}

	private function get_parser( $file ) {
		$preferred_parser = defined( 'PREFERRED_WXR_PARSER' ) ? constant( 'PREFERRED_WXR_PARSER' ) : null;

		if ( $preferred_parser ) {
			$available_parsers = array(
				'simplexml' => 'WXR_Parser_SimpleXML',
				'xml'       => 'WXR_Parser_XML',
				'regex'     => 'WXR_Parser_Regex',
			);
			if ( isset( $available_parsers[ $preferred_parser ] ) ) {
				return new $available_parsers[ $preferred_parser ]();
			}

			return new WP_Error( 'invalid_parser', sprintf( __( 'Invalid parser specified: %s', 'wordpress-importer' ), $preferred_parser ) );
		}

		if ( extension_loaded( 'simplexml' ) ) {
			return new WXR_Parser_SimpleXML();
		}

		if ( extension_loaded( 'xml' ) ) {
			return new WXR_Parser_XML();
		}

		return new WXR_Parser_Regex();
	}
}
