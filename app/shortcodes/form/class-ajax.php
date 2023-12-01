<?php
/**
 * Ajax Calls.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\App
 */

namespace WPMU_SF\App\Shortcodes\Form;

// Abort if called directly.
defined( 'WPINC' ) || die;

/**
 * Ajax Class for WPMU Simple Form plugin.
 *
 * @package WPMU_SF\App\Shortcodes\Form
 */
class Ajax {

	/**
	 * Variable to hold instance of Ajax.
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Get single instance of Ajax.
	 *
	 * @return Ajax class object
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
		global $wpdb;
		$nonce = ! empty( $_POST['simple_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['simple_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'simple_form_nonce' ) ) {
			wp_send_json_error();
		}

		$user_name  = ! empty( $_POST['user_name'] ) ? esc_sql( sanitize_text_field( wp_unslash( $_POST['user_name'] ) ) ) : '';
		$user_notes = ! empty( $_POST['user_notes'] ) ? esc_sql( sanitize_text_field( wp_unslash( $_POST['user_notes'] ) ) ) : '';
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
				'list'    => Controller::list_data(),
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

			$next_offset   = (int) $offset + 10;
			$prev_offset   = (int) $offset - 10;
			$prev_disabled = '';
			if ( $offset < 10 ) {
				$prev_offset   = 0;
				$prev_disabled = 'disabled';
			}
			$nonce = wp_create_nonce( 'simple_search_nonce' );
			$data .= '<div><button type="button" class="wpmu_search_prev" data-offset="' . esc_attr( $prev_offset ) . '" data-nonce="' . esc_attr( $nonce ) . '" data-key="' . esc_attr( $key ) . '" ' . esc_attr( $prev_disabled ) . '>' . __( '<< Prev', 'wpmu-simple-form' ) . '</button> <button type="button" class="wpmu_search_next" data-offset="' . esc_attr( $next_offset ) . '" data-nonce="' . esc_attr( $nonce ) . '" data-key="' . esc_attr( $key ) . '" >' . __( 'Next >>', 'wpmu-simple-form' ) . '</button> ';
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

	/**
	 * WPMU simple form list
	 *
	 * @return void
	 */
	public function wpmusf_ajax_wpmu_simple_form_list() {
		$offset = ! empty( $_POST['offset'] ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : 0; //phpcs:ignore
		wp_send_json_success(
			array(
				'list' => Controller::list_data( 10, $offset ),
			)
		);
	}

}

