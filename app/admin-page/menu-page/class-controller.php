<?php
/**
 * Plugin Menu Page.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\App
 */

namespace WPMU_SF\App\Admin_Page\Menu_Page;

// Abort if called directly.

defined( 'WPINC' ) || die;

/**
 * Class Controller
 *
 * @package WPMU_SF\App\Admin_Page\Menu_Page
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
	 * Controller initialization.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
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
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}?>
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<div class="welcome-panel-header">
					<div class="welcome-panel-header-image">
						<svg preserveAspectRatio="xMidYMin slice" fill="none" viewBox="0 0 1232 240" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
							<g clip-path="url(#a)">
								<path fill="#151515" d="M0 0h1232v240H0z"></path>
								<ellipse cx="616" cy="232" fill="url(#b)" opacity=".05" rx="1497" ry="249"></ellipse>
								<mask id="d" width="1000" height="400" x="232" y="20" maskUnits="userSpaceOnUse" style="mask-type:alpha">
									<path fill="url(#c)" d="M0 0h1000v400H0z" transform="translate(232 20)"></path>
								</mask>
								<g stroke-width="2" mask="url(#d)">
									<path stroke="url(#e)" d="M387 20v1635"></path>
									<path stroke="url(#f)" d="M559.5 20v1635"></path>
									<path stroke="url(#g)" d="M732 20v1635"></path>
									<path stroke="url(#h)" d="M904.5 20v1635"></path>
									<path stroke="url(#i)" d="M1077 20v1635"></path>
								</g>
							</g>
							<defs>
								<linearGradient id="e" x1="387.5" x2="387.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
									<stop stop-color="#3858E9" stop-opacity="0"></stop>
									<stop offset=".297" stop-color="#3858E9"></stop>
									<stop offset=".734" stop-color="#3858E9"></stop>
									<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
									<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
								</linearGradient>
								<linearGradient id="f" x1="560" x2="560" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FFFCB5" stop-opacity="0"></stop>
									<stop offset="0" stop-color="#FFFCB5" stop-opacity="0"></stop>
									<stop offset=".297" stop-color="#FFFCB5"></stop>
									<stop offset=".734" stop-color="#FFFCB5"></stop>
									<stop offset="1" stop-color="#FFFCB5" stop-opacity="0"></stop>
								</linearGradient>
								<linearGradient id="g" x1="732.5" x2="732.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
									<stop stop-color="#C7FFDB" stop-opacity="0"></stop>
									<stop offset=".297" stop-color="#C7FFDB"></stop>
									<stop offset=".693" stop-color="#C7FFDB"></stop>
									<stop offset="1" stop-color="#C7FFDB" stop-opacity="0"></stop>
								</linearGradient>
								<linearGradient id="h" x1="905" x2="905" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
									<stop stop-color="#FFB7A7" stop-opacity="0"></stop>
									<stop offset=".297" stop-color="#FFB7A7"></stop>
									<stop offset=".734" stop-color="#FFB7A7"></stop>
									<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
									<stop offset="1" stop-color="#FFB7A7" stop-opacity="0"></stop>
								</linearGradient>
								<linearGradient id="i" x1="1077.5" x2="1077.5" y1="20" y2="1655" gradientUnits="userSpaceOnUse">
									<stop stop-color="#7B90FF" stop-opacity="0"></stop>
									<stop offset=".297" stop-color="#7B90FF"></stop>
									<stop offset=".734" stop-color="#7B90FF"></stop>
									<stop offset="1" stop-color="#3858E9" stop-opacity="0"></stop>
									<stop offset="1" stop-color="#7B90FF" stop-opacity="0"></stop>
								</linearGradient>
								<radialGradient id="b" cx="0" cy="0" r="1" gradientTransform="matrix(0 249 -1497 0 616 232)" gradientUnits="userSpaceOnUse">
									<stop stop-color="#3858E9"></stop>
									<stop offset="1" stop-color="#151515" stop-opacity="0"></stop>
								</radialGradient>
								<radialGradient id="c" cx="0" cy="0" r="1" gradientTransform="matrix(0 765 -1912.5 0 500 -110)" gradientUnits="userSpaceOnUse">
									<stop offset=".161" stop-color="#151515" stop-opacity="0"></stop>
									<stop offset=".682"></stop>
								</radialGradient>
								<clipPath id="a">
									<path fill="#fff" d="M0 0h1232v240H0z"></path>
								</clipPath>
							</defs>
						</svg>
					</div>
					<h2><?php esc_html_e( 'Welcome to WPMU Simple Form', 'wpmu-simple-form' ); ?></h2>
					<p></p>
					<h3>
						<?php esc_html_e( 'Quick Guide', 'wpmu-simple-form' ); ?>
					</h3>
					<p>
					<ol>
						<li> <?php esc_html_e( 'To view the form on front-end, use the shortcode [my_form].', 'wpmu-simple-form' ); ?></li>
						<li> <?php esc_html_e( 'To list the data on front-end, use the shortcode [my_list].', 'wpmu-simple-form' ); ?></li>
						<li> <?php esc_html_e( "Done, yeah that's easy.", 'wpmu-simple-form' ); ?></li>
					</ol>
					</p>
				</div>
				<div class="welcome-panel-column-container">

				</div>


			</div>
		</div>
		<?php
	}
}
