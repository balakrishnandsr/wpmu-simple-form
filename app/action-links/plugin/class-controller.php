<?php
/**
 * Plugin action links.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\App
 */

namespace WPMU_SF\App\Action_Links\Plugin;

// Abort if called directly.

defined( 'WPINC' ) || die;

/**
 * Class Controller
 *
 * @package WPMU_SF\App\Action_Links\Plugin
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
		$plugin_file = plugin_basename( WPMUSF_PLUGIN_FILE );

		add_filter( "plugin_action_links_{$plugin_file}", array( $this, 'action_links' ) );
		add_filter( "network_admin_plugin_action_links_{$plugin_file}", array( $this, 'action_links' ) );
	}

	/**
	 * Sets the plugin action links in plugins page.
	 *
	 * @param array $actions links array.
	 *
	 * @return array
	 */
	public function action_links( $actions = array() ) {
		if ( ! is_array( $actions ) ) {
			$actions = array();
		}

		$settings_link = array(
			'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=wpmu-simple-form' ) ) . '">' . __( 'Settings', 'wpmu-simple-form' ) . '</a>',
		);
		return array_merge( $settings_link, $actions );
	}
}
