<?php
/**
 * Plugin admin settings page
 *
 * @package Custom_Comment_Links
 * @since 0.1.1
 */

namespace EcoTechie\Custom_Comment_Links;

defined( 'ABSPATH' ) || die( 'These are not the files you are looking for...' );

if ( ! class_exists( 'Admin' ) ) {

	/**
	 * Admin admin page class.
	 *
	 * @since 0.1.1
	 *
	 * @package Custom_Comment_Links
	 */
	class Admin {

		/**
		 * Instance of this class.
		 *
		 * @since 0.1.1
		 *
		 * @var object
		 */
		protected static $instance = null;

		private function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ) );
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
		 * Add admin page plugin menu.
		 *
		 * @since 0.1.1
		 */
		public function add_admin_menu() {
			add_options_page(
				'Custom Comment Links',
				'Custom Comment Links',
				'manage_options',
				'custom_comment_links',
				'EcoTechie\Custom_Comment_Links\Settings::options_page'
			);
		}

		private function settings_section_callback() {
		}

		/**
		 * Add settings page settings.
		 *
		 * @since 0.1.1
		 */
		public function settings_init() {

			register_setting( 'pluginPage', 'settings' );

			add_settings_section(
				'pluginPage_section',
				'',
				$this->settings_section_callback(),
				'pluginPage'
			);

			add_settings_field(
				'bypass_users',
				__( 'Bypass settings', 'custom-comment-links' ),
				'EcoTechie\Custom_Comment_Links\Settings::bypass_users_render',
				'pluginPage',
				'pluginPage_section'
			);

			add_settings_field(
				'comment_author_url',
				__( 'Comment author', 'custom-comment-links' ),
				'EcoTechie\Custom_Comment_Links\Settings::comment_author_url_render',
				'pluginPage',
				'pluginPage_section'
			);

			add_settings_field(
				'comment_links',
				__( 'Comment body links', 'custom-comment-links' ),
				'EcoTechie\Custom_Comment_Links\Settings::comment_links_render',
				'pluginPage',
				'pluginPage_section'
			);
		}
	}

	/**
	 * Init the plugin.
	 */
	add_action( 'init', array( 'EcoTechie\Custom_Comment_Links\Admin', 'get_instance' ) );
}
