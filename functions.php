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
	 *
	 * @return void
	 */
	function sent_ones_wp_setup() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		
		// Add support for font appearance tools
		add_theme_support( 'appearance-tools' );
		
		// Add support for custom units
		add_theme_support( 'custom-units' );
		
		// Enqueue editor styles.
		add_editor_style( 'style.css' );
	}
}
add_action( 'after_setup_theme', 'sent_ones_wp_setup' );

/**
 * Enqueue theme stylesheets.
 *
 * @return void
 */
function sent_ones_wp_styles() {
		// Register theme stylesheet.
		wp_register_style(
			'sent-ones-wp-style',
			get_stylesheet_directory_uri() . '/style.css',
			array(),
			wp_get_theme()->get( 'Version' )
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'sent-ones-wp-style' );

		// Add inline styles for fonts to ensure they load on the front end
		$font_css = '
		@font-face {
			font-family: "Montserrat";
			font-style: normal;
			font-weight: 400;
			src: url(' . get_theme_file_uri( 'assets/fonts/montserrat/montserrat.woff2' ) . ') format("woff2");
			font-display: swap;
		}
		@font-face {
			font-family: "Covered by Your Grace";
			font-style: normal;
			font-weight: 400;
			src: url(' . get_theme_file_uri( 'assets/fonts/covered-by-your-grace/covered-by-your-grace.woff2' ) . ') format("woff2");
			font-display: swap;
		}
		';

		wp_add_inline_style( 'sent-ones-wp-style', $font_css );
}
add_action( 'wp_enqueue_scripts', 'sent_ones_wp_styles' );

/**
 * Register custom blocks
 */
function sent_ones_wp_register_blocks() {
	$blocks_dir = get_template_directory() . '/blocks/categories-section/build';
	
	if ( ! file_exists( $blocks_dir ) ) {
		return;
	}
	
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( $blocks_dir, $blocks_dir . '/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( $blocks_dir, $blocks_dir . '/blocks-manifest.php' );
	}
	
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 */
	$manifest_file = $blocks_dir . '/blocks-manifest.php';
	if ( file_exists( $manifest_file ) ) {
		$manifest_data = require $manifest_file;
		foreach ( array_keys( $manifest_data ) as $block_type ) {
			register_block_type( $blocks_dir . "/{$block_type}" );
		}
	}
}
add_action( 'init', 'sent_ones_wp_register_blocks' );

/**
 * Add Podcasting post type
 */
require_once get_template_directory() . '/podcasts.php';
