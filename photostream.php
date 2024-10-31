<?php
/**
 * @package Photostream
 * @author Lennart Pilon
 * @version 0.1
 */
/*
Plugin Name: Photostream
Plugin URI: http://wordpress.org/#
Description: Display a stream of photo's
Author: Lennart Pilon
Version: 0.1
Author URI: http://pilon.nl/
*/
add_shortcode('Photostream', 'main');

function PhotostreamFolders($dir) {

	$folders = array();
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($dir.$file) && $file != "." && $file != ".." ) {
					$folders[] = $dir . $file ."/";
				}
			}
			closedir($dh);
		}
	}
	arsort($folders);
	return $folders;
}

function PhotostreamFiles($dir) {
	
	$files = array();
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if ($file != '.' && $file != '..') {
				$files[] = $file;
			}
		}
		closedir($dh);
	}
	return $files;	
}

function getPhotostream($dir) {
	
	$list		= array();
	$folders	= PhotostreamFolders($dir .'/');
	$output		= '<div id="photostream">';
	$href		= get_bloginfo('wpurl') .'/';

	foreach($folders as $k => $v) {		
		$name = substr(str_replace($dir, "", $v), 0, -1);		
		$files = PhotostreamFiles($v);

		foreach ($files as $k => $file) {		
			
			if (substr($file, 0, 6) == "thumb_") {
				
				$original = str_replace("thumb_", "", $file);
				$album = substr(str_replace($dir, "", $v), 0, -1);
				
				$output .= "\t\t".'	<a title="'.$album.'" rel="lightbox-'.$album.'" href="'.$href . $v.$original.'">
										<img height="144" class="'.$album.'" src="'.$href.$v.$file.'" alt="'.$href.$v.$original.'" />
									</a>'."\n";
			}
		}
	}
	$output .= '</div>';

	return $output;
}

function enqueue_my_scripts() {
	wp_enqueue_script('mootools', WP_PLUGIN_URL.'/photostream/mootools-1.2.3-core.js');
	wp_enqueue_script('slimbox', WP_PLUGIN_URL.'/photostream/slimbox.js');
	wp_enqueue_script('photostream', WP_PLUGIN_URL.'/photostream/photostream.js');
}

function enqueue_my_styles() {
	wp_enqueue_style('slimbox', WP_PLUGIN_URL.'/photostream/slimbox.css');
	wp_enqueue_style('photostream', WP_PLUGIN_URL.'/photostream/photostream.css');
}

add_action( 'wp_print_scripts', 'enqueue_my_scripts' );
add_action( 'wp_print_styles', 'enqueue_my_styles' );

function main($atts) {

	// Extract WordPress parameters
	extract(shortcode_atts(array('path' => 'INVALID'), $atts));

	// Make sure they entered an album path
	if( $album == 'INVALID' ) {
	  echo 'Album name required.<br>';
	  echo 'Usage: [Photostream path="path_to_albums"]<br>';
	  exit;
	}
	echo getPhotostream($path);
} 

?>