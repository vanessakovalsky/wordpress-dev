<?php
/*
Plugin Name: Demo
Plugin URI: https://github.com/vanessakovalsky/wordpress-dev
Description: Demonstration de la creation d un Plugin
Author: Vanessa Kovalsky David
Version: 0.1
*/

add_action('wp_footer','demo_footer');
function demo_footer(){
  echo '<p>Un petit texte en plus sur le footer ?</p>';
}

function people_init() {
  // create a new taxonomy
  register_taxonomy(
    'people',
    'post',
    array(
      'label' => __( 'People' ),
      'rewrite' => array( 'slug' => 'person' ),
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
    )
  );
}
add_action( 'init', 'people_init' );

add_action('widgets_init','demo_register_widget');
function demo_register_widget(){
  include_once('admin/controller/bio_widget.php');
  register_widget('bio_widget');
}
