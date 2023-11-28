<?php
/**
 * WPMU simple form initialize
 *
 * @package wpmu-simple-form/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( ' WPMU_Simple_Form_Activator_And_Deactivator' ) ) {

		/**
		 * Class for handling activation and deactivation actions.
		 */
	class  WPMU_Simple_Form_Activator_And_Deactivator {

		/**
		 * Changes required when install plugin
		 */
		public static function activate_wpmu_simple_form() {
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
		}

		/**
		 * Changes required when uninstall plugin
		 */
		public static function deactivate_wpmu_simple_form() {
		}

		/**
		 * Function to add more action on plugins page
		 *
		 * @param array $links Existing links.
		 * @return array|string[]
		 */
		public static function wpmusf_add_plugin_actions( $links = array() ) {
			$action_links = array(
				'settings' => '<a href="' . esc_url( admin_url( 'admin.php?page=wpmu-simple-form' ) ) . '">' . __( 'Settings', 'wpmu-simple-form' ) . '</a>',
			);
			return array_merge( $action_links, $links );
		}

	}

}

