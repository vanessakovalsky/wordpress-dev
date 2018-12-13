<?php
/*
  Theme Name: Twenty Ninety
  Theme URI: http://localhost/wordpress
  Description: Theme enfant de twenty nineteen
  Author: Vanessa David
  Author URI: http://github.com/vanessakovalsky
  Template: twentynineteen
  License: GNU GPL
  License URI: http://tpot.combak
  Tags: purple, demo, photograph
  Text Domain: twenty-ninety
*/

add_action('wp_enqueue_scripts', 'theme_ajout_style_parent');
function theme_ajout_style_parent(){
  wp_enqueue_style('parent-style',get_template_directory_uri().'/style.css');
}

add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'appartement',
  array(
  'labels' => array(
  'name' => __( 'Appartements' ),
  'singular_name' => __( 'Appartement' )
  ),
  'supports' => array('title'), // 'editor','thumbnail', 'author', 'excerpt', 'revisions', 'page-attributes'
  'public' => true,
  'menu_icon' => 'dashicons-megaphone',
  'has_archive' => true
  )
  );
}
