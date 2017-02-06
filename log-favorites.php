<?php
/**
 * Log Favorites Plugin
 *
 * @link              https://github.com/Narayon/wp-favorite-posts
 * @since             1.0.0
 * @package           Log_Favorite_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Log Favorite Posts
 * Plugin URI:        https://github.com/Narayon/wp-favorite-posts
 * Description:       Adds a button to each post to mark/unmark it as favorite.
 * Version:           1.0.0
 * Author:            Rui Barbosa
 * Author URI:        http://narayon.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       log-favorites
 * Domain Path:       /languages
 */

use log\WP\Plugin\FavoritePosts as Favs;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// composer autoloader.
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_log_favorites() {
	$plugin = new Favs\Plugin( 'log-favorites', '1.0.0' );
	$plugin->run();
}
\add_action( 'plugins_loaded', 'run_log_favorites' );
