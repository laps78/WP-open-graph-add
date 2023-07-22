<?php 

/*
 * Plugin Name: L.A.P.S. WC set Open Graph
 * Plugin URI:  https://github.com/laps78/WP-set-open-graph
 * Description: Данный плагин добавляет мета теги разметки Open Graph для страницы и для записи
 * Version: 1.0.0
 * Author: Евгений L.A.P.S Лаптев
 * Author URI: https://laps78.github.io
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: L.A.P.S. WC set Open Graph
 * Domain Path: /languages
 *
 * Network: true
 */

// Поддержка Open Graph в WordPress
function add_opengraph_doctype( $output ) {
  return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}

function remove_preseted_meta_tags(){
  remove_action( 'wp_head', 'feed_links',  2 );
  remove_action( 'wp_head',  'feed_links_extra',  3 );
  remove_action( 'wp_head',  'rsd_link' );
  remove_action( 'wp_head',  'wlwmanifest_link' );
  remove_action( 'wp_head',  'index_rel_link' );
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
  remove_action( 'wp_head', 'wp_generator' );
  remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
}

function apply_page_og_meta() {
  echo '<meta property="fb:admins" content="Ваш ID в Facebook" />';
  echo '<meta property="og:title" content="' . get_the_title() . '" />';
  echo '<meta property="og:type" content="article" />';
  echo '<meta property="og:url" content="' . get_permalink() . '" />';
  echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';
}

function apply_post_og_meta($post) {
  if(!has_post_thumbnail( $post -> ID )) {
    $default_image_url = "URL дефолтного изображения";
    echo '<meta property="og:image" content="' . $default_image_url . '" />';
  } else {
    $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ), 'medium' );
    echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '" />';
}
}

function autodetect_item_type_and_add_og_meta() {
  global $post;
  $default_image = "URL дефолтного изображения";
  if (!is_singular()) {
    apply_page_og_meta();
    return;
  }
  apply_post_og_meta($post);
}

// filteres
add_filter('language_attributes', 'add_opengraph_doctype');

// actions
add_action('wp_head', 'remove_preseted_meta_tags', 98);
add_action( 'wp_head', 'autodetect_item_type_and_add_og_meta', 99);
