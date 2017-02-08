<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks to
 * enqueue the client-specific stylesheet and JavaScript.
 *
 * @since    1.0.0
 * @package  Log_Favorites\client
 */

namespace log\WP\Plugin\FavoritePosts\Client;

use log\WP\Plugin\FavoritePosts as Lib;

class Loader {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The current user.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      User    $user    The current user.
	 */
	private $user;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $name    The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
		$this->user    = new Lib\User();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		\wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/log-favorites-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		\wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/log-favorites-public.js', array( 'jquery' ), $this->version, false );
		\wp_localize_script( 'log-favorites', 'log_favorites', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	/**
	 * Filter the Content
	 *
	 * @since    1.0.0
	 * @param    $content    string    The post content
	 * @return   string    The content with the button added.
	 */
	public function filter_content( $content ) {
		global $post;

		if ( $this->user->logged_in() && \is_single() && \in_the_loop() && \is_main_query() ) {
			$content .= $this->get_favorites_button( $post->ID );
		}

		return $content;
	}

	/**
	 * Add the Favorite Button
	 *
	 * @since    1.0.0
	 * @param    $post_id    int    The post id
	 * @return   string    The button HTML.
	 */
	private function get_favorites_button( $post_id ) {
		$button = sprintf( '<button id="favBtn" class="button-fav %s" data-postid="%s">%s</button>',
			$this->user->is_favorite( $post_id ) ? 'active' : '',
			\esc_attr( $post_id ),
			\esc_html__( 'Favorite Post', 'log-favorites' )
		);

		return $button;
	}

	/**
	 * Build all hooks and dependencies
	 *
	 * @since    1.0.0
	 */
	public function run() {
		\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		\add_filter( 'the_content', array( $this, 'filter_content' ) );
		new Lib\Events;
	}

}
