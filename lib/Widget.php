<?php
/**
 * Register and implements the widget
 *
 * @since    1.0.0
 * @package  Log_Favorites/lib
 */

namespace log\WP\Plugin\FavoritePosts;

class Widget extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget-favorites',
			'description' => 'See all the posts you mark as favorite.',
		);

		parent::__construct( 'log_favorites_widget', 'Favorite Posts', $widget_ops );
	}

	/**
	 * Register the widget
	 *
	 * @since    1.0.0
	 */
	public function register_widgets() {
		\register_widget( $this );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @since    1.0.0
	 * @param    array    $args    The Widget args
	 * @param    array    $instance    The widget options
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . \apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$user      = new User();
		$favorites = $user->get_formated_favorites();

		if ( $favorites === '' ) {
			\esc_html_e( 'Go ahead, make my day.', 'log-favorites' );
		} else {
			echo $favorites;
		}

		echo $args['after_widget'];
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @since    1.0.0
	 * @param    array    $instance    The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		sprintf( '<p><label for="%s">%s</label><input class="widefat" id="%d" name="%s" type="text" value="%s"></p>',
			\esc_attr( $this->get_field_id( 'title' ) ),
			\esc_html__( 'Title:', 'log-favorites' ),
			\esc_attr( $this->get_field_id( 'title' ) ),
			\esc_attr( $this->get_field_name( 'title' ) ),
			\esc_attr( $title )
		);
	}


	/**
	 * Processing widget options on save
	 *
	 * @since    1.0.0
	 * @param    array    $new_instance    The new options
	 * @param    array    $old_instance    The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		foreach ( $new_instance as $key => $value ) {
			$updated_instance[ $key ] = \sanitize_text_field( $value );
		}

		return $updated_instance;
	}
}
