<?php

/**
 * Performs actions on publishing a new post
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Surprise_Publish_Posts {

	/**
	 * Class constructor
	 * Adds all the methods to appropriate hooks or shortcodes
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function __construct() {

		// Add action hooks
		if ( ! isset( $_GET['message'] ) || ! isset( $_GET['action'] ) )
			return;
		if ( '6' == $_GET['message'] && 'edit' == $_GET['action'] && ! isset( $_GET['post_type'] ) ) {
			add_action( 'admin_notices',         array( $this, 'display_image' ) );

			// Add Colorbox
			add_action( 'admin_enqueue_scripts', array( $this, 'external_css' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'external_scripts' ) );
			add_action( 'admin_head',            array( $this, 'inline_scripts' ) );
		}
	}
	
	/**
	 * Print scripts onto pages
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function display_image() {
		$array = get_option( 'surprise' );
		
		// Loop through each event
		foreach( $array as $event_number => $event_data ) {
			$type = $event_data['type'];
			$trigger = $event_data['trigger'];
			$value = $event_data['value'];
			$url = $event_data['url'];
			
			// Only proceed if set to use image and check post count
			if ( 'image' == $type && 'post_count' == $trigger ) {
				$count_posts = wp_count_posts( 'post' );
				$number_published_posts = $count_posts->publish;
				if ( $value == $number_published_posts ) {
					echo '<a id="surprise-message" href="' . home_url( '/?show_surprise_iframe=' . $event_number ) . '">Thank you for using the Surprise plugin</a>';
				}
			}
		}

	}

	/**
	 * Print scripts onto pages
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	*/
	public function external_scripts() {

		wp_enqueue_script(
			'colorbox',
			SURPRISE_ADMIN_URL . '/scripts/jquery.colorbox-min.js',
			array( 'jquery' ),
			1.0,
			true
		);		
	}

	/**
	 * Print scripts onto pages
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function inline_scripts() {

		// Colorbox settings
		echo '
		<script>
			jQuery(function($){
				$("a#surprise-message").colorbox({
					iframe:\'true\',
					width:\'50%\',
					height:\'70%\',
					maxWidth:\'50%\',
					maxHeight:\'70%\',
					opacity:\'0.8\',
					rel:\'group\',
					open:\'true\'
				});
			});
		</script>';
	}

	/*
	 * Adds CSS to front end of site
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function external_css() {

		// Load the stylesheet
		wp_enqueue_style( 'colorbox', SURPRISE_ADMIN_URL . '/admin.css', false, '', 'screen' );
	}

}

