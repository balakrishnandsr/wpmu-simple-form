<?php
/**
 * Class to boot up plugin.
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

use DirectoryIterator;


/**
 * Class Loader
 *
 * @package WPMU_SF\Core
 */
final class Loader {

	/**
	 * Variable to hold instance of loader class.
	 *
	 * @since 1.0.0
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * Get instance of loader class.
	 *
	 * @since 1.0.0
	 * @return loader  object of loader class
	 */
	public static function get_instance() {

		// Check if instance is already exists.
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Scripts to be registered for frontend.
	 *
	 * @since 1.0.0
	 * @var array $scripts Front js scripts to be enqueued.
	 */
	public static $scripts = array();



	/**
	 * Settings helper class instance.
	 *
	 * @since  1.0.0
	 * @var object
	 */
	public $settings;

	/**
	 * Minimum supported php version.
	 *
	 * @since  1.0.0
	 * @var float
	 */
	public $php_version = '7.2';

	/**
	 * Minimum WordPress version.
	 *
	 * @since  1.0.0
	 * @var float
	 */
	public $wp_version = '5.2';

	/**
	 * Initialize functionality of the plugin.
	 *
	 * This is where we kick-start the plugin by defining
	 * everything required and register all hooks.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @return void
	 */
	protected function __construct() {
		if ( ! $this->can_boot() ) {
			return;
		}

		$this->init();
	}

	/**
	 * Main condition that checks if plugin parts should conitnue loading.
	 *
	 * @return bool
	 */
	private function can_boot() {
		/**
		 * Checks
		 *  - PHP version
		 *  - WP Version
		 * If not then return.
		 */
		global $wp_version;

		return (
			version_compare( PHP_VERSION, $this->php_version, '>' ) &&
			version_compare( $wp_version, $this->wp_version, '>' )
		);
	}

	/**
	 * Register all the actions and filters.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function init() {
		// Initialize the core files and the app files.
		// Core files are the base files that the app classes can rely on.
		// Not all core files need to be initiated.
		$this->init_app();

		/*
		 * Setup plugin script
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'handle_front_scripts' ) );

		/**
		 * Action hook to trigger after initializing all core actions.
		 *
		 * @since 2.0.0
		 */
		do_action( 'wpmusf_after_core_init' );
	}

	/**
	 * Load all App modules.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init_app() {
		// Load text domain.
		load_plugin_textdomain(
			'wpmu-simple-form',
			false,
			dirname( WPMUSF_BASENAME ) . '/languages'
		);

		/*
		 * Load plugin components. (Admin pages, Shortcodes, Rest Endpoints etc).
		 * Structures that build the plugins features and ui.
		 */
		$this->load_components(
			apply_filters(
				'wpmusf_load_components',
				array(
					'Action_Links',
					'Admin_Page',
					'Rest_Endpoints',
					'Shortcodes',
				)
			)
		);
	}

	/**
	 * Loads components.
	 *
	 * @since 2.0.0
	 *
	 * @param array $components An array of components (root folder names).
	 */
	private function load_components( $components = array() ) {
		if ( ! empty( $components ) ) {
			array_map(
				array( $this, 'load_component' ),
				$components
			);
		}
	}




	/**
	 * Registers and enqueues plugin scripts and styles for backend
	 *
	 * @since 2.0.0
	 */
	public function handle_front_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'custom_script', WPMUSF_ASSETS_URL . 'js/wpmusf-script.js', array( 'jquery' ), WPMUSF_SCIPTS_VERSION, true );
		wp_enqueue_script( 'custom_script' );

		$js_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);

		wp_localize_script( 'custom_script', 'custom_script', $js_data );
	}

	/**
	 * Loads component's controller.
	 *
	 * @since 1.0.0
	 *
	 * @param string $component The component name which is the folder name that contains the component files (mvc etc).
	 * @param string $namespace The namespace where the component belongs to. Default is App which derives from the `plugin_path/app` main folder.
	 */
	private function load_component( $component = null, $namespace = 'App' ) {
		$this->includes();
		if ( ! is_null( $component ) ) {
			$component_path_part = str_replace( '_', '-', $component );
			$component_path      = trailingslashit( WPMUSF_DIR ) . strtolower( trailingslashit( $namespace ) . trailingslashit( $component_path_part ) );
			if ( is_dir( $component_path ) ) {
				$component_dir = new DirectoryIterator( $component_path );

				foreach ( $component_dir as $fileinfo ) {
					if ( $fileinfo->isDir() && ! $fileinfo->isDot() ) {
						$component_item_dir = $fileinfo->getFilename();
						$component_item     = ucfirst( str_replace( '-', '_', $component_item_dir ) );

						if ( file_exists( trailingslashit( $component_path ) . trailingslashit( $component_item_dir ) . 'class-controller.php' ) ) {
							$component_item = "WPMU_SF\\{$namespace}\\{$component}\\{$component_item}\\Controller";

							try {
								if ( method_exists( $component_item::get_instance(), 'init' ) ) {
									$component_item::get_instance()->init();
								} else {
									throw new \Exception( 'Method init() is missing from class ' . get_class( $component_item::instance() ) );
								}
							} catch ( \Exception $e ) {
								error_log( $e->getMessage() );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Load required files.
	 *
	 * @return void
	 */
	private function includes() {
		include_once WPMUSF_DIR . 'app/action-links/plugin/class-controller.php';
		include_once WPMUSF_DIR . 'app/admin-page/menu-page/class-controller.php';
		include_once WPMUSF_DIR . 'app/rest-endpoints/controller/class-controller.php';
		include_once WPMUSF_DIR . 'app/shortcodes/form/class-controller.php';
	}

}
