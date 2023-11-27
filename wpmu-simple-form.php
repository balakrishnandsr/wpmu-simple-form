<?php
/**
 * Plugin Name:     WPMU Demo Plugin
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wpmu-simple-form
 * Domain Path:     /languages
 * Version:         0.1.0
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
