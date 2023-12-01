<?php
/**
 * REST API Controller.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\App
 */

namespace WPMU_SF\App\Rest_Endpoints\Controller;

// Abort if called directly.

defined( 'WPINC' ) || die;

/**
 * Class Controller
 *
 * @package WPMU_SF\App\Rest_Endpoints\Controller
 */
class Controller {

	/**
	 * Variable to hold instance of controller class.
	 *
	 * @since 1.0.0
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Get instance of controller class.
	 *
	 * @since 1.0.0
	 * @return Controller object of controller class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Controller Initialization.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_end_points' ) );
	}

	/**
	 * Register all the required custom end points
	 */
	public function register_end_points() {
		register_rest_route(
			'wpmu-simple-form-api/v1',
			'/wpmu-simple-form',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_data' ),
					'permission_callback' => '__return_true',
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'add_data' ),
					'permission_callback' => '__return_true',
				),
			)
		);

		register_rest_route(
			'wpmu-simple-form-api/v1',
			'/wpmu-simple-form/(?P<id>[\d]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'get_data' ),
			)
		);
	}


	/**
	 * Store the data that comes from rest api to database.
	 *
	 * @param array $request Data.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function add_data( $request ) {
		global $wpdb;

		$response_data = array(
			'message'     => __( 'Please send valid the valid data', 'wpmu-simple-form' ),
			'status_code' => 500,
		);

		$user_name  = ! empty( $request['user_name'] ) ? esc_sql( sanitize_text_field( wp_unslash( $request['user_name'] ) ) ) : '';
		$user_notes = ! empty( $request['user_notes'] ) ? esc_sql( sanitize_textarea_field( wp_unslash( $request['user_notes'] ) ) ) : '';
		if ( ! empty( $user_name ) && ! empty( $user_notes ) ) {
			$response = $wpdb->insert(// phpcs:ignore
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
			$response_data = array(
				'message'     => true,
				'status_code' => 200,
			);
		}

		return rest_ensure_response( $response_data );
	}


	/**
	 * Get stored Data for rest api.
	 *
	 * @param array $request Data.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_data( $request ) {
		global $wpdb;

		$id = ! empty( $request['id'] ) ? esc_sql( sanitize_text_field( wp_unslash( $request['id'] ) ) ) : '';
		if ( empty( $id ) ) {
			$result =  $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpmu_form"); // phpcs:ignore
		} else {
			$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpmu_form WHERE id = %d", $id ) ); // phpcs:ignore
		}

		$response_data = array(
			'message'     => $result,
			'status_code' => 200,
		);

		return rest_ensure_response( $response_data );
	}

}
