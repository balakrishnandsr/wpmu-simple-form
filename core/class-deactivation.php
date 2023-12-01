<?php
/**
 * Class is called upon plugin Deactivation.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\Core
 */

namespace WPMU_SF\Core;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;


/**
 * Class Activation
 *
 * @package WPMU_SF\Core
 */
final class Deactivation {
	/**
	 * Variable to hold instance of deactivation class.
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Get instance of deactivation class.
	 *
	 * @return Deactivation  object of deactivation class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Deactivation hooks.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function __construct() {
		do_action( 'wpmusf_plugin_deactivated' );
	}



}
