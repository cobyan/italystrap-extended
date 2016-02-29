<?php namespace ItalyStrap\Core;
/**
 * Validation API: Validation Class
 *
 * @package ItalyStrap
 * @since 2.0.0
 */

if ( ! defined( 'ITALYSTRAP_PLUGIN' ) or ! ITALYSTRAP_PLUGIN ) {
	die();
}

/**
 * Validation class
 */
class Validation {

	/**
	 * Validate the value of key
	 *
	 * @access private
	 * @param  string $rules          Insert the rule name you want to use
	 *                                for validation.
	 *                                Use | to separate more rules.
	 * @param  string $instance_value The value you want to validate.
	 * @return bool                   Return true if valid and folse if it is not
	 */
	public function validate( $rules, $instance_value ) {
		$rules = explode( '|', $rules );

		if ( empty( $rules ) || count( $rules ) < 1 ) {
			return true;
		}

		foreach ( $rules as $rule ) {
			if ( false === $this->do_validation( $rule, $instance_value ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Validate the value of key
	 *
	 * @access private
	 * @param  string $rule           Insert the rule name you want to use for validation.
	 * @param  string $instance_value The value you want to validate.
	 * @return string                 Return the value validated
	 */
	public function do_validation( $rule, $instance_value = '' ) {
		switch ( $rule ) {

			case 'ctype_alpha':
				return ctype_alpha( $instance_value );
			break;

			case 'ctype_alnum':
				return ctype_alnum( $instance_value );
			break;

			case 'alpha_dash':
				return preg_match( '/^[a-z0-9-_]+$/', $instance_value );
			break;

			case 'numeric':
				return ctype_digit( $instance_value );
			break;

			case 'integer':
				return (bool) preg_match( '/^[\-+]?[0-9]+$/', $instance_value );
			break;

			case 'boolean':
				return is_bool( $instance_value );
			break;

			case 'email':
				return is_email( $instance_value );
			break;

			case 'decimal':
				return (bool) preg_match( '/^[\-+]?[0-9]+\.[0-9]+$/', $instance_value );
			break;

			case 'natural':
				return (bool) preg_match( '/^[0-9]+$/', $instance_value );
			return;

			case 'natural_not_zero':
				if ( ! preg_match( '/^[0-9]+$/', $instance_value ) ) { return false; }
				if ( 0 === $instance_value ) { return false; }
				return true;
			return;

			default:
				if ( method_exists( $this, $rule ) ) {
					return $this->$rule( $instance_value );
				} else { return false; }
			break;

		}
	}
}
