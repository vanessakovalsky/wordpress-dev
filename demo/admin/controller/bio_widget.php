<?php

class bio_widget extends WP_Widget {
  function bio_widget(){
    $widget_ops = array(
      'classname' => 'bio_widget',
      'description' => __('Widget pour afficher la bio du photographe')
    );
    $this->WP_Widget('bio_widget_photo',
      __('Bio Widget', 'demo'), $widget_ops);
  }

  function update($new_instance, $old_instance){
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['name'] = strip_tags($new_instance['name']);
    $instance['bio'] = strip_tags($new_instance['bio']);
    return $instance;
  }

  function form($instance){
    $defaults = array(
      'title' => __('Ma bio','demo'),
      'name' => '','bio' => ''
    );
    $title = strip_tags($instance['title']);
    $name = strip_tags($instance['name']);
    $bio = strip_tags($instance['bio']);?>
    <p>Titre : <input name="<?php echo $this->get_field_name('title'); ?>"
      type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>Nom : <input name="<?php echo $this->get_field_name('name'); ?>"
      type="text" value="<?php echo esc_attr($name); ?>" />
    </p>
    <p>Bio : <textarea name="<?php echo $this->get_field_name('bio'); ?>">
      <?php echo esc_attr($bio); ?></textarea>
    </p>
  <?php
  }

  function widget($args, $instance){
    extract($args);
    echo $before_widget;
    $title = apply_filters('widget_title', $instance['title']);
    $name = empty($instance['name']) ? '&nbsp;':
            apply_filters('widget_name',$instance['name']);
    $bio = empty($instance['bio']) ? '&nbsp;' :
            apply_filters('widget_bio',$instance['bio']);
    if(!empty($title)){
      echo $before_title.$title.$after_title;
    }
    echo '<p>'. __('Name','demo').' : '.$name.'</p>';
    echo '<p>'. __('Bio','demo').' : '.$bio.'</p>';
    echo $after_widget;
  }
}
