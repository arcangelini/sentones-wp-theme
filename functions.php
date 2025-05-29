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

/**
 * Register Podcast Channels taxonomy.
 */
function sent_ones_wp_register_taxonomies() {
    $labels = array(
        'name'                       => _x( 'Podcast Channels', 'Taxonomy general name', 'sent-ones-wp' ),
        'singular_name'              => _x( 'Podcast Channel', 'Taxonomy singular name', 'sent-ones-wp' ),
        'search_items'               => __( 'Search Channels', 'sent-ones-wp' ),
        'popular_items'              => __( 'Popular Channels', 'sent-ones-wp' ),
        'all_items'                  => __( 'All Channels', 'sent-ones-wp' ),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __( 'Edit Channel', 'sent-ones-wp' ),
        'update_item'                => __( 'Update Channel', 'sent-ones-wp' ),
        'add_new_item'               => __( 'Add New Channel', 'sent-ones-wp' ),
        'new_item_name'              => __( 'New Channel Name', 'sent-ones-wp' ),
        'separate_items_with_commas' => __( 'Separate channels with commas', 'sent-ones-wp' ),
        'add_or_remove_items'        => __( 'Add or remove channels', 'sent-ones-wp' ),
        'choose_from_most_used'      => __( 'Choose from the most used channels', 'sent-ones-wp' ),
        'not_found'                  => __( 'No channels found.', 'sent-ones-wp' ),
        'menu_name'                  => __( 'Channels', 'sent-ones-wp' ),
    );

    $args = array(
        'hierarchical'        => false,
        'labels'              => $labels,
        'show_ui'             => true,
        'show_admin_column'   => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'podcast-channel' ),
        'show_in_rest'        => true,
        'rest_base'           => 'podcast-channels',
        'show_in_graphql'     => true,
        'graphql_single_name' => 'podcastChannel',
        'graphql_plural_name' => 'podcastChannels',
    );

    register_taxonomy( 'podcast_channel', array( 'podcast' ), $args );
}
add_action( 'init', 'sent_ones_wp_register_taxonomies' );

/**
 * Register Podcast post type.
 */
function sent_ones_wp_register_post_types() {
    $labels = array(
        'name'                 => _x( 'Podcasts', 'Post type general name', 'sent-ones-wp' ),
        'singular_name'        => _x( 'Podcast', 'Post type singular name', 'sent-ones-wp' ),
        'menu_name'            => _x( 'Podcasts', 'Admin Menu text', 'sent-ones-wp' ),
        'add_new'              => __( 'Add New', 'sent-ones-wp' ),
        'add_new_item'         => __( 'Add New Podcast', 'sent-ones-wp' ),
        'edit_item'            => __( 'Edit Podcast', 'sent-ones-wp' ),
        'new_item'             => __( 'New Podcast', 'sent-ones-wp' ),
        'view_item'            => __( 'View Podcast', 'sent-ones-wp' ),
        'search_items'         => __( 'Search Podcasts', 'sent-ones-wp' ),
        'not_found'            => __( 'No podcasts found', 'sent-ones-wp' ),
        'not_found_in_trash'   => __( 'No podcasts found in Trash', 'sent-ones-wp' ),
    );

    $args = array(
        'labels'               => $labels,
        'public'               => true,
        'publicly_queryable'   => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'query_var'            => true,
        'rewrite'              => array( 
            'slug'       => 'podcast',
            'with_front' => true,
            'pages'      => true,
            'feeds'      => true,
        ),
        'capability_type'      => 'post',
        'has_archive'          => true,
        'hierarchical'         => false,
        'menu_position'        => 5,
        'menu_icon'            => 'dashicons-microphone',
        'supports'             => array( 
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions',
            'page-attributes',
            'post-formats',
            'template',
        ),
        'taxonomies'            => array( 'podcast_channel', 'post_tag' ),
        'show_in_rest'          => true,
        'rest_base'             => 'podcasts',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'template'              => array(),
        'template_lock'         => false,
        'show_in_graphql'       => true,
    );

    register_post_type( 'podcast', $args );

    // Force flush rewrite rules on theme activation
    flush_rewrite_rules( false );
}
add_action( 'init', 'sent_ones_wp_register_post_types' );

/**
 * Make podcast_channel taxonomy column sortable.
 */
function sent_ones_wp_sortable_columns( $columns ) {
    $columns['taxonomy-podcast_channel'] = 'podcast_channel';
    return $columns;
}
add_filter( 'manage_edit-podcast_sortable_columns', 'sent_ones_wp_sortable_columns' );

/**
 * Add filter dropdown for podcast_channel taxonomy in admin.
 */
function sent_ones_wp_admin_filter_dropdown() {
    global $typenow;
    
    if ( $typenow === 'podcast' ) {
        $selected = isset( $_GET['podcast_channel'] ) ? $_GET['podcast_channel'] : '';
        
        echo '<select name="podcast_channel" id="dropdown_podcast_channel">';
        echo '<option value="">' . __( 'All Channels', 'sent-ones-wp' ) . '</option>';
        
        $terms = get_terms( array(
            'taxonomy'   => 'podcast_channel',
            'hide_empty' => true,
            'orderby'    => 'name',
        ) );
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                echo '<option value="' . esc_attr( $term->slug ) . '"' . selected( $selected, $term->slug, false ) . '>';
                echo esc_html( $term->name ) . ' (' . $term->count . ')';
                echo '</option>';
            }
        }
        
        echo '</select>';
    }
}
add_action( 'restrict_manage_posts', 'sent_ones_wp_admin_filter_dropdown' );