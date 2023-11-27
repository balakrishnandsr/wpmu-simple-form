<?php
/**
 * WPMU simple form initialize
 *
 * @package wpmu-simple-form/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPMU_Simple_Form_Admin' ) ) {

	/**
	 * Class for handling admin actions.
	 */
	class  WPMU_Simple_Form_Admin {


		/**
		 * Variable to hold instance of WPMU_Simple_Form_Admin
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WPMU_Simple_Form_Admin
		 *
		 * @return WPMU_Simple_Form_Admin Singleton object of WPMU_Simple_Form_Admin
		 */
		public static function get_instance() {
			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register the event
		 * */
		private function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
		}

		/**
		 * Handle call to functions which is not available in this class
		 *
		 * @param string $function_name The function name.
		 * @param array  $arguments Array of arguments passed while calling $function_name.
		 * @return result of function call
		 */
		public function __call( $function_name, $arguments = array() ) {
			if ( ! is_callable( array( 'WPMU_Simple_Form', $function_name ) ) ) {
				return;
			}

			if ( ! empty( $arguments ) ) {
				return call_user_func_array( array( 'WPMU_Simple_Form', $function_name ), $arguments );
			} else {
				return call_user_func( array( 'WPMU_Simple_Form', $function_name ) );
			}
		}

		/**
		 * For adding admin menu
		 * */
		public function add_admin_menu_page() {
			add_menu_page(
				__( 'WPMU Simple Form', 'wpmu-simple-form' ),
				'WPMU Simple Form',
				'manage_options',
				'wpmu-simple-form',
				array( $this, 'admin_page_content' ),
				'',
				6
			);
		}

		/**
		 * Admin page content
		 * */
		public function admin_page_content() {
		}

	}
}

WPMU_Simple_Form_Admin::get_instance();

