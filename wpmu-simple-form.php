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

namespace WPMU_SF;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;





// Define WPMUDEV_BLC_PLUGIN_FILE.
if ( ! defined( 'WPMUSF_PLUGIN_FILE' ) ) {
	define( 'WPMUSF_PLUGIN_FILE', __FILE__ );
}

// Plugin basename.
if ( ! defined( 'WPMUSF_BASENAME' ) ) {
	define( 'WPMUSF_BASENAME', plugin_basename( __FILE__ ) );
}

// Plugin directory.
if ( ! defined( 'WPMUSF_DIR' ) ) {
	define( 'WPMUSF_DIR', plugin_dir_path( __FILE__ ) );
}

// Assets url.
if ( ! defined( 'WPMUSF_ASSETS_URL' ) ) {
	define( 'WPMUSF_ASSETS_URL', plugin_dir_url( __FILE__ ) . trailingslashit( 'assets' ) );
}

// Scripts version.
if ( ! defined( 'WPMUSF_SCIPTS_VERSION' ) ) {
	define( 'WPMUSF_SCIPTS_VERSION', '1.0.0' );
}



require_once WPMUSF_DIR . 'core/class-activation.php';
require_once WPMUSF_DIR . 'core/class-deactivation.php';
require_once WPMUSF_DIR . 'core/class-loader.php';

/**
 * Run plugin activation hook to setup plugin.
 *
 * @since 1.0.0
 */


register_activation_hook(
	__FILE__,
	function() {
		Core\Activation::get_instance();
	}
);

/**
 * Run plugin deactivation hook to remove plugin data.
 *
 * @since 1.0.0
 */

register_deactivation_hook(
	__FILE__,
	function() {
		Core\Deactivation::get_instance();
	}
);


// Load the plugin.
add_action(
	'plugins_loaded',
	function() {
		Core\Loader::get_instance();
	},
	11
);

