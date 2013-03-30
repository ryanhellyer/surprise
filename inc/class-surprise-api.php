<?php

/**
 * Create the API
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Surprise_API {

	/**
	 * Class constructor
	 * Adds all the methods to appropriate hooks or shortcodes
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function __construct() {
		if ( ! isset( $_GET['surprise_api'] ) ) {
		}

		add_action( 'init',           array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'meta_boxes_add' ) );
		add_action( 'init',           array( $this, 'meta_boxes_save' ) );
	}
	
	/**
	 * Add meta boxes
	 * 
	 * @since 1.0 HiHostels
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function meta_boxes_add() {
		add_meta_box(
			'dates-id',
			'Trigger',
			array( $this, 'dates_meta_box' ),
			'surprises',
			'side',
			'low'
		);
	}
	
	/**
	 * Opening times meta box
	 * 
	 * @since 1.0 HiHostels
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function dates_meta_box() {
		global $post;
		$post_ID = (int) $post->ID;

		$start_day = get_post_meta( $post_ID, 'start_day', true );
		if ( isset( $start_day[0] ) ) {
			$start_day = $start_day[0];
		}
		$end_day = get_post_meta( $post_ID, 'end_day', true );
		if ( isset( $end_day[0] ) ) {
			$end_day = $end_day[0];
		}
		?>
		<p>
			<label for="trigger_meta_box_text">Start day</label>
			<select id="trigger_meta_box_text" name="surprise_trigger">
				<option>Date</option>
				<option>Number of posts</option>
				<option>Number of comments</option>
			</select>
		</p>

		<p>
			<label for="start_day_meta_box_text">Start day</label>
			<input type="text" name="start_day" id="start_day_meta_box_text" value="<?php echo $start_day; ?>" />
		</p>
		<p>
			<label for="end_day_meta_box_text">End day</label>
			<input type="text" name="end_day" id="end_day_meta_box_text" value="<?php echo $end_day; ?>" />
		</p>

		<p>
			<label for="fontfamily_meta_box_text">Number of posts</label>
			<input type="text" name="font_family" id="fontfamily_meta_box_text" value="<?php echo $font_family; ?>" />
		</p>
		<p>
			<label for="fontfamily_meta_box_text">Number of comments</label>
			<input type="text" name="font_family" id="fontfamily_meta_box_text" value="<?php echo $font_family; ?>" />
		</p>
		<?php
	}

	/**
	 * Save opening times meta box data
	 * 
	 * @since 1.0 HiHostels
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	function meta_boxes_save() {

		if ( empty( $_POST['_wpnonce'] ) || empty( $_POST['post_ID'] ) )
			return;

		wp_verify_nonce( '_wpnonce', $_POST['_wpnonce'] );

		$post_ID = (int) $_POST['post_ID'];

		$start_day = (int) $_POST['start_day'];
		update_post_meta( $post_ID, 'start_day', $start_day );
		
		$end_day = (int) $_POST['end_day'];
		update_post_meta( $post_ID, 'end_day', $end_day );
	}
	
	/**
	 * Add surprise post type
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function register_post_type() {
		$args = array(
			'public'               => false,
			'labels'        => array(
				'name'          => __( 'Surprises', 'hihostels' ),
				'singular_name' => __( 'Surprise', 'hihostels' ),
			),
			'show_ui'              => true,
			'menu_position'        => 3,
			'supports'             => array(
				'title',
				'thumbnail',
				'excerpt',
				'revisions',
			),
		);
		register_post_type( 'surprises', $args );

	}
	
}
