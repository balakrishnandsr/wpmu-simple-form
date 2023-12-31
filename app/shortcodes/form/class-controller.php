<?php
/**
 * Plugin Shortcodes.
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
 * Class Controller
 *
 * @package WPMU_SF\App\Shortcodes\Form
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
		$this->includes();
		add_shortcode( 'my_form', array( $this, 'my_shortcode_form' ) );
		add_shortcode( 'my_list', array( $this, 'my_shortcode_list' ) );
	}

	/**
	 * Include Ajax Functions.
	 *
	 * @return void
	 */
	private function includes() {
		include_once WPMUSF_DIR . 'app/shortcodes/form/class-ajax.php';
		Ajax::get_instance();
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
		$wpmu_list_html = apply_filters( 'wpmu_simple_form_list_content', ob_get_contents() );
		ob_end_clean();
		return $wpmu_list_html;
	}

	/**
	 * List Stored Data.
	 *
	 * @param int $limit limit.
	 * @param int $offset offset.
	 * @return string
	 */
	public static function list_data( $limit = 10, $offset = 0 ) {
		global $wpdb;
		$lists = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpmu_form ORDER BY id DESC LIMIT %d OFFSET %d", $limit, $offset  ) ); // phpcs:ignore
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
		$next_offset = (int) $offset + 10;
		$prev_offset = (int) $offset - 10;
		if ( $offset < 10 ) {
			$prev_offset = 0;
		}
		$nonce = wp_create_nonce( 'wpmu_list_nonce' );
		$data .= '<div><button type="button" class="wpmu_list_prev" data-offset="' . esc_attr( $prev_offset ) . '" data-nonce="' . esc_attr( $nonce ) . '">' . __( '<< Prev', 'wpmu-simple-form' ) . '</button> <button type="button" class="wpmu_list_next" data-offset="' . esc_attr( $next_offset ) . '" data-nonce="' . esc_attr( $nonce ) . '">' . __( 'Next >>', 'wpmu-simple-form' ) . '</button> ';
		return $data;
	}
}
