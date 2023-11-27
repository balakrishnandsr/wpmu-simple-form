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
			add_action( 'wp_enqueue_scripts', [ $this, 'wpmusf_add_scripts' ], 100 );
			add_shortcode( 'my_form', [ $this, 'my_shortcode_form'] );
			add_shortcode( 'my_list', [ $this, 'my_shortcode_list'] );
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
			wp_register_script( 'custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/wpmusf-script.js', ['jquery'], '1.0.0', true );
			wp_enqueue_script( 'custom_script' );

			$js_data = [
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			];

			wp_localize_script( 'custom_script', 'custom_script', $js_data );
		}

		/**
		 * Display WPMU Form
		 * @return void
		 */
		public function my_shortcode_form(){
			ob_start();?>
					<div class="wpmu-form-container">
						<div class="wpmu-form-title">
							<h3><?php esc_html_e( 'Simple Form', 'wpmu-simple-form' ); ?></h3>
						</div>
						<div class="mpmu-form">
							<form name="mpmu-simple-form" method="post" id="mpmu-simple-form">
								<div class="row">
									<div class="label">
										<label for="uname"><?php esc_html_e( 'User Name', 'wpmu-simple-form' );?></label>
									</div>
									<div class="wpmu-input">
										<input type="text"  id='uname' name="user_name" placeholder="<?php esc_html_e( 'UserName', 'wpmu-simple-form' );?>" required>
									</div>
								</div>
								<div class="row">
									<div class="label">
										<label for="unotes"> <?php esc_html_e( 'Notes', 'wpmu-simple-form' );?></label>
									</div>
									<div class="wpmu-input">
										<textarea id='unotes' name="user_notes" placeholder="<?php esc_html_e( 'User Notes...', 'wpmu-simple-form' );?>" rows="4" cols="50" required></textarea>
									</div>
								</div>
								<div>
									<input type="hidden" name="action" value="wpmu_ajax">
									<input type="hidden" name="method" value="save_wpmu_simple_form">
									<input type="hidden" name="simple_nonce" value="<?php echo wp_create_nonce('simple_form_nonce'); ?>">
									<input type="submit" value="<?php esc_html_e( 'submit', 'wpmu-simple-form' );?>" id="mpmu-submit-button">
								</div>
							</form>
						</div>
					</div>
			<?php
			$wpmu_form_html = apply_filters( 'wpmu_simple_form_customizable_content', ob_get_contents() );
			ob_end_clean();
			return $wpmu_form_html;
		}


	}

}

