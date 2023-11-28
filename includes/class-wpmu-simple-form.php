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
			add_action( 'rest_api_init', array( $this, 'register_end_points' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wpmusf_add_scripts' ), 100 );

			add_shortcode( 'my_form', array( $this, 'my_shortcode_form' ) );
			add_shortcode( 'my_list', array( $this, 'my_shortcode_list' ) );
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

		/**
		 * Including css/js files to the project.
		 *
		 * @return void
		 */
		public function wpmusf_add_scripts() {
			/**
			 * Enqueue js
			 */
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/wpmusf-script.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_script( 'custom_script' );

			$js_data = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);

			wp_localize_script( 'custom_script', 'custom_script', $js_data );
		}

		/**
		 * Register all the required custom end points
		 */
		public function register_end_points() {
			register_rest_route(
				'wpmu-simple-form-api/v1',
				'/wpmu-simple-form',
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'permission_callback' => '__return_true',
					'callback'            => array( $this, 'add_data' ),

				)
			);
			register_rest_route(
				'wpmu-simple-form-api/v1',
				'/wpmu-simple-form',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'permission_callback' => '__return_true',
					'callback'            => array( $this, 'get_data' ),
				)
			);
			register_rest_route(
				'wpmu-simple-form-api/v1',
				'/wpmu-simple-form/(?P<id>[\d]+)',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'permission_callback' => '__return_true',
					'callback'            => array( $this, 'get_data' ),
				)
			);
		}

		/**
		 * Display WPMU Form
		 *
		 * @return string
		 */
		public function my_shortcode_form() {
			ob_start();?>
				<div class="wpmu-form-container">
					<div class="wpmu-form-title">
						<h3><?php esc_html_e( 'Simple Form', 'wpmu-simple-form' ); ?></h3>
					</div>
					<div class="mpmu-form">
						<form name="mpmu-simple-form" method="post" id="mpmu-simple-form">
							<div class="row">
								<div class="label">
									<label for="uname"><?php esc_html_e( 'User Name', 'wpmu-simple-form' ); ?></label>
								</div>
								<div class="wpmu-input">
									<input type="text"  id='uname' name="user_name" placeholder="<?php esc_html_e( 'UserName', 'wpmu-simple-form' ); ?>" required>
								</div>
							</div>
							<div class="row">
								<div class="label">
									<label for="unotes"> <?php esc_html_e( 'Notes', 'wpmu-simple-form' ); ?></label>
								</div>
								<div class="wpmu-input">
									<textarea id='unotes' name="user_notes" placeholder="<?php esc_html_e( 'User Notes...', 'wpmu-simple-form' ); ?>" rows="4" cols="50" required></textarea>
								</div>
							</div>
							<div>
								<input type="hidden" name="action" value="wpmu_ajax">
								<input type="hidden" name="method" value="save_wpmu_simple_form">
								<input type="hidden" name="simple_nonce" value="<?php echo esc_attr( wp_create_nonce( 'simple_form_nonce' ) ); ?>">
								<input type="submit" value="<?php esc_html_e( 'submit', 'wpmu-simple-form' ); ?>" id="mpmu-submit-button">
								<p class="wpmu_message"></p>
							</div>
						</form>
					</div>
				</div>
			<?php
			$wpmu_form_html = apply_filters( 'wpmu_simple_form_customizable_content', ob_get_contents() );
			ob_end_clean();
			return $wpmu_form_html;
		}

		/**
		 * List the custom table data
		 *
		 * @return string
		 */
		public function my_shortcode_list() {
			ob_start();
			?>
			<div class="wpmu-form-list-container">
				<div class="wpmu-form-list-title">
					<h3><?php esc_html_e( 'Simple Form User List', 'wpmu-simple-form' ); ?></h3>

					<div class="list-search-form">
							<form name="wpmu_search" id="wpmu_search">
							<input type="text" name="wpmu_search_key" />
							<input type="hidden" name="action" value="wpmu_ajax">
							<input type="hidden" name="method" value="search_wpmu_simple_form">
							<input type="hidden" name="search_nonce" value="<?php echo esc_attr( wp_create_nonce( 'simple_search_nonce' ) ); ?>">
							<input type="submit" id="wpmu_search_button" value="<?php esc_html_e( 'Search', 'wpmu-simple-form' ); ?>" />
						</form>
						<p class="wpmu_search_message"></p>
					</div>

				</div>
				<div class="mpmu-form-list">
					<?php echo self::list_data(); // phpcs:ignore ?>
				</div>
			</div>
			<?php
			$wpmu_list_html = apply_filters( 'wpmu_simple_form_list_content', ob_get_contents(), $lists );
			ob_end_clean();
			return $wpmu_list_html;
		}

		/**
		 * List Stored Data
		 *
		 * @return string
		 */
		public static function list_data() {
			global $wpdb;
			$lists = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpmu_form ORDER BY id DESC LIMIT 10 OFFSET 0" ); // phpcs:ignore
			$data  = '';
			if ( empty( $lists ) ) {
				return $data;
			}
			foreach ( $lists as $list ) {
				$user_name  = ( is_object( $list ) && isset( $list->name ) ) ? $list->name : '';
				$user_notes = ( is_object( $list ) && isset( $list->user_notes ) ) ? $list->user_notes : '';
				$data      .= '<div class="row"><h5>' . esc_html( $user_name ) . '</h5>
				<p>' . esc_html( $user_notes ) . '</p></div>';
			}
			$data .= '<div><button type="button" class="wpmu_list_prev" data-offset="0" disabled>' . __( '<< Prev', 'wpmu-simple-form' ) . '</button> <button type="button" class="wpmu_list_next" data-offset="10">' . __( 'Next >>', 'wpmu-simple-form' ) . '</button> ';
			return $data;
		}

		/**
		 * Store the data that comes from rest api to database.
		 *
		 * @param array $request Data.
		 * @return bool|int|mysqli_result|null
		 */
		public static function add_data( $request ) {
			global $wpdb;

			$user_name  = ! empty( $request['user_name'] ) ? esc_sql( sanitize_text_field( wp_unslash( $request['user_name'] ) ) ) : '';
			$user_notes = ! empty( $request['user_notes'] ) ? esc_sql( sanitize_textarea_field( wp_unslash( $request['user_notes'] ) ) ) : '';
			if ( ! empty( $user_name ) && ! empty( $user_notes ) ) {
				return $wpdb->insert(// phpcs:ignore
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
			}
			return false;
		}

		/**
		 * Get stored Data for rest api.
		 *
		 * @param array $request Data.
		 * @return array|object|stdClass[]|null
		 */
		public static function get_data( $request ) {
			global $wpdb;
			$id = ! empty( $request['id'] ) ? esc_sql( sanitize_text_field( wp_unslash( $request['id'] ) ) ) : '';
			if ( empty( $id ) ) {
				return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpmu_form"); // phpcs:ignore
			}
			 return $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpmu_form WHERE id = %d", $id ) ); // phpcs:ignore
		}
	}

}

