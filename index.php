<?php
/*
Plugin Name: Surprise
Plugin URI: http://geek.ryanhellyer.net/products/surprise/
Description: XXXXXXX
Version: 0.2
Author: Ryan Hellyer / Metronet
Author URI: http://geek.ryanhellyer.net/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
license.txt file included with this plugin for more information.

*/

/*
 *
 *
 *
 
 
From Siobhan
	- floating word saying "cunt"
	- Donate to plugins
	- Comic Sans

Valentines day theme
Halloween
Longest day
shortest day
WordPress invented
WordPress.com launched
WP Realm latest blog post
Perhaps make footer images clickable - so you can use them to go some place surprising
Random link for the day.
 
 **/

// temporarily set dir for test code
define( 'SURPRISE_ADMIN_DIR', dirname( __FILE__ ) . '/' ); // Plugin folder DIR
define( 'SURPRISE_ADMIN_URL', plugins_url( '', __FILE__ ) ); // Plugin folder URL


// Adapted from http://ben.lobaugh.net/blog/20787/wordpress-how-to-use-wp-cron
function add_cron_intervals( $schedules ) {

	$schedules['5seconds'] = array( // Provide the programmatic name to be used in code
		'interval' => 5, // Intervals are listed in seconds
		'display'  => 'Every 5 Seconds' // Easy to read display name
	);
	return $schedules; // Do not forget to give back the list of schedules!
}
add_filter( 'cron_schedules', 'add_cron_intervals' );


require( SURPRISE_ADMIN_DIR . 'inc/class-surprise-wp.php' );
require( SURPRISE_ADMIN_DIR . 'inc/class-surprise-admin-footer.php' );
require( SURPRISE_ADMIN_DIR . 'inc/class-surprise-publish-posts.php' );
require( SURPRISE_ADMIN_DIR . 'inc/class-surprise-api.php' );
$surprise_wp = new Surprise_WP;
$surprise_admin_footer = new Surprise_Admin_Footer;
$surprise_publish_posts = new Surprise_Publish_Posts;
$surprise_api = new Surprise_API;
