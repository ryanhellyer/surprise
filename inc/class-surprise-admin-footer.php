<?php


/**
 * Displays an image in the admin panel footer
 * 
 * @author Ryan Hellyer <ryan@metronet.no>
 * @since 1.0
 */
class Surprise_Admin_Footer {
	
	/**
	 * Class constructor
	 * 
	 * @since 1.0
	 * @author Ryan Hellyer <ryan@metronet.no>
	 */
	public function __construct() {
		
		// Bail out if not in admin panel, since we only surprise via the admin panel
		if ( ! is_admin() ) {
			return;
		}
		
		add_action( 'admin_footer', array( $this, 'show_admin_footer_image' ) );
	}
	
	/**
	 * Add some Christmas holly
	 * 
	 * @since 0.1
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 */
	public function show_admin_footer_image() {
		$url = $this->get_admin_footer_image();
		if ( $url ) {
			echo '
			<style>
				#surprise-footer-left {
					position: fixed;
					left: 0;
					bottom: 0;
					width: 100px;
					height: 49px;
					background: url(' . esc_url( $url ) . ');
				}
				#surprise-footer-right {
					position: fixed;
					right: 0;
					bottom: 0;
					width: 100px;
					height: 50px;
					background: url(' . esc_url( $url ) . ') 0 -50px;
				}
			</style>
			<div id="surprise-footer-left"></div>
			<div id="surprise-footer-right"></div>';
		}
	}

	/*
	 * Get current footer images
	 *
	 * @since 0.2
	 * @author Ryan Hellyer <ryanhellyer@gmail.com>
	 * @return NULL or string 
	 */
	public function get_admin_footer_image() {
		$array = get_option( 'surprise' );
		
		// Loop through each event
		foreach( $array as $event_number => $event_data ) {
			$type = $event_data['type'];
			$trigger = $event_data['trigger'];
			$value = $event_data['value'];
			$url = $event_data['url'];
			
			// Only proceed if set to use admin_images and we have an array of dates to work with
			if ( 'admin_images' == $type && 'date' == $trigger ) {
				$start = $value['start'] - 1; // Subtract 1 to ensure current day is not ignored
				$end = $value['end'] + 1; // Add 1 to ensure current day is not ignored
				
				// Only proceed if current date is between start and end dates
				if ( $start < date( 'z' ) && $end > date( 'z' ) ) {
					return $url;
				}
			}
		}
	
	}	
	
}
