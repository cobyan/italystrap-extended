<?php
/**
 * Post Meta Class
 *
 * Get the post meta and apply some functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

if ( ! defined( 'ABSPATH' ) or ! ABSPATH ) {
	die();
}

/**
 * The Post Meta Class
 */
class Post_Meta extends A_Post_Meta {

	/**
	 * Init the constructor
	 *
	 * @param array $options The plugin options
	 */
	function __construct( array $options = array() ) {

		/**
		 * Start with an underscore to hide fields from custom fields list
		 *
		 * @var string
		 */
		$this->prefix = 'italystrap';

		$this->_prefix = '_' . $this->prefix;

		$this->options = $options;

		parent::__construct( $options );

	}

	/**
	 * Take the custom css from WP Editor and
	 * add it to the ItalyStrapGlobalsCss::set( $style ).
	 */
	public function add_post_type_custom_css() {

		$style = $this->get_metabox( get_the_id(), $this->_prefix . '_custom_css_settings', true );

		ItalyStrapGlobalsCss::set( $style );

	}

	/**
	 * Get classes
	 *
	 * @param  string $filter_name The name of the filter.
	 * @param  array  $classes The array with body classes.
	 *
	 * @return array               The new array
	 */
	public function get_class( $filter_name, array $classes ) {

		$class_name = '';

		if ( isset( $this->options[ $filter_name ] ) ) {
			$class_name = $this->options[ $filter_name ] . ',';
		}

		$class_name .= $this->get_metabox( get_the_id(), $this->_prefix . '_custom_' . $filter_name .'_settings', true );

		$class_name = array_filter( explode( ',', $class_name ) );

		foreach ( $class_name as $key => $value ) {
			$classes[] = $value;
		}

		return $classes;

	}

	/**
	 * Add body class to the body_class filter
	 *
	 * @param  array $classes The array with body classes.
	 *
	 * @return array          The new array
	 */
	function body_class( $classes ) {
		return $this->get_class( 'body_class', $classes );
	}

	/**
	 * Add post class to the post_class filter
	 *
	 * @param  array $classes The array with post classes.
	 *
	 * @return array          The new array
	 */
	function post_class( $classes ) {
		return $this->get_class( 'post_class', $classes );
	}
}