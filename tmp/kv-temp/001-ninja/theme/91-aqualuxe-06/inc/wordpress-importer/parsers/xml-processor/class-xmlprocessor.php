<?php

namespace WordPress\XML;

use WP_HTML_Span;
use WP_HTML_Text_Replacement;

use function WordPress\Encoding\utf8_codepoint_at;

/**
 * XML API: XMLProcessor class
 *
 * Scans through an XML document to find specific tags, then
 * transforms those tags by adding, removing, or updating the
 * values of the XML attributes within that tag (opener).
 *
 * It implements a subset of the XML 1.0 specification (https://www.w3.org/TR/xml/)
 * and supports XML documents with the following characteristics:
 *
 * * XML 1.0
 * * Well-formed
 * * UTF-8 encoded
 * * Not standalone (so can use external entities)
 * * No DTD, DOCTYPE, ATTLIST, ENTITY, or conditional sections (will fail on them)
 *
 * ### Possible future direction for this module
 *
 * XMLProcessor only aims to support XML 1.0, which is mostly well-defined and
 * supported across the web. There are no plans to support XML 1.1 or any other
 * XML-based format.
 *
 * ### Design and limitations
 *
 * The XMLProcessor is designed to linearly scan XML documents and tokenize
 * XML tags and their attributes. It's designed to do this as efficiently as
 * possible without compromising parsing integrity. Therefore it will be
 * slower than some methods of modifying XML, such as those incorporating
 * over-simplified PCRE patterns, but will not introduce the defects and
 * failures that those methods bring in, which lead to broken page renders
 * and often to security vulnerabilities. On the other hand, it will be faster
 * than full-blown XML parsers such as DOMDocument and use considerably
 * less memory. It requires a negligible memory overhead, enough to consider
 * it a zero-overhead system.
 *
 * The performance characteristics are maintained by avoiding tree construction.
 *
 * The XMLProcessor checks the most important aspects of XML integrity as it scans
 * through the document. It verifies that a single root element exists, that there are
 * no unclosed tags, and that each opener tag has a corresponding closer. It also
 * ensures no duplicate attributes exist on a single tag.
 *
 * At the same time, the XMLProcessor also skips expensive validation of XML entities
 * in the document. The processor will initially pass through invalid entity references
 * and only fail when the developer attempts to read their value. If that doesn't happen,
 * the invalid values will be left untouched in the final document.
 *
 * Most operations within the XMLProcessor are designed to minimize the difference
 * between an input and output document for any given change. For example, the
 * `set_attribute` and `remove_attribute` methods preserve whitespace and the attribute
 * ordering within the element definition. An exception to this rule is that all attribute
 * updates store their values as double-quoted strings, meaning that attributes on input with
 * single-quoted or unquoted values will appear in the output with double-quotes.
 *
 * ### Text Encoding
 *
 * The XMLProcessor assumes that the input XML document is encoded with a
 * UTF-8 encoding and will refuse to process documents that declare other encodings.
 *
 * ### Namespaces
 *
 * The XMLProcessor supports XML namespaces.
 *
 * #### Querying for namespaced tags
 *
 * To query for a namespaced tag, you can use the `breadcrumbs` query format, where each breadcrumb is a tuple of (namespace prefix, local name):
 *
 *     $xml = '<root xmlns:wp="http://wordpress.org/export/1.2/"><wp:image src="cat.jpg" /></root>';
 *     $processor = XMLProcessor::create_from_string( $xml );
 *     // Find the <wp:image> tag
 *     if ( $processor->next_tag( array( 'http://wordpress.org/export/1.2/', 'image' ) ) ) {
 *         // Get the namespace URI of the matched tag
 *         $ns = $processor->get_tag_namespace(); // 'http://wordpress.org/export/1.2/'
 *         // Get the value of the 'src' attribute
 *         $src = $processor->get_attribute( $ns, 'src' );
 *         // Set a new attribute in the same namespace
 *         $processor->set_attribute( $ns, 'alt', 'A cat' );
 *     }
 *
 * If a tag has no namespace, you can use an empty string as the namespace prefix:
 *
 *    if ( $processor->next_tag( array( '', 'image' ) ) ) { ... }
 *
 * #### Internal representation of names
 *
 * Internally, the XMLProcessor stores names using the following format:
 *
 *     {namespace}local_name
 *
 * It's safe, because the "{" and "}" bytes cannot be used in tag names or attribute names:
 *
 *     [4]      NameStartChar  ::=      ":" | [A-Z] | "_" | [a-z] | [#xC0-#xD6] | [#xD8-#xF6] | [#xF8-#x2FF] | [#x370-#x37D] | [#x37F-#x1FFF] | [#x200C-#x200D] | [#x2070-#x218F] | [#x2C00-#x2FEF] | [#x3001-#xD7FF] | [#xF900-#xFDCF] | [#xFDF0-#xFFFD] | [#x10000-#xEFFFF]
 *     [4a]     NameChar       ::=      NameStartChar | "-" | "." | [0-9] | #xB7 | [#x0300-#x036F] | [#x203F-#x2040]
 *
 * @since WP_VERSION
 */
class XMLProcessor {
	/**
	 * The maximum number of bookmarks allowed to exist at
	 * any given time.
	 *
	 * @since WP_VERSION
	 * @var int
	 *
	 * @see XMLProcessor::set_bookmark()
	 */
	const MAX_BOOKMARKS = 10;

	/**
	 * Maximum number of times seek() can be called.
	 * Prevents accidental infinite loops.
	 *
	 * @since WP_VERSION
	 * @var int
	 *
	 * @see XMLProcessor::seek()
	 */
	const MAX_SEEK_OPS = 1000;

	/**
	 * The XML document to parse.
	 *
	 * @since WP_VERSION
	 * @var string
	 */
	public $xml;

	/**
	 * Specifies mode of operation of the parser at any given time.
	 *
	 * | State           | Meaning                                                              |
	 * |-----------------|----------------------------------------------------------------------|
	 * | *Ready*           | The parser is ready to run.                                          |
	 * | *Complete*        | There is nothing left to parse.                                      |
	 * | *Incomplete*      | The XML ended in the middle of a token; nothing more can be parsed.  |
	 * | *Matched tag*     | Found an XML tag; it's possible to modify its attributes.            |
	 * | *Text node*       | Found a #text node; this is plaintext and modifiable.                |
	 * | *CDATA node*      | Found a CDATA section; this is modifiable.                           |
	 * | *PI node*         | Found a processing instruction; this is modifiable.                  |
	 * | *XML declaration* | Found an XML declaration; this is modifiable.                        |
	 * | *Comment*         | Found a comment or bogus comment; this is modifiable.                |
	 *
	 * @since WP_VERSION
	 *
	 * @see XMLProcessor::STATE_READY
	 * @see XMLProcessor::STATE_COMPLETE
	 * @see XMLProcessor::STATE_INCOMPLETE_INPUT
	 * @see XMLProcessor::STATE_MATCHED_TAG
	 * @see XMLProcessor::STATE_TEXT_NODE
	 * @see XMLProcessor::STATE_CDATA_NODE
	 * @see XMLProcessor::STATE_PI_NODE
	 * @see XMLProcessor::STATE_XML_DECLARATION
	 * @see XMLProcessor::STATE_COMMENT
	 *
	 * @var string
	 */
	protected $parser_state = self::STATE_READY;

	/**
	 * Whether the input has been finished.
	 *
	 * @var bool
	 */
	protected $expecting_more_input = true;

	/**
	 * How many bytes from the current XML chunk have been read and parsed.
	 *
	 * This value points to the latest byte offset in the input document which
	 * has been already parsed. It is the internal cursor for the Tag Processor
	 * and updates while scanning through the XML tokens.
	 *
	 * @since WP_VERSION
	 * @var int
	 */
	public $bytes_already_parsed = 0;

	/**
	 * How many XML bytes from the original stream have already been removed
	 * from the memory.
	 *
	 * @since WP_VERSION
	 * @var int
	 */
	public $upstream_bytes_forgotten = 0;

	/**
	 * Byte offset in the current `$xml` string where the currently recognized token starts.
	 * `null` if no token is currently active.
	 *
	 * Example:
	 *
	 *     <content id="test">...
	 *     ^-- token_starts_at = 0
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $token_starts_at = null;

	/**
	 * Byte length of the currently recognized token.
	 * `null` if no token is currently active.
	 *
	 * Example:
	 *
	 *     <content id="test">...
	 *     ^-----------------^
	 *     \----- 17 chars -----/
	 *
	 *     token_length = 17
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $token_length = null;

	/**
	 * Byte offset in the current `$xml` string where the tag name of the currently-recognized token starts.
	 * `null` if no token is currently active or if the token is not a tag.
	 *
	 * Example:
	 *
	 *     <content id="test">...
	 *      ^-- tag_name_starts_at = 1
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $tag_name_starts_at = null;

	/**
	 * Byte length of the tag name of the currently-recognized token.
	 * `null` if no token is currently active or if the token is not a tag.
	 *
	 * Example:
	 *
	 *     <content id="test">...
	 *      ^------^
	 *      \- 7 ch /
	 *
	 *     tag_name_length = 7
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $tag_name_length = null;

	/**
	 * Byte offset in the current `$xml` string where the text of the currently-recognized token starts.
	 * `null` if no token is currently active or if the token has no text.
	 *
	 * Example:
	 *
	 *     <p>This is the text</p>
	 *        ^-- text_starts_at = 3
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $text_starts_at = null;

	/**
	 * Byte length of the text of the currently-recognized token.
	 * `null` if no token is currently active or if the token has no text.
	 *
	 * Example:
	 *
	 *     <p>This is the text</p>
	 *        ^--------------^
	 *        \-- 16 chars --/
	 *
	 *     text_length = 16
	 *
	 * @since WP_VERSION
	 *
	 * @var int|null
	 */
	protected $text_length = null;

	/**
	 * Whether the currently-recognized token is a tag closer.
	 * `null` if no token is currently active or if the token is not a tag.
	 *
	 * Example:
	 *
	 *     </p>
	 *     ^
	 *     is_closing_tag = true
	 *
	 * @since WP_VERSION
	 *
	 * @var bool|null
	 */
	protected $is_closing_tag = null;

	/**
	 * Lazily-parsed attributes found within an XML tag.
	 *
	 * Example:
	 *
	 *     // Supposing the parser just finished parsing the wp:content tag:
	 *     // <channel xmlns:wp="http://wordpress.org/export/1.2/">
	 *     //   <wp:content wp:id="test-4" class="outline">
	 *     // </channel>
	 *     //
	 *     // Then, the attributes array would be:
	 *     $this->qualified_attributes = array(
	 *         'wp:id' => new XMLAttributeToken( 9, 6, 5, 14 ),
	 *         'class' => new XMLAttributeToken( 23, 7, 17, 13 )
	 *     );
	 *
	 * @since WP_VERSION
	 *
	 * @var XMLAttributeToken[]
	 */
	protected $qualified_attributes = array();

	/**
	 * Lazily-parsed attributes found within an XML tag, keyed by their namespace and local name combination.
	 *
	 * Example:
	 *
	 *     // Supposing the parser just finished parsing the wp:content tag:
	 *     // <channel xmlns:wp="http://wordpress.org/export/1.2/">
	 *     //   <wp:content wp:id="test-4" class="outline">
	 *     // </channel>
	 *     //
	 *     // Then, the attributes array would be:
	 *     $this->attributes = array(
	 *         '{http://wordpress.org/export/1.2/}id' => new XMLAttributeToken( 9, 6, 5, 14, 'wp', 'id' ),
	 *         'class' => new XMLAttributeToken( 23, 7, 17, 13, '', 'class', '' )
	 *     );
	 *
	 * @since WP_VERSION
	 *
	 * @var XMLAttributeToken[]
	 */
	protected $attributes = array();

	/**
	 * Bookmarks created by `set_bookmark()`.
	 *
	 * @since WP_VERSION
	 *
	 * @see XMLProcessor::set_bookmark()
	 *
	 * @var WP_HTML_Span[]
	 */
	protected $bookmarks = array();

	/**
	 * Lexical replacements to apply to the XML document.
	 *
	 * The XML Processor builds a list of lexical updates to apply to the
	 * XML document. These updates are applied in a batch when the
	 * `get_updated_xml()` method is called.
	 *
	 * This is a performance optimization. Instead of applying updates
	 * as they are requested, the processor enqueues them and applies
	 * them all at once. This avoids the overhead of string copies and
	 * memory allocations that would otherwise be required for each
	 * update.
	 *
	 * The lexical updates are stored as an array of `WP_HTML_Text_Replacement`
	 * objects. Each object represents a single text-diffing operation,
	 * which is a low-level way of describing a change to the document.
	 *
	 * For example, to replace an attribute's value, the processor
	 * creates a `WP_HTML_Text_Replacement` object that describes the
	 * start and length of the attribute's value, and the new value
	 * to replace it with.
	 *
	 * These are enqueued while editing the document instead of being immediately
	 * applied to avoid processing overhead, string allocations, and string
	 * copies when applying many updates to a single document.
	 *
	 * Example:
	 *
	 *     // Replace an attribute stored with a new value, indices
	 *     // sourced from the lazily-parsed XML recognizer.
	 *     $start  = $attributes['src']->start;
	 *     $length = $attributes['src']->length;
	 *     $modifications[] = new WP_HTML_Text_Replacement( $start, $length, $new_value );
	 *
	 *     // Correspondingly, something like this will appear in this array.
	 *     $lexical_updates = array(
	 *         WP_HTML_Text_Replacement( 14, 28, 'https://my-site.my-domain/wp-content/uploads/2014/08/kittens.jpg' )
	 *     );
	 *
	 * @since WP_VERSION
	 * @var WP_HTML_Text_Replacement[]
	 */
	protected $lexical_updates = array();

	/**
	 * The Name from the DOCTYPE declaration.
	 *
	 * doctypedecl ::= '<!DOCTYPE' S Name (S ExternalID)? S? ('['
	 *                                 ^^^^
	 *
	 * Example:
	 *
	 *     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	 *
	 * In this example, the doctype_name would be:
	 * "html"
	 *
	 * @since WP_VERSION
	 * @var WP_HTML_Span|null
	 */
	protected $doctype_name = null;

	/**
	 * The system literal value from the DOCTYPE declaration.
	 *
	 * doctypedecl ::= '<!DOCTYPE' S Name (S ExternalID)? S? ('[' intSubset ']' S?)? '>'
	 * ExternalID ::= 'SYSTEM' S SystemLiteral | 'PUBLIC' S PubidLiteral
	 *                           ^^^^^^^^^^^^^
	 *
	 * Example:
	 *
	 *     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	 *
	 * In this example, the system_literal would be:
	 * "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	 *
	 * @since WP_VERSION
	 * @var WP_HTML_Span|null
	 */
	protected $system_literal = null;

	/**
	 * The public identifier value from the DOCTYPE declaration.
	 *
	 * doctypedecl ::= '<!DOCTYPE' S Name (S ExternalID)? S? ('[' intSubset ']' S?)? '>'
	 * ExternalID ::= 'SYSTEM' S SystemLiteral | 'PUBLIC' S PubidLiteral
	 *                                                      ^^^^^^^^^^^^
	 *
	 * Example:
	 *
	 *     <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	 *
	 * In this example, the publid_literal would be:
	 * "-//W3C//DTD XHTML 1.0 Strict//EN"
	 *
	 * @since WP_VERSION
	 * @var WP_HTML_Span|null
	 */
	protected $pubid_literal = null;

	/**
	 * Memory budget for the processed XML.
	 *
	 * append_bytes() will flush the processed bytes whenever the XML buffer
	 * exceeds this budget. The lexical updates will be applied and the bookmarks
	 * will be reset.
	 *
	 * @var int
	 */
	protected $memory_budget = 1024 * 1024 * 1024;

	/**
	 * Tracks and limits `seek()` calls to prevent accidental infinite loops.
	 *
	 * @since WP_VERSION
	 * @var int
	 *
	 * @see XMLProcessor::seek()
	 */
	protected $seek_count = 0;

	/**
	 * Indicates the current parsing stage.
	 *
	 * A well-formed XML document has the following structure:
	 *
	 *     document ::= prolog element Misc*
	 *     prolog   ::= XMLDecl? Misc* (doctypedecl Misc*)?
	 *     Misc     ::= Comment | PI | S
	 *
	 * There is exactly one element, called the root. No elements or text nodes may
	 * precede or follow it.
	 *
	 * See https://www.w3.org/TR/xml/#NT-document.
	 *
	 * | Stage           | Meaning                                                             |
	 * |-----------------|---------------------------------------------------------------------|
	 * | *Prolog*        | The parser is parsing the prolog.                                   |
	 * | *Element*       | The parser is parsing the root element.                             |
	 * | *Misc*          | The parser is parsing miscellaneous content.                        |
	 *
	 * @see XMLProcessor::IN_PROLOG_CONTEXT
	 * @see XMLProcessor::IN_ELEMENT_CONTEXT
	 * @see XMLProcessor::IN_MISC_CONTEXT
	 *
	 * @since WP_VERSION
	 * @var bool
	 */
	protected $parser_context = self::IN_PROLOG_CONTEXT;

	/**
	 * Top-level namespaces for the currently parsed document.
	 */
	private $document_namespaces;

	/**
	 * Tracks open elements and their namespaces while scanning XML.
	 */
	private $stack_of_open_elements = array();

	public static function create_from_string( $xml, $cursor = null, $known_definite_encoding = 'UTF-8', $document_namespaces = array() ) {
		$processor = static::create_for_streaming( $xml, $cursor, $known_definite_encoding, $document_namespaces );
		if ( null === $processor ) {
			return false;
		}
		$processor->input_finished();

		return $processor;
	}

	public static function create_for_streaming( $xml = '', $cursor = null, $known_definite_encoding = 'UTF-8', $document_namespaces = array() ) {
		if ( 'UTF-8' !== $known_definite_encoding ) {
			return false;
		}
		$processor = new XMLProcessor( $xml, $document_namespaces, self::CONSTRUCTOR_UNLOCK_CODE );
		if ( null !== $cursor && true !== $processor->initialize_from_cursor( $cursor ) ) {
			return false;
		}

		return $processor;
	}

	public function get_reentrancy_cursor() {
		$stack_of_open_elements = array();
		foreach ( $this->stack_of_open_elements as $element ) {
			$stack_of_open_elements[] = $element->to_array();
		}

		return base64_encode(
			json_encode(
				array(
					'is_finished'              => $this->is_finished(),
					'upstream_bytes_forgotten' => $this->upstream_bytes_forgotten,
					'parser_context'           => $this->parser_context,
					'stack_of_open_elements'   => $stack_of_open_elements,
					'expecting_more_input'     => $this->expecting_more_input,
					'document_namespaces'      => $this->document_namespaces,
				)
			)
		);
	}

	/**
	 * Returns the byte offset in the input stream where the current token starts.
	 *
	 * You should probably not use this method.
	 *
	 * It's only exists to allow resuming the input stream at the same offset where
	 * the XML parsing was finished. It will never expose any attribute's byte
	 * offset and no method in the XML processor API will ever accept the byte offset
	 * to move to another location. If you need to move around the document, use
	 * `set_bookmark()` and `seek()` instead.
	 */
	public function get_token_byte_offset_in_the_input_stream() {
		return $this->token_starts_at + $this->upstream_bytes_forgotten;
	}

	protected function initialize_from_cursor( $cursor ) {
		if ( ! is_string( $cursor ) ) {
			_doing_it_wrong( __METHOD__, 'Cursor must be a JSON-encoded string.', '1.0.0' );

			return false;
		}
		$cursor = base64_decode( $cursor );
		if ( false === $cursor ) {
			_doing_it_wrong( __METHOD__, 'Invalid cursor provided to initialize_from_cursor().', '1.0.0' );

			return false;
		}
		$cursor = json_decode( $cursor, true );
		if ( false === $cursor ) {
			_doing_it_wrong( __METHOD__, 'Invalid cursor provided to initialize_from_cursor().', '1.0.0' );

			return false;
		}
		if ( $cursor['is_finished'] ) {
			$this->parser_state = self::STATE_COMPLETE;
		}
		// Assume the input stream will start from the last known byte offset.
		$this->bytes_already_parsed     = 0;
		$this->upstream_bytes_forgotten = $cursor['upstream_bytes_forgotten'];
		$this->stack_of_open_elements   = array();
		foreach ( $cursor['stack_of_open_elements'] as $element ) {
			array_push( $this->stack_of_open_elements, XMLElement::from_array( $element ) );
		}
		$this->document_namespaces  = $cursor['document_namespaces'];
		$this->parser_context       = $cursor['parser_context'];
		$this->expecting_more_input = $cursor['expecting_more_input'];

		return true;
	}

	/**
	 * Constructor.
	 *
	 * Do not use this method. Use the static creator methods instead.
	 *
	 * @access private
	 *
	 * @param  string  $xml  XML to process.
	 * @param  string|null  $use_the_static_create_methods_instead  This constructor should not be called manually.
	 *
	 * @see XMLProcessor::create_stream()
	 *
	 * @since 6.4.0
	 *
	 * @see XMLProcessor::create_fragment()
	 */
	protected function __construct( $xml, $document_namespaces = array(), $use_the_static_create_methods_instead = null ) {
		if ( self::CONSTRUCTOR_UNLOCK_CODE !== $use_the_static_create_methods_instead ) {
			_doing_it_wrong(
				__METHOD__,
				sprintf(
					/* translators: %s: XMLProcessor::create_fragment(). */
					__( 'Call %s to create an XML Processor instead of calling the constructor directly.' ),
					'<code>XMLProcessor::create_fragment()</code>'
				),
				'6.4.0'
			);
		}
		$this->xml                 = isset( $xml ) ? $xml : '';
		$this->document_namespaces = array_merge(
			$document_namespaces,
			// These initial namespaces cannot be overridden.
			array(
				'xml'   => 'http://www.w3.org/XML/1998/namespace', // Predefined, cannot be unbound or changed
				'xmlns' => 'http://www.w3.org/2000/xmlns/',        // Reserved for xmlns attributes, not a real namespace for elements/attributes
				''      => '', // Default namespace is initially empty (no namespace)
			)
		);
	}

	/**
	 * Wipes out the processed XML and appends the next chunk of XML to
	 * any remaining unprocessed XML.
	 *
	 * @param  string  $next_chunk  XML to append.
	 */
	public function append_bytes( $next_chunk ) {
		if ( ! $this->expecting_more_input ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Cannot append bytes after the last input chunk was provided and input_finished() was called.' ),
				'WP_VERSION'
			);

			return false;
		}
		$this->xml .= $next_chunk;
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			$this->parser_state = self::STATE_READY;
		}

		// Periodically flush the processed bytes to avoid high memory usage.
		if (
			null !== $this->memory_budget &&
			strlen( $this->xml ) > $this->memory_budget
		) {
			$this->flush_processed_xml();
		}

		return true;
	}

	/**
	 * Forgets the XML bytes that have been processed and are no longer needed to
	 * avoid high memory usage.
	 *
	 * @return string The flushed bytes.
	 */
	private function flush_processed_xml() {
		// Flush updates
		$this->get_updated_xml();

		$unreferenced_bytes = $this->bytes_already_parsed;
		if ( null !== $this->token_starts_at ) {
			$unreferenced_bytes = min( $unreferenced_bytes, $this->token_starts_at );
		}

		$flushed_bytes               = substr( $this->xml, 0, $unreferenced_bytes );
		$this->xml                   = substr( $this->xml, $unreferenced_bytes );
		$this->bookmarks             = array();
		$this->lexical_updates       = array();
		$this->seek_count            = 0;
		$this->bytes_already_parsed -= $unreferenced_bytes;
		if ( null !== $this->token_starts_at ) {
			$this->token_starts_at -= $unreferenced_bytes;
		}
		if ( null !== $this->tag_name_starts_at ) {
			$this->tag_name_starts_at -= $unreferenced_bytes;
		}
		if ( null !== $this->text_starts_at ) {
			$this->text_starts_at -= $unreferenced_bytes;
		}
		$this->upstream_bytes_forgotten += $unreferenced_bytes;

		return $flushed_bytes;
	}

	/**
	 * Indicates that all the XML document bytes have been provided.
	 *
	 * After calling this method, the processor will emit errors where
	 * previously it would have entered the STATE_INCOMPLETE_INPUT state.
	 */
	public function input_finished() {
		$this->expecting_more_input = false;
		$this->parser_state         = self::STATE_READY;
	}

	/**
	 * Indicates if the processor is expecting more data bytes.
	 * If not, the processor will expect the remaining XML bytes to form
	 * a valid document and will not stop on incomplete input.
	 *
	 * @return bool Whether the processor is expecting more data bytes.
	 */
	public function is_expecting_more_input() {
		return $this->expecting_more_input;
	}

	/**
	 * Internal method which finds the next token in the XML document.
	 *
	 * This method is a protected internal function which implements the logic for
	 * finding the next token in a document. It exists so that the parser can update
	 * its state without affecting the location of the cursor in the document and
	 * without triggering subclass methods for things like `next_token()`, e.g. when
	 * applying patches before searching for the next token.
	 *
	 * @return bool Whether a token was parsed.
	 * @since 6.5.0
	 *
	 * @access private
	 *
	 */
	protected function parse_next_token() {
		$was_at = $this->bytes_already_parsed;
		$this->after_tag();

		// Don't proceed if there's nothing more to scan.
		if (
			self::STATE_COMPLETE === $this->parser_state
			||
			self::STATE_INCOMPLETE_INPUT === $this->parser_state ||
			null !== $this->last_error
		) {
			return false;
		}

		/*
		 * The next step in the parsing loop determines the parsing state;
		 * clear it so that state doesn't linger from the previous step.
		 */
		$this->parser_state = self::STATE_READY;

		if ( $this->bytes_already_parsed >= strlen( $this->xml ) ) {
			if ( $this->expecting_more_input ) {
				$this->parser_state = self::STATE_INCOMPLETE_INPUT;
			} else {
				$this->parser_state = self::STATE_COMPLETE;
			}

			return false;
		}

		// Find the next tag if it exists.
		if ( false === $this->parse_next_tag() ) {
			if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
				$this->bytes_already_parsed = $was_at;
			}

			return false;
		}

		if ( null !== $this->last_error ) {
			return false;
		}

		/*
		 * For legacy reasons the rest of this function handles tags and their
		 * attributes. If the processor has reached the end of the document
		 * or if it matched any other token then it should return here to avoid
		 * attempting to process tag-specific syntax.
		 */
		if (
			self::STATE_INCOMPLETE_INPUT !== $this->parser_state &&
			self::STATE_COMPLETE !== $this->parser_state &&
			self::STATE_MATCHED_TAG !== $this->parser_state
		) {
			return true;
		}

		if ( $this->is_closing_tag ) {
			$this->skip_whitespace();
		} else {
			// Parse all of its attributes.
			while ( $this->parse_next_attribute() ) {
				continue;
			}
		}

		if ( null !== $this->last_error ) {
			return false;
		}

		// Consume the closing `>` or `/>`.
		$this->skip_whitespace();
		$at = $this->bytes_already_parsed;
		if ( $at >= strlen( $this->xml ) ) {
			$this->mark_incomplete_input();

			return false;
		}

		$is_self_closing = '/' === $this->xml[ $at ];
		if ( $is_self_closing ) {
			++$at;
		}

		if ( $at >= strlen( $this->xml ) || '>' !== $this->xml[ $at ] ) {
			$this->mark_incomplete_input();

			return false;
		}

		$this->bytes_already_parsed = $at + 1;
		$this->token_length         = $this->bytes_already_parsed - $this->token_starts_at;

		// Now that we have all the attributes, let's resolve their namespaces.
		if ( ! $this->is_closing_tag ) {
			$namespaces            = $this->get_tag_namespaces_in_scope();
			$namespaced_attributes = array();
			foreach ( $this->qualified_attributes as $attribute ) {
				list( $attribute_namespace_prefix, $attribute_local_name ) = $this->parse_qualified_name( $attribute->qualified_name );
				if ( ! isset( $namespaces[ $attribute_namespace_prefix ] ) ) {
					$this->bail( 'Undeclared namespace prefix found in an attribute.', self::ERROR_SYNTAX );

					return false;
				}
				$namespace_reference = $attribute_namespace_prefix ? $namespaces[ $attribute_namespace_prefix ] : '';

				/**
				 * It looks supicious but it's safe â€“ $local_name is guaranteed to not contain
				 * curly braces at this point.
				 */
				$attribute_full_name = $namespace_reference ? '{' . $namespace_reference . '}' . $attribute_local_name : $attribute_local_name;
				if ( isset( $namespaced_attributes[ $attribute_full_name ] ) ) {
					$this->bail(
						sprintf(
							'Duplicate attribute "%s" with namespace "%s" found in the same element.',
							$attribute_local_name,
							$namespace_reference
						),
						self::ERROR_SYNTAX
					);

					return false;
				}
				$namespaced_attributes[ $attribute_full_name ] = $attribute;
				$attribute->namespace                          = $namespace_reference;
			}

			// Store attributes with their namespaces and discard the temporary
			// qualified attributes array.
			$this->attributes           = $namespaced_attributes;
			$this->qualified_attributes = array();
		}

		return true;
	}

	/**
	 * Skips the content of a void tag.
	 *
	 * When the parser encounters a void tag, it needs to skip its content.
	 * For example, when parsing `<br>`, it should not look for a closing `</br>`.
	 *
	 * This is not a concern in XML, but it is in HTML. This method is a no-op
	 * in the XML processor.
	 *
	 * @return bool Whether the content was successfully skipped.
	 */
	protected function skip_void_tag_content() {
		return true;
	}

	/**
	 * Skips the inner content of a tag.
	 *
	 * When the parser encounters a tag that is not a void tag, it needs to
	 * skip its inner content to find the matching closing tag.
	 *
	 * @return bool Whether the content was successfully skipped.
	 */
	protected function skip_tag_content() {
		$tag_opener_at      = $this->token_starts_at;
		$tag_name_starts_at = $this->tag_name_starts_at;
		$tag_name_length    = $this->tag_name_length;
		$attributes         = $this->qualified_attributes;
		$tag_ends_at        = $this->bytes_already_parsed;

		$tag_name = substr( $this->xml, $tag_name_starts_at, $tag_name_length );

		$depth = 1;
		while ( 0 < $depth && $this->parse_next_tag() ) {
			$next_tag_name = substr( $this->xml, $this->tag_name_starts_at, $this->tag_name_length );

			if ( $this->is_closing_tag ) {
				if ( $tag_name === $next_tag_name ) {
					--$depth;
				}
			} else {
				if ( $tag_name === $next_tag_name ) {
					++$depth;
				}
			}
		}

		if ( 0 !== $depth ) {
			$this->mark_incomplete_input();
			return false;
		}

		/*
		 * Now that the internal cursors are past the closing tag, restore
		 * the values for the opening tag. This is because the public methods
		 * that expose values like the tag name and attributes will need to
		 * expose the values from the opening tag, not the closing tag.
		 *
		 * For example, if someone calls `get_tag_name()` after calling
		 * `next_tag( array( 'tag_closers' => 'skip' ) )`, they expect to
		 * get the name of the opening tag, not the closing tag.
		 *
		 * The values for the text content are also set here. The text content
		 * is the content between the opening and closing tags. The public
		 * methods that expose the text content will need to know where the
		 * text starts and ends.
		 *
		 * It's a bit confusing because the names of the properties don't
		 * make it clear that they reference the opening tag but they reference
		 * the closing tag instead. This is why the opening tag values were stored
		 * above in a variable. It reads confusingly here, but that's because the
		 * functions that skip the contents have moved all the internal cursors past
		 * the inner content of the tag.
		 */
		$this->token_starts_at      = $tag_opener_at;
		$this->token_length         = $this->bytes_already_parsed - $this->token_starts_at;
		$this->text_starts_at       = $tag_ends_at;
		$this->text_length          = $this->tag_name_starts_at - $this->text_starts_at;
		$this->tag_name_starts_at   = $tag_name_starts_at;
		$this->tag_name_length      = $tag_name_length;
		$this->qualified_attributes = $attributes;

		return true;
	}

	private function get_tag_namespaces_in_scope() {
		$top = $this->top_element();
		if ( ! $top ) {
			return $this->document_namespaces;
		}

		return $top->namespaces_in_scope;
	}

	/**
	 * Returns the namespace prefix of the matched tag.
	 *
	 * Example:
	 *
	 *     $p = new XMLProcessor(
	 *         '<wp:content
	 *             xmlns="http://www.w3.org/1999/xhtml"
	 *             xmlns:wp="http://wordpress.org/export/1.2/"
	 *         >
	 *             Test
	 *         </wp:content>
	 *     ' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_namespace_prefix('http://wordpress.org/export/1.2/') === 'wp';
	 *
	 * @internal
	 * @param string|null $xml_namespace Fully-qualified namespace to return the prefix for.
	 * @return string|null The namespace prefix of the matched tag, or null if not available.
	 */
	private function get_tag_namespace_prefix( $xml_namespace = null ) {
		if ( null === $xml_namespace ) {
			if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
				return null;
			}
			return $this->element->namespace_prefix;
		} else {
			$namespaces_in_scope = $this->get_tag_namespaces_in_scope();
			$prefix              = array_search( $xml_namespace, $namespaces_in_scope, true );
			return false === $prefix ? null : $prefix;
		}
	}

	/**
	 * Creates a bookmark in the XML document, which is a lightweight pointer to the current location.
	 *
	 * Bookmarks are stable references to a given token in the XML
	 * document. When the document is modified, the offsets of a
	 * token may shift; the bookmark is kept updated with those shifts and remains
	 * stable unless the entire span of text in which the token sits is removed.
	 *
	 * Release bookmarks when they are no longer needed.
	 *
	 * Example:
	 *
	 *     <main><h2>Surprising fact you may not know!</h2></main>
	 *           ^  ^
	 *            \-|-- this `H2` opener bookmark tracks the token
	 *
	 *     <main class="clickbait"><h2>Surprising fact you may noâ€¦
	 *                             ^  ^
	 *                              \-|-- it shifts with edits
	 *
	 * Bookmarks provide the ability to seek to a previously-scanned
	 * place in the XML document. This avoids the need to re-scan
	 * the entire document.
	 *
	 * Example:
	 *
	 *     <ul><li>One</li><li>Two</li><li>Three</li></ul>
	 *                                ^^^^
	 *                                 want to note this last item
	 *
	 *     $p = new XMLProcessor( $xml );
	 *     $in_list = false;
	 *     while ( $p->next_tag( array( 'tag_closers' => $in_list ? 'visit' : 'skip' ) ) ) {
	 *         if ( 'UL' === $p->get_qualified_tag() ) {
	 *             if ( $p->is_tag_closer() ) {
	 *                 $in_list = false;
	 *                 $p->set_bookmark( 'resume' );
	 *                 if ( $p->seek( 'last-li' ) ) {
	 *                     $p->add_class( 'last-li' );
	 *                 }
	 *                 $p->seek( 'resume' );
	 *                 $p->release_bookmark( 'last-li' );
	 *                 $p->release_bookmark( 'resume' );
	 *             } else {
	 *                 $in_list = true;
	 *             }
	 *         }
	 *
	 *         if ( 'LI' === $p->get_qualified_tag() ) {
	 *             $p->set_bookmark( 'last-li' );
	 *         }
	 *     }
	 *
	 * Bookmarks intentionally hide the internal string offsets
	 * to which they refer. They are maintained internally as
	 * updates are applied to the XML document and therefore
	 * retain their "position" - the location to which they
	 * originally pointed. The inability to use bookmarks with
	 * functions like `substr` is therefore intentional to guard
	 * against accidentally breaking the XML.
	 *
	 * Because bookmarks allocate memory and require processing
	 * for every applied update, they should be released when no
	 * longer needed. There's also a hard limit on how many bookmarks
	 * may exist at any given time.
	 *
	 * @since WP_VERSION
	 *
	 * @param string $name Name to identify this bookmark.
	 * @return bool Whether the bookmark was successfully created.
	 */
	public function set_bookmark( $name ) {
		if (
			self::STATE_MATCHED_TAG !== $this->parser_state &&
			self::STATE_TEXT_NODE !== $this->parser_state &&
			self::STATE_CDATA_NODE !== $this->parser_state &&
			self::STATE_COMMENT !== $this->parser_state &&
			self::STATE_DOCTYPE_NODE !== $this->parser_state &&
			self::STATE_PI_NODE !== $this->parser_state &&
			self::STATE_XML_DECLARATION !== $this->parser_state &&
			self::STATE_COMPLETE === $this->parser_state ||
			self::STATE_INCOMPLETE_INPUT === $this->parser_state
		) {
			return false;
		}

		if ( ! array_key_exists( $name, $this->bookmarks ) && count( $this->bookmarks ) >= static::MAX_BOOKMARKS ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Too many bookmarks: cannot create any more.' ),
				'WP_VERSION'
			);

			return false;
		}

		$this->bookmarks[ $name ] = new WP_HTML_Span( $this->token_starts_at, $this->token_length );

		return true;
	}


	/**
	 * Removes a bookmark that is no longer needed.
	 *
	 * Releasing a bookmark frees up the small
	 * performance overhead it requires.
	 *
	 * @param  string  $name  Name of the bookmark to remove.
	 *
	 * @return bool Whether the bookmark was successfully removed.
	 */
	public function release_bookmark( $name ) {
		if ( ! array_key_exists( $name, $this->bookmarks ) ) {
			return false;
		}

		unset( $this->bookmarks[ $name ] );
		return true;
	}

	/**
	 * Finds the next tag in the XML document.
	 *
	 * @since WP_VERSION
	 *
	 * @param array|string|null $query_or_ns      Optional. May be a tag name, a namespace, or a query array.
	 *                                            Default is `null`, which finds the next tag of any name.
	 * @param string|null       $null_or_local_name Optional. If the first argument is a namespace, this is the local name.
	 *
	 * @return bool Whether a tag was matched.
	 */
	public function next_tag( $query_or_ns = null, $null_or_local_name = null ) {
		if ( null === $query_or_ns ) {
			while ( $this->step() ) {
				if ( '#tag' !== $this->get_token_type() ) {
					continue;
				}

				if ( ! $this->is_tag_closer() ) {
					return true;
				}
			}

			return false;
		}

		if ( is_string( $query_or_ns ) ) {
			if ( is_string( $null_or_local_name ) ) {
				$query = array( 'breadcrumbs' => array( array( $query_or_ns, $null_or_local_name ) ) );
			} else {
				$query = array( 'breadcrumbs' => array( array( '', $query_or_ns ) ) );
			}
		} else {
			$query = $query_or_ns;
		}

		if ( ! is_array( $query ) ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Please pass a query array to this function.' ),
				'WP_VERSION'
			);

			return false;
		}

		if ( array_keys( $query ) === array( 0, 1 ) && is_string( $query[0] ) && is_string( $query[1] ) ) {
			$query = array( 'breadcrumbs' => array( $query ) );
		}

		if ( ! ( array_key_exists( 'breadcrumbs', $query ) && is_array( $query['breadcrumbs'] ) ) ) {
			while ( $this->step() ) {
				if ( '#tag' !== $this->get_token_type() ) {
					continue;
				}

				if ( ! $this->is_tag_closer() ) {
					return true;
				}
			}

			return false;
		}

		if ( isset( $query['tag_closers'] ) && 'visit' === $query['tag_closers'] ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Cannot visit tag closers in XML Processor.' ),
				'WP_VERSION'
			);

			return false;
		}

		$namespaced_breadcrumbs = array();
		foreach ( $query['breadcrumbs'] as $breadcrumb ) {
			if ( is_array( $breadcrumb ) && count( $breadcrumb ) === 2 ) {
				$namespaced_breadcrumbs[] = $breadcrumb;
			} elseif ( is_string( $breadcrumb ) ) {
				$namespaced_breadcrumbs[] = array( '', $breadcrumb );
			} else {
				_doing_it_wrong(
					__METHOD__,
					__( 'Breadcrumbs must be an array of strings or two-tuples of (namespace, local name).' ),
					'WP_VERSION'
				);
			}
		}
		$breadcrumbs  = $namespaced_breadcrumbs;
		$match_offset = isset( $query['match_offset'] ) ? (int) $query['match_offset'] : 1;

		while ( $match_offset > 0 && $this->step() ) {
			if ( '#tag' !== $this->get_token_type() ) {
				continue;
			}

			if ( $this->matches_breadcrumbs( $breadcrumbs ) && 0 === --$match_offset ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Finds the next tag opener in the XML document.
	 *
	 * @return bool Whether a tag opener was found.
	 */
	private function parse_next_tag() {
		$was_at     = $this->bytes_already_parsed;
		$doc_length = strlen( $this->xml );
		$at         = $was_at;

		while ( false !== ( $at = strpos( $this->xml, '<', $at ) ) ) {
			$this->token_starts_at = $at;

			// It's a tag if the next character is a letter or a slash.
			if (
				$at + 1 < $doc_length &&
				(
					// Check for a letter.
					( 'a' <= $this->xml[ $at + 1 ] && 'z' >= $this->xml[ $at + 1 ] ) ||
					( 'A' <= $this->xml[ $at + 1 ] && 'Z' >= $this->xml[ $at + 1 ] ) ||
					// Check for a slash.
					'/' === $this->xml[ $at + 1 ] ||
					// Check for a question mark.
					'?' === $this->xml[ $at + 1 ] ||
					// Check for an exclamation mark.
					'!' === $this->xml[ $at + 1 ]
				)
			) {
				// Found a text node.
				if ( $at > $was_at ) {
					$this->parser_state         = self::STATE_TEXT_NODE;
					$this->token_starts_at      = $was_at;
					$this->token_length         = $at - $was_at;
					$this->text_starts_at       = $was_at;
					$this->text_length          = $at - $was_at;
					$this->bytes_already_parsed = $at;

					return true;
				}

				// Found a tag.
				$this->bytes_already_parsed = $at;
				$this->is_closing_tag       = '/' === $this->xml[ $at + 1 ];
				if ( $this->is_closing_tag ) {
					$this->tag_name_starts_at = $at + 2;
				} else {
					$this->tag_name_starts_at = $at + 1;
				}

				// Found a comment, CDATA section, or DOCTYPE declaration.
				if ( '!' === $this->xml[ $at + 1 ] ) {
					/*
					 * Identify comments.
					 *
					 * Comments must start with `<!--` and end with `-->`.
					 * See https://www.w3.org/TR/xml/#sec-comments
					 */
					if (
						$doc_length > $this->token_starts_at + 3 &&
						'-' === $xml[ $this->token_starts_at + 2 ] &&
						'-' === $xml[ $this->token_starts_at + 3 ]
					) {
						$closer_at = strpos( $xml, '-->', $at + 1 );
						if ( false === $closer_at ) {
							$this->mark_incomplete_input( 'Unclosed comment' );

							return false;
						}

						$this->parser_state         = self::STATE_COMMENT;
						$this->token_length         = $closer_at + 3 - $this->token_starts_at;
						$this->text_starts_at       = $this->token_starts_at + 4;
						$this->text_length          = $closer_at - $this->text_starts_at;
						$this->bytes_already_parsed = $closer_at + 3;

						return true;
					}

					/*
					 * Identify CDATA sections.
					 *
					 * Within a CDATA section, everything until the ]]> string is treated
					 * as data, not markup. Left angle brackets and ampersands may occur in
					 * their literal form; they need not (and cannot) be escaped using "&lt;"
					 * and "&amp;". CDATA sections cannot nest.
					 *
					 * See https://www.w3.org/TR/xml11.xml/#sec-cdata-sect
					 */
					if (
						$doc_length > $this->token_starts_at + 8 &&
						'[' === $xml[ $this->token_starts_at + 2 ] &&
						'C' === $xml[ $this->token_starts_at + 3 ] &&
						'D' === $xml[ $this->token_starts_at + 4 ] &&
						'A' === $xml[ $this->token_starts_at + 5 ] &&
						'T' === $xml[ $this->token_starts_at + 6 ] &&
						'A' === $xml[ $this->token_starts_at + 7 ] &&
						'[' === $xml[ $this->token_starts_at + 8 ]
					) {
						$closer_at = strpos( $xml, ']]>', $at + 1 );
						if ( false === $closer_at ) {
							$this->mark_incomplete_input( 'Unclosed CDATA section' );

							return false;
						}

						$this->parser_state         = self::STATE_CDATA_NODE;
						$this->token_length         = $closer_at + 1 - $this->token_starts_at;
						$this->text_starts_at       = $this->token_starts_at + 9;
						$this->text_length          = $closer_at - $this->text_starts_at;
						$this->bytes_already_parsed = $closer_at + 3;

						return true;
					}

					/*
					 * Identify DOCTYPE nodes.
					 *
					 * doctypedecl    ::=      '<!DOCTYPE' S Name (S ExternalID)? S? ('[' intSubset ']' S?)? '>'
					 * ExternalID      ::=      'SYSTEM' S SystemLiteral | 'PUBLIC' S PubidLiteral S SystemLiteral
					 * SystemLiteral   ::=      ('"' [^"]* '"') | ("'" [^']* "'")
					 * PubidLiteral    ::=      '"' PubidChar* '"' | "'" (PubidChar - "'")* "'"
					 * PubidChar       ::=      #x20 | #xD | #xA | [a-zA-Z0-9] | [-'()+,./:=?;!*#@$_%]
					 * See https://www.w3.org/TR/xml11.html/#dtd
					 */
					if (
						$doc_length > $this->token_starts_at + 8 &&
						'D' === $xml[ $at + 2 ] &&
						'O' === $xml[ $at + 3 ] &&
						'C' === $xml[ $at + 4 ] &&
						'T' === $xml[ $at + 5 ] &&
						'Y' === $xml[ $at + 6 ] &&
						'P' === $xml[ $at + 7 ] &&
						'E' === $xml[ $at + 8 ]
					) {
						$at += 9;
						// Skip whitespace.
						$at += strspn( $this->xml, " \t\f\r\n", $at );

						// Parse the DOCTYPE name.
						$name_length = $this->parse_name( $at );
						if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
							$this->mark_incomplete_input( 'Unclosed DOCTYPE name.' );

							return false;
						}
						$this->doctype_name = new WP_HTML_Span( $at, $name_length );
						$at                += $name_length;

						// Skip whitespace.
						$at += strspn( $this->xml, " \t\f\r\n", $at );

						// Check for SYSTEM or PUBLIC identifiers
						if (
							$doc_length > $at + 6 &&
							'S' === $this->xml[ $at ] &&
							'Y' === $this->xml[ $at + 1 ] &&
							'S' === $this->xml[ $at + 2 ] &&
							'T' === $this->xml[ $at + 3 ] &&
							'E' === $this->xml[ $at + 4 ] &&
							'M' === $this->xml[ $at + 5 ]
						) {
							$at += 6;
							// Skip whitespace.
							$at += strspn( $this->xml, " \t\f\r\n", $at );

							// Parse the SystemLiteral token.
							$quoted_string_length = $this->parse_quoted_string( $at );
							if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
								$this->mark_incomplete_input( 'Unclosed SYSTEM literal.' );

								return false;
							}

							$this->system_literal = new WP_HTML_Span(
								// Start after the opening quote.
								$at + 1,
								// Exclude the closing quote.
								$quoted_string_length - 2
							);
							$at                  += $quoted_string_length;
						} elseif (
							$doc_length > $at + 6 &&
							'P' === $this->xml[ $at ] &&
							'U' === $this->xml[ $at + 1 ] &&
							'B' === $this->xml[ $at + 2 ] &&
							'L' === $this->xml[ $at + 3 ] &&
							'I' === $this->xml[ $at + 4 ] &&
							'C' === $this->xml[ $at + 5 ]
						) {
							$at += 6;
							// Skip whitespace.
							$at += strspn( $this->xml, " \t\f\r\n", $at );

							// Parse the PubidLiteral token.
							$pubid_char           = "\x20\x0D\x0AabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-'()+,./:=?;!*#@$_%";
							$pubid_literal_length = strspn( $this->xml, $pubid_char, $at + 1 );
							$this->pubid_literal  = new WP_HTML_Span(
								$at + 1,
								$pubid_literal_length
							);
							$at                  += $pubid_literal_length + 2;

							// Skip whitespace.
							$at += strspn( $this->xml, " \t\f\r\n", $at );

							// Parse the SystemLiteral token.
							$quoted_string_length = $this->parse_quoted_string( $at );
							if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
								$this->mark_incomplete_input( 'Unclosed SYSTEM literal.' );

								return false;
							}

							$this->system_literal = new WP_HTML_Span(
								// Start after the opening quote.
								$at + 1,
								// Exclude the closing quote.
								$quoted_string_length - 2
							);
							$at                  += $quoted_string_length;
						}

						// Skip whitespace.
						$at += strspn( $this->xml, " \t\f\r\n", $at );

						// Consume the closer.
						if ( $at >= $doc_length || '>' !== $this->xml[ $at ] ) {
							$this->mark_incomplete_input( 'Unclosed DOCTYPE declaration.' );

							return false;
						}

						$this->token_length         = $at + 1 - $this->token_starts_at;
						$this->bytes_already_parsed = $at + 1;
						$this->parser_state         = self::STATE_DOCTYPE_NODE;

						return true;
					}
				}

				// Found an XML declaration.
				if (
					'?' === $this->xml[ $at + 1 ] &&
					$doc_length > $at + 5 &&
					( 'x' === $this->xml[ $at + 2 ] || 'X' === $this->xml[ $at + 2 ] ) &&
					( 'm' === $this->xml[ $at + 3 ] || 'M' === $this->xml[ $at + 3 ] ) &&
					( 'l' === $this->xml[ $at + 4 ] || 'L' === $this->xml[ $at + 4 ] )
				) {
					$this->bytes_already_parsed = $at + 5;
					$this->skip_whitespace();
					while ( false !== $this->parse_next_attribute() ) {
						$this->skip_whitespace();
						// Parse until the XML declaration closer.
						if ( '?' === $xml[ $this->bytes_already_parsed ] ) {
							break;
						}
					}

					if ( null !== $this->last_error ) {
						return false;
					}

					foreach ( $this->qualified_attributes as $name => $attribute ) {
						if ( 'version' !== $name && 'encoding' !== $name && 'standalone' !== $name ) {
							$this->bail( 'Invalid attribute found in XML declaration.', self::ERROR_SYNTAX );
							return false;
						}
					}

					if ( '1.0' !== $this->get_qualified_attribute( 'version' ) ) {
						$this->bail( 'Unsupported XML version declared', self::ERROR_UNSUPPORTED );
						return false;
					}

					/**
					 * Standalone XML documents have no external dependencies,
					 * including predefined entities like `&nbsp;` and `&copy;`.
					 *
					 * See https://www.w3.org/TR/xml/#sec-predefined-ent.
					 */
					if (
						null !== $this->get_qualified_attribute( 'encoding' )
						&& 'UTF-8' !== strtoupper( $this->get_qualified_attribute( 'encoding' ) )
					) {
						$this->bail( 'Unsupported XML encoding declared, only UTF-8 is supported.', self::ERROR_UNSUPPORTED );
						return false;
					}

					if (
						null !== $this->get_qualified_attribute( 'standalone' )
						&& 'YES' !== strtoupper( $this->get_qualified_attribute( 'standalone' ) )
					) {
						$this->bail( 'Standalone XML documents are not supported.', self::ERROR_UNSUPPORTED );
						return false;
					}

					$at = $this->bytes_already_parsed;

					// Skip whitespace.
					$at += strspn( $this->xml, " \t\f\r\n", $at );

					// Consume the closer.
					if ( ! (
						$at + 2 <= $doc_length &&
						'?' === $xml[ $at ] &&
						'>' === $xml[ $at + 1 ]
					) ) {
						$this->bail( 'XML declaration closer not found.', self::ERROR_SYNTAX );
						return false;
					}

					$this->token_length         = $at + 2 - $this->token_starts_at;
					$this->text_starts_at       = $this->token_starts_at + 2;
					$this->text_length          = $at - $this->text_starts_at;
					$this->bytes_already_parsed = $at + 2;
					$this->parser_state         = self::STATE_XML_DECLARATION;

					// Processing instructions don't have namespaces. We can just
					// copy the qualified attributes to the attributes array without
					// resolving anything.
					$this->attributes           = $this->qualified_attributes;
					$this->qualified_attributes = array();

					return true;
				}

				/*
				 * `<?` denotes a processing instruction.
				 * See https://www.w3.org/TR/xml/#sec-pi
				 */
				if (
					! $this->is_closing_tag &&
					'?' === $xml[ $at + 1 ]
				) {
					if ( $at + 4 >= $doc_length ) {
						$this->mark_incomplete_input();

						return false;
					}

					if ( ! (
						( 'x' === $xml[ $at + 2 ] || 'X' === $xml[ $at + 2 ] ) &&
						( 'm' === $xml[ $at + 3 ] || 'M' === $xml[ $at + 3 ] ) &&
						( 'l' === $xml[ $at + 4 ] || 'L' === $xml[ $at + 4 ] )
					) ) {
						$this->bail( 'Invalid processing instruction target.', self::ERROR_SYNTAX );
					}

					$at += 5;

					// Skip whitespace.
					$this->skip_whitespace();

					/*
					 * Find the closer.
					 *
					 * We could, at this point, only consume the bytes allowed by the specification, that is:
					 *
					 * [2] Char ::= #x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF] // any Unicode character, excluding the surrogate blocks, FFFE, and FFFF.
					 *
					 * However, that would require running a slow regular-expression engine for, seemingly,
					 * little benefit. For now, we are going to pretend that all bytes are allowed until the
					 * closing ?> is found. Some failures may pass unnoticed. That may not be a problem in practice,
					 * but if it is then this code path will require a stricter implementation.
					 */
					$closer_at = strpos( $xml, '?>', $at );
					if ( false === $closer_at ) {
						$this->mark_incomplete_input();

						return false;
					}

					$this->parser_state         = self::STATE_PI_NODE;
					$this->token_length         = $closer_at + 5 - $this->token_starts_at;
					$this->text_starts_at       = $this->token_starts_at + 5;
					$this->text_length          = $closer_at - $this->text_starts_at;
					$this->bytes_already_parsed = $closer_at + 2;

					return true;
				}

				// Find the end of the tag name.
				$this->tag_name_length = $this->parse_name( $this->tag_name_starts_at );
				if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
					return false;
				}

				$this->bytes_already_parsed = $this->tag_name_starts_at + $this->tag_name_length;
				$this->parser_state         = self::STATE_MATCHED_TAG;

				return true;
			}

			++$at;
		}

		// There's no more tag openers and we're not expecting more data â€“
		// this mist be a trailing text node.
		if ( ! $this->expecting_more_input ) {
			$this->parser_state         = self::STATE_TEXT_NODE;
			$this->token_starts_at      = $was_at;
			$this->token_length         = $doc_length - $was_at;
			$this->text_starts_at       = $was_at;
			$this->text_length          = $doc_length - $was_at;
			$this->bytes_already_parsed = $doc_length;

			return true;
		}

		/*
		 * This does not imply an incomplete parse; it indicates that there
		 * can be nothing left in the document other than a #text node.
		 */
		$this->mark_incomplete_input();
		$this->token_starts_at = $was_at;
		$this->token_length    = $doc_length - $was_at;
		$this->text_starts_at  = $was_at;
		$this->text_length     = $doc_length - $was_at;

		return false;
	}

	/**
	 * Parses the next attribute.
	 *
	 * @return bool Whether an attribute was found.
	 */
	private function parse_next_attribute() {
		$this->skip_whitespace();
		$at = $this->bytes_already_parsed;

		// Stop if there are no more attributes.
		if (
			$at >= strlen( $this->xml ) ||
			'>' === $this->xml[ $at ] ||
			'/' === $this->xml[ $at ] ||
			'?' === $this->xml[ $at ]
		) {
			return false;
		}

		$name_starts_at = $at;
		$name_length    = $this->parse_name( $name_starts_at );
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			return false;
		}
		$at        += $name_length;
		$name_text  = substr( $this->xml, $name_starts_at, $name_length );

		// Skip whitespace.
		$at += strspn( $this->xml, " \t\f\r\n", $at );

		// Attributes must have a value.
		if ( $at >= strlen( $this->xml ) || '=' !== $this->xml[ $at ] ) {
			$this->bail( 'Attribute must have a value.', self::ERROR_SYNTAX );
			return false;
		}
		++$at;

		// Skip whitespace.
		$at += strspn( $this->xml, " \t\f\r\n", $at );

		// Parse the attribute value.
		$value_starts_at = $at;
		$value_length    = $this->parse_quoted_string( $value_starts_at );
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			return false;
		}
		$at += $value_length;

		$this->bytes_already_parsed = $at;

		list( $namespace_prefix, $local_name ) = $this->parse_qualified_name( $name_text );
		$this->qualified_attributes[ $name_text ] = new XMLAttributeToken(
			$value_starts_at + 1,
			$value_length - 2,
			$name_starts_at,
			$at - $name_starts_at,
			$namespace_prefix,
			$local_name
		);

		return true;
	}

	/**
	 * Parses a quoted string.
	 *
	 * @param int $at The starting position of the quoted string.
	 * @return int The length of the quoted string.
	 */
	private function parse_quoted_string( $at ) {
		if ( $at >= strlen( $this->xml ) ) {
			$this->mark_incomplete_input();

			return 0;
		}

		$quote = $this->xml[ $at ];
		if ( '"' !== $quote && "'" !== $quote ) {
			$this->bail( 'Invalid quote character encountered in an attribute value.', self::ERROR_SYNTAX );
		}
		$value_length = strcspn( $this->xml, $quote, $at + 1 );
		if ( $at + $value_length + 1 >= strlen( $this->xml ) ) {
			$this->mark_incomplete_input();

			return false;
		}

		if ( $this->xml[ $at + $value_length + 1 ] !== $quote ) {
			$this->bail( 'A disallowed character encountered in an attribute value (either < or &).', self::ERROR_SYNTAX );
		}

		return $value_length + 2;
	}

	/**
	 * Move the internal cursor past any immediate successive whitespace.
	 *
	 * @since WP_VERSION
	 */
	private function skip_whitespace() {
		$this->bytes_already_parsed += strspn( $this->xml, " \t\f\r\n", $this->bytes_already_parsed );
	}

	/**
	 * Parses a tag or attribute name.
	 *
	 * @param int $at The starting position of the name.
	 * @return int The length of the name.
	 */
	private function parse_name( $at ) {
		$length = 0;
		while ( $at + $length < strlen( $this->xml ) ) {
			$char_at_offset = $at + $length;
			$codepoint      = utf8_codepoint_at( $this->xml, $char_at_offset, $matched_bytes );
			if ( 0 === $matched_bytes ) {
				$this->mark_incomplete_input();
				return 0;
			}

			if ( 0 === $length ) {
				if ( ! $this->is_name_start_char( $codepoint ) ) {
					break;
				}
			} elseif ( ! $this->is_name_char( $codepoint ) ) {
				break;
			}
			$length += $matched_bytes;
		}

		return $length;
	}

	/**
	 * Checks if a codepoint is a valid name start character.
	 *
	 * @param int $codepoint The codepoint to check.
	 * @return bool Whether the codepoint is a valid name start character.
	 */
	private function is_name_start_char( $codepoint ) {
		// NameStartChar ::= ":" | [A-Z] | "_" | [a-z] | [#xC0-#xD6] | [#xD8-#xF6] | [#xF8-#x2FF] | [#x370-#x37D] | [#x37F-#x1FFF] | [#x200C-#x200D] | [#x2070-#x218F] | [#x2C00-#x2FEF] | [#x3001-#xD7FF] | [#xF900-#xFDCF] | [#xFDF0-#xFFFD] | [#x10000-#xEFFFF]
		// See https://www.w3.org/TR/xml/#NT-Name
		if (
			// :
			( 0x3A <= $codepoint && $codepoint <= 0x3A ) ||
			// _
			( 0x5F <= $codepoint && $codepoint <= 0x5F ) ||
			// A-Z
			( 0x41 <= $codepoint && $codepoint <= 0x5A ) ||
			// a-z
			( 0x61 <= $codepoint && $codepoint <= 0x7A ) ||
			// [#xC0-#xD6]
			( 0xC0 <= $codepoint && $codepoint <= 0xD6 ) ||
			// [#xD8-#xF6]
			( 0xD8 <= $codepoint && $codepoint <= 0xF6 ) ||
			// [#xF8-#x2FF]
			( 0xF8 <= $codepoint && $codepoint <= 0x2FF ) ||
			// [#x370-#x37D]
			( 0x370 <= $codepoint && $codepoint <= 0x37D ) ||
			// [#x37F-#x1FFF]
			( 0x37F <= $codepoint && $codepoint <= 0x1FFF ) ||
			// [#x200C-#x200D]
			( 0x200C <= $codepoint && $codepoint <= 0x200D ) ||
			// [#x2070-#x218F]
			( 0x2070 <= $codepoint && $codepoint <= 0x218F ) ||
			// [#x2C00-#x2FEF]
			( 0x2C00 <= $codepoint && $codepoint <= 0x2FEF ) ||
			// [#x3001-#xD7FF]
			( 0x3001 <= $codepoint && $codepoint <= 0xD7FF ) ||
			// [#xF900-#xFDCF]
			( 0xF900 <= $codepoint && $codepoint <= 0xFDCF ) ||
			// [#xFDF0-#xFFFD]
			( 0xFDF0 <= $codepoint && $codepoint <= 0xFFFD ) ||
			// [#x10000-#xEFFFF]
			( 0x10000 <= $codepoint && $codepoint <= 0xEFFFF )
		) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if a codepoint is a valid name character.
	 *
	 * @param int $codepoint The codepoint to check.
	 * @return bool Whether the codepoint is a valid name character.
	 */
	private function is_name_char( $codepoint ) {
		if ( $this->is_name_start_char( $codepoint ) ) {
			return true;
		}

		// NameChar ::= NameStartChar | "-" | "." | [0-9] | #xB7 | [#x0300-#x036F] | [#x203F-#x2040]
		// See https://www.w3.org/TR/xml/#NT-Name
		return (
			// "-"
			45 === $codepoint ||
			// "."
			46 === $codepoint ||
			// [0-9]
			( 48 <= $codepoint && 57 >= $codepoint ) ||
			// #xB7
			183 === $codepoint ||
			// [#x0300-#x036F]
			( 0x0300 <= $codepoint && $codepoint <= 0x036F ) ||
			// [#x203F-#x2040]
			( 0x203F <= $codepoint && $codepoint <= 0x2040 )
		);
	}

	/**
	 * Applies attribute updates and cleans up once a tag is fully parsed.
	 *
	 * @since WP_VERSION
	 */
	private function after_tag() {
		/*
		 * Purge updates if there are too many. The actual count isn't
		 * scientific, but a few values from 100 to a few thousand were
		 * tests to find a practically-useful limit.
		 *
		 * If the update queue grows too big, then the Tag Processor
		 * will spend more time iterating through them and lose the
		 * efficiency gains of deferring applying them.
		 */
		if ( 1000 < count( $this->lexical_updates ) ) {
			$this->get_updated_xml();
		}

		foreach ( $this->lexical_updates as $name => $update ) {
			/*
			 * Any updates appearing after the cursor should be applied
			 * before proceeding, otherwise they may be overlooked.
			 */
			if ( $update->start >= $this->bytes_already_parsed ) {
				$this->get_updated_xml();
				break;
			}

			if ( is_int( $name ) ) {
				continue;
			}

			$this->lexical_updates[] = $update;
			unset( $this->lexical_updates[ $name ] );
		}

		$this->element              = null;
		$this->token_starts_at      = null;
		$this->token_length         = null;
		$this->tag_name_starts_at   = null;
		$this->tag_name_length      = null;
		$this->text_starts_at       = null;
		$this->text_length          = null;
		$this->is_closing_tag       = null;
		$this->pubid_literal        = null;
		$this->system_literal       = null;
		$this->attributes           = array();
		$this->qualified_attributes = array();
	}

	/**
	 * Applies lexical updates to XML document.
	 *
	 * @param  int  $shift_this_point  Optional. If provided, will shift this point by the sum of all applied changes.
	 * @return int Amount the given point was shifted.
	 */
	public function get_updated_xml( $shift_this_point = 0 ) {
		if ( 0 === count( $this->lexical_updates ) ) {
			return 0;
		}

		$accumulated_shift_for_given_point = 0;

		// Sort updates by start position, but keep their relative order if they start at the same position.
		uasort( $this->lexical_updates, array( self::class, 'sort_start_ascending' ) );

		$bytes_already_copied = 0;
		$output_buffer        = '';
		foreach ( $this->lexical_updates as $diff ) {
			$shift = strlen( $diff->text ) - $diff->length;

			// Adjust the cursor position by however much an update affects it.
			if ( $diff->start < $this->bytes_already_parsed ) {
				$this->bytes_already_parsed += $shift;
			}

			// Accumulate shift of the given pointer within this function call.
			if ( $diff->start <= $shift_this_point ) {
				$accumulated_shift_for_given_point += $shift;
			}

			$output_buffer       .= substr( $this->xml, $bytes_already_copied, $diff->start - $bytes_already_copied );
			$output_buffer       .= $diff->text;
			$bytes_already_copied = $diff->start + $diff->length;
		}
		$this->xml = $output_buffer . substr( $this->xml, $bytes_already_copied );

		/*
		 * Adjust bookmark locations to account for how the text
		 * replacements adjust offsets in the input document.
		 */
		foreach ( $this->bookmarks as $bookmark_name => $bookmark ) {
			$bookmark_end = $bookmark->start + $bookmark->length;

			/*
			 * Each lexical update which appears before the bookmark's endpoints
			 * might shift the offsets for those endpoints. Loop through each change
			 * and accumulate the total shift for each bookmark, then apply that
			 * shift after tallying the full delta.
			 */
			$head_delta = 0;
			$tail_delta = 0;

			foreach ( $this->lexical_updates as $diff ) {
				$diff_end = $diff->start + $diff->length;

				if ( $bookmark->start < $diff->start && $bookmark_end < $diff->start ) {
					break;
				}

				if ( $bookmark->start >= $diff->start && $bookmark_end < $diff_end ) {
					$this->release_bookmark( $bookmark_name );
					continue 2;
				}

				$delta = strlen( $diff->text ) - $diff->length;

				if ( $bookmark->start >= $diff->start ) {
					$head_delta += $delta;
				}

				if ( $bookmark_end >= $diff_end ) {
					$tail_delta += $delta;
				}
			}

			$bookmark->start  += $head_delta;
			$bookmark->length += $tail_delta - $head_delta;
		}

		$this->lexical_updates = array();

		return $accumulated_shift_for_given_point;
	}

	/**
	 * Checks whether a bookmark with the given name exists.
	 *
	 * @param  string  $bookmark_name  Name to identify a bookmark that potentially exists.
	 *
	 * @return bool Whether that bookmark exists.
	 * @since WP_VERSION
	 *
	 */
	public function has_bookmark( $bookmark_name ) {
		return array_key_exists( $bookmark_name, $this->bookmarks );
	}

	/**
	 * Move the internal cursor in the Tag Processor to a given bookmark's location.
	 *
	 * Be careful! Seeking backwards to a previous location resets the parser to the
	 * start of the document and reparses the entire contents up until it finds the
	 * sought-after bookmarked location.
	 *
	 * In order to prevent accidental infinite loops, there's a
	 * maximum limit on the number of times seek() can be called.
	 *
	 * @param  string  $bookmark_name  Jump to the place in the document identified by this bookmark name.
	 *
	 * @return bool Whether the internal cursor was successfully moved to the bookmark's location.
	 * @since WP_VERSION
	 *
	 */
	public function seek( $bookmark_name ) {
		if ( ! array_key_exists( $bookmark_name, $this->bookmarks ) ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Unknown bookmark name.' ),
				'WP_VERSION'
			);

			return false;
		}

		if ( ++$this->seek_count > static::MAX_SEEK_OPS ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Too many calls to seek() - this can lead to performance issues.' ),
				'WP_VERSION'
			);

			return false;
		}

		// Flush out any pending updates to the document.
		$this->get_updated_xml();

		// Point this tag processor before the sought tag opener and consume it.
		$this->bytes_already_parsed = $this->bookmarks[ $bookmark_name ]->start;
		$this->parser_state         = self::STATE_READY;

		return $this->parse_next_token();
	}

	/**
	 * Compare two WP_HTML_Text_Replacement objects.
	 *
	 * @param  WP_HTML_Text_Replacement  $a  First object.
	 * @param  WP_HTML_Text_Replacement  $b  Second object.
	 * @return int Comparison result.
	 */
	private static function sort_start_ascending( $a, $b ) {
		return $a->start - $b->start;
	}

	/**
	 * Returns the value of an enqueued attribute update if it exists.
	 *
	 * @param string $name Name of the attribute to check.
	 * @return string|false The enqueued value, or false if no update is enqueued.
	 */
	private function get_enqueued_attribute_value( $name ) {
		if ( ! isset( $this->lexical_updates[ $name ] ) ) {
			return false;
		}

		$enqueued_text = $this->lexical_updates[ $name ]->text;
		$equals_at     = strpos( $enqueued_text, '=' );
		if ( false === $equals_at ) {
			return null;
		}

		// Return the value between the quotes, which start after the equals sign and a space.
		// The value is the substring after the first quote and before the last character in the update.
		$enqueued_value = substr( $enqueued_text, $equals_at + 2, -1 );

		/*
		 * We're deliberately not decoding entities in attribute values:
		 *
		 *     Attribute values must not contain direct or indirect entity references to external entities.
		 *
		 * See https://www.w3.org/TR/xml/#sec-starttags.
		 */

		return $enqueued_value;
	}

	/**
	 * Returns the value of a requested attribute from a matched tag opener if that attribute exists.
	 *
	 * Example:
	 *
	 *     $p = new XMLProcessor(
	 *         '<root xmlns:wp="http://www.w3.org/1999/xhtml">
	 *             <content enabled="true" class="test" data-test-id="14" wp:enabled="true">Test</content>
	 *         </root>'
	 *     );
	 *     $p->next_tag( array( 'http://www.w3.org/1999/xhtml', 'content' ) );
	 *     'true' === $p->get_attribute( 'http://www.w3.org/1999/xhtml', 'enabled' );
	 *     'true' === $p->get_attribute( '', 'enabled' );
	 *
	 * @param string $namespace_reference The namespace of the attribute.
	 * @param string $local_name The local name of the attribute.
	 * @return string|null Value of the attribute, or `null` if the attribute doesn't exist.
	 * @since WP_VERSION
	 *
	 */
	public function get_attribute( $namespace_reference, $local_name ) {
		if (
			self::STATE_MATCHED_TAG !== $this->parser_state
			&&
			self::STATE_XML_DECLARATION !== $this->parser_state
		) {
			return null;
		}

		$full_name = $namespace_reference ? '{' . $namespace_reference . '}' . $local_name : $local_name;

		// Return any enqueued attribute value updates if they exist.
		$enqueued_value = $this->get_enqueued_attribute_value( $full_name );
		if ( false !== $enqueued_value ) {
			return $enqueued_value;
		}

		if ( ! isset( $this->attributes[ $full_name ] ) ) {
			return null;
		}

		$attribute = $this->attributes[ $full_name ];
		$raw_value = substr( $this->xml, $attribute->value_starts_at, $attribute->value_length );

		/*
		 * We're deliberately not decoding entities in attribute values:
		 *
		 *     Attribute values must not contain direct or indirect entity references to external entities.
		 *
		 * See https://www.w3.org/TR/xml/#sec-starttags.
		 */

		return $raw_value;
	}

	/**
	 * Returns the value of a requested attribute from a matched tag opener if that attribute exists.
	 *
	 * @param string $qualified_name The qualified name of the attribute.
	 * @return string|null Value of the attribute, or `null` if the attribute doesn't exist.
	 */
	private function get_qualified_attribute( $qualified_name ) {
		if ( self::STATE_MATCHED_TAG !== $this->parser_state && self::STATE_XML_DECLARATION !== $this->parser_state ) {
			return null;
		}

		// Return any enqueued attribute value updates if they exist.
		$enqueued_value = $this->get_enqueued_attribute_value( $qualified_name );
		if ( false !== $enqueued_value ) {
			return $enqueued_value;
		}

		if ( ! isset( $this->qualified_attributes[ $qualified_name ] ) ) {
			return null;
		}

		$attribute = $this->qualified_attributes[ $qualified_name ];
		$raw_value = substr( $this->xml, $attribute->value_starts_at, $attribute->value_length );

		/*
		 * We're deliberately not decoding entities in attribute values:
		 *
		 *     Attribute values must not contain direct or indirect entity references to external entities.
		 *
		 * See https://www.w3.org/TR/xml/#sec-starttags.
		 */

		return $raw_value;
	}

	/**
	 * Returns an array of attribute names that match a given prefix in the current tag.
	 *
	 * This method allows you to filter attributes by both their namespace prefix (e.g., 'wp') and the start of their local name (e.g., 'data-').
	 * Matching is case-sensitive for both namespace and local name prefixes, in accordance with the XML specification.
	 *
	 * Each returned attribute is represented as a two-element array: [namespace_prefix, local_name].
	 *
	 * Examples:
	 *
	 *     // No namespace, local name prefix only
	 *     $p = new XMLProcessor( '<content data-ENABLED="1" class="test" DATA-test-id="14">Test</content>' );
	 *     $p->next_tag( array( 'class_name' => 'test' ) ) === true;
	 *     $p->get_attribute_names_with_prefix( '', 'data-' );
	 *     // Returns: array( array( '', 'data-ENABLED' ), array( '', 'DATA-test-id' ) )
	 *
	 *     // With namespace prefix
	 *     $p = new XMLProcessor( '<content xmlns:wp="http://wordpress.org/export/1.2/" wp:data-foo="bar" wp:data-bar="baz" data-no-namespace="true" />' );
	 *     $p->next_tag();
	 *     $p->get_attribute_names_with_prefix( 'http://wordpress', 'data-' );
	 *     // Returns: array( array( 'http://wordpress.org/export/1.2/', 'data-foo' ), array( 'http://wordpress.org/export/1.2/', 'data-bar' ) )
	 *
	 *     // Empty string namespace prefix matches all attributes.
	 *     $p->get_attribute_names_with_prefix( '', 'data-' );
	 *     // Returns: array( array( 'http://wordpress.org/export/1.2/', 'data-foo' ), array( 'http://wordpress.org/export/1.2/', 'data-bar' ), array( '', 'data-no-namespace' ) )
	 *
	 *     // Null namespace prefix matches attributes with no namespace.
	 *     $p->get_attribute_names_with_prefix( null, 'data-' );
	 *     // Returns: array( array( '', 'data-no-namespace' ) )
	 *
	 *     // No match for wrong namespace prefix
	 *     $p->get_attribute_names_with_prefix( 'other', 'data-' );
	 *     // Returns: array()
	 *
	 * @param  string $full_namespace_prefix   Prefix of the fully qualified namespace to match on (e.g., 'http://wordpress.org/'). Use '' for no namespace prefix.
	 * @param  string $local_name_prefix       Local name prefix to match (e.g., 'data-').
	 *
	 * @return array|null List of [namespace, local_name] pairs, or `null` when no tag opener is matched.
	 * @since WP_VERSION
	 *
	 */
	public function get_attribute_names_with_prefix( $full_namespace_prefix, $local_name_prefix ) {
		if (
			self::STATE_MATCHED_TAG !== $this->parser_state
			||
			$this->is_closing_tag
		) {
			return null;
		}

		$matches = array();
		foreach ( $this->attributes as $attr ) {
			if ( 0 === strncmp( $attr->local_name, $local_name_prefix, strlen( $local_name_prefix ) ) &&
				(
					// Distinguish between no namespace and empty namespace.
					( null === $full_namespace_prefix && '' === $attr->namespace ) ||
					( null !== $full_namespace_prefix && 0 === strncmp( $attr->namespace, $full_namespace_prefix, strlen( $full_namespace_prefix ) ) )
				)
			) {
				$matches[] = array( $attr->namespace, $attr->local_name );
			}
		}

		return $matches;
	}

	/**
	 * Returns the local name of the matched tag.
	 *
	 * Example without namespaces:
	 *
	 *     $p = new XMLProcessor( '<content class="test">Test</content>' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_local_name() === 'content';
	 *
	 * Example with namespaces:
	 *
	 *     $p = new XMLProcessor( '<root xmlns:wp="http://www.w3.org/1999/xhtml"><wp:content>Test</wp:content></root>' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_local_name() === 'content';
	 *
	 * @return string|null Name of currently matched tag in input XML, or `null` if none found.
	 * @since WP_VERSION
	 */
	public function get_tag_local_name() {
		if ( null !== $this->element ) {
			// Return cached name if we already have it.
			return $this->element->local_name;
		}

		if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
			return null;
		}

		return substr( $this->xml, $this->tag_name_starts_at, $this->tag_name_length );
	}

	/**
	 * Returns the namespace and local name of the matched tag.
	 *
	 * Example:
	 *
	 *     $p = new XMLProcessor( '<root xmlns:wp="http://www.w3.org/1999/xhtml"><wp:content>Test</wp:content></root>' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_namespace_and_local_name() === '{http://www.w3.org/1999/xhtml"}content';
	 *
	 * @return string|null The namespace and local name of the matched tag, or null if not available.
	 */
	public function get_tag_namespace_and_local_name() {
		$namespace = $this->get_tag_namespace();
		if ( ! $namespace ) {
			return $this->get_tag_local_name();
		}

		return '{' . $namespace . '}' . $this->get_tag_local_name();
	}

	/**
	 * Returns the namespace reference of the matched tag.
	 *
	 * Example:
	 *
	 *     $p = new XMLProcessor( '<root xmlns:wp="http://www.w3.org/1999/xhtml"><wp:content>Test</wp:content></root>' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_namespace() === 'http://www.w3.org/1999/xhtml';
	 *
	 * @return string|null The namespace of the matched tag, or null if not available.
	 */
	public function get_tag_namespace() {
		if ( null !== $this->element ) {
			// Return cached name if we already have it.
			return $this->element->namespace;
		}

		if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
			return null;
		}

		return null;
	}

	/**
	 * Returns the qualified name of the matched tag.
	 *
	 * Example:
	 *
	 *     $p = new XMLProcessor( '<root xmlns:wp="http://www.w3.org/1999/xhtml"><wp:content>Test</wp:content></root>' );
	 *     $p->next_tag() === true;
	 *     $p->get_tag_name_qualified() === 'wp:content';
	 *
	 * @return string|null The qualified name of the matched tag, or null if not available.
	 */
	private function get_tag_name_qualified() {
		if ( null !== $this->element ) {
			// Return cached name if we already have it.
			return $this->element->qualified_name;
		}

		if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
			return null;
		}

		return substr( $this->xml, $this->tag_name_starts_at, $this->tag_name_length );
	}

	/**
	 * Returns the modifiable text for a matched token.
	 *
	 * Modifiable text is text content that may be read and changed without
	 * changing the XML structure of the document around it. This includes
	 * the contents of `#text` and `#cdata-section` nodes in the XML as well
	 * as the inner contents of XML comments, Processing Instructions, and
	 * others.
	 *
	 * @return string|false The modifiable text, or `false` if none is available for the current token.
	 */
	public function get_modifiable_text() {
		if ( null === $this->text_starts_at || null === $this->text_length || 0 === $this->text_length ) {
			return '';
		}

		$text = substr( $this->xml, $this->text_starts_at, $this->text_length );

		/*
		 * > the XML processor must behave as if it normalized all line breaks in external parsed
		 * > entities (including the document entity) on input, before parsing, by translating both
		 * > the two-character sequence #xD #xA and any #xD that is not followed by #xA to a single
		 * > #xA character.
		 *
		 * See https://www.w3.org/TR/xml/#sec-line-ends
		 */
		$text = str_replace( array( "\r\n", "\r" ), "\n", $text );

		// Comment data and CDATA sections contents are not decoded any further.
		if (
			self::STATE_CDATA_NODE === $this->parser_state ||
			self::STATE_COMMENT === $this->parser_state
		) {
			return $text;
		}

		$decoded = XMLDecoder::decode( $text );
		if ( ! isset( $decoded ) ) {
			/**
			 * If the attribute contained an invalid value, it's
			 * a fatal error.
			 *
			 * @see WP_XML_Decoder::decode()
			 */

			$this->last_error = self::ERROR_SYNTAX;
			_doing_it_wrong(
				__METHOD__,
				__( 'Invalid text content encountered.' ),
				'WP_VERSION'
			);

			return false;
		}

		return $decoded;
	}

	/**
	 * Updates the modifiable text for a matched token.
	 *
	 * Modifiable text is text content that may be read and changed without
	 * changing the XML structure of the document around it. This includes
	 * the contents of `#text` and `#cdata-section` nodes in the XML as well
	 * as the inner contents of XML comments, Processing Instructions, and
	 * others.
	 *
	 * @param string $new_text The new text to set.
	 * @return bool Whether the text was successfully updated.
	 */
	public function set_modifiable_text( $new_text ) {
		if ( null === $this->text_starts_at || null === $this->text_length ) {
			return false;
		}

		$this->lexical_updates[] = new WP_HTML_Text_Replacement( $this->text_starts_at, $this->text_length, $new_text );

		return true;
	}

	/**
	 * Removes an attribute from the currently matched tag.
	 *
	 * @param string $namespace_reference The namespace of the attribute.
	 * @param string $local_name The local name of the attribute.
	 * @return bool Whether the attribute was successfully removed.
	 */
	public function remove_attribute( $namespace_reference, $local_name ) {
		if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
			return false;
		}

		$name = $namespace_reference ? '{' . $namespace_reference . '}' . $local_name : $local_name;

		/*
		 * If this attribute was already updated, but hasn't been applied,
		 * then remove the enqueued update and move on.
		 *
		 * For example, this might occur when calling `remove_attribute()`
		 * after calling `set_attribute()` for the same attribute
		 * and when that attribute wasn't originally present.
		 */
		if ( ! isset( $this->attributes[ $name ] ) ) {
			if ( isset( $this->lexical_updates[ $name ] ) ) {
				unset( $this->lexical_updates[ $name ] );
			}

			return false;
		}

		/*
		 * Removes an existing tag attribute.
		 *
		 * Example â€“ remove the attribute id from <content id="initial_id"/>:
		 *    <content id="initial_id"/>
		 *             ^-------------^
		 *           start          end
		 *   replacement: ``
		 *
		 *    Result: <content />
		 */
		$this->lexical_updates[ $name ] = new WP_HTML_Text_Replacement(
			$this->attributes[ $name ]->start,
			$this->attributes[ $name ]->length,
			''
		);

		return true;
	}

	/**
	 * Sets an attribute on the currently matched tag.
	 *
	 * @param string $namespace_reference The namespace of the attribute.
	 * @param string $local_name The local name of the attribute.
	 * @param string $value The value of the attribute.
	 * @return bool Whether the attribute was successfully set.
	 */
	public function set_attribute( $namespace_reference, $local_name, $value ) {
		if ( self::STATE_MATCHED_TAG !== $this->parser_state ) {
			return false;
		}

		$full_name = $namespace_reference ? '{' . $namespace_reference . '}' . $local_name : $local_name;

		// If the attribute already exists, replace its value.
		if ( isset( $this->attributes[ $full_name ] ) ) {
			$this->lexical_updates[ $full_name ] = new WP_HTML_Text_Replacement(
				$this->attributes[ $full_name ]->value_starts_at,
				$this->attributes[ $full_name ]->value_length,
				$value
			);

			return true;
		}

		// If the attribute doesn't exist, add it.
		$prefix = $this->get_tag_namespace_prefix( $namespace_reference );
		if ( null === $prefix ) {
			// This should not happen, as the namespace should be in scope.
			return false;
		}
		$qualified_name = $prefix ? $prefix . ':' . $local_name : $local_name;

		$new_attribute = " {$qualified_name}=\"{$value}\"";

		$this->lexical_updates[ $full_name ] = new WP_HTML_Text_Replacement(
			$this->tag_name_starts_at + $this->tag_name_length,
			0,
			$new_attribute
		);

		return true;
	}

	/**
	 * Returns the XML document with all updates applied.
	 *
	 * @return string The updated XML document.
	 */
	public function get_updated_xml() {
		/*
		 * Keep track of the position right before the current token. This will
		 * be necessary for reparsing the current token after updating the XML.
		 */
		$before_current_token = isset( $this->token_starts_at ) ? $this->token_starts_at : 0;

		/*
		 * 1. Apply the enqueued edits and update all the pointers to reflect those changes.
		 */
		$before_current_token += $this->apply_lexical_updates( $before_current_token );

		/*
		 * 2. Rewind to before the current tag and reparse to get updated attributes.
		 *
		 * At this point the internal cursor points to the end of the tag name.
		 * Rewind before the tag name starts so that it's as if the cursor didn't
		 * move; a call to `next_tag()` will reparse the recently-updated attributes
		 * and additional calls to modify the attributes will apply at this same
		 * location, but in order to avoid issues with subclasses that might add
		 * behaviors to `next_tag()`, the internal methods should be called here
		 * instead.
		 *
		 * It's important to note that in this specific place there will be no change
		 * because the processor was already at a tag when this was called and it's
		 * rewinding only to the beginning of this very tag before reprocessing it
		 * and its attributes.
		 *
		 * <p>Previous XML<em>More XML</em></p>
		 *                 â†‘  â”‚ back up by the length of the tag name plus the opening <
		 *                 â””â†â”€â”˜ back up by strlen("em") + 1 ==> 3
		 */
		$this->bytes_already_parsed = $before_current_token;
		$this->parse_next_token();

		return $this->xml;
	}

	/**
	 * Finds the next token in the XML document.
	 *
	 * An XML document can be viewed as a stream of tokens,
	 * where tokens are things like XML tags, XML comments,
	 * text nodes, etc. This method finds the next token in
	 * the XML document and returns whether it found one.
	 *
	 * If it starts parsing a token and reaches the end of the
	 * document then it will seek to the start of the last
	 * token and pause, returning `false` to indicate that it
	 * failed to find a complete token.
	 *
	 * Possible token types, based on the XML specification:
	 *
	 *  - an XML tag
	 *  - a text node - the plaintext inside tags.
	 *  - a CData section
	 *  - an XML comment.
	 *  - a DOCTYPE declaration.
	 *  - a processing instruction, e.g. `<?xml version="1.0"?>`
	 *
	 * @return bool Whether a token was found.
	 */
	public function step( $node_to_process = self::PROCESS_NEXT_NODE ) {
		if ( self::PROCESS_NEXT_NODE === $node_to_process ) {
			$has_next_node = $this->parse_next_token();
			if ( false === $has_next_node && ! $this->expecting_more_input ) {
				$this->bail( 'A tag was not closed.', self::ERROR_SYNTAX );
			}
		}

		if ( null !== $this->last_error ) {
			return false;
		}

		// Finish stepping when there are no more tokens in the document.
		if (
			self::STATE_INCOMPLETE_INPUT === $this->parser_state
			||
			self::STATE_COMPLETE === $this->parser_state
		) {
			return false;
		}

		if ( self::PROCESS_NEXT_NODE === $node_to_process ) {
			if ( $this->is_empty_element() ) {
				array_pop( $this->stack_of_open_elements );
			}
		}

		try {
			switch ( $this->parser_context ) {
				case self::IN_PROLOG_CONTEXT:
					return $this->step_in_prolog( $node_to_process );
				case self::IN_ELEMENT_CONTEXT:
					return $this->step_in_element( $node_to_process );
				case self::IN_MISC_CONTEXT:
					return $this->step_in_misc( $node_to_process );
				default:
					$this->bail( 'Unknown parser context.', self::ERROR_SYNTAX );
			}
		} catch ( XMLUnsupportedException $e ) {
			$this->last_error = $e;
			return false;
		}
	}

	/**
	 * Parses the next node in the 'prolog' part of the XML document.
	 *
	 * @return bool Whether a node was found.
	 * @see https://www.w3.org/TR/xml/#NT-document.
	 * @see XMLProcessor::step
	 *
	 * @since WP_VERSION
	 *
	 */
	private function step_in_prolog( $node_to_process = self::PROCESS_NEXT_NODE ) {
		if ( self::PROCESS_NEXT_NODE === $node_to_process ) {
			$has_next_node = $this->parse_next_token();
			if ( false === $has_next_node && ! $this->expecting_more_input ) {
				$this->bail( 'The root element was not found.', self::ERROR_SYNTAX );
			}
		}

		// XML requires a root element. If we've reached the end of data in the prolog stage,
		// before finding a root element, then the document is incomplete.
		if ( self::STATE_COMPLETE === $this->parser_state ) {
			$this->mark_incomplete_input();

			return false;
		}
		// Do not step if we paused due to an incomplete input.
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			return false;
		}
		switch ( $this->get_token_type() ) {
			case '#text':
				$text        = $this->get_modifiable_text();
				$whitespaces = strspn( $text, " \t\n\r" );
				if ( strlen( $text ) !== $whitespaces ) {
					// @TODO: Only look for this in the 2 initial bytes of the document:
					if ( substr( $text, 0, 2 ) == "\xFF\xFE" ) {
						$this->bail( 'Unexpected UTF-16 BOM byte sequence (0xFFFE) in the document. XMLProcessor only supports UTF-8.', self::ERROR_SYNTAX );
					}
					$this->bail( 'Unexpected non-whitespace text token in prolog stage.', self::ERROR_SYNTAX );
				}

				return $this->step();
			// @TODO: Fail if there's more than one <!DOCTYPE> or if <!DOCTYPE> was found before the XML declaration token.
			case '#doctype':
			case '#comment':
			case '#xml-declaration':
			case '#processing-instructions':
				return true;
			case '#tag':
				$this->parser_context = self::IN_ELEMENT_CONTEXT;

				return $this->step( self::PROCESS_CURRENT_NODE );
			default:
				$this->bail( 'Unexpected token type in prolog stage.', self::ERROR_SYNTAX );
		}
	}

	/**
	 * Parses the next node in the 'element' part of the XML document.
	 *
	 * @return bool Whether a node was found.
	 * @see https://www.w3.org/TR/xml/#NT-document.
	 * @see XMLProcessor::step
	 *
	 * @since WP_VERSION
	 *
	 */
	private function step_in_element( $node_to_process = self::PROCESS_NEXT_NODE ) {
		if ( self::PROCESS_NEXT_NODE === $node_to_process ) {
			$has_next_node = $this->parse_next_token();
			if ( false === $has_next_node && ! $this->expecting_more_input ) {
				$this->bail( 'A tag was not closed.', self::ERROR_SYNTAX );
			}
		}

		// Do not step if we paused due to an incomplete input.
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			return false;
		}

		switch ( $this->get_token_type() ) {
			case '#text':
			case '#cdata-section':
			case '#comment':
			case '#processing-instructions':
				return true;
			case '#tag':
				// Update the stack of open elements
				$tag_qname = $this->get_tag_name_qualified();
				if ( $this->is_tag_closer() ) {
					if ( ! count( $this->stack_of_open_elements ) ) {
						$this->bail(
							__( 'The closing tag "%1$s" did not match the opening tag "%2$s".' ),
							$tag_qname,
							$tag_qname
						);
						return false;
					}
					$this->element = array_pop( $this->stack_of_open_elements );
					$popped_qname  = $this->element->qualified_name;
					if ( $popped_qname !== $tag_qname ) {
						$this->bail(
							sprintf(
								'The closing tag "%1$s" did not match the opening tag "%2$s".',
								$tag_qname,
								$popped_qname
							),
							self::ERROR_SYNTAX
						);
						return false;
					}
					if ( 0 === count( $this->stack_of_open_elements ) ) {
						$this->parser_context = self::IN_MISC_CONTEXT;
					}
				} else {
					$namespaces_in_scope = $this->get_tag_namespaces_in_scope();
					foreach ( $this->attributes as $attribute ) {
						if ( 'xmlns' === $attribute->namespace_prefix ) {
							$namespaces_in_scope[ $attribute->local_name ] = $this->get_attribute( $attribute->namespace, $attribute->local_name );
						} elseif ( 'xmlns' === $attribute->local_name && '' === $attribute->namespace_prefix ) {
							$namespaces_in_scope[''] = $this->get_attribute( $attribute->namespace, $attribute->local_name );
						}
					}
					list( $namespace_prefix, $local_name ) = $this->parse_qualified_name( $tag_qname );
					if ( ! isset( $namespaces_in_scope[ $namespace_prefix ] ) ) {
						$this->bail( 'Undeclared namespace prefix found in a tag.', self::ERROR_SYNTAX );
						return false;
					}
					$namespace     = $namespaces_in_scope[ $namespace_prefix ];
					$this->element = new XMLElement( $local_name, $namespace_prefix, $namespace, $namespaces_in_scope );
					array_push( $this->stack_of_open_elements, $this->element );
				}
				return true;
			default:
				$this->bail(
					sprintf(
						__( 'Unexpected token type "%1$s" in element stage. This is a bug in the XML Processor. Please report it on https://github.com/WordPress/wordpress-data-liberation' ),
						$this->get_token_type()
					),
					self::ERROR_SYNTAX
				);
		}
	}

	/**
	 * Parses the next node in the 'misc' part of the XML document.
	 *
	 * @return bool Whether a node was found.
	 * @see https://www.w3.org/TR/xml/#NT-document.
	 * @see XMLProcessor::step
	 *
	 * @since WP_VERSION
	 *
	 */
	private function step_in_misc( $node_to_process = self::PROCESS_NEXT_NODE ) {
		if ( self::PROCESS_NEXT_NODE === $node_to_process ) {
			$has_next_node = $this->parse_next_token();
			if ( false === $has_next_node && ! $this->expecting_more_input ) {
				// Parsing is complete.
				$this->parser_state = self::STATE_COMPLETE;

				return true;
			}
		}

		// Do not step if we paused due to an incomplete input.
		if ( self::STATE_INCOMPLETE_INPUT === $this->parser_state ) {
			return false;
		}

		if ( self::STATE_COMPLETE === $this->parser_state ) {
			return true;
		}

		switch ( $this->get_token_type() ) {
			case '#comment':
			case '#processing-instructions':
				return true;
			case '#text':
				$text        = $this->get_modifiable_text();
				$whitespaces = strspn( $text, " \t\n\r" );
				if ( strlen( $text ) !== $whitespaces ) {
					$this->bail( 'Unexpected token type "' . $this->get_token_type() . '" in misc stage.', self::ERROR_SYNTAX );
				}

				return $this->step();
			default:
				$this->bail( 'Unexpected token type "' . $this->get_token_type() . '" in misc stage.', self::ERROR_SYNTAX );
		}
	}

	/**
	 * Computes the XML breadcrumbs for the currently-matched element, if matched.
	 *
	 * Breadcrumbs start at the outermost parent and descend toward the matched element.
	 * They always include the entire path from the root XML node to the matched element.
	 * Example
	 *
	 *     $processor = XMLProcessor::create_fragment( '<p><strong><em><img/></em></strong></p>' );
	 *     $processor->next_tag( 'img' );
	 *     $processor->get_breadcrumbs() === array( 'p', 'strong', 'em', 'img' );
	 *
	 * @return string[]|null Array of tag names representing path to matched node, if matched, otherwise NULL.
	 * @since WP_VERSION
	 *
	 */
	public function get_breadcrumbs() {
		return array_map(
			function ( $element ) {
				return array( $element->namespace, $element->local_name );
			},
			$this->stack_of_open_elements
		);
	}

	/**
	 * Returns whether the currently-matched tag is found at the given DOM sub-path.
	 *
	 * Example:
	 *
	 *     $processor = XMLProcessor::create_fragment( '<article><section><p><img></p></section></article>' );
	 *     $processor->next_tag( 'img' );
	 *     true  === $processor->matches_breadcrumbs( array( 'article', 'section', 'p', 'img' ) );
	 *     true  === $processor->matches_breadcrumbs( array( 'p', 'img' ) );
	 *     false === $processor->matches_breadcrumbs( array( 'article', 'p', 'img' ) );
	 *     true  === $processor->matches_breadcrumbs( array( 'article', '*', 'p', 'img' ) );
	 *
	 * Example with namespaces:
	 *
	 *     $processor = XMLProcessor::create_fragment( '<wp:post><wp:content><wp:image/></wp:content></wp:post>' );
	 *     $processor->next_tag( 'wp:image' );
	 *     true  === $processor->matches_breadcrumbs( array( 'wp:post', 'wp:content', 'wp:image' ) );
	 *     true  === $processor->matches_breadcrumbs( array( 'wp:content', 'wp:image' ) );
	 *     false === $processor->matches_breadcrumbs( array( 'wp:post', 'wp:image' ) );
	 *     true  === $processor->matches_breadcrumbs( array( 'wp:post', '*', 'wp:image' ) );
	 *
	 * @param  string[]  $breadcrumbs  DOM sub-path at which element is found, e.g. `array( 'content', 'image' )`.
	 *                               May also contain the wildcard `*` which matches a single element, e.g. `array( 'post', '*' )`.
	 *
	 * @return bool Whether the currently-matched tag is found at the given nested structure.
	 * @since WP_VERSION
	 *
	 */
	public function matches_breadcrumbs( $breadcrumbs ) {
		// Everything matches when there are zero constraints.
		if ( 0 === count( $breadcrumbs ) ) {
			return true;
		}

		// Start at the last crumb.
		$crumb = end( $breadcrumbs );

		if ( '#tag' !== $this->get_token_type() ) {
			return false;
		}

		$open_elements = $this->stack_of_open_elements;
		$crumb_count   = count( $breadcrumbs );
		$elem_count    = count( $open_elements );

		// Walk backwards through both arrays, matching each crumb to the corresponding open element.
		for ( $j = 1; $j <= $crumb_count; $j++ ) {
			$crumb   = $breadcrumbs[ $crumb_count - $j ];
			$element = isset( $open_elements[ $elem_count - $j ] ) ? $open_elements[ $elem_count - $j ] : null;

			if ( ! $element ) {
				return false;
			}

			// Normalize crumb to [namespace, local_name]
			if ( ! is_array( $crumb ) ) {
				if ( '*' === $crumb ) {
					$crumb = array( '*', '*' );
				} else {
					$crumb = array( '*', $crumb );
				}
			}
			list( $namespace, $local_name ) = $crumb;

			// Match local name, respecting wildcard '*'
			if ( '*' !== $local_name && $local_name !== $element->local_name ) {
				return false;
			}

			// Match namespace, respecting wildcard '*'
			if ( '*' !== $namespace && $namespace !== $element->namespace ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns the nesting depth of the current location in the document.
	 *
	 * Example:
	 *
	 *     $processor = new XMLProcessor( '<?xml version="1.0" ?><root><text></text></root>' );
	 *     0 === $processor->get_current_depth();
	 *
	 *     $processor->next_tag( 'root' );
	 *     1 === $processor->get_current_depth();
	 *
	 *     $processor->next_tag( 'text' );
	 *     2 === $processor->get_current_depth();
	 *
	 * @return int The current nesting depth.
	 */
	public function get_current_depth() {
		return count( $this->stack_of_open_elements );
	}

	/**
	 * Parses a qualified name into its namespace prefix and local name.
	 *
	 * Example:
	 *
	 *     $this->parse_qualified_name( 'wp:post' ); // Returns array( 'wp.org', 'post' )
	 *     $this->parse_qualified_name( 'image' ); // Returns array( '', 'image' )
	 *
	 * @param  string  $qualified_name  The qualified name to parse.
	 *
	 * @return array<string, string> The namespace prefix and local name.
	 */
	private function parse_qualified_name( $qualified_name ) {
		$namespace_prefix = '';
		$local_name       = $qualified_name;

		$prefix_length = strcspn( $qualified_name, ':' );
		if ( null !== $prefix_length && strlen( $qualified_name ) !== $prefix_length ) {
			$namespace_prefix = substr( $qualified_name, 0, $prefix_length );
			$local_name       = substr( $qualified_name, $prefix_length + 1 );
		}

		return array( $namespace_prefix, $local_name );
	}

	/**
	 * Parser Ready State.
	 *
	 * Indicates that the parser is ready to run and waiting for a state transition.
	 * It may not have started yet, or it may have just finished parsing a token and
	 * is ready to find the next one.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_READY = 'STATE_READY';

	/**
	 * Parser Complete State.
	 *
	 * Indicates that the parser has reached the end of the document and there is
	 * nothing left to scan. It finished parsing the last token completely.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_COMPLETE = 'STATE_COMPLETE';

	/**
	 * Parser Incomplete Input State.
	 *
	 * Indicates that the parser has reached the end of the document before finishing
	 * a token. It started parsing a token but there is a possibility that the input
	 * XML document was truncated in the middle of a token.
	 *
	 * The parser is reset at the start of the incomplete token and has paused. There
	 * is nothing more than can be scanned unless provided a more complete document.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_INCOMPLETE_INPUT = 'STATE_INCOMPLETE_INPUT';

	/**
	 * Parser Invalid Input State.
	 *
	 * Indicates that the parsed xml document contains malformed input and cannot be parsed.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_INVALID_DOCUMENT = 'STATE_INVALID_DOCUMENT';

	/**
	 * Parser Matched Tag State.
	 *
	 * Indicates that the parser has found an XML tag and it's possible to get
	 * the tag name and read or modify its attributes (if it's not a closing tag).
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_MATCHED_TAG = 'STATE_MATCHED_TAG';

	/**
	 * Parser Text Node State.
	 *
	 * Indicates that the parser has found a text node and it's possible
	 * to read and modify that text.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_TEXT_NODE = 'STATE_TEXT_NODE';

	/**
	 * Parser CDATA Node State.
	 *
	 * Indicates that the parser has found a CDATA node and it's possible
	 * to read and modify its modifiable text. Note that in XML there are
	 * no CDATA nodes outside of foreign content (SVG and MathML). Outside
	 * of foreign content, they are treated as XML comments.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_CDATA_NODE = 'STATE_CDATA_NODE';

	/**
	 * Parser DOCTYPE Node State.
	 *
	 * Indicates that the parser has found a DOCTYPE declaration and it's possible
	 * to read and modify its modifiable text.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_DOCTYPE_NODE = 'STATE_DOCTYPE_NODE';

	/**
	 * Indicates that the parser has found an XML processing instruction.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_PI_NODE = 'STATE_PI_NODE';

	/**
	 * Indicates that the parser has found an XML declaration
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_XML_DECLARATION = 'STATE_XML_DECLARATION';

	/**
	 * Indicates that the parser has found an XML comment and it's
	 * possible to read and modify its modifiable text.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const STATE_COMMENT = 'STATE_COMMENT';

	/**
	 * Indicates that the parser encountered unsupported syntax and has bailed.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const ERROR_UNSUPPORTED_SYNTAX = 'unsupported-syntax';

	/**
	 * Indicates that the parser encountered a syntax error and has bailed.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const ERROR_SYNTAX = 'syntax';

	/**
	 * Indicates that the provided XML document contains a declaration that is
	 * unsupported by the parser.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const ERROR_UNSUPPORTED = 'unsupported';

	/**
	 * Indicates that the parser encountered more XML tokens than it
	 * was able to process and has bailed.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const ERROR_EXCEEDED_MAX_BOOKMARKS = 'exceeded-max-bookmarks';


	/**
	 * Indicates that we're parsing the `prolog` part of the XML
	 * document.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const IN_PROLOG_CONTEXT = 'prolog';

	/**
	 * Indicates that we're parsing the `element` part of the XML
	 * document.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const IN_ELEMENT_CONTEXT = 'element';

	/**
	 * Indicates that we're parsing the `misc` part of the XML
	 * document.
	 *
	 * @since WP_VERSION
	 *
	 * @access private
	 */
	const IN_MISC_CONTEXT = 'misc';

	/**
	 * Indicates that the next HTML token should be parsed and processed.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const PROCESS_NEXT_NODE = 'process-next-node';

	/**
	 * Indicates that the current HTML token should be processed without advancing the parser.
	 *
	 * @since WP_VERSION
	 *
	 * @var string
	 */
	const PROCESS_CURRENT_NODE = 'process-current-node';


	/**
	 * Unlock code that must be passed into the constructor to create this class.
	 *
	 * This class extends the WP_HTML_Tag_Processor, which has a public class
	 * constructor. Therefore, it's not possible to have a private constructor here.
	 *
	 * This unlock code is used to ensure that anyone calling the constructor is
	 * doing so with a full understanding that it's intended to be a private API.
	 *
	 * @access private
	 */
	const CONSTRUCTOR_UNLOCK_CODE = 'Use WP_HTML_Processor::create_fragment() instead of calling the class constructor directly.';
}
