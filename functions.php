<?php

/**
 * support functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package support
 */


/**
 * Main Support class
 *
 * This class is the one and only instance of the theme. It is used
 * to load the core and all its components.
 *
 * @since 1.0.0
 */
final class Support {

	/**
	 * @var Support Holds the unique instance of Support
	 * @since 1.0.0
	 */
	private static $instance;


	/**
	 * Instantiate and return the unique Support object
	 *
	 * @since 1.0.0
	 * @return 	object Support Unique instance of Support
	 */
	public static function instance() {
		if ( isset( self::$instance ) && ( self::$instance instanceof Support ) ) {
			return;
		}

		self::$instance = new Support;
		self::$instance->init();

		return self::$instance;
	}


	/**
	 * Instantiate the plugin
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init() {
		// First of all we need the constants
		self::$instance->setup_constants();
		self::$instance->includes();


		if ( is_admin() ) {
			self::$instance->includes_admin();
		}
	}


	/**
	 * Setup all plugin constants
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {
		define( 'SUPPORT_PATH', get_template_directory() );
	}


	/**
	 * Include all files used sitewide
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {
		require_once SUPPORT_PATH . '/includes/functions-post-type.php';
	}


	/**
	 * Include all files used in admin only
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function includes_admin() {
		require_once SUPPORT_PATH . '/includes/admin/functions-metaboxes.php';
	}
}


/**
 * The main function responsible for returning the unique Support instance
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @since 1.0.0
 * @return object Support
 */
function SUPPORT() {
	return Support::instance();
}


// Get Support Running
SUPPORT();
