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
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

        // Add support for Block Styles.
        add_theme_support( 'wp-block-styles' );

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );

        // Add support for responsive embeds.
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Register nav menus.
        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary Menu', 'sent-ones-wp' ),
                'footer'  => esc_html__( 'Footer Menu', 'sent-ones-wp' ),
            )
        );
    }
}
add_action( 'after_setup_theme', 'sent_ones_wp_setup' );

/**
 * Enqueue theme stylesheets.
 */
function sent_ones_wp_styles() {
    // Main stylesheet
    wp_enqueue_style(
        'sent-ones-wp-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'sent_ones_wp_styles' );

/**
 * Register block patterns.
 */
function sent_ones_wp_register_block_patterns() {
    if ( function_exists( 'register_block_pattern_category' ) ) {
        register_block_pattern_category(
            'sent-ones-wp',
            array( 'label' => esc_html__( 'SentOnes', 'sent-ones-wp' ) )
        );
    }
}
add_action( 'init', 'sent_ones_wp_register_block_patterns' );

/**
 * Register block styles.
 */
function sent_ones_wp_register_block_styles() {
    if ( function_exists( 'register_block_style' ) ) {
        // Example of registering a block style
        register_block_style(
            'core/button',
            array(
                'name'  => 'sent-ones-wp-flat-button',
                'label' => esc_html__( 'Flat Button', 'sent-ones-wp' ),
            )
        );
    }
}
add_action( 'init', 'sent_ones_wp_register_block_styles' ); 