<?php

/**
 * Plugin Name: Use Hashtags
 * Plugin URI: https://witvlogs.com/
 * Description: Convert all the #hashtags in your content and excerpts to a hashtags link.
 * Version: 1.1.0
 * Author: Wit
 * Author URI: https://witvlogs.com/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: use-hashtags
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Add a filter to use hashtags in the context of the_content.
 */
function use_hashtags_in_content( $content ) {
	if ( in_the_loop() && is_main_query() ) {
		return use_hashtags_convert_hashtags_to_links( $content );
	}
	return $content;
}

add_filter( 'the_content', 'use_hashtags_in_content', 5, 1 );



/**
 * Search for hashtags and replace then with a link.
 */
function use_hashtags_convert_hashtags_to_links( $content ) {
	$regex            = "(?<=[\s\n\r\\ ])#([\w\-\*\@\/]+)";
	$site_url         = home_url();
	$link_qualify     = "follow";
	$link_target      = "_blank";
	$prepared_options = ' class="hashtag-link"';

	// Qualify the link
	if ( ! empty( $link_qualify ) ) {
		$prepared_options .= " rel=\"{$link_qualify}\"";
	}

	// Target the link
	if ( ! empty( $link_target ) ) {
		$prepared_options .= " target=\"{$link_target}\"";
	}

	// Prepare the base href
	// %23 is the # symbol encoded
	$prepared_href = esc_url( $site_url . "/tag/" );

	// Search and replace the hashtags with a link
	return preg_replace(
		"/{$regex}/",
		"<a href=\"{$prepared_href}$1\"{$prepared_options}>#$1</a>",
		$content
	);
}
