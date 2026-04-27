<?php

function remove_textarea() {
  remove_post_type_support( 'exhibition', 'editor' );
}

function remove_featured_image() {
  remove_theme_support( 'post-thumbnails' );
}

function remove_editor() {
  if (isset($_GET['post'])) {
    $id = $_GET['post'];
    $post = get_post($id);
    $post_name = $post->post_name;

    $pages_to_remove = [
      'home',
      'whats-on',
      'about',
      'calendar',
      'support',
      'shop',
      'learn',
      'engage',
      'visit',
      'history',
      'magazine',
      'news',
      'video-series'
    ];

    if (in_array($post_name, $pages_to_remove)) {
      remove_post_type_support('page', 'editor');
    }
  }
}