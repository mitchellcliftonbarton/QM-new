<?php
global $params;
global $paged;

if (!isset($paged) || !$paged){
  $paged = 1;
} else {
  $paged = $GLOBALS['params']['paged'];
}

$context = Timber::context();
$context['site_theme'] = 'grey-theme';
$category_slug = $params['category'];
$term = new Timber\Term($category_slug);
$context['term_title'] = $term->name;
$context['archive_slug'] = '/programs/';


$context['button_theme'] = 'grey';
$context['term'] = 'Programs';

/*
----------------
PAGE DATA
----------------
*/

$args = array(
  'post_type' => 'program',
  'posts_per_page' => 20,
  'paged' => $paged,
  'tax_query' => [
      [
          'taxonomy' => 'program-categories',
          'field' => 'slug',
          'terms' => $category_slug
      ]
  ]
);

$context['posts'] = new Timber\PostQuery($args);

$posts_per_page = get_option( 'posts_per_page' );
$posts = new Timber\PostQuery($args);
$total_upto = ($posts_per_page * ($paged - 1)) + count($posts);
$context['total_upto'] = $total_upto;
$context['paged'] = $paged;

Timber::render( 'archive.twig', $context );
