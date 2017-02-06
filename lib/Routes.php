<?php
/**
 * Register and implements the REST API custom routes
 *
 * @since      1.0.0
 * @package    Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

class Routes {

	/**
	 * Register all the custom endpoints
	 *
	 * @since    1.0.0
	 */
	public function register_routes() {
		\register_rest_route( $this->get_api_namespace(), '/favorites', array(
			'methods'  => \WP_REST_Server::READABLE,
			'callback' => array( $this, 'get_favorites' ),
		) );

		\register_rest_route( $this->get_api_namespace(), '/favorites/(?P<id>\d+)', array(
			'methods'  => \WP_REST_Server::EDITABLE,
			'callback' => array( $this, 'update_favorites' ),
		) );
	}

	/**
	 * Callback for GET all favorites
	 *
	 * @since  1.0.0
	 * @param  $data
	 * @return array
	 */
	public function get_favorites( $data ) {
		return $this->response( 'GET' );
	}

	/**
	 * Callback for update favorite
	 *
	 * @since  1.0.0
	 * @param  $data
	 * @return array
	 */
	public function update_favorites( $data ) {
		return $this->send_error();
	}

	/**
	 * Validates the user
	 *
	 * @since    1.0.0
	 * @param    $user
	 * @return   int User id
	 */
	private function validate_user( $user ) {
		if ( ! empty( $user ) ) {
			return $user;
		}

		$username = $_SERVER['PHP_AUTH_USER'];
		$password = $_SERVER['PHP_AUTH_PW'];

		$user = \wp_authenticate( $username, $password );

		if ( \is_wp_error( $user ) ) {
			$this->send_error();
		}

		return $user->ID;
	}

	/**
	 * Send an Error Response
	 *
	 * @since    1.0.0
	 * @param $error string
	 */
	protected function send_error( $error = null ) {
		$error = ( $error ) ? $error : \__( 'Ups, wrong user.', 'log-favorites' ); //<- escreve isto com {}
		$error = new \WP_Error( 'wrong_user', $error, array( 'status' => 401 ) );
		$error = \rest_ensure_response( $error );

		return $error;
	}

	/**
	 * Send a response
	 *
	 * @since    1.0.0
	 * @param   $response string
	 * @return  WP_REST_Response
	 */
	protected function response( $response ) {
		$res = new \WP_REST_Response();
		$res->set_data( $response );
		$res->set_status( 200 );
		$res = \rest_ensure_response( $res );

		return $res;
	}

	/**
	* Get WP API namespace.
	*
	* @since 1.0.0
	* @return string
	*/
	public static function get_api_namespace() {
		return 'log_favorites/v1';
	}
}
