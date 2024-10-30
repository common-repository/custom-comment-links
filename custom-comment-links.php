<?php
/**
 * Plugin Name:     Custom Comment Links
 * Plugin URI:      https//www.ecotechie.io/
 * Description:     Easily disable links for comment author and content.
 * Author:          Sergio Scabuzzo
 * Author URI:      https://www.ecotechie.io
 * Text Domain:     custom-comment-links
 * Domain Path:     /languages
 * Version:         0.2.1
 *
 * @package         Custom_Comment_Links
 * @since 0.1.0
 */

namespace EcoTechie\Custom_Comment_Links;

defined( 'ABSPATH' ) || die( 'These are not the files you are looking for...' );

if ( ! class_exists( 'Main' ) ) {

	/**
	 * Main class.
	 *
	 * @since 0.1.1
	 *
	 * @package Custom_Comment_Links
	 */
	class Main {

		/**
		 * Plugin version.
			 *
		 * @since 0.1.1
		 *
		 * @var string
		 */
		const VERSION = '0.2.1';

		/**
		 * Instance of this class.
		 *
		 * @since 0.1.1
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin.
		 *
		 * @since 0.1.1
		 */
		private function __construct() {
			// Load plugin text domain.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			$this->includes();
		}

		/**
		 * Return an instance of this class.
		 *
		 * @since 0.1.1
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since 0.1.1
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'custom-comment-links', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Include plugin functions.
		 *
		 * @since 0.1.1
		 */
		protected function includes() {
			include_once dirname( __FILE__ ) . '/class-admin.php';
			include_once dirname( __FILE__ ) . '/class-settings.php';
			include_once dirname( __FILE__ ) . '/class-helpers.php';
		}
	}

	/**
	 * Init the plugin.
	 */
	add_action( 'plugins_loaded', array( 'EcoTechie\Custom_Comment_Links\Main', 'get_instance' ) );
}
