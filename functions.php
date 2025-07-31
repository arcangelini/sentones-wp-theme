<?php
/**
 * SentOnes Theme functions and definitions
 *
 * @package SentOnes
 * @since 1.0.0
 */

if ( ! function_exists( 'sent_ones_wp_setup' ) ) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function sent_ones_wp_setup() {
		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );
    }
}
add_action( 'after_setup_theme', 'sent_ones_wp_setup' );

/**
 * Enqueue theme stylesheets.
 */
function sent_ones_wp_styles() {
    $theme_version = wp_get_theme()->get( 'Version' );

    $version_string = is_string( $theme_version ) ? $theme_version : false;
    wp_register_style(
        'sent-ones-wp-style',
        get_template_directory_uri() . '/style.css',
        array(),
        $version_string
    );
}
add_action( 'wp_enqueue_scripts', 'sent_ones_wp_styles' );

/**
 * Add Podcasting post type
 */
require_once get_template_directory() . '/podcasts.php';