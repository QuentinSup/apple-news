<?php
namespace Exporter\Components;

require_once __DIR__ . '/../class-markdown.php';

/**
 * Base component class. All components must inherit from this class and
 * implement its abstract method "build".
 *
 * @since 0.0.0
 */
abstract class Component {

	protected $workspace;
	protected $text;
	protected $json = null;

	/**
	 * Given a DomNode, if it matches the component, return the relevant node to
	 * work on. Otherwise, return null.
	 */
	public static function node_matches( $node ) {
		return null;
	}

	function __construct( $text, $workspace, $markdown = null ) {
		$this->text      = $text;
		$this->workspace = $workspace;
		$this->markdown  = $markdown ?: new \Exporter\Markdown();
	}

	/**
	 * Given a source (either a file path or an URL) gets the contents and writes
	 * them into a file with the given filename.
	 *
	 * @param string $filename  The name of the file to be created
	 * @param string $source    The path or URL of the resource which is going to
	 *                          be bundled
	 */
	protected function bundle_source( $filename, $source ) {
		$content = $this->workspace->get_file_contents( $source );
		$this->workspace->write_tmp_file( $filename, $content );
	}

	/**
	 * Lazily transforms HTML into an array that describes the component using
	 * the build function.
	 */
	public function value() {
		// Lazy value evaluation
		if ( is_null( $this->json ) ) {
			$this->build( $this->text );
		}

		return $this->json;
	}

	protected static function node_find_by_tagname( $node, $tagname ) {
		$result = self::node_find_all_by_tagname( $node, $tagname );

		if( $result ) {
			return $result->item( 0 );
		}

		return false;
	}

	protected static function node_find_all_by_tagname( $node, $tagname ) {
		if ( ! method_exists( $node, 'getElementsByTagName' ) ) {
			return false;
		}

		$elements = $node->getElementsByTagName( $tagname );

		if ( $elements->length == 0 ) {
			return false;
		}

		return $elements;
	}


	protected static function node_has_class( $node, $classname ) {
		if ( ! method_exists( $node, 'getAttribute' ) ) {
			return false;
		}

		$classes = trim( $node->getAttribute( 'class' ) );

		if ( empty( $classes ) ) {
			return false;
		}

		return 1 == preg_match( "/(?:\s+|^)$classname(?:\s+|$)/", $classes );
	}

	/**
	 * This function is in charge of transforming HTML into a Article Format
	 * valid array.
	 */
	abstract protected function build( $text );

}
