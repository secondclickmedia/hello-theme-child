<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

// Safely include all PHP files in the 'inc' folder from the child theme
function hello_elementor_child_include_files() {
    $include_path = get_stylesheet_directory() . '/inc/'; // Define the path to your folder

    // Use glob() to find all PHP files in the directory
    $files = glob( $include_path . '*.php' );

    // Loop through each file and require it
    if ( $files ) {
        foreach ( $files as $file ) {
            // Use require_once to prevent fatal errors if a file is somehow included twice
            require_once( $file );
        }
    }
}

// Hook this function early in the WordPress loading process
add_action( 'after_setup_theme', 'hello_elementor_child_include_files' );