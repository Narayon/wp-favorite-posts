<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, widgets, shortcodes and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

use log\WP\Plugin\FavoritePosts\Client;

class Plugin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $name    The string used to uniquely identify this plugin.
	 */
	protected $name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Log_Favorites_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n();
		$plugin_i18n->set_domain( $this->get_name() );
		$plugin_i18n->load_plugin_textdomain();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$client_loader = new Client\Loader( $this->get_name(), $this->get_version() );
		$client_loader->run();
	}

	/**
	 * Register all the widgets.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_widgets() {
		\add_action( 'widgets_init', array( new Widget, 'register_widgets' ) );
	}

	/**
	 * Register all the shortcodes.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_shortcodes() {
		new Shortcode();
	}

	/**
	 * Register all the rest api routes(endpoints).
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_endpoints() {
		\add_filter( 'rest_api_init', array( new Routes, 'register_routes' ) );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->set_locale();
		$this->define_public_hooks();
		$this->register_widgets();
		$this->register_shortcodes();
		$this->register_endpoints();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
