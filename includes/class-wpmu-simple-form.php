<?php
/**
 * WPMU Simple Form
 *
 * @package wpmu-simple-form/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( ' WPMU_Simple_Form' ) ) {

	/**
	 * Main Class for WPMU Simple Form plugin.
	 */
	class WPMU_Simple_Form {

		/**
		 * Variable to hold instance of WPMU Simple Form.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WPMU Simple Form.
		 *
		 * @return WPMU_Simple_Form Singleton object of WPMU_Simple_Form
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Cloning is forbidden.
		 */
		private function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wpmu-simple-form' ), '1.0.0' );
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			$this->includes();
		}

		/**
		 * Include class Files
		 *
		 * @return void
		 */
		public function includes() {
			include_once 'class-wpmu-simple-form-admin.php';
			include_once 'class-wpmu-simple-form-ajax.php';
		}


	}

}

