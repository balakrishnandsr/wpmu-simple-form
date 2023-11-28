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

			if ( method_exists( $this, $method_name ) ) {
				$result = $this->$method_name();
			} else {
				$result = __( 'Requested method not exists', 'wpmu-simple-form' );
			}
			wp_send_json_success( $result );
		}

		/**
		 * Save WPMU Simple From Data.
		 *
		 * @return void
		 */
		public function wpmusf_ajax_save_wpmu_simple_form() {
			$nonce = ! empty( $_POST['simple_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['simple_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'simple_form_nonce' ) ) {
				wp_send_json_error();
			}
			global $wpdb;

			$user_name  = ! empty( $_POST['user_name'] ) ? esc_sql( sanitize_text_field( wp_unslash( $_POST['user_name'] ) ) ) : '';
			$user_notes = ! empty( $_POST['user_notes'] ) ? esc_sql( sanitize_text_field( wp_unslash( $_POST['user_notes'] ) ) ) : '';
			$result     = false;
			$message    = __( 'OOPs!! Something Went Wrong, Please try again later.', 'wpmu-simple-form' );
			if ( ! empty( $user_name ) && ! empty( $user_notes ) ) {
				$result = $wpdb->insert(// phpcs:ignore
					$wpdb->prefix . 'wpmu_form',
					array(
						'name'       => $user_name,
						'user_notes' => $user_notes,
					),
					array(
						'%s',
						'%s',
					)
				);
				if ( 1 === $result ) {
					$message = __( 'Successfully Data Inserted!', 'wpmu-simple-form' );
				}
			}
			wp_send_json_success(
				array(
					'list'    => WPMU_Simple_Form::list_data(),
					'message' => $message,
				)
			);
		}

		/**
		 * Search WPMU Simple From Data.
		 *
		 * @return void
		 */
		public function wpmusf_ajax_search_wpmu_simple_form() {
			$message = __( 'Oh! No results found!', 'wpmu-simple-form' );
			$nonce   = ! empty( $_POST['search_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['search_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'simple_search_nonce' ) ) {
				wp_send_json_error();
			}

			$data    = '';
			$key     = ! empty( $_POST['wpmu_search_key'] ) ? sanitize_text_field( wp_unslash( $_POST['wpmu_search_key'] ) ) : '';
			$offset  = ! empty( $_POST['offset'] ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : 0;
			$limit   = ! empty( $_POST['limit'] ) ? sanitize_text_field( wp_unslash( $_POST['limit'] ) ) : 10;
			$results = $this->get_search_results( $key, $limit, $offset );

			if ( ! empty( $results ) ) {
				$message = __( 'Results are listed below!', 'wpmu-simple-form' );
				foreach ( $results as $result ) {
					$user_name  = ( ! empty( $result['name'] ) ) ? $result['name'] : '';
					$user_notes = ( ! empty( $result['user_notes'] ) ) ? $result['user_notes'] : '';
					$data      .= '<div class="row"><h5>' . esc_html( $user_name ) . '</h5>
				<p>' . esc_html( $user_notes ) . '</p></div>';
				}
				$offset++;
				$nonce         = wp_create_nonce( 'search_nonce' );
				$prev_disabled = ( 10 === $offset ) ? 'disabled' : '';
				$data         .= '<div><button type="button" class="wpmu_search_prev" data-offset="' . esc_attr( $offset ) . '" data-nonce="' . esc_attr( $nonce ) . '" data-key="' . esc_attr( $key ) . '" ' . esc_attr( $prev_disabled ) . '>' . __( '<< Prev', 'wpmu-simple-form' ) . '</button> <button type="button" class="wpmu_search_next" data-offset="' . esc_attr( $offset ) . '" data-nonce="' . esc_attr( $nonce ) . '" data-key="' . esc_attr( $key ) . '" >' . __( 'Next >>', 'wpmu-simple-form' ) . '</button> ';
			}

			wp_send_json_success(
				array(
					'list'    => $data,
					'message' => $message,
				)
			);
		}

		/**
		 * Get Search Results.
		 *
		 * @param string $key   search key.
		 * @param int    $limit  limit.
		 * @param int    $offset  offset.
		 * @return array|void
		 */
		public function get_search_results( $key = array(), $limit = 10, $offset = 0 ) {
			global $wpdb;

			if ( empty( $key ) ) {
				return array();
			}
			return $wpdb->get_results( // phpcs:ignore
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}wpmu_form
												  WHERE name LIKE %s or user_notes LIKE %s
												  ORDER BY id DESC
												  LIMIT %d
												  OFFSET %d",
					'%' . $wpdb->esc_like( $key ) . '%',
					'%' . $wpdb->esc_like( $key ) . '%',
					$limit,
					$offset
				),
				ARRAY_A
			);
		}



	}

}

WPMU_Simple_Form_Ajax::get_instance();
