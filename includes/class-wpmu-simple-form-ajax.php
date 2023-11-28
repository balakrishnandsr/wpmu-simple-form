<?php
/**
 * WPMU simple form
 *
 * @package wp-simple-form/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPMU_Simple_Form_Ajax' ) ) {

	/**
	 * Main Class for WP Simple  plugin.
	 */
	class WPMU_Simple_Form_Ajax {

		/**
		 * Variable to hold instance of WP Simple Form.
		 *
		 * @var $instance
		 */
		private static $instance = null;

		/**
		 * Get single instance of WP Simple Form Ajax.
		 *
		 * @return WPMU_Simple_Form_Ajax Singleton object of WP_Simple_Form_Ajax
		 */
		public static function get_instance() {

			// Check if instance is already exists.
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_action( 'wp_ajax_wpmu_ajax', array( $this, 'wpmusf_ajax_requests' ) );
			add_action( 'wp_ajax_nopriv_wpmu_ajax', array( $this, 'wpmusf_ajax_requests' ) );
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
		 * Ajax Controller
		 *
		 * @return void
		 */
		public function wpmusf_ajax_requests() {
			$method_name = ! empty( $_POST['method'] ) ? 'wpmusf_ajax_' . sanitize_text_field( wp_unslash( $_POST['method'] ) ) : ''; //phpcs:ignore

			if ( current_user_can( 'manage_options' ) && method_exists( $this, $method_name ) ) {
				$result = $this->$method_name();
			} else {
				$result = __( 'Requested method not exists', 'wpmu-simple-form' );
			}
			wp_send_json_success( $result );
		}

		/**
		 * @return void
		 */
		public function wpmusf_ajax_save_wpmu_simple_form(){
			$nonce  = ! empty( $_POST['simple_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['simple_nonce'] ) ) : '';
			if (  ! wp_verify_nonce( $nonce, 'simple_form_nonce' ) ) {
				wp_send_json_error();
			}
			global $wpdb;

			$user_name = ! empty( $_POST['user_name'] ) ? sanitize_text_field( wp_unslash( $_POST['user_name'] ) ) : '';
			$user_notes = ! empty( $_POST['user_notes'] ) ? sanitize_text_field( wp_unslash( $_POST['user_notes'] ) ) : '';

			if(!empty($user_name) && !empty($user_notes)){
				$wpdb->insert(
					$wpdb->prefix . 'wpmu_form',
					array(
						'name'   =>  esc_sql( $user_name),
						'user_notes' =>  esc_sql( $user_notes),
					)
				);
			}
		}



	}

}

WPMU_Simple_Form_Ajax::get_instance();
