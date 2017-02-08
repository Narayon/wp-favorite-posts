<?php
/**
 * Define the User Favorites functionality
 *
 * @since    1.0.0
 * @package  Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

class User {

	/**
	 * User ID
	 *
	 * @since    1.0.0
	 * @var      int
	 */
	private $user_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    int    $user_id    The user id.
	 */
	public function __construct( $user_id = null ) {
		if ( isset( $user_id ) ) {
			$this->user_id = $user_id;
		} else {
			$this->user_id = \get_current_user_id();
		}
	}

	/**
	 * Check if user is logged in
	 *
	 * @since    1.0.0
	 * @return   boolean    The user state
	 */
	public function logged_in() {
		if ( $this->user_id ) {
			return true;
		}

		return false;
	}

	/**
	 * Validates the user and gets the favorites
	 *
	 * @since    1.0.0
	 * @return    array    All the favorites
	 */
	public function get_all_favorites() {
		if ( $this->logged_in() ) {
			return $this->get_favorites();
		}
	}

	/**
	 * Get Logged In User Favorites
	 *
	 * @since    1.0.0
	 * @param    $user_id    int    The user id
	 * @return   array    All the favorites
	 */
	private function get_favorites( $user_id = null ) {
		if ( ! isset( $user_id ) ) {
			$user_id = $this->get_user_id();
		}
		$favorites = \get_user_meta( $user_id, 'logfavorites', true );

		if ( ! is_array( $favorites ) ) {
			$favorites = array();
		}

		return $favorites;
	}

	/**
	 * Has the user favorited a specified post?
	 *
	 * @since    1.0.0
	 * @param    int    $post_id    The post id to check
	 * @param    int    $user_id    The user id
	 * @return   boolean    The favorite state
	 */
	public function is_favorite( $post_id, $user_id = null ) {
		if ( ! isset( $user_id ) ) {
			$user_id = $this->get_user_id();
		}
		$favorites = $this->get_favorites( $user_id );

		if ( in_array( $post_id, $favorites ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the user id
	 *
	 * @since    1.0.0
	 * @return    int    The user id
	*/
	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * Decide to remove or add favorite
	 *
	 * @since     1.0.0
	 * @param     int    $post_id
	 * @return    array    The favorites updated
	*/
	public function update_favorite( $post_id ) {
		if ( $this->is_favorite( $post_id ) ) {
			$favorites = $this->remove_favorite( $post_id );
		} else {
			$favorites = $this->add_favorite( $post_id );
		}

		return $favorites;
	}

	/**
	 * Push the favorite and update
	 *
	 * @since    1.0.0
	 * @param    int    $post_id The post id
	 * @return   array    The favorites updated
	*/
	private function add_favorite( $post_id ) {
		$favorites = $this->get_favorites();

		array_push( $favorites, $post_id );

		return $this->update_user_meta( $favorites );
	}

	/**
	 * Filter the favorite to remove and update
	 *
	 * @since    1.0.0
	 * @param    int    $post_id    The post id
	 * @return   array    The favorites updated
	*/
	private function remove_favorite( $post_id ) {
		$favorites = $this->get_favorites();

		$favorites = array_filter( $favorites, function( $elem ) use ( $post_id ) {
			return $elem != $post_id;
		} );

		return $this->update_user_meta( $favorites );
	}

	/**
	 * Update favorites inside user meta
	 *
	 * @since    1.0.0
	 * @param    array    $favorites    The favorites
	 * @return   array|false    The favorites or false
	*/
	private function update_user_meta( $favorites ) {
		if ( ! $this->logged_in() ) {
			return false;
		}

		if ( \update_user_meta( $this->get_user_id(), 'logfavorites', $favorites ) ) {
			return $favorites;
		}

		return false;
	}

	/**
	 * Get all favorites inside a HTML list
	 *
	 * @since    1.0.0
	 * @return   string    All the favorites in an unordered HTML list
	*/
	public function get_formated_favorites() {
		$favorites = $this->get_all_favorites();
		$output    = '';

		if ( is_array( $favorites ) && count( $favorites ) ) {
			$output .= '<ul>';

			foreach ( $favorites as $post_id ) {
				$post = \get_post( $post_id );

				$output .= sprintf( '<li><a href="%s">%s</a></li>',
					\esc_url( \get_permalink( $post_id ) ),
					\esc_html( $post->post_title )
				);
			}

			$output .= '</ul>';
		}

		return $output;
	}

}
