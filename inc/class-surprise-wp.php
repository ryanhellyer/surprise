<?php


/**
 * Runs the cron to pull in external API data
 * 
 * @author Ryan Hellyer <ryan@metronet.no>
 * @since 1.0
 */
class Surprise_WP {
	
	/**
	 * Class constructor
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function __construct() {
		
		add_action( 'init', array( $this, 'show_iframe' ) );

		// Bail out if not in admin panel, since we only surprise via the admin panel
		if ( ! is_admin() ) {
			return;
		}
		
		add_action( 'init', array( $this, 'init' ) );
	}
	
	/**
	 * Show the iframe
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function show_iframe() {
		if ( ! is_user_logged_in() || ! isset( $_GET['show_surprise_iframe'] ) ) {
			return;
		}
		
		$array = get_option( 'surprise' );
		$show_surprise_iframe = (int) $_GET['show_surprise_iframe'];
		$event_data = $array[$show_surprise_iframe];
		$type = $event_data['type'];
		$trigger = $event_data['trigger'];
		$value = $event_data['value'];
		$url = $event_data['url'];
		$text = $event_data['text'];
		$heading = $event_data['heading'];
		
		echo '<!DOCTYPE html><html lang="en-US"><head><meta charset="UTF-8" /><style>img {width:100%;height:auto;}</style><title>An Admin iframe</title></head><body>';
		
		// If type is set to 'Image" then display an image tag
		if ( 'image' == $type ) {
			echo '<h2>' . esc_html( $heading ) . '</h2>';
			echo '<img src="' . esc_url( $url ) . '" alt="" />';
			echo '<p>' . esc_html( $text ) . '</p>';
		}

		echo '</body></html>';
		die;
	}
	
	/**
	 * init
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function init() {
		
		// Only define external API if not defined already - allows users to override it if they like
		if ( ! defined( 'SURPRISE_EXTERNAL_API' ) ) {
			define( 'SURPRISE_EXTERNAL_API', 'http://stuff.ryanhellyer.net/surprise2.txt' ); // External URL to pull API data from
		}
		
		$default = array(
			0 => array(
				'name'    => 'Christmas',
				'trigger' => 'date',
				'value' => array(
					'start' => 347,
					'end'   => 348,
				),
				'type'    => 'admin_images',
				'url'     => 'http://prsb.co/3/files/2012/12/frozen-saft-1.jpg',
				'heading' => 'Could display a heading here, not sure what for though',
				'text'    => 'We could display this in the footer I guess, but not sure of what use.',
			),
			1 => array(
				'name'    => 'Some thing',
				'trigger' => 'post_count',
				'value'   => 34,
				'type'    => 'image',
				'url'     => 'http://prsb.co/3/files/2012/12/sognsvann-panorama1.jpg',
				'heading' => 'Yay, you just made your 34th post!',
				'text'    => 'This will display in the iFrame',
			),
		);
		//delete_option( 'surprise' );
		add_option( 'surprise', $default, '', 'no' );
		
		// Schedule the event
		if ( ! wp_next_scheduled( 'surprise_cron_hook' ) ) {
			wp_schedule_event(
				time(),     // The current time
				'5seconds', // How often to run the cron, can be set to "daily"
				'surprise_cron_hook' // Name of cron hook
			);
		}
		add_action( 'surprise_cron_hook', array( $this, 'cron_exec' ) );

update_option( 'surprise', $default );
	}

	// REMEMBER TO SANITIZE HERE ***************************************
	public function sanitise( $array ) {
		return $array;
	}
	
	/*
	 * Converts JSON object back into an array
	 * Gives same format as before converting to JSON
	 *
	 * @since 0.2
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 * @param object $data Object to be converted to array
	 * @return array
	 */
	public function object_to_array( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			$result = array();
			foreach ( $data as $key => $value ) {
				$result[$key] = $this->object_to_array( $value );
			}
			return $result;
		}
		return $data;
	}
	
	/*
	 * Cron job
	 * 
	 * @since 0.2
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function cron_exec() {
		
		// Get string from external API
		$response = wp_remote_get (
			SURPRISE_EXTERNAL_API,
			array ( 'sslverify' => false )
		);
		
		// If no error received from external API, then process contents of JSON blob
		if ( ! is_wp_error ( $response ) ) {  
			$json_blob = $response['body'];
			$object = json_decode( $json_blob );
			$array = $this->object_to_array( $object );
			$array = $this->sanitise( $array );
			update_option( 'surprise', $array );
ob_start();print_r( $array );$string = ob_get_contents();ob_end_clean();
file_put_contents( SURPRISE_ADMIN_DIR . 'bla.txt', $string . time() ); // Test code
		}
	}

}
