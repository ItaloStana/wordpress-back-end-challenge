<?php
/**
 * Plugin Name: Favoritar Posts
 * Description: Plugin para favoritar posts usando a WP REST API e PHP.
 * Version: 1.0
 * Author: Italo Maranhão de Santana
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/favoritar-posts-db.php';

function favoritar_posts_init() {
    register_rest_route( 'favoritar/v1', '/post/', array(
        'methods' => 'POST',
        'callback' => 'favoritar_post',
        'permission_callback' => 'is_user_logged_in',
    ));

    register_rest_route( 'favoritar/v1', '/post/', array(
        'methods' => 'DELETE',
        'callback' => 'desfavoritar_post',
        'permission_callback' => 'is_user_logged_in',
    ));
}

add_action( 'rest_api_init', 'favoritar_posts_init' );

function favoritar_post( $request ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'favoritos';
    
    $user_id = get_current_user_id();
    $post_id = $request['post_id'];

    $wpdb->insert( $table_name, array(
        'user_id' => $user_id,
        'post_id' => $post_id,
    ));

    return new WP_REST_Response( 'Post favoritado com sucesso!', 200 );
}

function desfavoritar_post( $request ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'favoritos';

    $user_id = get_current_user_id();
    $post_id = $request['post_id'];

    $wpdb->delete( $table_name, array(
        'user_id' => $user_id,
        'post_id' => $post_id,
    ));

    return new WP_REST_Response( 'Post desfavoritado com sucesso!', 200 );
}


function favoritar_posts_enqueue_scripts() {
    wp_enqueue_script( 'favoritar-posts', plugins_url( 'favoritar-posts.js', __FILE__ ), array( 'jquery' ), null, true );
}

add_action( 'wp_enqueue_scripts', 'favoritar_posts_enqueue_scripts' );
