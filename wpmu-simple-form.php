<?php
/**
 * Plugin Name: WPMU Demo Plugin
 * Plugin URI:      https://github.com/balakrishnandsr
 * Description: A simple form plugin.
 * Version: 1.0.0
 * Requires at least: 5.0
 * Requires PHP: 7.1
 * Author:  Balakrishnan D
 * Author URI: https://github.com/balakrishnandsr
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Update URI: ttps://wordpress.org/plugins/wpmu-simple-form/
 * Text Domain: wpmu-simple-form
 * Domain Path: /languages
 *
 * @package         Wpmu_Simple_Form
 */

/**
 * Including the class having function to execute during activation & deactivation of plugin
 */
require_once 'includes/class-wpmu-simple-form-activator-and-deactivator.php';

/**
 * On activation
 */
register_activation_hook( __FILE__, array( 'WPMU_Simple_Form_Activator_And_Deactivator', 'activate_wpmu_simple_form' ) );

/**
 * On deactivation
 */
register_deactivation_hook( __FILE__, array( 'WPMU_Simple_Form_Activator_And_Deactivator', 'deactivate_wpmu_simple_form' ) );

if ( ! defined( 'WPMU_SIMPLE_FORM_PLUGIN_DIR_URL' ) ) {
	define( 'WPMU_SIMPLE_FORM_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}
/**
 * Start the Plugin if meets the requirements
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( 'WPMU_Simple_Form_Activator_And_Deactivator', 'wpmusf_add_plugin_actions' ) );

require_once 'includes/class-wpmu-simple-form.php';

WPMU_Simple_Form::get_instance();
