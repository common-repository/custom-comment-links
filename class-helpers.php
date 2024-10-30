<?php
/**
 * Plugin helper functions
 *
 * @package Custom_Comment_Links
 * @since 0.1.1
 */

namespace EcoTechie\Custom_Comment_Links;

defined( 'ABSPATH' ) || die( 'These are not the files you are looking for...' );

if ( ! class_exists( 'Helpers' ) ) {

	/**
	 * Helper function class.
	 *
	 * @since 0.1.1
	 *
	 * @package Custom_Comment_Links
	 */
	class Helpers {

		/**
		 * Instance of this class.
		 *
		 * @since 0.1.1
		 *
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Plugin settings.
		 *
		 * @since 0.2.1
		 */
		protected static $options = null;

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

		public function __construct() {
			self::$options = get_option( 'settings' );

			if ( isset( self::$options['comment_author_url'] ) ) {
				add_filter( 'get_comment_author_url', array( $this, 'remove_comment_author_url' ), 20, 3 );
			}

			if ( isset( self::$options['comment_links'] ) && '1' === self::$options['comment_links'] ) {
				add_filter( 'comment_text', array( $this, 'comment_text_links_move' ), 20, 3 );
			}

			if ( isset( self::$options['comment_links'] ) && '2' === self::$options['comment_links'] ) {
				add_filter( 'comment_text', array( $this, 'comment_text_links_unset' ), 20, 3 );
			}
		}

		/**
		 * Verify user has, at least, edit posts priviledges.
		 *
		 * @since 0.2
		 *
		 * @param int The comment ID.
		 *
		 * @return bool
		 */
		private function is_user_priviledged( $id ) {
			return user_can( get_user_by( 'login', get_comment_author( $id ) ), 'edit_posts' );
		}

		/**
		 * Verify priviledged users should be bypassed.
		 *
		 * @since 0.2
		 *
		 * @return bool
		 */
		private function bypass_priviledged_users() {
			return isset( self::$options['bypass_users'] );
		}

		/**
		 * Unsets comment author URL.
		 *
		 * @since 0.1.0
		 *
		 * @param string     $url        The comment author's URL.
		 * @param int        $comment_id The comment ID.
		 * @param WP_Comment $comment    The comment object.
		 *
		 * @return string The comment author's URL.
		 */
		public function remove_comment_author_url( $url = '', $comment_id = '', $comment = null ) {
			if ( ( ! self::bypass_priviledged_users() ) || ( self::bypass_priviledged_users() && ! self::is_user_priviledged( $comment->ID ) ) ) {
				$url = '';
			}
			return $url;
		}

		/**
		 * Filters comment to be displayed by removing links' href tag and moving URL next to it.
		 *
		 * @since 0.1.0
		 *
		 * @param string          $comment_text Text of the current comment.
		 * @param WP_Comment/null $comment      The comment object. Null if not found.
		 * @param array           $args         An array of arguments.
		 *
		 * @return string Text of the current comment.
		 */
		public function comment_text_links_move( $comment_text = '', $comment = null, $args = [] ) {
			if ( ( ! self::bypass_priviledged_users() ) || ( self::bypass_priviledged_users() && ! self::is_user_priviledged( $comment->ID ) ) ) {
				$comment_text = preg_replace( '/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/', "\\2 (\\1)", $comment_text );
			}
				return $comment_text;
		}

		/**
		 * Filters comment to be displayed by removing links' href.
		 *
		 * @since 0.1.1
		 *
		 * @param string          $comment_text Text of the current comment.
		 * @param WP_Comment/null $comment      The comment object. Null if not found.
		 * @param array           $args         An array of arguments.
		 *
		 * @return string Text of the current comment.
		 */
		public function comment_text_links_unset( $comment_text = '', $comment = null, $args = [] ) {
			if ( ( ! self::bypass_priviledged_users() ) || ( self::bypass_priviledged_users() && ! self::is_user_priviledged( $comment->ID ) ) ) {
				$comment_text = preg_replace( '/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/', "\\2", $comment_text );
			}
			return $comment_text;
		}
	}

	/**
	 * Init the plugin.
	 */
	add_action( 'init', array( 'EcoTechie\Custom_Comment_Links\Helpers', 'get_instance' ) );
}
