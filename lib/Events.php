<?php
/**
 * Define AJAX events.
 *
 * Register AJAX callbacks, handles nonce generation
 * and validation and AJAX response
 *
 * @since      1.0.0
 * @package    Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

class Events {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		// Generate a Nonce
		\add_action( 'wp_ajax_logfavorites_nonce', array( $this, 'nonce' ) );

		// Front End Favorite Post
		\add_action( 'wp_ajax_logfavorites_favorite', array( $this, 'favorite_post' ) );
	}

	/**
	 * Favorite Post AJAX callback
	 *
	 * @since    1.0.0
	 * @return   JSON response of the request.
	 */
	public function favorite_post() {
		$this->validate_nonce();

		$this->data['postid'] = intval( \sanitize_text_field( $_POST['postid'] ) );

		//<- valign
		$user = new User( \get_current_user_id() );
		$favorites = $user->update_favorite( $this->data['postid'] );

		return $this->response(array(
			'status' => 'success', //<-valign
			'favorite_data' => array( 'favorites' => $favorites ),
		));
	}

	/**
	 * Generate a Nonce
	 *
	 * @since    1.0.0
	 * @return   Generated nonce.
	 */
	public function nonce() {
		$data = array(
			'status' => 'success', //<-valign
			'nonce' => \wp_create_nonce( 'log_favorites_nonce' ),
		);

		return $this->response( $data );
	}

	/**
	 * Validate the Nonce for each request
	 *
	 * @since    1.0.0
	 */
	protected function validate_nonce() {
		if ( ! isset( $_POST['nonce'] ) ) {
			return $this->sendError();
		}

		$nonce = \sanitize_text_field( $_POST['nonce'] );

		if ( ! \wp_verify_nonce( $nonce, 'log_favorites_nonce' ) ) {
			return $this->send_error();
		}
	}

	/**
	 * Send an Error Response
	 *
	 * @since    1.0.0
	 * @param $error string
	 */
	protected function send_error( $error = null ) {
		$error = ( $error ) ? $error : __( 'Invalid form field', 'log-favorites' ); //<- escreve isto em {}

		return \wp_send_json(array(
			'status' => 'error', //<-valign
			'message' => $error,
		));
	}

	/**
	 * Send a JSON response
	 *
	 * @since    1.0.0
	 * @param $response mixed
	 */
	protected function response( $response ) {
		return \wp_send_json( $response );
	}

}
