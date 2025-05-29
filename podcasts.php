<?php
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