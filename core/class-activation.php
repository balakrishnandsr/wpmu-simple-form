<?php
/**
 * Class is called upon plugin activation.
 *
 * @link    https://github.com/balakrishnandsr
 * @since   1.0.0
 *
 * @author  Balakrishnan D
 * @package WPMU_SF\Core
 */

namespace WPMU_SF\Core;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;


/**
 * Class Activation
 *
 * @package WPMU_SF\Core
 */
final class Activation {

	/**
	 * Variable to hold instance of activation class.
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Get  instance of activation class.
	 *
	 * @return Activation  object of activation class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Activation hooks.
	 *
	 * @return void
	 * @since 2.0.0
	 */
	public function __construct() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		include_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$wpmu_tables = "
							CREATE TABLE {$wpdb->prefix}wpmu_form (
							  	id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
								name varchar(255) NOT NULL,
								user_notes longtext NOT NULL,
								PRIMARY KEY  (id)
							) $collate;";

		maybe_create_table( $wpdb->prefix . 'wpmu_form', $wpmu_tables );

		do_action( 'wpmusf_plugin_activated' );
	}

	/**
	 * Function to add more actions on plugins page
	 *
	 * @param array $links Existing links.
	 * @return array|string[]
	 */
	protected function wpmusf_add_plugin_actions( $links = array() ) {
		$action_links = array(
			'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=wpmu-simple-form' ) ) . '">' . __( 'Settings', 'wpmu-simple-form' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}

}
