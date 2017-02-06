<?php
/**
 * Register and implements the shortcode
 *
 * @since      1.0.0
 * @package    Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

class Shortcode {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		\add_shortcode( 'log-favorites', array( $this, 'render_shortcode' ) );
	}

	/**
	 * The shortcode callback
	 *
	 * @since    1.0.0
	 * @param    array $options  The shortcode options.
	 * @return   string The shortcode HTML
	 */
	public function render_shortcode( $options ) {
		$user   = new User();
		$output = $user->get_formated_favorites();

		return $output;
	}

}
