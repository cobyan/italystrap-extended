<?php
/**
 * Abstract class for Fields
 *
 * This is the abstract class for Fields functionality
 *
 * @since 2.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Admin;

/**
 * Abstract class for fields functionality
 */
abstract class A_Fields implements I_Fields {

	/**
	 * Determine whether this field should show, based on the 'show_on_cb' callback.
	 * Forked from CMB2
	 * @see CMB2_Field.php
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether the field should be shown.
	 */
	public function should_show( $args ) {
		
		/**
		 * Default. Show the field
		 *
		 * @var bool
		 */
		$show = true;

		if ( ! isset( $args[ 'show_on_cb' ] ) ) {
			return $show;
		}

		/**
		 * Use the callback to determine showing the field, if it exists
		 */
		if ( is_callable( $args[ 'show_on_cb' ] ) ) {
			$show = call_user_func( $args[ 'show_on_cb' ], $this );
		}

		return $show;
	}

	/**
	 * Combines attributes into a string for a form element
	 *
	 * @since  2.0.0
	 * @param  array $attrs        Attributes to concatenate.
	 * @param  array $attr_exclude Attributes that should NOT be concatenated.
	 *
	 * @return string               String of attributes for form element
	 */
	public function concat_attrs( $attrs, $attr_exclude = array() ) {
		$attributes = '';
		foreach ( $attrs as $attr => $val ) {
			$excluded = in_array( $attr, (array) $attr_exclude, true );
			$empty    = false === $val && 'value' !== $attr;
			if ( ! $excluded && ! $empty ) {
				// If data attribute, use single quote wraps, else double.
				$quotes = stripos( $attr, 'data-' ) !== false ? "'" : '"';
				$attributes .= sprintf( ' %1$s=%3$s%2$s%3$s', $attr, $val, $quotes );
			}
		}
		return $attributes;
	}

	/**
	 * Handles outputting an 'input' element
	 *
	 * @link http://html5doctor.com/html5-forms-input-types/
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 *
	 * @return string     Form input element
	 */
	public function input( array $attr = array(), array $key = array() ) {

		$a = wp_parse_args( $attr, array(
			'type'				=> 'text',
			'class'				=> esc_attr( isset( $key['class'] ) ? $key['class'] : 'none' ),
			'name'				=> esc_attr( $key['_name'] ),
			'id'				=> esc_attr( $key['_id'] ),
			'value'				=> isset( $key['value'] ) ? esc_attr( $key['value'] ) : ( isset( $key['default'] ) ? esc_attr( $key['default'] ) : '' ),
			'desc'				=> $this->field_type_description( $key['desc'] ),
			'js_dependencies'	=> array(),
		) );

		if ( isset( $key['size'] ) ) {
			$a['size'] = esc_attr( $key['size'] );
		}

		if ( isset( $key['placeholder'] ) ) {
			$a['placeholder'] = esc_attr( $key['placeholder'] );
		}

		// if ( ! empty( $a['js_dependencies'] ) ) {
		// 	CMB2_JS::add_dependencies( $a['js_dependencies'] );
		// }

		return sprintf( '<input%s/>%s', $this->concat_attrs( $a, array( 'desc', 'js_dependencies' ) ), $a['desc'] );
	}

	/**
	 * Handles outputting an 'textarea' element
	 *
	 * @since  2.0.0
	 * @param  array $attr Override arguments.
	 * @param  array $key Override arguments.
	 *
	 * @return string      Form textarea element
	 */
	public function textarea( array $attr = array(), array $key = array() ) {
		$a = wp_parse_args( $attr, array(
			'class' => esc_attr( isset( $key['class'] ) ? $key['class'] : '' ),
			'name'  => esc_attr( $key['_name'] ),
			'id'    => esc_attr( $key['_id'] ),
			'cols'  => '60',
			'rows'  => '10',
			'value' => esc_attr( isset( $key['value'] ) ? $key['value'] : ( isset( $key['default'] ) ? $key['default'] : '' ) ),
			'desc'  => $this->field_type_description( $key['desc'] ),
		) );
		return sprintf( '<textarea%s>%s</textarea>%s', $this->concat_attrs( $a, array( 'desc', 'value' ) ), $a['value'], $a['desc'] );
	}

	/**
	 * Get element with image for media fields
	 *
	 * @param  int $id The ID of the image.
	 * @param  string $text The text.
	 * @return string        The HTML of the element with image
	 */
	public function get_el_media_field( $id ) {
	
		$attr = array(
			'data-id'	=> $id,
		);
		$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );

		if ( '' === $output ) {
			$id = (int) get_post_thumbnail_id( $id );
			$output = wp_get_attachment_image( $id , 'thumbnail', false, $attr );
		}

		if ( $output ) {
			echo '<li class="carousel-image ui-state-default"><div><i class="dashicons dashicons-no"></i>' . $output . '</div></li>';// XSS ok.
		}
	}
}
